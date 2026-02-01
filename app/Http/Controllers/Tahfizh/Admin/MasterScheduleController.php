<?php

namespace App\Http\Controllers\Tahfizh\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahfizhSchedule;
use App\Models\TahfizhJournal;
use Illuminate\Http\Request;

class MasterScheduleController extends Controller
{
    public function index()
    {
        // Kelompokkan jadwal berdasarkan Hari agar mudah dibaca
        // 1=Senin, 7=Minggu
        $schedules = TahfizhSchedule::orderBy('day_of_week')
                        ->orderBy('start_time')
                        ->get()
                        ->groupBy('day_of_week');

        return view('tahfizh.admin.schedule.index', compact('schedules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'session_name' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'days' => 'required|array', // Admin bisa pilih banyak hari sekaligus
        ]);

        // Loop create untuk setiap hari yang dipilih
        foreach ($request->days as $day) {
            TahfizhSchedule::create([
                'session_name' => $request->session_name,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'day_of_week' => $day,
                'is_active' => true,
                'color' => $request->color ?? '#0d6efd'
            ]);
        }

        return back()->with('success', 'Jadwal baru berhasil ditambahkan.');
    }

    
    public function update(Request $request, $id)
    {
        $schedule = TahfizhSchedule::findOrFail($id);
        
        // Simpan nama lama untuk referensi pencarian bulk update
        $oldSessionName = $schedule->session_name;

        $request->validate([
            'session_name' => 'required',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        // Data yang akan diupdate
        $dataToUpdate = [
            'session_name' => $request->session_name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ];

        // LOGIKA BULK UPDATE
        if ($request->has('update_all_days') && $request->update_all_days == '1') {
            
            // Update SEMUA jadwal yang memiliki nama sesi yang sama (Case Insensitive)
            // Contoh: Mengubah semua "Qabla Shubuh" dari Senin-Minggu
            TahfizhSchedule::where('session_name', $oldSessionName)
                ->update($dataToUpdate);

            return back()->with('success', "Jadwal '$oldSessionName' berhasil diperbarui untuk SEMUA HARI.");
        } else {
            // Update HANYA SATU (Default)
            $schedule->update($dataToUpdate);
            
            return back()->with('success', 'Jadwal berhasil diperbarui.');
        }
    }

    // LOGIKA PENTING: HAPUS AMAN
    public function destroy($id)
    {
        $schedule = TahfizhSchedule::findOrFail($id);

        // 1. Cek apakah jadwal ini sudah pernah dipakai untuk absen?
        $isUsed = TahfizhJournal::where('tahfizh_schedule_id', $id)->exists();

        if ($isUsed) {
            // JIKA SUDAH DIPAKAI -> JANGAN HAPUS, TAPI NONAKTIFKAN
            // Agar history laporan tahun lalu tidak rusak
            $schedule->update(['is_active' => false]);
            return back()->with('warning', 'Jadwal ini memiliki riwayat absen. Data tidak dihapus, hanya dinonaktifkan (Diarsipkan).');
        } 

        // JIKA BELUM PERNAH DIPAKAI -> HAPUS PERMANEN
        $schedule->delete();
        return back()->with('success', 'Jadwal berhasil dihapus permanen.');
    }
    
    // Fitur Re-Activate (Jika ingin mengaktifkan lagi jadwal lama)
    public function toggleStatus($id)
    {
        $schedule = TahfizhSchedule::findOrFail($id);
        $schedule->update(['is_active' => !$schedule->is_active]);
        return back()->with('success', 'Status jadwal diperbarui.');
    }
}