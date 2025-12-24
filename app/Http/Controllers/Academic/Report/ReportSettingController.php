<?php

namespace App\Http\Controllers\Academic\Report;

use App\Models\Stage;
use App\Models\Pegawai;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Models\ReportSetting;
use App\Http\Controllers\Controller;

class ReportSettingController extends Controller
{
    public function index()
    {
        // Ambil Tahun Ajaran Aktif
        $activeYear = AcademicYear::where('is_active', true)->firstOrFail();
        
        // Ambil semua jenjang (SD, SMP, SMA)
        $stages = Stage::orderBy('id')->get();

        // Ambil semua data pegawai
        $employees = Pegawai::orderBy('nama')->get();

        
        // Ambil settingan yang sudah tersimpan (jika ada)
        $settings = ReportSetting::where('academic_year_id', $activeYear->id)
                    ->get()
                    ->keyBy('stage_id'); // Biar mudah dipanggil: $settings[1]

        return view('academic.report.settings.index', compact('activeYear', 'stages', 'settings', 'employees'));
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