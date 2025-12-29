<?php

namespace App\Traits;

use Alkoumi\LaravelHijriDate\Hijri;
use Carbon\Carbon;

trait HijriConverter
{
    /**
     * Mengubah tanggal Masehi ke String Hijriah
     * Contoh Output: "5 Rajab 1445 H"
     */
    public function convertToHijriString($date)
    {
        // Cek apakah class library-nya ada (untuk mencegah error jika lupa install)
        if (!class_exists('Alkoumi\LaravelHijriDate\Hijri')) {
            return null; 
        }

        try {
            // 1. Pastikan input menjadi objek Carbon
            $carbonDate = Carbon::parse($date);

            // 2. Ambil komponen tanggal Hijriah secara manual agar bisa custom bahasa
            $day = Hijri::Date('j', $carbonDate);   // Tanggal (1-30)
            $month = Hijri::Date('n', $carbonDate); // Bulan (1-12)
            $year = Hijri::Date('Y', $carbonDate);  // Tahun

            // 3. Mapping Nama Bulan dalam Bahasa Indonesia
            $months = [
                1 => 'Muharram',
                2 => 'Safar',
                3 => 'Rabiul Awal',
                4 => 'Rabiul Akhir',
                5 => 'Jumadil Awal',
                6 => 'Jumadil Akhir',
                7 => 'Rajab',
                8 => "Sya'ban",
                9 => 'Ramadhan',
                10 => 'Syawal',
                11 => "Dzulqa'dah",
                12 => 'Dzulhijjah',
            ];

            $hijriDate = $day . ' ' . ($months[$month] ?? '') . ' ' . $year;

            return $hijriDate . ' H';
        } catch (\Exception $e) {
            // Jika terjadi error konversi, kembalikan null agar user bisa input manual
            return null;
        }
    }
}