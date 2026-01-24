<?php

namespace App\Http\Controllers\Academic\Permission;

use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Models\LessonSchedule;
use App\Models\TeacherPermission;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\TeacherPermissionDetail;
use Illuminate\Support\Facades\Storage;

class TeacherPermissionController extends Controller
{
    // 1. List Riwayat Izin Saya
    public function index()
    {
        $teacher = Teacher::where('name', Auth::user()->name)->first();
        if (!$teacher) abort(403, 'Akun ini tidak terhubung dengan data Guru.');

        $permissions = TeacherPermission::where('teacher_id', $teacher->id)
                        ->orderByDesc('date')
                        ->get();

        return view('academic.permission.index', compact('permissions'));
    }

    // 2. Form Pengajuan
    public function create()
    {
        return view('academic.permission.create');
    }

    // [BARU] API untuk mengambil jadwal guru pada tanggal tertentu
    public function getSchedulesByDate(Request $request)
    {
        $request->validate(['date' => 'required|date']);

        $date = $request->date;
        
        // Fix: Cari guru berdasarkan nama user (sesuai logic di index/store) karena relasi user->teacher belum ada
        $teacher = Teacher::where('name', Auth::user()->name)->first();

        if (!$teacher) {
            return response()->json(['message' => 'Data guru tidak ditemukan.'], 404);
        }

        $dayOfWeek = \Carbon\Carbon::parse($date)->dayOfWeekIso;

        $schedules = LessonSchedule::where('teacher_id', $teacher->id)
                        ->where('day_of_week', $dayOfWeek)
                        ->with(['classroom', 'subject'])
                        ->orderBy('start_time')
                        ->get()
                        ->map(function($item) {
                            return [
                                'id' => $item->id,
                                'time' => \Carbon\Carbon::parse($item->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::parse($item->end_time)->format('H:i'),
                                'classroom' => $item->classroom->name,
                                'subject' => $item->subject->name
                            ];
                        });

        return response()->json($schedules);
    }

    // 3. Simpan Pengajuan
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today', // Minimal hari ini (kecuali sakit mendadak, logic bisa disesuaikan)
            'type' => 'required|in:sick,permit,duty',
            'reason' => 'required|string|min:10',
            'schedule_ids' => 'required|array|min:1', // Wajib pilih minimal 1 jadwal
            'schedule_ids.*' => 'exists:lesson_schedules,id',
            'attachment' => 'nullable|image|max:2048', // Bukti foto max 2MB
        ]);

        $path = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('permissions', 'public');
        }

        $teacher = Teacher::where('name', Auth::user()->name)->first();
        if (!$teacher) return back()->with('error', 'Data Guru tidak ditemukan. Hubungi Admin.');

        // 1. Buat Header Izin
        $permission =TeacherPermission::create([
            'teacher_id' => $teacher->id,
            'date' => $request->date,
            'type' => $request->type,
            'reason' => $request->reason,
            'attachment' => $path ?? null,
            'status' => 'pending', // Default menunggu persetujuan
        ]);

        // 2. Simpan Detail Jadwal yang dipilih (Looping)
        foreach ($request->schedule_ids as $scheduleId) {
            TeacherPermissionDetail::create([
                'teacher_permission_id' => $permission->id,
                'lesson_schedule_id' => $scheduleId
            ]);
        }

        return redirect()->route('academic.permission.index')
               ->with('success', 'Pengajuan izin berhasil dikirim. Menunggu persetujuan Admin.');
    }
}