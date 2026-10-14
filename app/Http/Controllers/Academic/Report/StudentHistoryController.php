<?php

namespace App\Http\Controllers\Academic\Report;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Models\ReportSetting; // Jangan lupa import
use App\Models\Course;
use App\Models\Grade;
use App\Models\ReportCard;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentHistoryController extends Controller
{
    public function show(Student $student)
    {
        // 1. Ambil semua kelas yang PERNAH diduduki siswa ini
        // Kita urutkan dari Tahun Ajaran terbaru ke terlama
        $history = $student->classrooms()
            ->with(['academicYear', 'level'])
            ->get()
            ->sortByDesc(function ($classroom) {
                return $classroom->academicYear->id; // Asumsi ID tahun ajaran auto increment (semakin baru semakin besar)
            });

        // 2. Ambil Rekap Nilai (ReportCard) & Rata-rata Nilai
        // Kita perlu data tambahan untuk ditampilkan di card (Ranking, Status, Rata-rata)
        foreach ($history as $class) {
            // Ambil data rekap (sakit, izin, catatan)
            $class->report_summary = \App\Models\ReportCard::where('student_id', $student->id)
                ->where('classroom_id', $class->id)
                ->first();

            // Hitung rata-rata nilai di semester itu (Opsional, biar keren)
            $grades = \App\Models\Grade::where('student_id', $student->id)
                ->whereIn('course_id', function ($q) use ($class) {
                    $q->select('id')->from('courses')->where('classroom_id', $class->id);
                })->get();

            $class->average_score = $grades->avg('score_final');
        }

        return view('academic.report.student_history', compact('student', 'history'));
    }

    // method print biodata
    public function printBiodata(Student $student)
    {
        $isPdf = true;
        // 1. Cari Kelas Terakhir / Aktif untuk menentukan Jenjang & Kepala Sekolah
        // Kita ambil kelas terbaru berdasarkan tahun ajaran
        $latestClass = $student->classrooms()
            ->with(['level.stage', 'academicYear'])
            ->get()
            ->sortByDesc(function ($classroom) {
                return $classroom->academicYear->id;
            })->first();


        // 2. Ambil Setting Kepala Sekolah (Sesuai Jenjang Kelas Terakhir)
        $headmaster = '..........................';
        $headmasterNip = '-';
        $printDate = now()->translatedFormat('d F Y'); // Default hari ini
        $city = 'Kota Santri';

        if ($latestClass) {
            $setting = ReportSetting::where('academic_year_id', $latestClass->academic_year_id)
                ->where('stage_id', $latestClass->level->stage_id)
                ->first();

            // Fallback: Jika setting belum diatur untuk tahun ajaran kelas ini, ambil dari tahun ajaran aktif
            if (!$setting) {
                $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
                if ($activeYear) {
                    $setting = ReportSetting::where('academic_year_id', $activeYear->id)
                        ->where('stage_id', $latestClass->level->stage_id)
                        ->first();
                }
            }

            if ($setting) {
                $headmaster = $setting->headmaster_name;
                $headmasterNip = $setting->headmaster_nip;
                $city = $setting->city;
                if ($setting->report_date) {
                    $printDate = \Carbon\Carbon::parse($setting->report_date)->translatedFormat('d F Y');
                }
                // Untuk biodata, tanggalnya biasanya tanggal "Diterima" atau tanggal cetak saat ini.
                // Kita pakai tanggal cetak saat ini saja (now) atau ambil dari setting.
            }
        }

        // 3. Cari Tanggal Diterima (Ambil dari data kelas paling awal/pertama kali masuk)
        // Atau jika Anda punya kolom 'joined_at' di tabel students, pakai itu lebih akurat.
        $firstClass = $student->classrooms()
            ->with('academicYear')
            ->get()
            ->sortBy(function ($classroom) {
                return $classroom->academicYear->id;
            })->first();

        $acceptedDate = $student->acceptance_date->translatedFormat('d F Y'); // Fallback: tgl input data
        $acceptedClass = 'VII (Tujuh)'; // Default

        if ($firstClass) {
            // Asumsi: Diterima di awal tahun ajaran kelas pertamanya
            // Anda bisa menyesuaikan logika ini
            $acceptedClass = $firstClass->level->name;
        }

        $pdf = Pdf::loadView('academic.report.biodata-pdf', compact(
            'student',
            'headmaster',
            'headmasterNip',
            'city',
            'printDate',
            'acceptedDate',
            'acceptedClass',
            'isPdf'
        ));

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Biodata_' . $student->name . '.pdf');
    }
    // method print biodata
    public function showBiodata(Student $student)
    {
        $isPdf = false;
        // 1. Cari Kelas Terakhir / Aktif untuk menentukan Jenjang & Kepala Sekolah
        // Kita ambil kelas terbaru berdasarkan tahun ajaran
        $latestClass = $student->classrooms()
            ->with(['level.stage', 'academicYear'])
            ->get()
            ->sortByDesc(function ($classroom) {
                return $classroom->academicYear->id;
            })->first();

        // 2. Ambil Setting Kepala Sekolah (Sesuai Jenjang Kelas Terakhir)
        $headmaster = '..........................';
        $headmasterNip = '-';
        $printDate = now()->translatedFormat('d F Y'); // Default hari ini
        $city = 'Kota Santri';

        if ($latestClass) {
            $setting = ReportSetting::where('academic_year_id', $latestClass->academic_year_id)
                ->where('stage_id', $latestClass->level->stage_id)
                ->first();

            // Fallback: Jika setting belum diatur untuk tahun ajaran kelas ini, ambil dari tahun ajaran aktif
            if (!$setting) {
                $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
                if ($activeYear) {
                    $setting = ReportSetting::where('academic_year_id', $activeYear->id)
                        ->where('stage_id', $latestClass->level->stage_id)
                        ->first();
                }
            }

            if ($setting) {
                $headmaster = $setting->headmaster_name;
                $headmasterNip = $setting->headmaster_nip;
                $city = $setting->city;
                if ($setting->report_date) {
                    $printDate = \Carbon\Carbon::parse($setting->report_date)->translatedFormat('d F Y');
                }
                // Untuk biodata, tanggalnya biasanya tanggal "Diterima" atau tanggal cetak saat ini.
                // Kita pakai tanggal cetak saat ini saja (now) atau ambil dari setting.
            }
        }

        // 3. Cari Tanggal Diterima (Ambil dari data kelas paling awal/pertama kali masuk)
        // Atau jika Anda punya kolom 'joined_at' di tabel students, pakai itu lebih akurat.
        $firstClass = $student->classrooms()
            ->with('academicYear')
            ->get()
            ->sortBy(function ($classroom) {
                return $classroom->academicYear->id;
            })->first();

        $acceptedDate = $student->acceptance_date->translatedFormat('d F Y'); // Fallback: tgl input data
        $acceptedClass = 'VII (Tujuh)'; // Default

        if ($firstClass) {
            // Asumsi: Diterima di awal tahun ajaran kelas pertamanya
            // Anda bisa menyesuaikan logika ini
            $acceptedClass = $firstClass->level->name;
        }

        $pdf = Pdf::loadView('academic.report.biodata-pdf', compact(
            'student',
            'headmaster',
            'headmasterNip',
            'city',
            'printDate',
            'acceptedDate',
            'acceptedClass',
            'latestClass',
            'isPdf'
        ));

        // $pdf->setPaper('A4', 'portrait');

        return view('academic.report.biodata-pdf', compact(
            'student',
            'headmaster',
            'headmasterNip',
            'city',
            'printDate',
            'acceptedDate',
            'acceptedClass',
            'latestClass',
            'isPdf'
        ));
    }

    /**
     * Form edit nilai dan rekap rapor santri untuk semester/kelas tertentu.
     */
    public function editReport(Student $student, Classroom $classroom)
    {
        $classroom->load(['academicYear', 'level.stage']);

        // Ambil semua mata pelajaran di kelas ini
        $courses = Course::with('subject')
            ->where('classroom_id', $classroom->id)
            ->join('subjects', 'courses.subject_id', '=', 'subjects.id')
            ->orderBy('subjects.group')
            ->orderBy('subjects.name')
            ->select('courses.*')
            ->get();

        // Ambil nilai yang sudah ada untuk santri ini
        $grades = Grade::where('student_id', $student->id)
            ->whereIn('course_id', $courses->pluck('id'))
            ->get()
            ->keyBy('course_id');

        // Ambil rekap rapor (ReportCard)
        $reportCard = ReportCard::firstOrNew([
            'student_id'   => $student->id,
            'classroom_id' => $classroom->id,
        ]);

        return view('academic.report.edit_report', compact('student', 'classroom', 'courses', 'grades', 'reportCard'));
    }

    /**
     * Simpan pembaruan nilai dan rekap rapor santri.
     */
    public function updateReport(Request $request, Student $student, Classroom $classroom)
    {
        $request->validate([
            'grades'             => 'nullable|array',
            'grades.*.harian'    => 'nullable|numeric|min:0|max:100',
            'grades.*.uts'       => 'nullable|numeric|min:0|max:100',
            'grades.*.uas'       => 'nullable|numeric|min:0|max:100',
            'grades.*.final'     => 'nullable|numeric|min:0|max:100',
            'grades.*.notes'     => 'nullable|string',
            'report.sick'        => 'nullable|integer|min:0',
            'report.permission'  => 'nullable|integer|min:0',
            'report.absent'      => 'nullable|integer|min:0',
            'report.notes'       => 'nullable|string',
            'report.status'      => 'nullable|string|max:50',
        ]);

        DB::beginTransaction();
        try {
            // 1. Simpan/Update Nilai per Mapel
            if ($request->has('grades')) {
                foreach ($request->grades as $courseId => $scoreData) {
                    $harian = isset($scoreData['harian']) && $scoreData['harian'] !== '' ? (float) $scoreData['harian'] : 0;
                    $uts    = isset($scoreData['uts']) && $scoreData['uts'] !== '' ? (float) $scoreData['uts'] : 0;
                    $uas    = isset($scoreData['uas']) && $scoreData['uas'] !== '' ? (float) $scoreData['uas'] : 0;

                    // Hitung nilai akhir jika tidak diisi manual (40% harian, 30% uts, 30% uas)
                    if (isset($scoreData['final']) && $scoreData['final'] !== '') {
                        $final = (float) $scoreData['final'];
                    } else {
                        $final = round(($harian * 0.4) + ($uts * 0.3) + ($uas * 0.3), 1);
                    }

                    // Tentukan Predikat
                    $gradeLetter = match (true) {
                        $final >= 85 => 'A',
                        $final >= 75 => 'B',
                        $final >= 60 => 'C',
                        $final >= 50 => 'D',
                        default      => 'E',
                    };

                    Grade::updateOrCreate(
                        [
                            'course_id'  => $courseId,
                            'student_id' => $student->id,
                        ],
                        [
                            'score_harian' => $harian,
                            'score_uts'    => $uts,
                            'score_uas'    => $uas,
                            'score_final'  => $final,
                            'grade_letter' => $gradeLetter,
                            'description'  => $scoreData['notes'] ?? null,
                        ]
                    );
                }
            }

            // 2. Simpan/Update Rekap Rapor (ReportCard)
            $reportData = $request->input('report', []);
            ReportCard::updateOrCreate(
                [
                    'student_id'   => $student->id,
                    'classroom_id' => $classroom->id,
                ],
                [
                    'sick'       => $reportData['sick'] ?? 0,
                    'permission' => $reportData['permission'] ?? 0,
                    'absent'     => $reportData['absent'] ?? 0,
                    'notes'      => $reportData['notes'] ?? null,
                    'status'     => $reportData['status'] ?? 'Naik Kelas',
                ]
            );

            DB::commit();

            return redirect()->route('student.history', $student->id)
                ->with('success', "Rapor {$student->name} untuk kelas {$classroom->name} berhasil diperbarui.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan perubahan rapor: ' . $e->getMessage());
        }
    }
}
