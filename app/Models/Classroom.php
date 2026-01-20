<?php

namespace App\Models;

use App\Models\LessonSchedule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classroom extends Model {
    use HasFactory, HasUlids;
    
    protected $guarded = ['id'];
    public function academicYear() { return $this->belongsTo(AcademicYear::class); }
    public function level() { return $this->belongsTo(Level::class); }
    public function major() { return $this->belongsTo(Major::class); }
    public function students() { return $this->belongsToMany(Student::class, 'classroom_student'); }
    public function lessonSchedules(){ return $this->hasMany(LessonSchedule::class); }
}
