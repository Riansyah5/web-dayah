<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model {
    use HasFactory, HasUlids;
    
    protected $guarded = ['id'];
    public function academicYear() { return $this->belongsTo(AcademicYear::class); }
    public function level() { return $this->belongsTo(Level::class); }
    public function major() { return $this->belongsTo(Major::class); }
    public function students() { return $this->belongsToMany(Student::class, 'classroom_student'); }
}
