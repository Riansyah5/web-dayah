<?php

namespace App\Http\Controllers\Cbt\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\CbtAccount;
use Illuminate\Support\Facades\Hash;
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
        // Cari santri yang BELUM punya akun CBT
        $students = Student::doesntHave('cbtAccount')->get();
        $count = 0;

        foreach ($students as $student) {
            // Generate PIN 6 digit acak (Contoh: 847291)
            $pin = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
            
            // Generate Username (Format: CBT-[Tahun]-[ID Santri 4 digit])
            // Contoh: CBT-26-0015
            $username = 'CBT-' . date('y') . '-' . str_pad($student->id, 4, '0', STR_PAD_LEFT);

            // Simpan ke database
            CbtAccount::create([
                'student_id' => $student->id,
                'username' => $username,
                'password' => Hash::make($pin), // Di-hash agar aman
                'raw_pin' => $pin, // Disimpan mentah untuk dicetak di kartu
                'is_active' => true,
            ]);

            $count++;
        }

        if ($count > 0) {
            return back()->with('success', "Alhamdulillah, berhasil meng-generate $count akun CBT baru.");
        }

        return back()->with('info', "Semua santri sudah memiliki akun CBT.");
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
}