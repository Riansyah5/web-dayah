<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CbtStudentAnswer extends Model {
    protected $guarded = ['id'];

    public function question() { return $this->belongsTo(CbtQuestion::class, 'cbt_question_id'); }
    public function selectedOption() { return $this->belongsTo(CbtOption::class, 'cbt_option_id'); }
}