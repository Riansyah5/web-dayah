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

            // Ambil data jenjang dari kelas yang dipilih (Misal: Kelas 7A -> Jenjang SMP)
            $stageId = $selectedClassroom->level->stage_id;
            // Cari Mapel yang HANYA terhubung dengan jenjang tersebut
            $subjects = Subject::whereHas('stages', function ($query) use ($stageId) {
                $query->where('stages.id', $stageId);
            })->orderBy('group')->orderBy('name')->get();

            $teachers = Teacher::orderBy('name')->get();

            // Ambil data plotting yang sudah tersimpan (jika ada)
            $courses = Course::where('classroom_id', $selectedClassroom->id)->get()->keyBy('subject_id');
        }

        return view('academic.grading.plotting.index', compact(
            'classrooms',
            'selectedClassroom',
            'subjects',
            'teachers',
            'courses',
            'activeYear'
        ));
    }

    /**
     * Simpan Plotting (Satu per satu atau Bulk - di sini kita buat per baris via AJAX/Form simpel)
     */
    public function update(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required',
            'subject_id' => 'required',
            'is_active' => 'nullable', // Checkbox (1 atau null)
            'teacher_id' => 'nullable',
            'kkm' => 'nullable|numeric'
        ]);

        // LOGIKA BARU:

        if ($request->has('is_active')) {
            // KASUS 1: Mapel Dipilih (Aktif) -> Simpan/Update
            Course::updateOrCreate(
                [
                    'classroom_id' => $request->classroom_id,
                    'subject_id' => $request->subject_id,
                ],
                [
                    'teacher_id' => $request->teacher_id,
                    'kkm' => $request->kkm ?? 75 // Default KKM jika kosong
                ]
            );
            $message = 'Mapel diaktifkan untuk kelas ini.';
        } else {
            // KASUS 2: Mapel Tidak Dipilih -> Hapus dari Plotting
            // Artinya kelas ini TIDAK belajar mapel tersebut
            Course::where('classroom_id', $request->classroom_id)
                ->where('subject_id', $request->subject_id)
                ->delete();

            $message = 'Mapel dinonaktifkan (dihapus) dari kelas ini.';
        }

        return back()->with('success', $message);
    }
}
