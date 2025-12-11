<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentPermission extends Model
{
    use HasFactory, HasUlids;
    protected $guarded = ['id'];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Siapa yang menyetujui?
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Helper untuk warna status (berguna jika pakai Filament/Bootstrap)
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'approved' => 'success',
            'pending' => 'warning',
            'rejected' => 'danger',
            default => 'secondary',
        };
    }
}
