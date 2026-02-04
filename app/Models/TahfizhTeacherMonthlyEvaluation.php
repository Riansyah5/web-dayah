<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahfizhTeacherMonthlyEvaluation extends Model
{
    protected $guarded = ['id'];
    
    protected $casts = [
        'month' => 'date',
        'is_locked' => 'boolean',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}