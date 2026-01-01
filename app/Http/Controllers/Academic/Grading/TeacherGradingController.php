<?php

namespace App\Http\Controllers\Academic\Grading;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\AcademicYear;
use App\Models\Grade;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GradeTemplateExport;
use App\Imports\GradesImport;

class TeacherGradingController extends Controller
{
    // Halaman Dashboard Guru (Daftar Mapel yang diajar)
    public function index()
    {
        // Ambil Tahun Aktif
        $activeYear = AcademicYear::where('is_active', true)->first();

        // 1. Ambil data Course (Jadwal Mengajar)
        // Kita load 'grades' (hanya kolom penting) untuk menghitung manual di PHP
        // agar akurat hanya menghitung siswa yang SAAT INI ada di kelas tersebut.
        $allCourses = Course::with(['classroom', 'subject', 'classroom.students', 'grades:id,course_id,student_id'])
            ->whereHas('classroom', function ($q) use ($activeYear) {
                $q->where('academic_year_id', $activeYear?->id);
            })
            ->orderBy('classroom_id')
            ->orderBy('subject_id')
            ->get();

        // 2. Grouping berdasarkan ID Kelas agar tampilannya rapi per kelas
        $groupedCourses = $allCourses->groupBy('classroom_id');

        return view('academic.grading.teacher.index', compact('groupedCourses'));
    }

    // Halaman Input Nilai (Web View)
    public function show(Course $course)
    {
        // Load data siswa di kelas ini beserta nilai mereka (jika ada)
        $course->load(['classroom.students', 'grades']);

        // Mapping nilai biar mudah dipanggil di view: grades[student_id]
        $grades = $course->grades->keyBy('student_id');

        return view('academic.grading.teacher.show', compact('course', 'grades'));
    }

    // Simpan Nilai via WEB
    public function update(Request $request, Course $course)
    {
        $data = $request->grades; // Array input dari form

        foreach ($data as $studentId => $scores) {
            // 1. Cek apakah semua input kosong/null
            $harian = $scores['harian'];
            $uts    = $scores['uts'];
            $uas    = $scores['uas'];

            // Logic: Jika semua kolom kosong, dianggap "Belum Dinilai" / "Hapus Nilai"
            if ($harian === null && $uts === null && $uas === null) {

                // Hapus data grade jika ada (agar progress bar turun)
                Grade::where('course_id', $course->id)
                    ->where('student_id', $studentId)
                    ->delete();
            } else {
                // 2. Jika ada salah satu yang diisi, Simpan/Update
                // Kita gunakan (float) untuk memastikan tersimpan angka, tapi null tetap null jika kosong
                $grade = Grade::updateOrCreate(
                    ['course_id' => $course->id, 'student_id' => $studentId],
                    [
                        'score_harian' => $harian ?? 0,
                        'score_uts'    => $uts ?? 0,
                        'score_uas'    => $uas ?? 0,
                    ]
                );

                // Hitung nilai akhir hanya jika data disimpan
                $grade->calculateFinal();
            }
        }

        return back()->with('success', 'Data nilai berhasil diperbarui.');
    }

    // Download Template Excel
    public function exportExcel(Course $course)
    {
        $fileName = 'Nilai_' . $course->subject->name . '_' . $course->classroom->name . '.xlsx';
        return Excel::download(new GradeTemplateExport($course), $fileName);
    }

    // Import Nilai Excel
    public function importExcel(Request $request, Course $course)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new GradesImport($course->id), $request->file('file'));
            return back()->with('success', 'Nilai berhasil diimport dari Excel.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import nilai: ' . $e->getMessage());
        }
    }
}
