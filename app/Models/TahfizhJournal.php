<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids; // Wajib untuk ID ULID
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TahfizhJournal extends Model
{
    use HasUlids; // Mengaktifkan fitur ULID otomatis

    protected $guarded = ['id'];

    // Casting agar format tanggal otomatis jadi Carbon Object
    protected $casts = [
        'date' => 'date',
        'clock_in' => 'datetime',
    ];

    /**
     * RELASI
     */
    
    // Ke Kelompok Halaqah
    public function halaqah(): BelongsTo
    {
        return $this->belongsTo(TahfizhHalaqah::class, 'tahfizh_halaqah_id');
    }

    // Ke Master Jadwal (Qabla/Ba'da)
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(TahfizhSchedule::class, 'tahfizh_schedule_id');
    }

    // Ke Guru (Musyrif)
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    // Ke Detail Absensi Santri
    public function attendances(): HasMany
    {
        return $this->hasMany(TahfizhAttendance::class, 'tahfizh_journal_id');
    }
}