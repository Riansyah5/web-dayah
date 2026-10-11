<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DormAttendanceDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function dormAttendance(): BelongsTo
    {
        return $this->belongsTo(DormAttendance::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'present' => '<span class="badge bg-success">Hadir</span>',
            'sick' => '<span class="badge bg-primary">Sakit</span>',
            'permission' => '<span class="badge bg-warning text-dark">Izin</span>',
            'alpha' => '<span class="badge bg-danger">Alpa</span>',
            default => '<span class="badge bg-secondary">' . ucfirst($this->status) . '</span>',
        };
    }
}

