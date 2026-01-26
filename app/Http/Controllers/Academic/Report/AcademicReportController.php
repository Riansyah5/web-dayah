<?php

namespace App\Http\Controllers\Academic\Report;

use Carbon\Carbon;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Classroom;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Models\TeachingJournal;
use App\Models\TeacherPermission;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\TeacherMonthlyEvaluation;

class AcademicReportController extends Controller
{
    // 1. LAPORAN KINERJA GURU (BULANAN)
    public function teacherRecap(Request $request)
    {
        $month = $request->month ?? date('m');
        $year = $request->year ?? date('Y');

        $teachers = Teacher::where('is_active', true)->orderBy('name')->get();

        // Kita map data untuk hitung statistik
        $recap = $teachers->map(function ($teacher) use ($month, $year) {

            // Hitung Kehadiran Real (Jurnal yang dia buat)
            $journals = TeachingJournal::where('teacher_id', $teacher->id)
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->get();

            $mainTeaching = $journals->where('is_substitute', false)->count();
            $substituteTeaching = $journals->where('is_substitute', true)->count();

            // Hitung Izin (Dari tabel teacher_permissions Tahap 2)
            // $permits = ... (Query ke tabel permissions)

            return [
                'teacher' => $teacher,
                'main_count' => $mainTeaching, // Mengajar jam sendiri
                'sub_count' => $substituteTeaching, // Mengajar badal
                'total_count' => $mainTeaching + $substituteTeaching,
            ];
        });

        return view('academic.report.teacher_recap', compact('recap', 'month', 'year'));
    }

    // 2. LAPORAN ABSENSI SISWA PER MAPEL
    public function studentSubjectRecap(Request $request)
    {
        // Dropdown Data
        $activeYear = AcademicYear::where('is_active', true)->first();
        $classrooms = Classroom::where('academic_year_id', $activeYear->id)->get();
        $subjects = Subject::orderBy('name')->get();

        $attendanceRecap = [];
        
        if ($request->classroom_id && $request->subject_id) {
            
            // 1. QUERY DASAR (Filter Kelas & Mapel)
            $query = TeachingJournal::whereHas('lessonSchedule', function($q) use ($request) {
                                $q->where('classroom_id', $request->classroom_id)
                                  ->where('subject_id', $request->subject_id);
                            });

            // 2. FILTER WAKTU (BARU)
            if ($request->period) {
                if ($request->period == 'ganjil') {
                    // Semester Ganjil (Juli - Desember)
                    $query->whereMonth('date', '>=', 7)
                          ->whereMonth('date', '<=', 12);
                } 
                elseif ($request->period == 'genap') {
                    // Semester Genap (Januari - Juni)
                    $query->whereMonth('date', '>=', 1)
                          ->whereMonth('date', '<=', 6);
                } 
                elseif (is_numeric($request->period)) {
                    // Per Bulan Spesifik (1 - 12)
                    $query->whereMonth('date', $request->period);
                }
            }

            // Ambil ID Jurnal hasil filter
            $journalIds = $query->pluck('id');
            $totalMeeting = $journalIds->count(); // Total pertemuan di periode ini

            // 3. HITUNG ABSENSI SISWA (Tetap Sama)
            $classroom = Classroom::with('students')->find($request->classroom_id);

            foreach($classroom->students as $student) {
                // ... (Kode hitung H/T/S/I/A sama persis seperti sebelumnya) ...
                // ... (Hanya query database-nya sekarang dibatasi $journalIds hasil filter diatas) ...
                
                $stats = DB::table('student_lesson_attendances')
                            ->whereIn('teaching_journal_id', $journalIds) // Kuncinya disini
                            ->where('student_id', $student->id)
                            ->select('status', DB::raw('count(*) as total'))
                            ->groupBy('status')
                            ->pluck('total', 'status')
                            ->toArray();

                $present = $stats['present'] ?? 0;
                $late = $stats['late'] ?? 0;
                $sick = $stats['sick'] ?? 0;
                $permission = $stats['permission'] ?? 0;
                $alpha = $stats['alpha'] ?? 0;

                $attendanceCount = $present + $late;
                $percentage = $totalMeeting > 0 ? round(($attendanceCount / $totalMeeting) * 100) : 0;

                $attendanceRecap[] = (object) [
                    'name' => $student->name,
                    'nis' => $student->nis,
                    'h' => $present,
                    't' => $late,
                    's' => $sick,
                    'i' => $permission,
                    'a' => $alpha,
                    'percent' => $percentage
                ];
            }
        }

        return view('academic.report.student_subject_recap', compact('classrooms', 'subjects', 'attendanceRecap', 'request'));
    }

