<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stage extends Model
{
    use HasFactory, HasUlids;

    protected $guarded = ['id'];
    public function levels()
    {
        return $this->hasMany(Level::class);
    }

    // Relasi Many-to-Many ke Subject melalui tabel pivot stage_subject
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'stage_subject');
    }
}
