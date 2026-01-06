<?php

namespace App\Http\Controllers\Tahfizh;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\TahfizhSetoran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TahfizhReportController extends Controller
{
    // Halaman Detail Rapor Tahfizh Per Santri
    public function show(Student $student)
    {
        // 1. Data Ringkasan Atas
        $totalSetoran = TahfizhSetoran::where('student_id', $student->id)->count();
        $totalZiyadah = TahfizhSetoran::where('student_id', $student->id)->where('type', 'ziyadah')->count();
        $lastSetoran = TahfizhSetoran::where('student_id', $student->id)->latest('date')->first();

        // 2. Data Grafik 1: Keaktifan Setoran 6 Bulan Terakhir
        // Mengelompokkan jumlah setoran berdasarkan Bulan
        $activityData = TahfizhSetoran::select(
                            DB::raw('count(id) as total'), 
                            DB::raw("DATE_FORMAT(date, '%Y-%m') as month_year")
                        )
                        ->where('student_id', $student->id)
                        ->where('date', '>=', Carbon::now()->subMonths(6)) // Ambil 6 bulan terakhir
                        ->groupBy('month_year')
                        ->orderBy('month_year')
                        ->get();

        // Format data untuk Chart.js
        $months = [];
        $counts = [];
        foreach($activityData as $data) {
            $months[] = Carbon::createFromFormat('Y-m', $data->month_year)->translatedFormat('F Y');
            $counts[] = $data->total;
        }

        // 3. Data Grafik 2: Kualitas Hafalan (Pie Chart)
        $qualityData = TahfizhSetoran::select('quality', DB::raw('count(*) as total'))
                        ->where('student_id', $student->id)
                        ->groupBy('quality')
                        ->pluck('total', 'quality')
                        ->all();
        
        // Mapping agar urutannya konsisten
        $pieData = [
            $qualityData['lancar'] ?? 0,
            $qualityData['kurang'] ?? 0,
            $qualityData['ulang'] ?? 0,
        ];

        // 4. Riwayat Tabel (10 Terakhir)
        $history = TahfizhSetoran::where('student_id', $student->id)
                    ->with(['surahStart', 'surahEnd'])
                    ->latest('date')
                    ->limit(10)
                    ->get();

        return view('tahfizh.report.show', compact(
            'student', 
            'totalSetoran', 'totalZiyadah', 'lastSetoran',
            'months', 'counts', 'pieData', 'history'
        ));
    }
}