    public function teacherDetail(Request $request, Teacher $teacher)
    {
        $month = $request->month ?? date('m');
        $year = $request->year ?? date('Y');

        // 1. Ambil Data Detail Jurnal & Izin (TETAP DIPERLUKAN UNTUK TABEL BAWAH)
        // Kita butuh ini sebagai "Bukti Audit" meskipun ringkasannya diambil dari snapshot
        $journals = TeachingJournal::where('teacher_id', $teacher->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->with(['lessonSchedule.classroom', 'lessonSchedule.subject'])
            ->orderByDesc('date')
            ->orderByDesc('clock_in_time')
            ->get();

        $permissions = TeacherPermission::where('teacher_id', $teacher->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        // 2. LOGIKA PRIORITAS DATA (UNTUK KARTU RINGKASAN ATAS)

        // Cek apakah sudah ada evaluasi tersimpan?
        $evaluation = TeacherMonthlyEvaluation::where('teacher_id', $teacher->id)
            ->where('month', $month)
            ->where('year', $year)
            ->first();

        if ($evaluation) {
            // SKENARIO A: SUDAH ADA SNAPSHOT
            // Ambil data dari tabel evaluasi (Data Beku/Terkunci)
            $summary = [
                'source' => 'snapshot', // Penanda untuk View
                'is_approved' => $evaluation->is_approved,
                'total_teaching' => $evaluation->total_teaching_hours,
                'total_substitute' => $evaluation->total_substitute_hours,
                'total_absent' => $evaluation->total_absent_days,
            ];
        } else {
            // SKENARIO B: BELUM ADA SNAPSHOT
            // Hitung manual dari data mentah (Data Live)
            $summary = [
                'source' => 'live', // Penanda untuk View
                'is_approved' => false,
                'total_teaching' => $journals->where('is_substitute', false)->count(),
                'total_substitute' => $journals->where('is_substitute', true)->count(),
                'total_absent' => $permissions->where('status', 'approved')->count(),
            ];
        }

        return view('academic.report.teacher_detail', compact(
            'teacher',
            'journals',
            'permissions',
            'summary',
            'month',
            'year',
            'evaluation'
        ));
    }


    // SIMPAN EVALUASI & APPROVAL (Oleh Kepala Sekolah)
    public function storeEvaluation(Request $request, Teacher $teacher)
    {
        $request->validate([
            'month' => 'required|integer',
            'year' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'headmaster_note' => 'nullable|string',
            'action' => 'required|in:save,approve', // save=draft, approve=final
        ]);

        // 1. Hitung Ulang Statistik (Snapshot Data)
        // Kita hitung lagi di sini untuk memastikan angka yang disimpan ke database adalah angka final
        $journals = TeachingJournal::where('teacher_id', $teacher->id)
            ->whereMonth('date', $request->month)
            ->whereYear('date', $request->year)
            ->get();

        $permissions = TeacherPermission::where('teacher_id', $teacher->id)
            ->whereMonth('date', $request->month)
            ->whereYear('date', $request->year)
            ->where('status', 'approved')
            ->get();

        // 2. Simpan / Update ke Tabel Evaluasi
        $evaluation = TeacherMonthlyEvaluation::updateOrCreate(
            [
                'teacher_id' => $teacher->id,
                'month' => $request->month,
                'year' => $request->year,
            ],
            [
                // Simpan Angka Statistik (Frozen Data)
                'total_teaching_hours' => $journals->where('is_substitute', false)->count(),
                'total_substitute_hours' => $journals->where('is_substitute', true)->count(),
                'total_absent_days' => $permissions->count(),

                // Simpan Inputan Kepala Sekolah
                'rating' => $request->rating,
                'headmaster_note' => $request->headmaster_note,
            ]
        );

        // 3. Jika Action = Approve
        if ($request->action == 'approve') {
            $evaluation->update([
                'is_approved' => true,
                'approved_by' => Auth::user()->id,
                'approved_at' => now(),
            ]);
            $msg = 'Laporan kinerja berhasil disetujui dan dikunci.';
        } else {
            $msg = 'Draft penilaian berhasil disimpan.';
        }

        return back()->with('success', $msg);
    }
}
