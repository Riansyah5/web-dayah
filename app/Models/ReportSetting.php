<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportSetting extends Model
{
    use HasFactory, HasUlids;
    protected $guarded = ['id'];
    
    // Casting agar otomatis jadi object Carbon (Date)
    protected $casts = [
        'report_date' => 'date',
    ];

    public function academicYear() { return $this->belongsTo(AcademicYear::class); }
    public function stage() { return $this->belongsTo(Stage::class); }
}