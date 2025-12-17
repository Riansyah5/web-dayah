<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory, HasUlids;

    protected $guarded = ['id'];

    // Relasi ke Silabus (Materi per semester/jenjang)
    public function syllabi()
    {
        return $this->hasMany(Syllabus::class);
    }

    // Relasi ke Course (Pembelajaran yang sedang berlangsung)
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
