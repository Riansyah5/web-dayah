<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Student;
use App\Models\CbtAccount;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GenerateCbtAccountsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle(): void
    {
        // Cari santri yang BELUM punya akun CBT
        $students = Student::doesntHave('cbtAccount')->get();

        // Jika tidak ada data, langsung hentikan job
        if ($students->isEmpty()) {
            return; 
        }

        // Ambil semua username yang sudah ada untuk pengecekan duplikat di RAM
        $existingUsernames = CbtAccount::pluck('username')->toArray();
        $dataToInsert = [];
        $now = now();

        foreach ($students as $student) {
            // Generate PIN 6 digit acak
            $pin = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
            
            // Loop untuk memastikan username benar-benar unik
            do {
                // Ambil 6 karakter terakhir ULID Student
                $suffix = strtoupper(substr($student->id, -6));
                $username = 'CBT-' . date('y') . '-' . $suffix;
                
                // Jika duplikat, buat suffix acak baru
                if (in_array($username, $existingUsernames)) {
                    $suffix = strtoupper(Str::random(6)); 
                    $username = 'CBT-' . date('y') . '-' . $suffix;
                }
            } while (in_array($username, $existingUsernames));

            // Catat username yang baru dibuat agar tidak dipakai di iterasi selanjutnya
            $existingUsernames[] = $username;

            // Siapkan data untuk bulk insert (TIDAK PERLU 'id' KARENA AUTO-INCREMENT)
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

        // Simpan ke database menggunakan chunk & transaction
        DB::transaction(function () use ($dataToInsert) {
            foreach (array_chunk($dataToInsert, 100) as $chunk) {
                CbtAccount::insert($chunk);
            }
        });
    }
}