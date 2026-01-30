<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherPermission extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = ['date' => 'date'];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function details()
    {
        return $this->hasMany(TeacherPermissionDetail::class);
    }

    // Shortcut untuk mengambil jadwal-jadwal yang terdampak
    public function affectedSchedules()
    {
        return $this->belongsToMany(LessonSchedule::class, 'teacher_permission_details', 'teacher_permission_id', 'lesson_schedule_id');
    }

    // Relasi untuk mengambil sesi tahfizh yang diizinkan
    public function tahfizhDetails()
    {
        return $this->belongsToMany(TahfizhSchedule::class, 'teacher_permission_tahfizh_details', 'teacher_permission_id', 'tahfizh_schedule_id');
    }
}
