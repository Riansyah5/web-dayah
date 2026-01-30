<?php

namespace App\Http\Controllers\Tahfizh\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahfizhSchedule;
use App\Models\TahfizhHalaqah;
use App\Models\TahfizhJournal;
use App\Models\TahfizhSubstitute;
use App\Models\TeacherPermission;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TahfizhMonitoringController extends Controller
{
    // 1. TAMPILAN HALAMAN UTAMA
    public function index()
    {
        // Ambil semua jadwal untuk dropdown filter
        $allSchedules = TahfizhSchedule::orderBy('order_index')->get()->unique('session_name');

        // Ambil guru aktif untuk dropdown badal
        $teachers = Teacher::where('is_active', true)->orderBy('name')->get();

        return view('tahfizh.admin.monitoring.index', compact('allSchedules', 'teachers'));
    }

    // 2. API DATA REALTIME (Dipanggil via AJAX tiap 10 detik)
    public function getRealtimeData(Request $request)
    {
        $date = Carbon::now()->format('Y-m-d');
        $timeNow = Carbon::now()->format('H:i:s');

        // A. Tentukan Jadwal Mana yang Aktif?
        // Jika user memilih filter, pakai itu. Jika tidak, cari otomatis berdasarkan jam.
        if ($request->schedule_id) {
            $currentSchedule = TahfizhSchedule::find($request->schedule_id);
        } else {
            // Cari jadwal yang sedang berlangsung atau yang paling dekat
            $currentSchedule = TahfizhSchedule::forToday()
                ->where('end_time', '>=', $timeNow)
                ->orderBy('start_time')
                ->first();

            // Jika tidak ada yang aktif (misal malam hari), ambil sesi pertama hari ini
            if (!$currentSchedule) {
                $currentSchedule = TahfizhSchedule::forToday()->orderBy('start_time')->first();
            }
        }

        if (!$currentSchedule) {
            return response()->json(['status' => 'empty', 'message' => 'Tidak ada jadwal hari ini']);
        }

        // B. Ambil Data Halaqah & Statusnya
        $halaqahs = TahfizhHalaqah::with('teacher')->get();

        $monitoringData = [];

        foreach ($halaqahs as $halaqah) {
            // 1. Cek Jurnal (Sudah masuk?)
            $journal = TahfizhJournal::where('tahfizh_halaqah_id', $halaqah->id)
                ->where('tahfizh_schedule_id', $currentSchedule->id)
                ->where('date', $date)
                ->first();

            // 2. Cek Izin (UPDATE LOGIC DISINI)
            // Kita cari izin yang statusnya approved ATAU pending
            $permission = TeacherPermission::where('teacher_id', $halaqah->teacher_id)
                            ->where('date', $date)
                            ->whereIn('status', ['approved', 'pending']) // Ambil Pending juga
                            ->whereHas('tahfizhDetails', function($q) use ($currentSchedule) {
                                $q->where('tahfizh_schedule_id', $currentSchedule->id);
                            })
                            ->first();

            // 3. Cek Badal (Apakah sudah ada badal?)
            $substitute = TahfizhSubstitute::where('tahfizh_halaqah_id', $halaqah->id)
                ->where('tahfizh_schedule_id', $currentSchedule->id)
                ->where('date', $date)
                ->with('substituteTeacher')
                ->first();

           // LOGIKA STATUS CARD (UPDATE)
            $status = 'waiting';
            $badgeClass = 'bg-light text-dark border';
            $statusText = 'BELUM MASUK';
            $photoUrl = null;
            $checkInTime = null;

            if ($journal) {
                $status = 'present';
                $badgeClass = 'bg-success';
                $statusText = 'SUDAH MASUK';
                $photoUrl = asset('storage/' . $journal->photo_proof);
                $checkInTime = $journal->clock_in->format('H:i');
                // Cek apakah yang mengajar Badal?
                if ($journal->teacher_id != $halaqah->teacher_id) {
                    $statusText .= ' (BADAL)';
                }
            } elseif ($substitute) {
                $status = 'badal_assigned'; // Badal sudah ditunjuk, tapi belum masuk
                $badgeClass = 'bg-primary';
                $statusText = 'BADAL: ' . $substitute->substituteTeacher->name;
            } elseif ($permission) {
                // [BARU] Logika Percabangan Izin
                if ($permission->status == 'approved') {
                    $status = 'permission_approved';
                    $badgeClass = 'bg-warning text-dark';
                    $statusText = 'IZIN DITERIMA';
                } else {
                    // JIKA PENDING
                    $status = 'permission_pending';
                    $badgeClass = 'bg-warning text-dark border border-dark'; // Atau warna Oranye
                    $statusText = 'PENGAJUAN IZIN';
                }
            } elseif ($timeNow > $currentSchedule->start_time) {
                $status = 'late';
                $badgeClass = 'bg-danger';
                $statusText = 'TERLAMBAT / ALPHA';
            }

            $monitoringData[] = [
                'halaqah_id' => $halaqah->id,
                'group_name' => $halaqah->name,
                'teacher_name' => $halaqah->teacher->name,
                'status' => $status,
                'badge_class' => $badgeClass,
                'status_text' => $statusText,
                'photo_url' => $photoUrl,
                'check_in_time' => $checkInTime,
                'is_late' => !$journal && $timeNow > $currentSchedule->start_time, // Penanda telat
                'permission_reason' => $permission ? $permission->reason : null,
                // Kirim ID Izin untuk tombol Approve
                'permission_id' => $permission ? $permission->id : null, // Tambahkan ini
                'schedule_id' => $currentSchedule->id, // Untuk parameter kirim badal
                'teacher_id' => $halaqah->teacher_id, // Guru Asli
            ];
        }

        return response()->json([
            'status' => 'success',
            'session_name' => $currentSchedule->session_name,
            'session_time' => Carbon::parse($currentSchedule->start_time)->format('H:i') . ' - ' . Carbon::parse($currentSchedule->end_time)->format('H:i'),
            'data' => $monitoringData
        ]);
    }

    // [BARU] Method Approve Cepat via AJAX
    public function approvePermission(Request $request)
    {
        $permission = TeacherPermission::find($request->permission_id);
        if ($permission) {
            $permission->update(['status' => 'approved']);
            return response()->json(['status' => 'success', 'message' => 'Izin disetujui.']);
        }
        return response()->json(['status' => 'error', 'message' => 'Data tidak ditemukan.'], 404);
    }

    // 3. SIMPAN BADAL
    public function assignBadal(Request $request)
    {
        TahfizhSubstitute::create([
            'tahfizh_halaqah_id' => $request->halaqah_id,
            'tahfizh_schedule_id' => $request->schedule_id,
            'original_teacher_id' => $request->original_teacher_id,
            'substitute_teacher_id' => $request->substitute_teacher_id,
            'date' => Carbon::now()->format('Y-m-d'),
        ]);

        return back()->with('success', 'Guru badal berhasil ditugaskan.');
    }
}
