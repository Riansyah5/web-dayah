<?php

namespace App\Http\Controllers\Academic\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

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
        $alumni = $query->latest('updated_at')->paginate(20)->withQueryString();

        // Data pendukung untuk Dropdown Filter Tahun
        $years = \App\Models\StudentExit::distinct()->orderBy('exit_year', 'desc')->pluck('exit_year');

        return view('academic.alumni.index', compact('alumni', 'years'));
    }
}