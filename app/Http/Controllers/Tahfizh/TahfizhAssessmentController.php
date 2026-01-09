<?php

namespace App\Http\Controllers\Tahfizh;

use App\Models\Student;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Models\TahfizhReport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\TahfizhSetting;

class TahfizhAssessmentController extends Controller
{
    // Halaman Form Input / Edit Nilai
    public function edit(Student $student)
    {
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();

        // Cari apakah sudah ada rapor semester ini?
        // Kita gunakan firstOrNew: Jika ada ambil, jika belum buat objek kosong
        $report = TahfizhReport::firstOrNew([
            'student_id' => $student->id,
            'academic_year_id' => $activeYear->id
        ]);

        return view('tahfizh.assessment.form', compact('student', 'report', 'activeYear'));
    }

    // Proses Simpan Data
    public function update(Request $request, Student $student)
    {
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();

        // Ambil Musyrif (Teacher) dari Halaqah Aktif Santri
        $activeHalaqah = $student->tahfizhHalaqahs()
            ->whereHas('academicYear', function ($q) {
                $q->where('is_active', true);
            })
            ->first();

        $request->validate([
            // A. Hafalan
            'juz_data' => 'nullable|array',         // Array Juz yg dipilih
            'score_data' => 'nullable|array',       // Array Nilai per juz
            'total_hafalan' => 'nullable|string',
            'score_tahriri' => 'nullable|integer|min:0|max:100',

            // B. Tahsin
            'score_makhraj' => 'nullable|integer|min:0|max:100',
            'score_ghunnah' => 'nullable|integer|min:0|max:100',
            'score_mad' => 'nullable|integer|min:0|max:100',
            'score_fasohah' => 'nullable|integer|min:0|max:100',
            'score_kelancaran' => 'nullable|integer|min:0|max:100',

            // E. Kehadiran
            'sick' => 'nullable|integer',
            'permission' => 'nullable|integer',
            'alpha' => 'nullable|integer',
        ]);

        // 1. OLAH DATA JUZ (Menggabungkan Array Juz & Score menjadi JSON)
        // Input: juz_data=[30, 29], score_data=[90, 85]
        // Output JSON: [{"juz":30, "score":90}, {"juz":29, "score":85}]

        $juzScores = [];
        if ($request->has('juz_data')) {
            foreach ($request->juz_data as $index => $juz) {
                // Pastikan nilai tidak kosong
                if (!empty($juz) && isset($request->score_data[$index])) {
                    $juzScores[] = [
                        'juz' => $juz,
                        'score' => $request->score_data[$index]
                    ];
                }
            }
        }

        // 2. SIMPAN KE DATABASE (Update or Create)
        TahfizhReport::updateOrCreate(
            [
                'student_id' => $student->id,
                'academic_year_id' => $activeYear->id
            ],
            [
                'teacher_id' => $activeHalaqah?->teacher_id, // Mengambil ID Musyrif dari Halaqah Aktif
                'juz_scores' => $juzScores, // Disimpan otomatis sbg JSON (krn $casts di Model)

                'total_hafalan' => $request->total_hafalan,
                'score_tahriri' => $request->score_tahriri,

                'score_makhraj' => $request->score_makhraj,
                'score_ghunnah' => $request->score_ghunnah,
                'score_mad' => $request->score_mad,
                'score_fasohah' => $request->score_fasohah,
                'score_kelancaran' => $request->score_kelancaran,

                'note_student' => $request->note_student,
                'note_parent' => $request->note_parent,

                'sick' => $request->sick ?? 0,
                'permission' => $request->permission ?? 0,
                'alpha' => $request->alpha ?? 0,
            ]
        );

        return redirect()->back()->with('success', 'Data Rapor berhasil disimpan.');
    }

    // Proses Cetak Rapor
    public function print(Student $student)
    {
        $isPdf = true;
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();

        // 1. Ambil Data Rapor
        $report = TahfizhReport::where('student_id', $student->id)
            ->where('academic_year_id', $activeYear->id)
            ->with('teacher') // Load data Musyrif
            ->first();

        if (!$report) {
            return back()->with('error', 'Data rapor belum diinput untuk semester ini.');
        }

        // --- LOGIKA BARU: AMBIL DARI SETTING ---
        $setting = TahfizhSetting::where('academic_year_id', $activeYear->id)->first();

        // 2. Data Kepala Sekolah / Tahfizh (Bisa hardcode atau ambil dari setting)
        // Disini saya buat variabel agar mudah diganti
        // $headmaster = "Ustadz Abdullah, Lc."; // Ganti sesuai nama Kepala Tahfizh
        $city = $setting->city ?? 'Lhokseumawe';
        $date = $setting && $setting->distribution_date
            ? $setting->distribution_date->locale('id')->translatedFormat('d F Y')
            : now()->locale('id')->translatedFormat('d F Y');

        // 3. Render PDF
        $pdf = Pdf::loadView('tahfizh.assessment.print', compact(
            'student',
            'report',
            'activeYear',
            'city',
            'date',
            'isPdf'
        ));

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Rapor_Tahfizh_' . $student->name . '.pdf');
    }

    // Preview Rapor (Untuk Testing)
    public function preview(Student $student)
    {
        $isPdf = false;
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();

        // 1. Ambil Data Rapor
        $report = TahfizhReport::where('student_id', $student->id)
            ->where('academic_year_id', $activeYear->id)
            ->with('teacher') // Load data Musyrif
            ->first();

        if (!$report) {
            return back()->with('error', 'Data rapor belum diinput untuk semester ini.');
        }

        // --- LOGIKA BARU: AMBIL DARI SETTING ---
        $setting = TahfizhSetting::where('academic_year_id', $activeYear->id)->first();

        // 2. Data Kepala Sekolah / Tahfizh (Bisa hardcode atau ambil dari setting)
        // Disini saya buat variabel agar mudah diganti
        // $headmaster = "Ustadz Abdullah, Lc."; // Ganti sesuai nama Kepala Tahfizh
        $city = $setting->city ?? 'Lhokseumawe';
        $date = $setting && $setting->distribution_date
            ? $setting->distribution_date->locale('id')->translatedFormat('d F Y')
            : now()->locale('id')->translatedFormat('d F Y');

        return view('tahfizh.assessment.print', compact(
            'student',
            'report',
            'activeYear',
            'city',
            'date',
            'isPdf'
        ));
    }
}
