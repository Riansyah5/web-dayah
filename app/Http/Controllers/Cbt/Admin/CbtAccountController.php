<?php

namespace App\Http\Controllers\Cbt\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateCbtAccountsJob; // Tambahkan ini
use App\Jobs\ResetCbtAccountsJob; // Tambahkan ini
use App\Models\CbtAccount;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CbtAccountController extends Controller
{
    // Menampilkan daftar santri dan akun CBT-nya
    public function index(Request $request)
    {
        // Mengambil semua data santri beserta relasi akun CBT-nya
        // Jika Anda punya filter kelas/halaqah, bisa ditambahkan di sini
        $query = Student::where('status', 'active')->with('cbtAccount');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('cbtAccount', function ($q2) use ($search) {
                      $q2->where('username', 'like', "%{$search}%");
                  });
            });
        }

        $students = $query->orderBy('name')->paginate(50);

        // Menghitung statistik
        $totalStudents = Student::where('status', 'active')->count();
        $totalAccounts = CbtAccount::count();
        $activeAccounts = CbtAccount::where('is_active', true)->whereHas('student', function ($q) {
            $q->where('status', 'active');
        })->count();
        $inactiveAccounts = CbtAccount::where('is_active', false)->whereHas('student', function ($q) {
            $q->where('status', 'active');
        })->count();
        $totalActiveStudentAccounts = CbtAccount::whereHas('student', function ($q) {
            $q->where('status', 'active');
        })->count();
        $totalInactiveStudentAccounts = CbtAccount::whereHas('student', function ($q) {
            $q->whereNot('status', 'active');
        })->count();
        $missingAccounts = $totalStudents - $totalActiveStudentAccounts;

        return view('cbt.admin.accounts.index', compact('students', 'totalStudents', 'totalAccounts', 'missingAccounts', 'totalActiveStudentAccounts', 'totalInactiveStudentAccounts', 'activeAccounts', 'inactiveAccounts'));
    }

    // Generate akun massal untuk santri yang belum punya
    public function generateBatch(Request $request)
    {
        // Ambil maksimal 20 santri per request agar ringan dan aman dari timeout
        $limit = 20;
        $students = Student::where('status', 'active')->doesntHave('cbtAccount')->limit($limit)->get();

        // Jika sudah tidak ada santri yang butuh akun, beritahu JavaScript untuk berhenti
        if ($students->isEmpty()) {
            return response()->json(['status' => 'done']);
        }

        $existingUsernames = CbtAccount::pluck('username')->toArray();
        $dataToInsert = [];
        $now = now();

        foreach ($students as $student) {
            $pin = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);

            do {
                $suffix = strtoupper(substr($student->id, -6));
                $username = 'CBT-' . date('y') . '-' . $suffix;

                if (in_array($username, $existingUsernames)) {
                    $suffix = strtoupper(Str::random(6));
                    $username = 'CBT-' . date('y') . '-' . $suffix;
                }
            } while (in_array($username, $existingUsernames));

            $existingUsernames[] = $username;

            $dataToInsert[] = [
                'student_id' => $student->id,
                'username'   => $username,
                'password'   => Hash::make($pin),
                'raw_pin'    => $pin,
                'is_active'  => true,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Insert 20 data sekaligus
        DB::transaction(function () use ($dataToInsert) {
            CbtAccount::insert($dataToInsert);
        });

        // Hitung sisa santri untuk ditampilkan di layar
        $remaining = Student::where('status', 'active')->doesntHave('cbtAccount')->count();

        return response()->json([
            'status' => 'processing',
            'remaining' => $remaining
        ]);
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
    public function resetBatch(Request $request)
    {
        $limit = 20;
        // Tangkap ID terakhir yang dikirim dari JavaScript (Mulai dari 0 jika belum ada)
        $lastId = $request->input('last_id', 0);

        // Ambil 20 akun yang ID-nya lebih besar dari ID terakhir yang diproses
        $accounts = CbtAccount::where('id', '>', $lastId)
            ->orderBy('id', 'asc')
            ->limit($limit)
            ->get();

        // Jika sudah tidak ada data lagi, beritahu JavaScript untuk berhenti
        if ($accounts->isEmpty()) {
            return response()->json(['status' => 'done']);
        }

        $dataToUpdate = [];
        $latestId = $lastId;

        foreach ($accounts as $account) {
            $newPin = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);

            $dataToUpdate[] = [
                'id'         => $account->id,
                'student_id' => $account->student_id, // Wajib disertakan dalam upsert
                'username'   => $account->username,   // Wajib disertakan dalam upsert
                'password'   => Hash::make($newPin),
                'raw_pin'    => $newPin,
                'is_active'  => false, // Langsung diblokir pasca ujian
            ];

            // Simpan ID dari akun yang sedang diproses untuk dikirim balik ke JS
            $latestId = $account->id;
        }

        // Eksekusi Bulk Update
        CbtAccount::upsert(
            $dataToUpdate,
            ['id'], // Patokan baris yang di-update (Primary Key)
            ['password', 'raw_pin', 'is_active'] // Kolom yang nilainya diubah
        );

        // Hitung sisa akun yang ID-nya masih lebih besar dari ID terakhir ini
        $remaining = CbtAccount::where('id', '>', $latestId)->count();

        return response()->json([
            'status' => 'processing',
            'remaining' => $remaining,
            'last_id' => $latestId // Kirim ID terakhir agar JS bisa melanjutkannya
        ]);
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
