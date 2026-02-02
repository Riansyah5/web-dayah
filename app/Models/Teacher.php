<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory, HasUlids;

    protected $guarded = ['id'];

    // Relasi ke Course (Untuk melihat beban mengajar guru)
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    // Helper untuk menampilkan nama + gelar
    public function getFullNameAttribute()
    {
        return $this->name . ($this->title ? ', ' . $this->title : '');
    }

    // Relasi ke Halaqah Tahfizh (Sebagai Musyrif)
    public function tahfizhHalaqahs()
    {
        return $this->hasMany(TahfizhHalaqah::class);
    }

    // Relasi ke Jurnal Tahfizh
    public function tahfizhJournals()
    {
        return $this->hasMany(TahfizhJournal::class);
    }

    // Relasi ke Izin Guru
    public function teacherPermissions()
    {
        return $this->hasMany(TeacherPermission::class);
    }

    // Relasi ke Badal (Sebagai Pengganti)
    public function tahfizhBadalsAsSubstitute()
    {
        return $this->hasMany(TahfizhSubstitute::class, 'substitute_teacher_id');
    }
}
