<?php

namespace App\Http\Controllers\Academic\Student;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\StudentExit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GraduationController extends Controller
{
    // Halaman 1: Pilih Kelas
    public function index()
    {
        // Ambil kelas hanya yang ada di Tahun Ajaran Aktif
        $activeYear = \App\Models\AcademicYear::where('is_active', true)->first();
        
        $classrooms = Classroom::where('academic_year_id', $activeYear->id)
            ->with(['level', 'students' => function($q) {
                // Hitung hanya siswa aktif
                $q->where('status', 'active');
            }])
            ->get();

        return view('academic.graduation.index', compact('classrooms', 'activeYear'));
    }

    // Halaman 2: Form Checklist Siswa
    public function create(Classroom $classroom)
    {
        // Ambil siswa di kelas ini yang statusnya masih ACTIVE
        $students = $classroom->students()->where('status', 'active')->orderBy('name')->get();

        return view('academic.graduation.create', compact('classroom', 'students'));
    }

    // Proses Simpan Massal
    public function store(Request $request, Classroom $classroom)
    {
        $request->validate([
            'student_ids' => 'required|array', // Harus array (hasil checklist)
            'student_ids.*' => 'exists:students,id',
            'exit_date' => 'required|date',
            'sk_number' => 'nullable|string', // SK Keputusan Bersama
            'note' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Loop setiap ID siswa yang dicentang
                foreach ($request->student_ids as $studentId) {
                    
                    // 1. Buat Record di student_exits
                    StudentExit::create([
                        'student_id' => $studentId,
                        'category' => 'graduated',
                        'exit_date' => $request->exit_date,
                        'exit_year' => date('Y', strtotime($request->exit_date)),
                        'sk_number' => $request->sk_number,
                        'reason' => 'Lulus dari satuan pendidikan', // Default text
                        'note' => $request->note,
                    ]);

                    // 2. Update Status Siswa jadi graduated
                    Student::where('id', $studentId)->update(['status' => 'graduated']);
                }
            });

            return redirect()->route('graduation.index')
                ->with('success', count($request->student_ids) . ' Santri berhasil diluluskan.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses: ' . $e->getMessage());
        }
    }
}