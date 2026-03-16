<?php

namespace App\Http\Controllers\Cbt\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CbtQuestionBank;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Level;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class QuestionBankController extends Controller
{
    // Menampilkan daftar bank soal milik guru yang login
    public function index()
    {
        // Mengambil data guru berdasarkan nama user yang login
        $user = Auth::user();
        $teacher = Teacher::where('name', $user->name)->first();
        
        $subjects = Subject::orderBy('name')->get();
        $levels = Level::all();

        if (!$teacher) {
            if (in_array($user->role, ['Admin', 'Superadmin'])) {
                $banks = CbtQuestionBank::withCount('questions')->latest()->get();
                return view('cbt.teacher.banks.index', compact('banks', 'subjects', 'levels'));
            }
            abort(403, 'Akun Anda tidak terhubung dengan data guru.');
        }

        $banks = CbtQuestionBank::where('teacher_id', $teacher->id)
                    ->withCount('questions')
                    ->latest()
                    ->get();

        return view('cbt.teacher.banks.index', compact('banks', 'subjects', 'levels'));
    }

    // Menyimpan bank soal baru
    public function store(Request $request)
    {
        $request->validate([
            'subject_name' => 'required|string|max:255',
            'level' => 'required|string|max:50',
        ]);

        $user = Auth::user();
        $teacher = Teacher::where('name', $user->name)->first();

        if (!$teacher) {
            if (in_array($user->role, ['Admin', 'Superadmin'])) {
                return back()->with('error', 'Admin harus terhubung dengan data guru untuk membuat bank soal.');
            }
            abort(403, 'Akun Anda tidak terhubung dengan data guru.');
        }

        CbtQuestionBank::create([
            'teacher_id' => $teacher->id,
            'subject_name' => $request->subject_name,
            'level' => $request->level,
            // Generate kode unik, misal: NHW-8A2F
            'bank_code' => strtoupper(substr($request->subject_name, 0, 3)) . '-' . strtoupper(Str::random(4)),
            'is_active' => true,
        ]);

        return back()->with('success', 'Bank Soal berhasil dibuat.');
    }

    // Melihat isi soal di dalam bank soal
    public function show(CbtQuestionBank $bank)
    {
        $user = Auth::user();
        $teacher = Teacher::where('name', $user->name)->first();

        if (!$teacher) {
            if (in_array($user->role, ['Admin', 'Superadmin'])) {
                $bank->load('questions.options');
                return view('cbt.teacher.banks.show', compact('bank'));
            }
            abort(403, 'Akun Anda tidak terhubung dengan data guru.');
        }

        // Pastikan guru hanya bisa melihat bank soalnya sendiri
        if ($bank->teacher_id !== $teacher->id) {
            abort(403, 'Akses ditolak.');
        }

        $bank->load('questions.options');
        return view('cbt.teacher.banks.show', compact('bank'));
    }

    // Hapus Bank Soal (Safe Delete)
    public function destroy(CbtQuestionBank $bank)
    {
        $user = Auth::user();
        $teacher = Teacher::where('name', $user->name)->first();
        
        // Jika bukan Admin/Superadmin, lakukan pengecekan ketat
        if (!in_array($user->role, ['Admin', 'Superadmin'])) {
            if (!$teacher) {
                abort(403, 'Akun Anda tidak terhubung dengan data guru.');
            }
            // Pastikan hanya pemilik bank soal yang bisa menghapus
            if ($bank->teacher_id !== $teacher->id) {
                abort(403, 'Akses ditolak.');
            }
        }

        // Cek apakah bank soal ini sudah pernah di-generate menjadi Jadwal Ujian
        $isUsedInExam = \App\Models\CbtExam::where('cbt_question_bank_id', $bank->id)->exists();

        if ($isUsedInExam) {
            return back()->with('error', 'GAGAL MENGHAPUS: Bank Soal ini sudah digunakan dalam pelaksanaan ujian. Jika sudah tidak terpakai, silakan ubah statusnya menjadi Draft (Tidak Aktif).');
        }

        // Jika belum pernah dipakai, aman untuk dihapus
        $bank->delete();

        return redirect()->route('teacher.cbt.banks.index')->with('success', 'Bank Soal beserta seluruh isinya berhasil dihapus permanen.');
    }

    // [BARU] Ubah Status Bank Soal (Aktif / Draft)
    public function toggleStatus(CbtQuestionBank $bank)
    {
        $user = Auth::user();
        $teacher = Teacher::where('name', $user->name)->first();

        // Jika bukan Admin/Superadmin, lakukan pengecekan ketat
        if (!in_array($user->role, ['Admin', 'Superadmin'])) {
            if (!$teacher) {
                abort(403, 'Akun Anda tidak terhubung dengan data guru.');
            }
            // Pastikan hanya pemilik bank soal yang bisa mengubah
            if ($bank->teacher_id !== $teacher->id) {
                abort(403, 'Akses ditolak.');
            }
        }

        // Ubah status kebalikannya (True jadi False, False jadi True)
        $bank->update([
            'is_active' => !$bank->is_active
        ]);

        $statusMessage = $bank->is_active 
            ? 'Bank Soal diaktifkan dan sekarang BISA dipilih oleh Admin untuk jadwal ujian.' 
            : 'Bank Soal diubah menjadi Draft dan TIDAK AKAN MUNCUL di pilihan Admin.';

        return back()->with('success', $statusMessage);
    }
}