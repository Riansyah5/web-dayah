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

        foreach ($students as $student) {
            // Generate PIN 6 digit acak
            $pin = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
            
            // Generate Username
            $username = 'CBT-' . date('y') . '-' . str_pad($student->id, 4, '0', STR_PAD_LEFT);

            // Simpan ke database
            CbtAccount::create([
                'student_id' => $student->id,
                'username' => $username,
                'password' => Hash::make($pin),
                'raw_pin' => $pin,
                'is_active' => true,
            ]);
        }
    }
}
