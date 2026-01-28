<?php

namespace App\Http\Controllers\Tahfizh\Journal;

use App\Http\Controllers\Controller;
use App\Models\TahfizhSchedule;
use App\Models\TahfizhJournal;
use App\Models\TahfizhHalaqah;
use App\Models\TahfizhAttendance;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TahfizhJournalController extends Controller
{
    // 1. DASHBOARD HARIAN MUSYRIF
    public function index()
    {
        $teacher = Teacher::where('name', Auth::user()->name)->first();
        if (!$teacher) abort(403, 'Akun ini tidak terhubung dengan data Guru.');

        // Cek apakah guru ini punya kelompok halaqah?
        // Asumsi: 1 Guru pegang 1 Halaqah Utama (Bisa disesuaikan jika banyak)
        $halaqah = TahfizhHalaqah::where('teacher_id', $teacher->id)->first();

        if (!$halaqah) {
            return view('tahfizh.journal.no_group'); // Tampilan jika belum diassign admin
        }

        // Ambil Jadwal Global HARI INI (Scope ForToday di model tadi)
        $schedules = TahfizhSchedule::forToday()->orderBy('start_time')->get();

        // Cek status pengisian jurnal untuk setiap sesi jadwal
        // Kita map agar di view mudah diakses statusnya
        $journalStatus = [];
        $today = Carbon::now()->format('Y-m-d');

        foreach ($schedules as $sched) {
            $journal = TahfizhJournal::where('tahfizh_halaqah_id', $halaqah->id)
                ->where('tahfizh_schedule_id', $sched->id)
                ->where('date', $today)
                ->first();

            $journalStatus[$sched->id] = $journal; // Isinya null atau object jurnal
        }

        return view('tahfizh.journal.dashboard', compact('halaqah', 'schedules', 'journalStatus', 'today'));
    }

    // === LANGKAH 1: GURU BUKA HALAQAH ===

    public function createJournal(TahfizhSchedule $schedule)
    {
        $teacher = Teacher::where('name', Auth::user()->name)->first();
        $halaqah = TahfizhHalaqah::where('teacher_id', $teacher->id)->firstOrFail();
        $today = Carbon::now()->format('Y-m-d');

        // Cek jika sudah pernah buat jurnal hari ini, langsung lempar ke halaman absen santri
        $existingJournal = TahfizhJournal::where('tahfizh_halaqah_id', $halaqah->id)
            ->where('tahfizh_schedule_id', $schedule->id)
            ->where('date', $today)
            ->first();

        if ($existingJournal) {
            return redirect()->route('tahfizh.journal.attendance', $existingJournal->id);
        }

        return view('tahfizh.journal.create_header', compact('schedule', 'halaqah'));
    }

    public function storeJournalHeader(Request $request, TahfizhSchedule $schedule)
    {
        $request->validate([
            'photo_proof' => 'required|file|mimes:jpeg,png,jpg,gif,svg,webp,heic,heif|max:10240', // Support HEIC & up to 10MB
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $teacher = Teacher::where('name', Auth::user()->name)->first();
        $halaqah = TahfizhHalaqah::where('teacher_id', $teacher->id)->firstOrFail();
        $today = Carbon::now()->format('Y-m-d');

        // Simpan Foto
        $photoPath = $request->file('photo_proof')->store('tahfizh/journals', 'public');

        // Buat Jurnal Baru
        $journal = TahfizhJournal::create([
            'id' => (string) Str::ulid(),
            'tahfizh_halaqah_id' => $halaqah->id,
            'tahfizh_schedule_id' => $schedule->id,
            'teacher_id' => $teacher->id,
            'date' => $today,
            'clock_in' => now(),
            'photo_proof' => $photoPath,
            'note' => $request->note,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        // Redirect langsung ke halaman absen santri
        return redirect()->route('tahfizh.journal.attendance', $journal->id)
            ->with('success', 'Halaqah dibuka! Silakan absen santri.');
    }

    // === LANGKAH 2: ABSEN SANTRI (BISA UPDATE) ===

    public function editStudentAttendance(TahfizhJournal $journal)
    {
        // Load data siswa di halaqah ini
        $halaqah = $journal->halaqah;
        $students = $halaqah->students()->where('status', 'active')->orderBy('name')->get();

        // Load data kehadiran yang sudah tersimpan (jika ada)
        // Kita keyBy('student_id') agar mudah diakses di View
        $attendances = $journal->attendances->keyBy('student_id');

        return view('tahfizh.journal.student_attendance', compact('journal', 'students', 'attendances'));
    }

    public function updateStudentAttendance(Request $request, TahfizhJournal $journal)
    {
        $request->validate([
            'attendance' => 'required|array',
        ]);

        DB::transaction(function () use ($request, $journal) {
            foreach ($request->attendance as $studentId => $status) {
                // UpdateOrCreate: Jika data belum ada, buat baru. Jika sudah ada (misal update telat), timpa statusnya.
                TahfizhAttendance::updateOrCreate(
                    [
                        'tahfizh_journal_id' => $journal->id,
                        'student_id' => $studentId
                    ],
                    [
                        'status' => $status,
                        'note' => $request->student_notes[$studentId] ?? null
                    ]
                );
            }
        });

        return redirect()->route('tahfizh.journal.dashboard')
            ->with('success', 'Data kehadiran santri berhasil disimpan/diupdate.');
    }
}
