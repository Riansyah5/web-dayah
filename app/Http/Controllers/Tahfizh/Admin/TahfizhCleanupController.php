<?php

namespace App\Http\Controllers\Tahfizh\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TahfizhJournal;
// use App\Models\TahfizhAttendance;
use App\Models\TahfizhCleanupLog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TahfizhCleanupController extends Controller
{
    public function index()
    {
        $recentLogs = TahfizhCleanupLog::with('admin')->latest()->take(10)->get();
        
        // Estimasi data yang bisa dibersihkan (lebih dari 1 bulan)
        $oldDate = Carbon::now()->subMonths(6);
        $stats = [
            'old_photos' => TahfizhJournal::whereDate('date', '<', $oldDate)->whereNotNull('photo_proof')->count(),
            'old_records' => TahfizhJournal::whereDate('date', '<', $oldDate)->count(),
        ];

        return view('system.maintenance.tahfizh.index', compact('recentLogs', 'stats', 'oldDate'));
    }

    public function runCleanup(Request $request)
    {
        $request->validate([
            'type' => 'required|in:photos,all_data',
            'months' => 'required|integer|min:1'
        ]);

        $cutoffDate = Carbon::now()->subMonths($request->months);
        $deletedCount = 0;

        if ($request->type == 'photos') {
            // HANYA HAPUS FOTO
            $journals = TahfizhJournal::where('date', '<', $cutoffDate->format('Y-m-d'))
                            ->whereNotNull('photo_proof')
                            ->get();

            foreach ($journals as $journal) {
                if (Storage::disk('public')->exists($journal->photo_proof)) {
                    Storage::disk('public')->delete($journal->photo_proof);
                }
                $journal->update(['photo_proof' => null]);
                $deletedCount++;
            }
        } else {
            // HAPUS SEMUA RECORD (Jurnal & Absensi Santri)
            // Hati-hati: Ini menghapus record database secara permanen
            $journals = TahfizhJournal::where('date', '<', $cutoffDate->format('Y-m-d'))->get();
            
            foreach ($journals as $j) {
                // Hapus foto jika ada
                if ($j->photo_proof && Storage::disk('public')->exists($j->photo_proof)) {
                    Storage::disk('public')->delete($j->photo_proof);
                }
                $deletedCount++;
                $j->delete(); // Cascades to attendances if setup in migration
            }
        }

        // Catat di Log
        TahfizhCleanupLog::create([
            'cleanup_type' => $request->type,
            'total_deleted' => $deletedCount,
            'period_threshold' => $request->months . ' months',
            'admin_id' => Auth::id(),
        ]);

        return back()->with('success', "Proses pembersihan selesai. $deletedCount item telah dihapus.");
    }
}