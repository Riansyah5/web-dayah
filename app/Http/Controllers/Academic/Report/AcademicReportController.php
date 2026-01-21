<?php

namespace App\Http\Controllers\Academic\Report;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\TeachingJournal;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\TeacherPermission;

class AcademicReportController extends Controller
{
    // 1. LAPORAN KINERJA GURU (BULANAN)
    public function teacherRecap(Request $request)
    {
        $month = $request->month ?? date('m');
        $year = $request->year ?? date('Y');

        $teachers = Teacher::where('is_active', true)->orderBy('name')->get();

        // Kita map data untuk hitung statistik
        $recap = $teachers->map(function($teacher) use ($month, $year) {
            
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
        
        // Jika User sudah filter Kelas & Mapel
        if ($request->classroom_id && $request->subject_id) {
            
            // Ambil Siswa di kelas itu
            $classroom = Classroom::with('students')->find($request->classroom_id);
            
            // Ambil ID Jurnal untuk Kelas & Mapel tersebut
            $journalIds = TeachingJournal::whereHas('lessonSchedule', function($q) use ($request) {
                                $q->where('classroom_id', $request->classroom_id)
                                  ->where('subject_id', $request->subject_id);
                            })->pluck('id');
            
            $totalMeeting = $journalIds->count();

            // Hitung Presensi tiap siswa
            foreach($classroom->students as $student) {
                
                // Hitung status dari tabel student_lesson_attendances
                $stats = DB::table('student_lesson_attendances')
                            ->whereIn('teaching_journal_id', $journalIds)
                            ->where('student_id', $student->id)
                            ->select('status', DB::raw('count(*) as total'))
                            ->groupBy('status')
                            ->pluck('total', 'status')
                            ->toArray();

                $present = $stats['present'] ?? 0;
                $sick = $stats['sick'] ?? 0;
                $permission = $stats['permission'] ?? 0;
                $alpha = $stats['alpha'] ?? 0;

                // Hitung Persentase Kehadiran
                // Rumus: (Hadir / Total Pertemuan Guru) * 100
                $percentage = $totalMeeting > 0 ? round(($present / $totalMeeting) * 100) : 0;

                $attendanceRecap[] = (object) [
                    'name' => $student->name,
                    'nis' => $student->nis,
                    'h' => $present,
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

        // 1. Ambil Data Jurnal (Mengajar & Badal)
        $journals = TeachingJournal::where('teacher_id', $teacher->id)
                    ->whereMonth('date', $month)
                    ->whereYear('date', $year)
                    ->with(['lessonSchedule.classroom', 'lessonSchedule.subject'])
                    ->orderByDesc('date')
                    ->orderByDesc('clock_in_time')
                    ->get();

        // 2. Ambil Data Izin (Absensi Guru)
        $permissions = TeacherPermission::where('teacher_id', $teacher->id)
                        ->whereMonth('date', $month)
                        ->whereYear('date', $year)
                        ->get();

        // 3. Hitung Ringkasan Sederhana
        $summary = [
            'total_teaching' => $journals->where('is_substitute', false)->count(),
            'total_substitute' => $journals->where('is_substitute', true)->count(),
            'total_absent' => $permissions->where('status', 'approved')->count(),
            'ontime_percentage' => 0 // Bisa dikembangkan nanti logic terlambatnya
        ];

        return view('academic.report.teacher_detail', compact('teacher', 'journals', 'permissions', 'summary', 'month', 'year'));
    }
}