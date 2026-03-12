<?php

namespace App\Http\Controllers\Cbt\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\CbtAccount;
use Illuminate\Support\Facades\Hash;
use App\Jobs\GenerateCbtAccountsJob; // Tambahkan ini
use App\Jobs\ResetCbtAccountsJob; // Tambahkan ini
use Illuminate\Support\Str;

class CbtAccountController extends Controller
{
    // Menampilkan daftar santri dan akun CBT-nya
    public function index(Request $request)
    {
        // Mengambil semua data santri beserta relasi akun CBT-nya
        // Jika Anda punya filter kelas/halaqah, bisa ditambahkan di sini
        $students = Student::with('cbtAccount')->orderBy('name')->paginate(50);
        
        // Menghitung statistik
        $totalStudents = Student::count();
        $totalAccounts = CbtAccount::count();
        $missingAccounts = $totalStudents - $totalAccounts;

        return view('cbt.admin.accounts.index', compact('students', 'totalStudents', 'totalAccounts', 'missingAccounts'));
    }

    // Generate akun massal untuk santri yang belum punya
    public function generateMassal()
    {
        // Kirim tugas ke background job
        GenerateCbtAccountsJob::dispatch();

        return back()->with('success', "Proses generate akun massal telah dimulai di latar belakang. Halaman ini akan diperbarui setelah selesai.");
    }

    // Reset PIN individu (Jika santri lupa / kartunya hilang dan disalahgunakan)
    public function resetPin(CbtAccount $account)
    {
        $newPin = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
        
        $account->update([
            'password' => Hash::make($newPin),
            'raw_pin' => $newPin
        ]);

        return back()->with('success', "PIN untuk Username {$account->username} berhasil direset menjadi $newPin.");
    }

    // Toggle Status Aktif (Blokir santri ujian, misal karena belum lunas administrasi)
    public function toggleStatus(CbtAccount $account)
    {
        $account->update([
            'is_active' => !$account->is_active
        ]);

        $status = $account->is_active ? 'diaktifkan' : 'dinonaktifkan (diblokir)';
        return back()->with('success', "Akun {$account->username} berhasil $status.");
    }

    // [BARU] Cetak Kartu Ujian Semua Santri
    public function printCards()
    {
        // Ambil santri yang sudah memiliki akun CBT
        // Asumsi ada relasi classRoom di model Student, jika tidak ada sesuaikan saja
        $students = Student::whereHas('cbtAccount')
            ->with(['cbtAccount', 'classrooms']) // Load relasi kelas jika ada
            ->orderBy('name')
            ->get();

        if ($students->isEmpty()) {
            return back()->with('error', 'Belum ada akun yang di-generate. Silakan generate terlebih dahulu.');
        }

        return view('cbt.admin.accounts.print_cards', compact('students'));
    }

    // [BARU] Reset Massal Pasca Ujian Selesai
    public function resetMassal()
    {
        // Kirim tugas ke background job
        ResetCbtAccountsJob::dispatch();

        return back()->with('success', "Proses reset PIN massal telah dimulai di latar belakang. Akun akan dinonaktifkan setelah PIN baru dibuat.");
    }

    // [BARU] Aktifkan Semua Akun Massal
    public function activateMassal()
    {
        // Ubah status is_active menjadi true untuk semua akun
        CbtAccount::query()->update(['is_active' => true]);

        return back()->with('success', 'Alhamdulillah, semua akun CBT berhasil diaktifkan. Santri sekarang bisa login menggunakan kartu ujian.');
    }

    // [BARU] Nonaktifkan Semua Akun Massal
    public function deactivateMassal()
    {
        // Ubah status is_active menjadi false untuk semua akun
        CbtAccount::query()->update(['is_active' => false]);

        return back()->with('success', 'Alhamdulillah, semua akun CBT berhasil dinonaktifkan.');
    }
    
}