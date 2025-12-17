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
}
