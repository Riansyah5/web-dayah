<?php

namespace App\Http\Controllers\Tahfizh\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\TahfizhHalaqah;
use App\Models\TeacherPermission;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TahfizhAttendanceReportController extends Controller
{
    // ===========================
    // 1. REKAP ABSENSI GURU
    // ===========================
    public function teacherRecap(Request $request)
    {
        // Default: Tanggal 1 s.d Hari ini bulan berjalan
        $startDate = $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? Carbon::now()->format('Y-m-d');

        // Ambil data Guru beserta relasi Jurnal & Izin pada rentang tanggal
        $teachers = Teacher::where('is_active', true)
            ->with(['tahfizhJournals' => function($q) use ($startDate, $endDate) {
                $q->whereBetween('date', [$startDate, $endDate]);
            }, 'teacherPermissions' => function($q) use ($startDate, $endDate) {
                $q->whereBetween('date', [$startDate, $endDate])
                  ->where('status', 'approved');
            }, 'tahfizhBadalsAsSubstitute' => function($q) use ($startDate, $endDate) {
                // Menghitung berapa kali dia jadi badal orang lain
                $q->whereBetween('date', [$startDate, $endDate]);
            }])
            ->orderBy('name')
            ->get();

        // Proses Data untuk View (Menghitung Total)
        $reportData = $teachers->map(function($teacher) {
            $hadir = $teacher->tahfizhJournals->count();
            
            // Hitung Telat (Asumsi logic sederhana: jika jam masuk > jam mulai jadwal)
            // Note: Idealnya disimpan di kolom is_late di jurnal agar query lebih cepat.
            // Disini kita hitung manual dari collection
            $telat = $teacher->tahfizhJournals->filter(function($journal) {
                // Load jadwal dari relasi (perlu eager load di query atas jika ingin performa tinggi)
                // Untuk simplifikasi, anggap logika telat sudah ditangani saat input
                return false; // Placeholder, bisa dikembangkan
            })->count();

            $izin = $teacher->teacherPermissions->count();
            $badal = $teacher->tahfizhBadalsAsSubstitute->count();

            return (object) [
                'id' => $teacher->id,
                'name' => $teacher->name,
                'hadir' => $hadir,
                'izin' => $izin,
                'badal' => $badal,
                'total_aktivitas' => $hadir + $izin // Total hari dia dibayar/dianggap ada
            ];
        });

        return view('tahfizh.admin.report.teacher', compact('reportData', 'startDate', 'endDate'));
    }

    // ===========================
    // 2. REKAP ABSENSI SANTRI
    // ===========================
    public function studentRecap(Request $request)
    {
        // Handle Filter Bulan (YYYY-MM)
        if ($request->month) {
            $date = Carbon::createFromFormat('Y-m', $request->month);
            $startDate = $date->copy()->startOfMonth()->format('Y-m-d');
            $endDate = $date->copy()->endOfMonth()->format('Y-m-d');
        } else {
            $startDate = $request->start_date ?? Carbon::now()->startOfMonth()->format('Y-m-d');
            $endDate = $request->end_date ?? Carbon::now()->format('Y-m-d');
        }

        $halaqahId = $request->halaqah_id;

        $halaqahs = TahfizhHalaqah::with('teacher')->get();
        $students = collect([]); // Kosong default
        $selectedHalaqah = null;

        if ($halaqahId) {
            $selectedHalaqah = TahfizhHalaqah::find($halaqahId);
            
            if ($selectedHalaqah) {
                // Ambil Santri via relasi halaqah -> students (Menghindari error kolom tidak ditemukan)
                $students = $selectedHalaqah->students()
                    ->with(['tahfizhAttendances' => function($q) use ($startDate, $endDate) {
                    $q->whereHas('tahfizhJournal', function($sq) use ($startDate, $endDate) {
                        $sq->whereBetween('date', [$startDate, $endDate]);
                    });
                }])
                ->orderBy('name')
                ->get()
                ->map(function($student) {
                    // Hitung Statistik
                    $sakit = $student->tahfizhAttendances->where('status', 'sakit')->count();
                    $izin = $student->tahfizhAttendances->where('status', 'izin')->count();
                    $alpha = $student->tahfizhAttendances->where('status', 'alpha')->count();
                    $hadir = $student->tahfizhAttendances->where('status', 'hadir')->count();
                    
                    return (object) [
                        'name' => $student->name,
                        'nis' => $student->nis, // atau id lain
                        'sakit' => $sakit,
                        'izin' => $izin,
                        'alpha' => $alpha,
                        'hadir' => $hadir,
                        'persentase' => ($hadir + $sakit + $izin + $alpha) > 0 
                                        ? round(($hadir / ($hadir + $sakit + $izin + $alpha)) * 100) 
                                        : 0
                    ];
                });
            }
        }

        return view('tahfizh.admin.report.student', compact('halaqahs', 'students', 'startDate', 'endDate', 'halaqahId', 'selectedHalaqah'));
    }
}