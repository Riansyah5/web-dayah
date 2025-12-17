<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportCard extends Model
{
    use HasFactory, HasUlids;
    
    protected $guarded = ['id'];

    public function student() { return $this->belongsTo(Student::class); }
    public function classroom() { return $this->belongsTo(Classroom::class); }
}