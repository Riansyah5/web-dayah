<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahfizhSetting extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $casts = [
        'distribution_date' => 'date',
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
