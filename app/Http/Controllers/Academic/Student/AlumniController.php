<?php

namespace App\Http\Controllers\Academic\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentExit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlumniController extends Controller
{
    public function index(Request $request)
    {
        // 1. Inisialisasi Query (Ambil siswa yang statusnya BUKAN active)
        $query = Student::with('exitDetail') // Eager load detail kelulusan
                    ->whereIn('status', ['graduated', 'moved', 'suspended', 'deceased']);

        // 2. Filter Pencarian Nama / NIS
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        // 3. Filter Tahun Keluar (Lulus/Pindah)
        // Kita filter berdasarkan tabel relasi 'exitDetail'
        if ($request->filled('year')) {
            $query->whereHas('exitDetail', function($q) use ($request) {
                $q->where('exit_year', $request->year);
            });
        }

        // 4. Filter Kategori (Lulus / Pindah)
        if ($request->filled('category')) {
            $query->where('status', $request->category);
        }

        // 5. Urutkan dari yang terbaru keluar
        $alumni = $query->latest('updated_at')->get();

        // Data pendukung untuk Dropdown Filter Tahun
        $years = StudentExit::distinct()->orderBy('exit_year', 'desc')->pluck('exit_year');

        return view('academic.alumni.index', compact('alumni', 'years'));
    }

    public function update(Request $request, Student $student)
    {
        if ($student->status === 'active') {
            return back()->with('error', 'Santri berstatus aktif tidak dapat diedit melalui direktori alumni.');
        }

        $validated = $request->validate([
            'status' => 'required|in:graduated,moved,suspended,deceased',
            'exit_date' => 'required|date',
            'sk_number' => 'nullable|string|max:100',
            'destination' => 'nullable|string|max:255',
            'reason' => 'nullable|string|max:1000',
            'note' => 'nullable|string|max:1000',
        ]);

        try {
            DB::transaction(function () use ($validated, $student) {
                StudentExit::updateOrCreate(
                    ['student_id' => $student->id],
                    [
                        'category' => $validated['status'],
                        'exit_date' => $validated['exit_date'],
                        'exit_year' => date('Y', strtotime($validated['exit_date'])),
                        'sk_number' => $validated['sk_number'] ?? null,
                        'destination' => $validated['destination'] ?? null,
                        'reason' => $validated['reason'] ?? null,
                        'note' => $validated['note'] ?? null,
                    ]
                );

                $student->update([
                    'status' => $validated['status'],
                ]);
            });

            return back()->with('success', 'Data mutasi/alumni ' . $student->name . ' berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function restore(Student $student)
    {
        if ($student->status === 'active') {
            return back()->with('error', 'Santri ' . $student->name . ' sudah berstatus aktif.');
        }

        try {
            DB::transaction(function () use ($student) {
                $student->exitDetail()->delete();
                $student->update([
                    'status' => 'active',
                ]);
            });

            return back()->with('success', 'Santri ' . $student->name . ' berhasil dikembalikan ke status Aktif. Silakan cek penempatan kelas dan kamar jika diperlukan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengembalikan status santri: ' . $e->getMessage());
        }
    }
}