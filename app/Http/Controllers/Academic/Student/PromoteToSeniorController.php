<?php

namespace App\Http\Controllers\Academic\Student;

use App\Models\Student;
use App\Models\Classroom;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PromoteToSeniorController extends Controller
{
    // Halaman Form Promosi
    public function index()
    {
        // 1. Ambil Tahun Ajaran Aktif (Tujuan Masuk)
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();

        // 2. Ambil Daftar Kelas Tujuan (Hanya kelas level awal, misal Kelas 7 atau 10)
        // Disini kita ambil semua kelas aktif saja biar fleksibel
        $targetClasses = Classroom::where('academic_year_id', $activeYear->id)
                            ->orderBy('name')
                            ->get();

        return view('academic.promotion.index', compact('activeYear', 'targetClasses'));
    }

    // API untuk mencari Data Alumni (Dipanggil via AJAX/Select2)
    public function searchAlumni(Request $request)
    {
        $search = $request->get('q');
        
        $alumni = Student::where('status', 'graduated') // Hanya yang sudah lulus
                    ->where(function($q) use ($search) {
                        $q->where('name', 'like', "%$search%")
                          ->orWhere('nis', 'like', "%$search%");
                    })
                    ->limit(20)
                    ->get(['id', 'name', 'nis', 'gender']);

        return response()->json($alumni);
    }

    // Proses Eksekusi Promosi
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'new_nis'    => 'required|unique:students,nis,' . $request->student_id, // NIS baru harus unik (kecuali punya sendiri)
            'classroom_id' => 'required|exists:classrooms,id',
            'join_date'  => 'required|date',
        ]);

        DB::transaction(function() use ($request) {
            $student = Student::findOrFail($request->student_id);
            $newClass = Classroom::with('level')->findOrFail($request->classroom_id);

            // 1. Update Data Siswa
            $student->update([
                'status' => 'active',          // Aktifkan kembali
                'nis'    => $request->new_nis, // Update NIS Baru (SMA)
                // 'level_id' => $newClass->level_id, // Update Level sesuai kelas tujuan
                // Jika Anda punya kolom 'unit' atau 'school_level', update disini juga
            ]);

            // 2. Masukkan ke Kelas Baru (Tabel Pivot)
            // Menggunakan syncWithoutDetaching agar riwayat kelas lama (SMP) tidak hilang
            $student->classrooms()->attach($newClass->id, ['created_at' => now()]);
            
            // Opsional: Jika ada tabel 'mutations' atau log pergerakan siswa, catat disini.
        });

        return redirect()->route('students.promotion.index')
               ->with('success', 'Siswa berhasil dipromosikan ke jenjang baru (Aktif Kembali).');
    }
}
