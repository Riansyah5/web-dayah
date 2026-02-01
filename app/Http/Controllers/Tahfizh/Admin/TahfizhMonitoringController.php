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
        // Dropdown jadwal (tetap)
        $allSchedules = TahfizhSchedule::orderBy('order_index')->get()->unique('session_name');
        
        // Dropdown guru badal (tetap)
        $teachers = Teacher::where('is_active', true)->orderBy('name')->get();

        return view('tahfizh.admin.monitoring.index', compact('allSchedules', 'teachers'));
    }

    // 2. API DATA REALTIME (DENGAN FILTER TANGGAL)
    public function getRealtimeData(Request $request)
    {
        // 1. Tentukan Tanggal (Default Hari Ini)
        $date = $request->date ? $request->date : Carbon::now()->format('Y-m-d');
        $isToday = $date === Carbon::now()->format('Y-m-d');
        $timeNow = Carbon::now()->format('H:i:s');
        
        // Ambil Hari dalam angka (1=Senin, 7=Minggu) untuk tanggal yang dipilih
        $dayOfWeek = Carbon::parse($date)->dayOfWeekIso;

        // 2. Tentukan Jadwal Mana yang Ditampilkan?
        if ($request->schedule_id) {
            // Jika Admin memilih manual dari dropdown
            $currentSchedule = TahfizhSchedule::find($request->schedule_id);
        } else {
            if ($isToday) {
                // HARI INI: Cari jadwal berdasarkan jam sekarang
                $currentSchedule = TahfizhSchedule::where('day_of_week', $dayOfWeek)
                                    ->where('end_time', '>=', $timeNow)
                                    ->orderBy('start_time')
                                    ->first();
            }
            
            // MASA LALU / BELUM WAKTUNYA:
            // Jika tidak ada jadwal aktif (karena cek masa lalu), ambil sesi pertama hari itu
            if (!isset($currentSchedule) || !$currentSchedule) {
                $currentSchedule = TahfizhSchedule::where('day_of_week', $dayOfWeek)
                                    ->orderBy('start_time')
                                    ->first();
            }
        }

        // Jika hari itu libur (tidak ada schedule sama sekali)
        if (!$currentSchedule) {
            return response()->json([
                'status' => 'empty', 
                'message' => 'Tidak ada jadwal halaqah pada tanggal/hari ini (' . Carbon::parse($date)->locale('id')->translatedFormat('l') . ').'
            ]);
        }

        // 3. Ambil Data Halaqah
        $halaqahs = TahfizhHalaqah::with('teacher')->get();
        $monitoringData = [];

        foreach ($halaqahs as $halaqah) {
            // A. Cek Jurnal
            $journal = TahfizhJournal::where('tahfizh_halaqah_id', $halaqah->id)
                        ->where('tahfizh_schedule_id', $currentSchedule->id)
                        ->where('date', $date)
                        ->first();

            // B. Cek Izin (Approved / Pending)
            $permission = TeacherPermission::where('teacher_id', $halaqah->teacher_id)
                            ->where('date', $date)
                            ->whereIn('status', ['approved', 'pending'])
                            ->whereHas('tahfizhDetails', function($q) use ($currentSchedule) {
                                $q->where('tahfizh_schedule_id', $currentSchedule->id);
                            })
                            ->first();

            // C. Cek Badal
            $substitute = TahfizhSubstitute::where('tahfizh_halaqah_id', $halaqah->id)
                            ->where('tahfizh_schedule_id', $currentSchedule->id)
                            ->where('date', $date)
                            ->with('substituteTeacher')
                            ->first();

            // LOGIKA STATUS
            $status = 'waiting';
            $badgeClass = 'bg-light text-dark border';
            $statusText = 'BELUM MASUK'; // Atau "ALPHA" jika tanggal masa lalu
            $photoUrl = null;
            $checkInTime = null;
            
            // Penyesuaian Teks jika Masa Lalu
            if (!$isToday && !$journal) {
                $statusText = 'TIDAK HADIR (ALPHA)';
                $badgeClass = 'bg-danger';
            }

            if ($journal) {
                $status = 'present';
                $badgeClass = 'bg-success';
                $statusText = 'SUDAH MASUK';
                $photoUrl = asset('storage/' . $journal->photo_proof);
                $checkInTime = $journal->clock_in->format('H:i');
                if ($journal->teacher_id != $halaqah->teacher_id) {
                    $statusText .= ' (BADAL)';
                }
            } elseif ($substitute) {
                $status = 'badal_assigned';
                $badgeClass = 'bg-primary';
                $statusText = 'BADAL: ' . $substitute->substituteTeacher->name;
            } elseif ($permission) {
                if ($permission->status == 'approved') {
                    $status = 'permission_approved';
                    $badgeClass = 'bg-warning text-dark';
                    $statusText = 'IZIN DITERIMA';
                } else {
                    $status = 'permission_pending';
                    $badgeClass = 'bg-warning text-dark border border-dark';
                    $statusText = 'PENGAJUAN IZIN';
                }
            } elseif ($isToday && $timeNow > $currentSchedule->start_time) {
                // Hanya tampilkan "Terlambat" jika hari ini
                $status = 'late';
                $badgeClass = 'bg-danger';
                $statusText = 'TERLAMBAT / ALPHA';
            }

            $monitoringData[] = [
                'halaqah_id' => $halaqah->id,
                'group_name' => $halaqah->name,
                'gender' => $halaqah->gender,
                'teacher_name' => $halaqah->teacher->name,
                'status' => $status,
                'badge_class' => $badgeClass,
                'status_text' => $statusText,
                'photo_url' => $photoUrl,
                'check_in_time' => $checkInTime,
                // Is Late hanya true jika hari ini dan lewat jam
                'is_late' => ($isToday && !$journal && $timeNow > $currentSchedule->start_time), 
                'permission_reason' => $permission ? $permission->reason : null,
                'permission_id' => $permission ? $permission->id : null,
                'schedule_id' => $currentSchedule->id,
                'teacher_id' => $halaqah->teacher_id,
                'substitute_id' => $substitute ? $substitute->substitute_teacher_id : null,
            ];
        }

        return response()->json([
            'status' => 'success',
            'is_today' => $isToday,
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

    // 3. SET / UPDATE BADAL
    public function assignBadal(Request $request)
    {
        $request->validate([
            'halaqah_id' => 'required',
            'schedule_id' => 'required',
            'original_teacher_id' => 'required',
            'substitute_teacher_id' => 'required|different:original_teacher_id', // Guru badal tidak boleh sama dengan guru asli
        ]);

        // Gunakan updateOrCreate agar tidak double data
        TahfizhSubstitute::updateOrCreate(
            [
                'tahfizh_halaqah_id' => $request->halaqah_id,
                'tahfizh_schedule_id' => $request->schedule_id,
                'date' => Carbon::now()->format('Y-m-d'),
            ],
            [
                'original_teacher_id' => $request->original_teacher_id,
                'substitute_teacher_id' => $request->substitute_teacher_id,
            ]
        );

        return back()->with('success', 'Guru pengganti berhasil ditetapkan.');
    }

    // 4. HAPUS BADAL (BATALKAN)
    public function removeBadal(Request $request)
    {
        $date = Carbon::now()->format('Y-m-d');

        $substitute = TahfizhSubstitute::where('tahfizh_halaqah_id', $request->halaqah_id)
            ->where('tahfizh_schedule_id', $request->schedule_id)
            ->where('date', $date)
            ->first();

        if ($substitute) {
            $substitute->delete();
            return back()->with('success', 'Guru pengganti dibatalkan. Status kembali seperti semula.');
        }

        return back()->with('error', 'Data badal tidak ditemukan.');
    }
}
