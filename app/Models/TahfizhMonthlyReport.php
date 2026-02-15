<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahfizhMonthlyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'period',
        'total_hours',
        // 'total_salary'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}
