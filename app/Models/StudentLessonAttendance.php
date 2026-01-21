<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentLessonAttendance extends Model
{
    protected $guarded = ['id'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    
    public function teachingJournal()
    {
        return $this->belongsTo(TeachingJournal::class);
    }
}