<?php

namespace App\Http\Controllers\Tahfizh;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\TahfizhMonthlyReport;
use App\Models\TahfizhSetoran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TahfizhReportController extends Controller
{
    // Halaman Detail Rapor Tahfizh Per Santri
    public function show(Student $student)
    {
        // 1. Data Ringkasan Atas
        $totalSetoran = TahfizhSetoran::where('student_id', $student->id)->count();
        $totalZiyadah = TahfizhSetoran::where('student_id', $student->id)->where('type', 'ziyadah')->count();
        $lastSetoran = TahfizhSetoran::where('student_id', $student->id)->latest('date')->latest('id')->first();

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
        foreach ($activityData as $data) {
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

        // === LOGIKA BARU: PETA 30 JUZ ===

        // 1. Referensi Standar Jumlah Ayat Per Juz (Estimasi Mushaf Madinah)
        // Array Key = Nomor Juz, Value = Total Ayat
        $juzStandards = [
            1 => 148,
            2 => 111,
            3 => 126,
            4 => 131,
            5 => 124,
            6 => 110,
            7 => 149,
            8 => 142,
            9 => 159,
            10 => 127,
            11 => 151,
            12 => 170,
            13 => 154,
            14 => 227,
            15 => 185,
            16 => 269,
            17 => 190,
            18 => 202,
            19 => 339,
            20 => 171,
            21 => 178,
            22 => 169,
            23 => 357,
            24 => 175,
            25 => 246,
            26 => 195,
            27 => 216,
            28 => 137,
            29 => 431,
            30 => 564
        ];

        // 2. Hitung Total Ayat yang sudah disetor Siswa per Juz
        // Kita gunakan GROUP BY juz
        $studentProgress = TahfizhSetoran::where('student_id', $student->id)
            ->where('type', 'ziyadah') // Hanya hitung hafalan baru
            ->select('juz', DB::raw('SUM(ayat_end - ayat_start + 1) as collected'))
            ->groupBy('juz')
            ->pluck('collected', 'juz') // Hasil: [Juz => JumlahAyat]
            ->toArray();

        // 3. Tentukan Status Per Juz
        $juzStatus = [];
        $totalVersesHafal = 0;

        for ($i = 1; $i <= 30; $i++) {
            $collected = $studentProgress[$i] ?? 0;
            $standard = $juzStandards[$i];

            $totalVersesHafal += $collected;

            // Logika Penentuan Status
            if ($collected >= $standard) {
                // Jika ayat yg disetor >= standar, dianggap KHATAM
                // (Note: >= untuk antisipasi jika ada overlapping setoran)
                $juzStatus[$i] = [
                    'status' => 'khatam',
                    'color' => 'btn-success', // Hijau Pekat
                    'percent' => 100
                ];
            } elseif ($collected > 0) {
                // Jika ada setoran tapi belum mencapai target
                $percent = round(($collected / $standard) * 100);
                $juzStatus[$i] = [
                    'status' => 'process',
                    'color' => 'btn-warning text-dark', // Kuning/Oranye
                    'percent' => $percent
                ];
            } else {
                // Belum disentuh
                $juzStatus[$i] = [
                    'status' => 'none',
                    'color' => 'btn-outline-light text-muted border-0 bg-light', // Abu-abu
                    'percent' => 0
                ];
            }
        }

        return view('tahfizh.report.show', compact(
            'student',
            'totalSetoran',
            'totalZiyadah',
            'lastSetoran',
            // ... Variable chart & history ...
            'months',
            'counts',
            'pieData',
            'history',
            // Variable Baru:
            'juzStatus',
            'totalVersesHafal'
        ));
    }

    public function storeHours(Request $request, $teacherId)
    {
        $request->validate([
            'total_hours' => 'required|integer|min:0',
            'period' => 'required|date_format:Y-m',
        ]);

        TahfizhMonthlyReport::updateOrCreate(
            [
                'teacher_id' => $teacherId,
                'period' => $request->period
            ],
            [
                'total_hours' => $request->total_hours
            ]
        );

        return back()->with('success', 'Total jam halaqah berhasil diperbarui.');
    }
}
