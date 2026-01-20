<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleSubstitute extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = ['date' => 'date'];

    // Relasi ke Jadwal Asli
    public function lessonSchedule() { return $this->belongsTo(LessonSchedule::class); }
    // Relasi ke Guru Pengganti
    public function substituteTeacher() { return $this->belongsTo(Teacher::class, 'substitute_teacher_id'); }
}