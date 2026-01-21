<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeachingJournal extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    
    protected $casts = [
        'date' => 'date',
        'clock_in_time' => 'datetime',
        'is_substitute' => 'boolean',
    ];

    public function lessonSchedule()
    {
        return $this->belongsTo(LessonSchedule::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
    
    // Relasi ke Absensi Siswa (Nanti Tahap 4)
    public function attendances()
    {
        // return $this->hasMany(StudentLessonAttendance::class);
    }
}