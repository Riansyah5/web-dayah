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
                        ->first();

        return view('tahfizh.setoran.create', compact('student', 'surahs', 'lastSetoran'));
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