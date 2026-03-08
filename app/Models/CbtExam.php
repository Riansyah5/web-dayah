<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CbtExam extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'randomize_questions' => 'boolean',
        'randomize_options' => 'boolean',
        'show_result' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function questionBank()
    {
        return $this->belongsTo(CbtQuestionBank::class, 'cbt_question_bank_id');
    }
}