<?php

namespace App\Http\Controllers\Academic\Grading; // Namespace Modular

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Halaman Utama Plotting.
     * User memilih Kelas dulu, baru muncul daftar mapelnya.
     */
    public function index(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();
        
        // Ambil list kelas untuk sidebar/dropdown selector
        $classrooms = Classroom::with('level')
            ->where('academic_year_id', $activeYear?->id)
            ->orderBy('level_id')
            ->orderBy('name')
            ->get();

        $selectedClassroom = null;
        $courses = collect(); // Kosong defaultnya
        $subjects = collect();
        $teachers = collect();

        // Jika user sudah memilih kelas (via parameter URL ?classroom_id=1)
        if ($request->filled('classroom_id')) {
            $selectedClassroom = Classroom::findOrFail($request->classroom_id);
            
            // Ambil Master Mapel & Guru untuk Dropdown
            $subjects = Subject::orderBy('group')->orderBy('name')->get();
            $teachers = Teacher::orderBy('name')->get();

            // Ambil data plotting yang sudah tersimpan (jika ada)
            $courses = Course::where('classroom_id', $selectedClassroom->id)->get()->keyBy('subject_id');
        }

        return view('academic.grading.plotting.index', compact(
            'classrooms', 'selectedClassroom', 'subjects', 'teachers', 'courses', 'activeYear'
        ));
    }

    /**
     * Simpan Plotting (Satu per satu atau Bulk - di sini kita buat per baris via AJAX/Form simpel)
     */
    public function update(Request $request)
    {
        // Validasi
        $request->validate([
            'classroom_id' => 'required',
            'subject_id' => 'required',
            'teacher_id' => 'nullable', // Boleh kosong jika belum ada guru
            'kkm' => 'required|numeric|min:0|max:100'
        ]);

        Course::updateOrCreate(
            [
                'classroom_id' => $request->classroom_id,
                'subject_id' => $request->subject_id,
            ],
            [
                'teacher_id' => $request->teacher_id,
                'kkm' => $request->kkm
            ]
        );

        return back()->with('success', 'Data KBM berhasil disimpan.');
    }
}