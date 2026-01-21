<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonSchedule extends Model
{
    protected $guarded = ['id'];

    // Helper untuk konversi angka hari ke Nama Hari Indonesia
    public function getDayNameAttribute()
    {
        $days = [
            1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu',
            4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'
        ];
        return $days[$this->day_of_week] ?? '-';
    }

    public function academicYear() { return $this->belongsTo(AcademicYear::class); }
    public function classroom() { return $this->belongsTo(Classroom::class); }
    public function subject() { return $this->belongsTo(Subject::class); }
    public function teacher() { return $this->belongsTo(Teacher::class); }
    /**
     * Relasi ke jadwal badal (pengganti)
     */
    public function substitutes()
    {
        return $this->hasMany(
            ScheduleSubstitute::class,
            'lesson_schedule_id'
        );
    }
}