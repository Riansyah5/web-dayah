<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentExit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ReportSetting; // Import Model Setting
use Barryvdh\DomPDF\Facade\Pdf;

class StudentExitController extends Controller
{
    public function store(Request $request, Student $student)
    {
        // 1. Validasi Input
        $request->validate([
            'status' => 'required|in:graduated,moved,suspended,deceased', // Sesuai ENUM di database
            'exit_date' => 'required|date',
            'sk_number' => 'nullable|string|max:100',
            'reason' => 'nullable|string',
            'destination' => 'nullable|string', // Wajib jika status = moved (bisa diatur logic validasi kondisional)
            'note' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($request, $student) {
                // 2. Simpan Data ke Tabel Detail (student_exits)
                StudentExit::create([
                    'student_id' => $student->id,
                    'category' => $request->status, // graduated/moved/etc
                    'exit_date' => $request->exit_date,
                    'exit_year' => date('Y', strtotime($request->exit_date)), // Ambil tahun dari tanggal keluar
                    'sk_number' => $request->sk_number,
                    'reason' => $request->reason,
                    'destination' => $request->destination,
                    'note' => $request->note,
                ]);

                // 3. Update Status di Tabel Utama (students)
                $student->update([
                    'status' => $request->status
                ]);
            });

            return back()->with('success', 'Status santri berhasil diperbarui menjadi ' . ucfirst($request->status));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function printLetter(Student $student)
    {
        // 1. Pastikan siswa memang statusnya 'moved' (Pindah)
        if ($student->status !== 'moved' || !$student->exitDetail) {
            return back()->with('error', 'Data kepindahan siswa belum lengkap atau status bukan Pindah.');
        }

        // 2. Ambil Data Kelas Terakhir Siswa (Untuk tahu Jenjang & Tahun Ajaran)
        $lastClass = $student->classrooms()
            ->with(['academicYear', 'level'])
            ->latest('academic_year_id') // Asumsi ID tahun ajaran makin besar makin baru
            ->first();

        // 3. Ambil Data Kepala Sekolah dari ReportSetting
        // Logic: Ambil setting berdasarkan Tahun Ajaran saat dia keluar
        $headmaster = '..........................';
        $headmasterNip = '-';
        $city = 'Kota Santri';

        if ($lastClass) {
            $setting = ReportSetting::where('academic_year_id', $lastClass->academic_year_id)
                ->where('stage_id', $lastClass->level->stage_id) // SD/SMP/SMA
                ->first();

            if ($setting) {
                $headmaster = $setting->headmaster_name;
                $headmasterNip = $setting->headmaster_nip;
                $city = $setting->city;
            }
        }

        // 4. Load View PDF
        $pdf = Pdf::loadView('academic.alumni.exports.mutation-letter-pdf', compact(
            'student',
            'lastClass',
            'headmaster',
            'headmasterNip',
            'city'
        ));

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('Surat_Pindah_' . $student->name . '.pdf');
    }

    public function printSkl(Student $student)
    {
        // 1. Validasi: Pastikan statusnya Graduated
        if ($student->status !== 'graduated' || !$student->exitDetail) {
            return back()->with('error', 'Siswa ini belum diluluskan atau datanya tidak lengkap.');
        }

        // 2. Ambil Kelas Terakhir (Untuk mengambil setting Kop Surat & Kepala Sekolah tahun itu)
        $lastClass = $student->classrooms()
            ->with(['academicYear', 'level'])
            ->latest('academic_year_id')
            ->first();

        // 3. Ambil Setting Kepala Sekolah
        $headmaster = '..........................';
        $headmasterNip = '-';
        $city = 'Kota Santri';

        if ($lastClass) {
            $setting = ReportSetting::where('academic_year_id', $lastClass->academic_year_id)
                ->where('stage_id', $lastClass->level->stage_id)
                ->first();

            if ($setting) {
                $headmaster = $setting->headmaster_name;
                $headmasterNip = $setting->headmaster_nip;
                $city = $setting->city;
            }
        }

        // 4. Load View PDF SKL
        $pdf = Pdf::loadView('academic.alumni.exports.skl-pdf', compact(
            'student',
            'lastClass',
            'headmaster',
            'headmasterNip',
            'city'
        ));

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('SKL_' . $student->name . '.pdf');
    }
}
