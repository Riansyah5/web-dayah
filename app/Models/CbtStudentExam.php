<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CbtStudentExam extends Model {
    protected $guarded = ['id'];
    protected $casts = ['started_at' => 'datetime', 'finished_at' => 'datetime'];

    public function exam() { return $this->belongsTo(CbtExam::class, 'cbt_exam_id'); }
    public function answers() { return $this->hasMany(CbtStudentAnswer::class)->orderBy('question_order'); }
}