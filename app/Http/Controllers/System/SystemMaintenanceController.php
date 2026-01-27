<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\TeachingJournal;
use App\Models\TeacherPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SystemMaintenanceController extends Controller
{
    // Halaman Utama Maintenance
    public function index()
    {
        // Hitung statistik sederhana (Opsional, agar admin tahu beban server)
        $journalCount = TeachingJournal::whereNotNull('photo_proof')->count();
        $permissionCount = TeacherPermission::whereNotNull('attachment')->count();
        
        return view('system.maintenance.index', compact('journalCount', 'permissionCount'));
    }

    // Proses Pembersihan
    public function cleanup(Request $request)
    {
        $request->validate([
            'target' => 'required|in:journals,permissions,all',
            'period' => 'required|in:3_months,6_months,1_year',
        ]);

        // Tentukan batas tanggal (Cutoff Date)
        $cutoffDate = match($request->period) {
            '3_months' => Carbon::now()->subMonths(3),
            '6_months' => Carbon::now()->subMonths(6),
            '1_year'   => Carbon::now()->subYear(),
        };

        $deletedCount = 0;

        // 1. BERSIHKAN FOTO JURNAL
        if ($request->target == 'journals' || $request->target == 'all') {
            
            // Ambil jurnal lama yang punya foto
            $journals = TeachingJournal::where('date', '<=', $cutoffDate)
                        ->whereNotNull('photo_proof')
                        ->get();

            foreach ($journals as $journal) {
                // Hapus file fisik
                if (Storage::disk('public')->exists($journal->photo_proof)) {
                    Storage::disk('public')->delete($journal->photo_proof);
                }
                
                // Set kolom database jadi NULL
                $journal->update(['photo_proof' => null]);
                $deletedCount++;
            }
        }

        // 2. BERSIHKAN LAMPIRAN IZIN
        if ($request->target == 'permissions' || $request->target == 'all') {
            
            $permissions = TeacherPermission::where('date', '<=', $cutoffDate)
                            ->whereNotNull('attachment')
                            ->get();

            foreach ($permissions as $perm) {
                if (Storage::disk('public')->exists($perm->attachment)) {
                    Storage::disk('public')->delete($perm->attachment);
                }

                $perm->update(['attachment' => null]);
                $deletedCount++;
            }
        }

        return back()->with('success', "Berhasil menghapus $deletedCount file arsip lama.");
    }
}