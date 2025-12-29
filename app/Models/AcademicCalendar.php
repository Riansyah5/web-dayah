<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicCalendar extends Model
{
    use HasFactory, HasUlids;

    protected $guarded = ['id'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_holiday' => 'boolean',
    ];

    public function academicYear() { return $this->belongsTo(AcademicYear::class); }
    
    // Accessor: Warna berdasarkan kategori
    public function getColorAttribute()
    {
        return match($this->category) {
            'holiday' => '#dc3545', // Merah
            'academic' => '#0d6efd', // Biru
            'islamic' => '#198754', // Hijau
            'boarding' => '#ffc107', // Kuning
            default => '#6c757d',
        };
    }
}
