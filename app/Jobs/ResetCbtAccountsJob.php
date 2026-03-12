<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\CbtAccount;
use Illuminate\Support\Facades\Hash;

class ResetCbtAccountsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        //
    }

    public function handle(): void
    {
        $accounts = CbtAccount::all();

        foreach ($accounts as $account) {
            // Buat PIN baru yang benar-benar acak
            $newPin = str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
            
            $account->update([
                'password' => Hash::make($newPin),
                'raw_pin' => $newPin,
                'is_active' => false // Langsung blokir akun agar aman pasca ujian
            ]);
        }
    }
}
