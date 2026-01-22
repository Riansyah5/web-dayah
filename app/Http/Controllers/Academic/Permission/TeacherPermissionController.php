<?php

namespace App\Http\Controllers\Academic\Permission;

use App\Models\Teacher;
use Illuminate\Http\Request;
use App\Models\TeacherPermission;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TeacherPermissionController extends Controller
{
    // 1. List Riwayat Izin Saya
    public function index()
    {
        $teacher = Teacher::where('name', Auth::user()->name)->first();
        
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

    // 3. Simpan Pengajuan
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today', // Minimal hari ini (kecuali sakit mendadak, logic bisa disesuaikan)
            'type' => 'required|in:sick,permit,duty',
            'reason' => 'required|string|min:10',
            'attachment' => 'nullable|image|max:2048', // Bukti foto max 2MB
        ]);

        $path = null;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('permissions', 'public');
        }

        $teacher = Teacher::where('name', Auth::user()->name)->first();
        if (!$teacher) return back()->with('error', 'Data Guru tidak ditemukan. Hubungi Admin.');

        TeacherPermission::create([
            'teacher_id' => $teacher->id,
            'date' => $request->date,
            'type' => $request->type,
            'reason' => $request->reason,
            'attachment' => $path,
            'status' => 'pending', // Default menunggu persetujuan
        ]);

        return redirect()->route('academic.permission.index')
               ->with('success', 'Pengajuan izin berhasil dikirim. Menunggu persetujuan Admin.');
    }
}