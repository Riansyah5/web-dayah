<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahfizhHalaqah extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function academicYear() { return $this->belongsTo(AcademicYear::class); }
    public function teacher() { return $this->belongsTo(Teacher::class); }
    
    // Relasi Many-to-Many ke Siswa melalui tabel pivot
    public function students() {
        return $this->belongsToMany(Student::class, 'tahfizh_students', 'tahfizh_halaqah_id', 'student_id')
                    ->withTimestamps();
    }
}
