<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TahfizhSchedule extends Model
{
    // Agar bisa diisi massal (mass assignment)
    protected $guarded = ['id'];

    // Casting tipe data (opsional, agar output waktu lebih rapi)
    protected $casts = [
        'is_active' => 'boolean',
        'order_index' => 'integer',
    ];

    /**
     * Accessor: Mendapatkan Nama Hari dari angka day_of_week
     * Cara panggil: $schedule->day_name
     */
    public function getDayNameAttribute()
    {
        $days = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu'
        ];

        return $days[$this->day_of_week] ?? 'Unknown';
    }

    /**
     * Helper: Cek apakah jadwal ini aktif hari ini?
     * Berguna untuk filter di Dashboard Musyrif
     */
    public function scopeForToday($query)
    {
        // Carbon::now()->dayOfWeekIso returns 1 (Mon) to 7 (Sun)
        return $query->where('day_of_week', Carbon::now()->dayOfWeekIso)
                     ->where('is_active', true);
    }
}