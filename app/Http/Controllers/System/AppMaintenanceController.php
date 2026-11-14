<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;

class AppMaintenanceController extends Controller
{
    /**
     * Halaman Pengaturan Mode Pemeliharaan (Khusus Superadmin)
     */
    public function index()
    {
        $maintenance = AppSetting::getMaintenanceData();
        return view('system.maintenance.setting', compact('maintenance'));
    }

    /**
     * Simpan Perubahan Mode Pemeliharaan
     */
    public function update(Request $request)
    {
        $request->validate([
            'is_maintenance' => 'nullable|in:0,1',
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'end_at' => 'nullable|date',
            'contact_phone' => 'nullable|string|max:30',
        ]);

        $isMaintenance = $request->has('is_maintenance') && $request->is_maintenance == '1';

        AppSetting::set('maintenance_mode', $isMaintenance);
        AppSetting::set('maintenance_title', $request->title);
        AppSetting::set('maintenance_message', $request->message);
        AppSetting::set('maintenance_end_at', $request->end_at);
        AppSetting::set('maintenance_contact_phone', $request->contact_phone);

        $statusText = $isMaintenance ? 'diaktifkan (aplikasi dinonaktifkan untuk umum)' : 'dinonaktifkan (aplikasi dapat diakses normal kembali)';

        return back()->with('success', "Mode pemeliharaan berhasil {$statusText}.");
    }

    /**
     * Tampilan Khusus Pengguna / Tamu saat Aplikasi Nonaktif
     */
    public function publicScreen()
    {
        $maintenance = AppSetting::getMaintenanceData();
        return view('system.maintenance.screen', compact('maintenance'));
    }
}
