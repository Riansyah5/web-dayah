<?php

namespace App\Http\Controllers\Academic\Grading;

use App\Http\Controllers\Controller;
use App\Models\Course;
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
        // Ambil data (Nanti tambahkan ->where('teacher_id', ...) jika login guru sudah aktif)
        $allCourses = Course::with(['classroom', 'subject', 'classroom.students'])
            ->withCount('grades') // Hitung jumlah nilai masuk
            ->get();

        // Grouping berdasarkan ID Kelas agar datanya menyatu
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
        $data = $request->grades; // Array [student_id => [harian, uts, uas]]

        foreach ($data as $studentId => $scores) {
            $harian = (float) ($scores['harian'] ?? 0);
            $uts = (float) ($scores['uts'] ?? 0);
            $uas = (float) ($scores['uas'] ?? 0);

            $grade = Grade::updateOrCreate(
                ['course_id' => $course->id, 'student_id' => $studentId],
                [
                    'score_harian' => $harian,
                    'score_uts' => $uts,
                    'score_uas' => $uas,
                ]
            );
            $grade->calculateFinal();
        }

        return back()->with('success', 'Nilai berhasil disimpan.');
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

        Excel::import(new GradesImport($course->id), $request->file('file'));

        return back()->with('success', 'Nilai berhasil diimport dari Excel.');
    }
}
