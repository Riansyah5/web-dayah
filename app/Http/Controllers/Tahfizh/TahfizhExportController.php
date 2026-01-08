<?php

namespace App\Http\Controllers\Tahfizh;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\TahfizhSetoran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class TahfizhExportController extends Controller
{
    // 1. TAMPILKAN FORM KONFIRMASI (PRA-CETAK)
    public function form(Student $student)
    {
        // Cari Juz mana saja yang sudah pernah disetor (Ziyadah)
        $juzHafalan = TahfizhSetoran::where('student_id', $student->id)
            ->where('type', 'ziyadah')
            ->select('juz')
            ->distinct()
            ->orderBy('juz')
            ->pluck('juz')
            ->toArray(); // Contoh output: [1, 29, 30]

        return view('tahfizh.export.form', compact('student', 'juzHafalan'));
    }

    // 2. GENERATE PDF
    public function print(Request $request, Student $student)
    {
        $isPdf = true;

        // Validasi input dari form konfirmasi
        $data = $request->validate([
            'letter_number' => 'required|string',
            'signer_name'   => 'required|string',
            'signer_role'   => 'required|string',
            'signer_address' => 'required|string',
            'juz_selected'  => 'required|array', // Juz yang dicentang
            'juz_selected.*' => 'integer',
            // OPSI tandatangan & stampel
            'show_signature' => 'nullable|boolean',
            'show_stamp'     => 'nullable|boolean',
        ]);

        $data['show_signature'] = $request->boolean('show_signature');
        $data['show_stamp']     = $request->boolean('show_stamp');


        // Hitung total juz yang dipilih
        $totalJuz = count($data['juz_selected']);

        // Format Rincian Hafalan (Mengubah array [1, 30] jadi string "1, 30")
        // Kita bisa urutkan biar rapi
        sort($data['juz_selected']);
        $rincianJuz = implode(', ', $data['juz_selected']);

        $pdf = Pdf::loadView('tahfizh.export.pdf', compact('student', 'data', 'totalJuz', 'rincianJuz', 'isPdf'));

        return $pdf->stream('Surat_Keterangan_Hafalan_' . $student->name . '.pdf');
    }

    // 3. PREVIEW PDF
    public function preview(Request $request, Student $student)
    {
        $isPdf = false;
        // Validasi input dari form konfirmasi
        $data = $request->validate([
            'letter_number' => 'required|string',
            'signer_name'   => 'required|string',
            'signer_role'   => 'required|string',
            'signer_address' => 'required|string',
            'juz_selected'  => 'required|array', // Juz yang dicentang
            'juz_selected.*' => 'integer',
            // OPSI tandatangan & stampel
            'show_signature' => 'nullable|boolean',
            'show_stamp'     => 'nullable|boolean',
        ]);

        $data['show_signature'] = $request->boolean('show_signature');
        $data['show_stamp']     = $request->boolean('show_stamp');


        // Hitung total juz yang dipilih
        $totalJuz = count($data['juz_selected']);

        // Format Rincian Hafalan (Mengubah array [1, 30] jadi string "1, 30")
        // Kita bisa urutkan biar rapi
        sort($data['juz_selected']);
        $rincianJuz = implode(', ', $data['juz_selected']);

        return view('tahfizh.export.pdf', compact(
            'student',
            'data',
            'totalJuz',
            'rincianJuz',
            'isPdf'
        ));
    }
}
