<?php

namespace App\Http\Controllers\Tahfizh;

use App\Models\Student;
use App\Models\QuranSurah;
use Illuminate\Http\Request;
use App\Models\TahfizhSetoran;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TahfizhSetoranController extends Controller
{
    // Halaman Form Input untuk Siswa Tertentu
    public function create(Student $student)
    {
        // Ambil semua surat untuk dropdown
        $surahs = QuranSurah::select('id', 'name_latin', 'total_verses')->get();
        
        // Ambil riwayat setoran terakhir (untuk referensi guru)
        $lastSetoran = TahfizhSetoran::where('student_id', $student->id)
                        ->with(['surahEnd'])
                        ->latest('date')
                        ->latest('id')
                        ->first();

        $activeHalaqah = $student->tahfizhHalaqahs()
            ->whereHas('academicYear', function ($q) {
                $q->where('is_active', true);
            })
            ->first();

        return view('tahfizh.setoran.create', compact('student', 'surahs', 'lastSetoran', 'activeHalaqah'));
    }

    public function store(Request $request, Student $student)
    {
        $request->validate([
            'date' => 'required|date',
            'type' => 'required',
            'juz' => 'required|integer|min:1|max:30',
            'surah_start_id' => 'required|exists:quran_surahs,id',
            'ayat_start' => 'required|integer|min:1',
            'surah_end_id' => 'required|exists:quran_surahs,id',
            'ayat_end' => 'required|integer|min:1',
            'quality' => 'required'
        ]);

        // Opsional: Validasi Ayat End tidak boleh melebihi jumlah ayat surat
        $surahEnd = QuranSurah::find($request->surah_end_id);
        if ($request->ayat_end > $surahEnd->total_verses) {
            return back()->withInput()->withErrors(['ayat_end' => 'Ayat melebihi jumlah ayat surat ' . $surahEnd->name_latin . ' (' . $surahEnd->total_verses . ')']);
        }

        // Validasi Batas Juz: Pastikan ayat akhir tidak melewati batas Juz yang dipilih
        $juzStandards = [
            1 => 148, 2 => 111, 3 => 126, 4 => 131, 5 => 124,
            6 => 110, 7 => 149, 8 => 142, 9 => 159, 10 => 127,
            11 => 151, 12 => 170, 13 => 154, 14 => 227, 15 => 185,
            16 => 269, 17 => 190, 18 => 202, 19 => 339, 20 => 171,
            21 => 178, 22 => 169, 23 => 357, 24 => 175, 25 => 246,
            26 => 195, 27 => 216, 28 => 137, 29 => 431, 30 => 564
        ];

        $juzEndLimit = 0;
        for ($i = 1; $i <= $request->juz; $i++) {
            $juzEndLimit += $juzStandards[$i];
        }
        $juzStartLimit = $juzEndLimit - $juzStandards[$request->juz];

        $surahs = QuranSurah::orderBy('id')->get();
        
        $inputAbsStart = 0;
        foreach ($surahs as $s) {
            if ($s->id == $request->surah_start_id) {
                $inputAbsStart += $request->ayat_start;
                break;
            }
            $inputAbsStart += $s->total_verses;
        }

        $inputAbsEnd = 0;
        foreach ($surahs as $s) {
            if ($s->id == $request->surah_end_id) {
                $inputAbsEnd += $request->ayat_end;
                break;
            }
            $inputAbsEnd += $s->total_verses;
        }

        if ($inputAbsStart <= $juzStartLimit) {
            return back()->withInput()->withErrors(['ayat_start' => 'Ayat awal tidak masuk dalam cakupan Juz ' . $request->juz . ' (Kurang dari batas awal).']);
        }

        if ($inputAbsStart > $juzEndLimit) {
            return back()->withInput()->withErrors(['ayat_start' => 'Ayat awal melewati batas Juz ' . $request->juz . '.']);
        }

        if ($inputAbsEnd > $juzEndLimit) {
            return back()->withInput()->withErrors(['ayat_end' => 'Ayat akhir melewati batas Juz ' . $request->juz . '.']);
        }

        // Validasi Ziyadah: Cek apakah ayat ini sudah pernah disetor sebagai Ziyadah sebelumnya
        if ($request->type == 'ziyadah') {
            $offsets = [];
            $cumulative = 0;
            foreach ($surahs as $s) {
                $offsets[$s->id] = $cumulative;
                $cumulative += $s->total_verses;
            }

            $newStart = $offsets[$request->surah_start_id] + $request->ayat_start;
            $newEnd = $offsets[$request->surah_end_id] + $request->ayat_end;

            $existingZiyadahs = TahfizhSetoran::where('student_id', $student->id)
                ->where('type', 'ziyadah')
                ->get();

            foreach ($existingZiyadahs as $existing) {
                $exStart = $offsets[$existing->surah_start_id] + $existing->ayat_start;
                $exEnd = $offsets[$existing->surah_end_id] + $existing->ayat_end;

                if (max($newStart, $exStart) <= min($newEnd, $exEnd)) {
                    return back()->withInput()->withErrors(['type' => 'Ayat ini sudah pernah disetor sebagai Ziyadah. Silakan pilih tipe Muraja\'ah atau cek kembali ayat.']);
                }
            }
        }

        // Ambil Musyrif (Teacher) dari Halaqah Aktif Santri
        $activeHalaqah = $student->tahfizhHalaqahs()
            ->whereHas('academicYear', function ($q) {
                $q->where('is_active', true);
            })
            ->first();

        TahfizhSetoran::create([
            'student_id' => $student->id,
            'teacher_id' => $activeHalaqah?->teacher_id, // Mengambil ID Musyrif dari Halaqah
            'date' => $request->date,
            'type' => $request->type,
            'juz' => $request->juz,
            'surah_start_id' => $request->surah_start_id,
            'ayat_start' => $request->ayat_start,
            'surah_end_id' => $request->surah_end_id,
            'ayat_end' => $request->ayat_end,
            'quality' => $request->quality,
            'note' => $request->note,
        ]);

        return redirect()->route('tahfizh.halaqah.show', $activeHalaqah->id ?? 0) // Redirect kembali ke halaqah
                ->with('success', 'Setoran berhasil dicatat.');
    }
}