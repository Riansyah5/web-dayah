<?php

namespace App\Http\Controllers;

use App\Models\Violation;
use App\Models\Student;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // Import PDF Facade
use Carbon\Carbon;

class ViolationController extends Controller
{
    // Halaman Daftar Poin Pelanggaran Semua Santri
    public function indexAll(Request $request)
    {
        $students = \App\Models\Student::query()
            ->withSum('violations', 'points') // Menghitung total poin
            ->withCount('violations')         // Menghitung jumlah kasus
            ->get();

        // Statistik Singkat untuk Header
        $totalViolationsThisMonth = \App\Models\Violation::whereMonth('violation_date', now()->month)->count();
        $highestPointStudent = \App\Models\Student::withSum('violations', 'points')->orderByDesc('violations_sum_points')->first();

        return view('violations.all_index', compact('students', 'totalViolationsThisMonth', 'highestPointStudent'));
    }
    // Halaman Riwayat Pelanggaran per Santri
    public function index(Request $request, Student $student)
    {
        // Ambil data pelanggaran
        $query = $student->violations();

        // Filter Tanggal (Jika user mau download/lihat periode tertentu)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('violation_date', [$request->start_date, $request->end_date]);
        }

        $violations = $query->get()
            ->groupBy(function ($item) {
                // Grouping Level 1: Tahun Ajaran & Semester
                return $item->academic_year . ' - Semester ' . $item->semester;
            })->map(function ($group) {
                // Grouping Level 2: Bulan
                return $group->groupBy(function ($item) {
                    return Carbon::parse($item->violation_date)->translatedFormat('F Y');
                });
            });

        // Jika Request adalah PDF, langsung download
        if ($request->has('export_pdf')) {
            $pdf = Pdf::loadView('violations.pdf', compact('student', 'violations', 'request'));
            return $pdf->download('Laporan_Pelanggaran_' . $student->name . '.pdf');
        }

        return view('violations.index', compact('student', 'violations'));
    }

    // Simpan Pelanggaran Baru
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'violation_date' => 'required|date',
            'category' => 'required',
            'points' => 'required|numeric',
            'description' => 'required',
            'academic_year' => 'required',
            'semester' => 'required'
        ]);

        Violation::create($request->all());

        return back()->with('success', 'Pelanggaran berhasil dicatat.');
    }
}
