<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory, HasUlids;

    protected $guarded = ['id'];

    // Relasi ke tabel master
    public function classroom() { return $this->belongsTo(Classroom::class); }
    public function subject() { return $this->belongsTo(Subject::class); }
    public function teacher() { return $this->belongsTo(Teacher::class); }

    // Relasi ke nilai siswa
    public function grades() { return $this->hasMany(Grade::class); }
}
