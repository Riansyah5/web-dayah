<?php

namespace App\Http\Controllers\Tahfizh\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahfizhSchedule; // Pastikan Model sudah dibuat
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TahfizhScheduleController extends Controller
{
    public function index()
    {
        // 1. Ambil Data Jadwal, Kelompokkan berdasarkan Nama Sesi
        // Tujuannya agar kita bisa menampilkan: "Qabla Shubuh: 04:30 - 05:30" (Berlaku 6 Hari)
        $groupedSchedules = TahfizhSchedule::select('session_name', 'start_time', 'end_time', DB::raw('count(*) as total_days'))
                            ->groupBy('session_name', 'start_time', 'end_time')
                            ->orderBy('start_time')
                            ->get();

        return view('tahfizh.admin.schedule.index', compact('groupedSchedules'));
    }

    // UPDATE JADWAL GLOBAL (Massal)
    public function updateGlobal(Request $request)
    {
        $request->validate([
            'session_name' => 'required|string',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        // Logic Update Massal:
        // Ubah jam semua jadwal yang namanya "Qabla Shubuh" (misalnya), baik itu hari Senin maupun Jumat.
        TahfizhSchedule::where('session_name', $request->session_name)
            ->update([
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'updated_at' => now()
            ]);

        return back()->with('success', "Waktu sesi '{$request->session_name}' berhasil diperbarui untuk semua hari.");
    }
}