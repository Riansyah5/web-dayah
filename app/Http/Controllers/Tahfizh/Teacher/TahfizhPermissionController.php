<?php

namespace App\Http\Controllers\Tahfizh\Teacher;

use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Models\TahfizhSchedule;
use App\Models\TeacherPermission;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TahfizhPermissionController extends Controller
{
    // Form Pengajuan
    public function create()
    {
        return view('tahfizh.permission.create');
    }

    // AJAX: Ambil Jadwal Sesi Hari Ini
    public function getSchedules(Request $request)
    {
        $date = $request->date;
        $dayOfWeek = \Carbon\Carbon::parse($date)->dayOfWeekIso;

        // Ambil sesi tahfizh yang aktif pada hari tersebut
        $schedules = TahfizhSchedule::where('day_of_week', $dayOfWeek)
                        ->where('is_active', true)
                        ->orderBy('start_time')
                        ->get()
                        ->map(function($item) {
                            return [
                                'id' => $item->id,
                                'session_name' => $item->session_name,
                                'time' => \Carbon\Carbon::parse($item->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::parse($item->end_time)->format('H:i'),
                            ];
                        });

        return response()->json($schedules);
    }

    // Simpan Izin
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'type' => 'required',
            'reason' => 'required',
            'schedule_ids' => 'required|array|min:1', // Wajib pilih minimal 1 sesi
            'attachment' => 'nullable|file|max:2048'
        ]);

        
        DB::transaction(function() use ($request) {
            $teacher = Teacher::where('name', Auth::user()->name)->first();
            if (!$teacher) return back()->with('error', 'Data Guru tidak ditemukan. Hubungi Admin.');
            
            $path = null;
            if ($request->hasFile('attachment')) {
                $path = $request->file('attachment')->store('permissions', 'public');
            }

            // 1. Simpan Header Izin
            $permission = TeacherPermission::create([
                'teacher_id' => $teacher->id,
                'date' => $request->date,
                'type' => $request->type,
                'reason' => $request->reason,
                'attachment' => $path,
                'status' => 'pending', // Menunggu Approval Admin
            ]);

            // 2. Simpan Detail Sesi yang Dipilih
            // Insert ke tabel pivot 'teacher_permission_tahfizh_details'
            $permission->tahfizhDetails()->attach($request->schedule_ids);
        });

        return redirect()->route('tahfizh.journal.dashboard')
               ->with('success', 'Pengajuan izin berhasil dikirim.');
    }
}