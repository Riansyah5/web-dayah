<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CbtQuestionBank extends Model
{
    protected $guarded = ['id'];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function questions()
    {
        return $this->hasMany(CbtQuestion::class);
    }
}