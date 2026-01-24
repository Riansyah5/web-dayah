<?php

namespace App\Http\Controllers\Academic\Schedule;

use Carbon\Carbon;
use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Models\LessonSchedule;
use App\Models\TeachingJournal;
use App\Models\TeacherPermission;
use App\Models\ScheduleSubstitute;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\TeacherPermissionDetail;

class PicketController extends Controller
{
    // Dashboard Piket Harian
    public function index(Request $request)
    {
        $date = $request->date ? Carbon::parse($request->date) : Carbon::today();
        $dayOfWeek = $date->dayOfWeekIso;

        // 1. Ambil Jadwal (Tetap sama)
        $schedules = LessonSchedule::where('day_of_week', $dayOfWeek)
            ->with(['teacher', 'subject', 'classroom.level.stage'])
            ->orderBy('start_time')
            ->get();

        // 1. Ambil Permission DETAIL hari ini
        // Kita ingin tahu: Jadwal ID mana saja yang 'Kena Izin'?
        // Query: Ambil Detail Izin -> Join ke Izin Utama (filter tanggal) -> Pluck ID Jadwal & Data Izinnya

        $affectedSchedules = TeacherPermissionDetail::whereHas('permission', function ($q) use ($date) {
            $q->whereDate('date', $date);
        })
            ->with('permission') // Eager load data izinnya (alasan, tipe, dll)
            ->get()
            ->keyBy('lesson_schedule_id'); // Key array pakai ID Jadwal

        // 3. Ambil Badal (Tetap sama)
        $substitutes = ScheduleSubstitute::whereDate('date', $date)
            ->with('substituteTeacher')
            ->get()
            ->keyBy('lesson_schedule_id');

        // 4. [BARU] Ambil Realisasi Jurnal (Siapa yg sudah masuk kelas?)
        // Kita ambil jurnal pada tanggal tersebut, key-nya schedule_id
        $journals = TeachingJournal::whereDate('date', $date)
            ->get()
            ->keyBy('lesson_schedule_id');

        $allTeachers = Teacher::where('is_active', true)->orderBy('name')->get();

        return view('academic.picket.index', compact('schedules', 'affectedSchedules', 'substitutes', 'journals', 'allTeachers', 'date'));
    }

    // Update Status Izin (Approve/Reject)
    public function updatePermissionStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        $permission = TeacherPermission::findOrFail($id);

        $permission->update([
            'status' => $request->status,
            'approved_by' => Auth::id(), // Siapa yang ACC
        ]);

        $msg = $request->status == 'approved' ? 'Izin disetujui.' : 'Izin ditolak.';
        return back()->with('success', $msg);
    }

    // Simpan Guru Pengganti
    public function assignSubstitute(Request $request)
    {
        $request->validate([
            'lesson_schedule_id' => 'required',
            'substitute_teacher_id' => 'required',
            'date' => 'required|date',
        ]);

        // Cek Bentrok: Apakah guru pengganti sudah mengajar di jam yang sama?
        // (Logic cek bentrok bisa ditambahkan nanti untuk validasi ketat)

        ScheduleSubstitute::updateOrCreate(
            [
                'lesson_schedule_id' => $request->lesson_schedule_id,
                'date' => $request->date,
            ],
            [
                'substitute_teacher_id' => $request->substitute_teacher_id,
                'note' => $request->note,
                'assigned_by' => Auth::id(),
            ]
        );

        return back()->with('success', 'Guru pengganti berhasil ditugaskan.');
    }

    // Hapus Guru Pengganti (Kembali ke Guru Asli)
    public function removeSubstitute($id)
    {
        ScheduleSubstitute::destroy($id);
        return back()->with('success', 'Guru pengganti dibatalkan.');
    }

}
