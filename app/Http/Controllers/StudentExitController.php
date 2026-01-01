<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentExit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentExitController extends Controller
{
    public function store(Request $request, Student $student)
    {
        // 1. Validasi Input
        $request->validate([
            'status' => 'required|in:graduated,moved,suspended,deceased', // Sesuai ENUM di database
            'exit_date' => 'required|date',
            'sk_number' => 'nullable|string|max:100',
            'reason' => 'nullable|string',
            'destination' => 'nullable|string', // Wajib jika status = moved (bisa diatur logic validasi kondisional)
            'note' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($request, $student) {
                // 2. Simpan Data ke Tabel Detail (student_exits)
                StudentExit::create([
                    'student_id' => $student->id,
                    'category' => $request->status, // graduated/moved/etc
                    'exit_date' => $request->exit_date,
                    'exit_year' => date('Y', strtotime($request->exit_date)), // Ambil tahun dari tanggal keluar
                    'sk_number' => $request->sk_number,
                    'reason' => $request->reason,
                    'destination' => $request->destination,
                    'note' => $request->note,
                ]);

                // 3. Update Status di Tabel Utama (students)
                $student->update([
                    'status' => $request->status
                ]);
            });

            return back()->with('success', 'Status santri berhasil diperbarui menjadi ' . ucfirst($request->status));

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}