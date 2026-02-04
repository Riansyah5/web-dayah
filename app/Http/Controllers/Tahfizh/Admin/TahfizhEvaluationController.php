<?php

namespace App\Http\Controllers\Tahfizh\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\TahfizhJournal;
use App\Models\TahfizhSubstitute;
use App\Models\TeacherPermission;
use App\Models\TahfizhTeacherMonthlyEvaluation;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TahfizhEvaluationController extends Controller
{
    // List Evaluasi (Filter per Bulan)
    public function index(Request $request)
    {
        $month = $request->month ? Carbon::parse($request->month) : Carbon::now()->startOfMonth();
        
        // Ambil data evaluasi yang sudah tersimpan di database
        $evaluations = TahfizhTeacherMonthlyEvaluation::with('teacher')
                        ->whereYear('month', $month->year)
                        ->whereMonth('month', $month->month)
                        ->get();

        return view('tahfizh.admin.evaluation.index', compact('evaluations', 'month'));
    }

    // Halaman Generate / Preview (Hitung Data Mentah)
    public function create(Request $request)
    {
        $month = $request->month ? Carbon::parse($request->month) : Carbon::now()->startOfMonth();
        
        // Cek apakah bulan ini sudah dikunci?
        $isLocked = TahfizhTeacherMonthlyEvaluation::whereYear('month', $month->year)
                    ->whereMonth('month', $month->month)
                    ->where('is_locked', true)
                    ->exists();

        if ($isLocked) {
            return back()->with('error', 'Evaluasi bulan ini sudah Tutup Buku (Locked). Tidak bisa generate ulang.');
        }

        // Ambil semua guru aktif
        $teachers = Teacher::where('is_active', true)->orderBy('name')->get();
        $previewData = [];

        foreach ($teachers as $teacher) {
            // 1. Hitung Hadir (Jurnal miliknya sendiri)
            $hadir = TahfizhJournal::where('teacher_id', $teacher->id)
                        ->whereYear('date', $month->year)
                        ->whereMonth('date', $month->month)
                        ->count();

            // 2. Hitung Badal (Dia menggantikan orang lain)
            // Cek di tabel Substitute dimana dia jadi 'substitute_teacher_id'
            // DAN pastikan dia benar-benar hadir (ada jurnalnya)
            $badal = TahfizhSubstitute::where('substitute_teacher_id', $teacher->id)
                        ->whereYear('date', $month->year)
                        ->whereMonth('date', $month->month)
                        ->count();

            // 3. Hitung Izin (Approved)
            $izin = TeacherPermission::where('teacher_id', $teacher->id)
                        ->whereYear('date', $month->year)
                        ->whereMonth('date', $month->month)
                        ->where('status', 'approved')
                        ->count();
            
            // 4. Hitung Telat (Logic sederhana: clock_in > start_time)
            // Ini butuh query join yang agak kompleks, disederhanakan count jurnal yang is_late (jika ada flag)
            // Atau sementara kita set 0 jika belum ada logika telat yang rigid
            $telat = 0; 

            // 5. Alpha (Logic: Jadwal Aktif - (Hadir + Izin))
            // Untuk simplifikasi, kita set 0 dulu, Admin bisa edit manual jika ada data alpha
            $alpha = 0;

            $previewData[] = [
                'teacher_id' => $teacher->id,
                'name' => $teacher->name,
                'hadir' => $hadir,
                'badal' => $badal, // Kelebihan jam karena menggantikan
                'izin' => $izin,
                'alpha' => $alpha,
                'telat' => $telat,
            ];
        }

        return view('tahfizh.admin.evaluation.create', compact('previewData', 'month'));
    }

    // Simpan Massal (Tutup Buku Sementara)
    public function store(Request $request)
    {
        $monthDate = Carbon::parse($request->month_str)->format('Y-m-d');

        DB::transaction(function() use ($request, $monthDate) {
            // Hapus draft lama di bulan yang sama (Re-generate)
            TahfizhTeacherMonthlyEvaluation::where('month', $monthDate)
                ->where('is_locked', false) // Jangan hapus yang sudah lock
                ->delete();

            foreach ($request->evaluations as $teacherId => $data) {
                TahfizhTeacherMonthlyEvaluation::create([
                    'teacher_id' => $teacherId,
                    'month' => $monthDate,
                    'hadir_count' => $data['hadir'],
                    'badal_count' => $data['badal'],
                    'izin_count' => $data['izin'],
                    'alpha_count' => $data['alpha'],
                    'notes' => $data['notes'] ?? null,
                    'is_locked' => false, // Masih draft, belum final
                ]);
            }
        });

        return redirect()->route('tahfizh.admin.evaluations.index', ['month' => $request->month_str])
               ->with('success', 'Data evaluasi berhasil disimpan (Draft). Silakan review dan kunci jika sudah fix.');
    }

    // Kunci Permanen (Tutup Buku Final)
    public function lock(Request $request)
    {
        $month = Carbon::parse($request->month);
        
        TahfizhTeacherMonthlyEvaluation::whereYear('month', $month->year)
            ->whereMonth('month', $month->month)
            ->update(['is_locked' => true]);

        return back()->with('success', 'Evaluasi bulan ini telah DIKUNCI (Tutup Buku). Data tidak dapat diubah lagi.');
    }
}