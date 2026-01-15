<?php

namespace App\Http\Controllers;

use App\Models\{Classroom, AcademicYear, Student, Level, Major, Stage, Pegawai};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf as PDF;


class ClassroomController extends Controller
{
    /* ==========================
     |  INDEX
     ========================== */
    public function index(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->first();

        $query = Classroom::with(['level.stage', 'major'])
            ->withCount('students')
            ->where('academic_year_id', $activeYear?->id);

        if ($request->filled('stage_id')) {
            $query->whereHas('level', fn($q) => $q->where('stage_id', $request->stage_id));
        }

        $classrooms = $query->orderBy('level_id')->orderBy('name')->get();

        return view('academic.classrooms.index', [
            'classrooms' => $classrooms,
            'activeYear' => $activeYear,
            'stages'     => Stage::all(),
            'levels'     => Level::all(),
            'majors'     => Major::all(),
            'teachers'   => Pegawai::orderBy('nama')->get(),
        ]);
    }

    /* ==========================
     |  STORE
     ========================== */
    public function store(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();

        $data = $request->validate([
            'level_id'          => 'required|exists:levels,id',
            'major_id'          => 'nullable|exists:majors,id',
            'name'              => 'required|string|max:50',
            'homeroom_teacher'  => 'nullable|string|max:100',
            'capacity'          => 'required|integer|min:1',
        ]);

        $data['academic_year_id'] = $activeYear->id;

        Classroom::create($data);

        return back()->with('success', 'Kelas berhasil dibuat.');
    }

    /* ==========================
     |  SHOW
     ========================== */
    public function show(Classroom $classroom)
    {
        $classroom->load(['students', 'level.stage', 'major']);
        $activeYearId = $classroom->academic_year_id;

        $bookedIds = DB::table('classroom_student')
            ->join('classrooms', 'classroom_student.classroom_id', '=', 'classrooms.id')
            ->where('classrooms.academic_year_id', $activeYearId)
            ->pluck('student_id');

        $availableStudents = Student::where('status', 'active')
            ->whereNotIn('id', $bookedIds)
            ->orderBy('name')
            ->get();

        $otherClasses = Classroom::where('academic_year_id', $activeYearId)
            ->where('id', '!=', $classroom->id)
            ->withCount('students')
            ->get();

        return view('academic.classrooms.show', compact(
            'classroom',
            'availableStudents',
            'otherClasses'
        ));
    }

    /* ==========================
     |  EDIT
     ========================== */
    public function edit(Classroom $classroom)
    {
        // Kita butuh data Levels dan Majors untuk mengisi Dropdown
        $levels = \App\Models\Level::with('stage')->orderBy('stage_id')->get();
        $majors = \App\Models\Major::all();
        $teachers = Pegawai::orderBy('nama')->get();

        return view('academic.classrooms.edit', compact('classroom', 'levels', 'majors', 'teachers'));
    }

    /* ==========================
     |  UPDATE
     ========================== */
    public function update(Request $request, Classroom $classroom)
    {
        $data = $request->validate([
            'level_id'          => 'required|exists:levels,id',
            'major_id'          => 'nullable|exists:majors,id',
            'name'              => 'required|string|max:50',
            'homeroom_teacher'  => 'nullable|string|max:100',
            'capacity'          => 'required|integer|min:1',
        ]);

        $classroom->update($data);

        return redirect()->route('classrooms.index')->with('success', 'Data kelas diperbarui');
    }

    /* ==========================
     |  DESTROY
     ========================== */
    public function destroy(Classroom $classroom)
    {
        DB::transaction(function () use ($classroom) {
            $studentIds = $classroom->students()->pluck('students.id');

            if ($studentIds->isNotEmpty()) {
                Student::whereIn('id', $studentIds)->update(['class_group' => null]);
            }

            $classroom->students()->detach();
            $classroom->delete();
        });

        return redirect()->route('classrooms.index')->with('success', 'Kelas berhasil dihapus');
    }

    /* ==========================
     |  ADD STUDENT (FIXED)
     ========================== */
    public function addStudent(Request $request, Classroom $classroom)
    {
        $request->validate([
            'student_ids'   => 'required|array|min:1',
            'student_ids.*' => 'exists:students,id',
        ]);
        // cek kapasitas kelas
        $currentCount = $classroom->students()->count();
        $addCount = count($request->student_ids);

        if ($currentCount + $addCount > $classroom->capacity) {
            return back()->with('error', 'Gagal! Jumlah siswa melebihi kapasitas kelas. Sisa slot: ' . ($classroom->capacity - $currentCount));
        }
        // -------------------
        $now = now();
        $data = [];

        foreach ($request->student_ids as $studentId) {
            $data[] = [
                'id'           => (string) Str::ulid(),
                'classroom_id' => $classroom->id,
                'student_id'  => $studentId,
                'created_at'  => $now,
                'updated_at'  => $now,
            ];
        }

        DB::table('classroom_student')->insert($data);

        $classroom->load('level.stage');
        $educationLevel = $classroom->level->stage->code ?? null;

        Student::whereIn('id', $request->student_ids)
            ->update([
                'class_group'     => $classroom->name,
                'education_level' => $educationLevel,
            ]);

        return back()->with('success', count($request->student_ids) . ' siswa ditambahkan');
    }

    /* ==========================
     |  REMOVE STUDENT (FIXED)
     ========================== */
    public function removeStudent(Classroom $classroom, $studentId)
    {
        $classroom->students()->detach($studentId);
        Student::where('id', $studentId)->update(['class_group' => null]);

        return back()->with('success', 'Siswa dikeluarkan dari kelas');
    }

    /* ==========================
     |  MOVE STUDENT (FIXED)
     ========================== */
    public function moveStudent(Request $request, Classroom $classroom, $studentId)
    {
        $request->validate([
            'destination_class_id' => 'required|exists:classrooms,id',
        ]);

        DB::transaction(function () use ($request, $classroom, $studentId) {
            $classroom->students()->detach($studentId);

            $newClass = Classroom::findOrFail($request->destination_class_id);

            DB::table('classroom_student')->insert([
                'id'           => (string) Str::ulid(),
                'classroom_id' => $newClass->id,
                'student_id'  => $studentId,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            Student::where('id', $studentId)
                ->update(['class_group' => $newClass->name]);
        });

        return back()->with('success', 'Siswa berhasil dipindahkan');
    }
    /* ==========================
     |  PRINT ATTENDANCE (PDF)
     ========================== */
    public function printAttendance(Classroom $classroom)
    {
        $classroom->load(['students', 'level.stage', 'major']);
        $pdf = PDF::loadView('academic.classrooms.print_attendance', compact('classroom'));
        return $pdf->setPaper('a4', 'portrait')->stream('Absensi_Kelas_' . $classroom->name . '.pdf');
    }
}
