<?php

namespace App\Http\Controllers\Tahfizh;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\TahfizhSetting;
use Illuminate\Http\Request;

class TahfizhSettingController extends Controller
{
    public function index()
    {
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();
        
        // Ambil setting tahun ini, atau buat objek kosong jika belum ada
        $setting = TahfizhSetting::firstOrNew(['academic_year_id' => $activeYear->id]);

        return view('tahfizh.setting.index', compact('activeYear', 'setting'));
    }

    public function update(Request $request)
    {
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();

        $request->validate([
            'city' => 'required|string',
            'distribution_date' => 'required|date',
            // 'headmaster_name' => 'required|string',
        ]);

        TahfizhSetting::updateOrCreate(
            ['academic_year_id' => $activeYear->id],
            [
                'city' => $request->city,
                'distribution_date' => $request->distribution_date,
                // 'headmaster_name' => $request->headmaster_name,
                // 'headmaster_niy' => $request->headmaster_niy,
            ]
        );

        return back()->with('success', 'Pengaturan rapor tahfizh berhasil disimpan.');
    }
}