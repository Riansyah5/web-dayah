<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Student;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PermissionController extends Controller
{
    public function index()
    {
        // Tab 1: Sedang Izin (Belum Kembali)
        $activePermissions = Permission::with(['student', 'user'])
            ->where('status', 'approved')
            ->orderBy('start_date', 'desc')
            ->get();

        // Tab 2: Riwayat (Sudah Kembali / Ditolak)
        $historyPermissions = Permission::with(['student', 'user'])
            ->whereIn('status', ['returned', 'rejected', 'late'])
            ->latest()
            ->paginate(10);

        return view('permissions.index', compact('activePermissions', 'historyPermissions'));
    }

    public function create()
    {
        // ambil santri aktif saja
        $students = Student::where('status', 'active')->get();
        return view('permissions.create', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'type' => 'required',
            'reason' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        // Cek apakah santri sedang izin (cegah double izin)
        $isOut = Permission::where('student_id', $request->student_id)
            ->where('status', 'approved')
            ->exists();
        if ($isOut) {
            return back()->with('error', 'Santri tersebut tercatat masih di luar/sedang izin.');
        }

        Permission::create([
            'student_id' => $request->student_id,
            'user_id' => Auth::id(),
            'type' => $request->type,
            'reason' => $request->reason,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'approved', // Anggap admin langsung approve saat input
        ]);

        return redirect()->route('permissions.index')->with('success', 'Surat Izin berhasil dibuat.');
    }

    // Fungsi untuk Mencatat Kepulangan Santri
    public function markAsReturned($id)
    {
        $permission = Permission::findOrFail($id);

        $returnedAt = now();
        $status = $returnedAt->gt($permission->end_date) ? 'late' : 'returned';

        $permission->returned_at = $returnedAt;
        $permission->status = $status;
        $permission->save();

        return back()->with('success', 'Santri tercatat sudah kembali ke pondok.');
    }

    // Fungsi Cetak Surat
    public function print($id)
    {
        $isPdf = false;
        $permission = Permission::with(['student'])->findOrFail($id);
        return view('permissions.print', compact('permission', 'isPdf'));
    }

    public function history(Student $student)
    {
        // Ambil data izin, urutkan dari yang terbaru
        $permissions = $student->permissions()
            ->orderBy('start_date', 'desc')
            ->get()
            ->groupBy(function ($item) {
                // Grouping berdasarkan "Bulan Tahun" (Contoh: Desember 2024)
                return Carbon::parse($item->start_date)->translatedFormat('F Y');
            });

        return view('permissions.student-history', compact('student', 'permissions'));
    }

    public function pdf(Request $request, Student $student)
    {

        // Ambil data izin, urutkan dari yang terbaru
        $query = $student->permissions()->orderBy('start_date', 'desc');

        // Filter berdasarkan periode
        if ($request->period == 'current_month') {
            $query->whereMonth('start_date', now()->month)
                  ->whereYear('start_date', now()->year);
        } elseif ($request->period == 'custom' && $request->start_date && $request->end_date) {
            $query->whereDate('start_date', '>=', $request->start_date)
                  ->whereDate('start_date', '<=', $request->end_date);
        }

        $permissions = $query->get()
            ->groupBy(function ($item) {
                return $item->start_date->translatedFormat('F Y');
            });

        $fileName = 'riwayat-izin-' . $student->nis . '.pdf';

        return Pdf::loadView('permissions.historypdf', [
            'student' => $student,
            'permissions' => $permissions,
            'period' => $request->period,
            'startDate' => $request->start_date,
            'endDate' => $request->end_date
        ])
        ->setPaper('A4', 'portrait')
        ->download($fileName);
    }
    public function downloadPdf($id)
    {
        $isPdf = true;
        $permission = Permission::with(['student'])->findOrFail($id);
        $pdf = Pdf::loadView('permissions.print', compact('permission', 'isPdf'));
        $fileName = 'Surat_Izin_' . $permission->student->name . '.pdf';

        return $pdf->setPaper('A4', 'portrait')->download($fileName);
    }
}
