<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahfizhSubstitute extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'date' => 'date',
    ];

    /**
     * RELASI
     */
    
    // Ke Kelompok Halaqah
    public function halaqah()
    {
        return $this->belongsTo(TahfizhHalaqah::class, 'tahfizh_halaqah_id');
    }

    // Ke Jadwal Sesi (Qabla/Ba'da)
    public function schedule()
    {
        return $this->belongsTo(TahfizhSchedule::class, 'tahfizh_schedule_id');
    }

    // Guru Asli (Yang digantikan)
    public function originalTeacher()
    {
        return $this->belongsTo(Teacher::class, 'original_teacher_id');
    }

    // Guru Badal (Pengganti)
    public function substituteTeacher()
    {
        return $this->belongsTo(Teacher::class, 'substitute_teacher_id');
    }
}