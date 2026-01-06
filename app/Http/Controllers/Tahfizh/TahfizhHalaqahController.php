<?php

namespace App\Http\Controllers\Tahfizh;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Models\TahfizhHalaqah;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TahfizhHalaqahController extends Controller
{
    // --- CRUD HALAQAH ---

    public function index()
    {
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();
        
        $halaqahs = TahfizhHalaqah::where('academic_year_id', $activeYear->id)
                    ->with('teacher')
                    ->withCount('students')
                    ->get();
        
        $teachers = Teacher::where('is_active', true)->orderBy('name')->get();

        return view('tahfizh.halaqah.index', compact('halaqahs', 'activeYear', 'teachers'));
    }

    public function create()
    {
        $teachers = Teacher::where('is_active', true)->orderBy('name')->get();
        return view('tahfizh.halaqah.create', compact('teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'teacher_id' => 'required|exists:teachers,id',
            'gender' => 'required|in:L,P',
        ]);

        $activeYear = AcademicYear::where('is_active', true)->first();

        TahfizhHalaqah::create([
            'academic_year_id' => $activeYear->id,
            'name' => $request->name,
            'teacher_id' => $request->teacher_id,
            'gender' => $request->gender,
            'description' => $request->description,
        ]);

        return redirect()->route('tahfizh.halaqah.index')->with('success', 'Halaqah berhasil dibuat.');
    }

    public function show(TahfizhHalaqah $halaqah)
    {
        // Load siswa yang ada di halaqah ini
        $halaqah->load(['students' => function($q){
            $q->orderBy('name');
        }, 'teacher']);

        // Ambil data siswa aktif yang BELUM punya halaqah (Untuk opsi dropdown tambah anggota)
        // Logic: Ambil siswa aktif yang ID-nya TIDAK ADA di tabel pivot tahfizh_students
        $availableStudents = Student::where('status', 'active')
            ->where('gender', $halaqah->gender) // Filter sesuai gender halaqah
            ->whereDoesntHave('tahfizhHalaqahs') // Asumsi relasi di model Student sudah dibuat
            ->orderBy('name')
            ->get();

        return view('tahfizh.halaqah.show', compact('halaqah', 'availableStudents'));
    }

    public function edit(TahfizhHalaqah $halaqah)
    {
        $teachers = Teacher::where('is_active', true)->orderBy('name')->get();
        return view('tahfizh.halaqah.edit', compact('halaqah', 'teachers'));
    }

    public function update(Request $request, TahfizhHalaqah $halaqah)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'teacher_id' => 'required',
            'gender' => 'required',
        ]);

        $halaqah->update($request->all());
        return redirect()->route('tahfizh.halaqah.index')->with('success', 'Data Halaqah diperbarui.');
    }

    public function destroy(TahfizhHalaqah $halaqah)
    {
        $halaqah->delete();
        return redirect()->route('tahfizh.halaqah.index')->with('success', 'Halaqah dihapus.');
    }

    // --- MEMBER MANAGEMENT (PLOTTING) ---

    public function addMember(Request $request, TahfizhHalaqah $halaqah)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
        ]);

        $studentIds = $request->student_ids;

        // 1. Cek siswa yang sudah punya halaqah (di kelompok manapun) agar tidak duplikat
        $existingStudents = DB::table('tahfizh_students')
            ->whereIn('student_id', $studentIds)
            ->pluck('student_id')
            ->toArray();

        // 2. Filter hanya siswa yang belum punya halaqah
        $newStudents = array_diff($studentIds, $existingStudents);

        if (empty($newStudents)) {
            return back()->with('error', 'Semua siswa yang dipilih sudah terdaftar di halaqah lain.');
        }

        // 3. Attach (Simpan ke Pivot) sekaligus
        $halaqah->students()->attach($newStudents);

        $count = count($newStudents);
        $message = "$count siswa berhasil ditambahkan.";

        if (count($existingStudents) > 0) {
            $message .= " (" . count($existingStudents) . " siswa dilewati karena sudah punya halaqah)";
        }

        return back()->with('success', $message);
    }

    public function removeMember(TahfizhHalaqah $halaqah, Student $student)
    {
        // Detach (Hapus dari Pivot)
        $halaqah->students()->detach($student->id);
        return back()->with('success', 'Siswa dikeluarkan dari halaqah.');
    }
}