<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CbtQuestion extends Model
{
    protected $guarded = ['id'];

    public function questionBank()
    {
        return $this->belongsTo(CbtQuestionBank::class, 'cbt_question_bank_id');
    }

    public function options()
    {
        return $this->hasMany(CbtOption::class);
    }
}