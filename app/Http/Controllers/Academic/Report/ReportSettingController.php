<?php

namespace App\Http\Controllers\Academic\Report;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Stage;
use App\Models\ReportSetting;
use Illuminate\Http\Request;

class ReportSettingController extends Controller
{
    public function index()
    {
        // Ambil Tahun Ajaran Aktif
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();
        
        // Ambil semua jenjang (SD, SMP, SMA)
        $stages = Stage::orderBy('id')->get();
        
        // Ambil settingan yang sudah tersimpan (jika ada)
        $settings = ReportSetting::where('academic_year_id', $activeYear->id)
                    ->get()
                    ->keyBy('stage_id'); // Biar mudah dipanggil: $settings[1]

        return view('academic.report.settings.index', compact('activeYear', 'stages', 'settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'academic_year_id' => 'required',
            'settings' => 'required|array', // Array input dari form
        ]);

        foreach ($request->settings as $stageId => $data) {
            // Validasi sederhana: Nama KS dan Tanggal wajib diisi
            if (!empty($data['headmaster_name']) && !empty($data['report_date'])) {
                
                ReportSetting::updateOrCreate(
                    [
                        'academic_year_id' => $request->academic_year_id,
                        'stage_id' => $stageId,
                    ],
                    [
                        'headmaster_name' => $data['headmaster_name'],
                        'headmaster_nip' => $data['headmaster_nip'],
                        'report_date' => $data['report_date'],
                        'city' => $data['city'] ?? 'Kota Santri',
                    ]
                );
            }
        }

        return back()->with('success', 'Pengaturan rapor berhasil disimpan.');
    }
}