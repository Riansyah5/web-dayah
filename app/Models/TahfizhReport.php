<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahfizhReport extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Casting otomatis
    protected $casts = [
        'juz_scores' => 'array', // Database JSON <-> PHP Array
        'is_locked' => 'boolean',
    ];

    // --- RELASI ---

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    // --- HELPER PREDIKAT ---
    // Fungsi untuk mengubah angka (85) menjadi Huruf/Predikat (Mumtaz)
    // Bisa dipanggil di View nanti: $report->getPredikat(90)
    
    public static function getPredikat($score)
    {
        if ($score === null) return '-';
        
        if ($score >= 90) return 'ممتاز';
        if ($score >= 80) return 'جيِّد جدان';
        if ($score >= 70) return 'جيد';
        return 'مقبول';
    }
}
