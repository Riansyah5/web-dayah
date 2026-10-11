<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DormAttendance extends Model
{
    use HasFactory, HasUlids;

    protected $guarded = ['id'];

    protected $casts = [
        'date' => 'date',
    ];

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function dorm(): BelongsTo
    {
        return $this->belongsTo(Dorm::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function details(): HasMany
    {
        return $this->hasMany(DormAttendanceDetail::class);
    }

    /**
     * Label representasi sesi absensi
     */
    public function getSessionLabelAttribute(): string
    {
        return match ($this->session) {
            'subuh' => 'Subuh / Pagi',
            'maghrib' => 'Maghrib',
            'isya' => 'Isya',
            'malam' => 'Malam / Apel Tidur',
            default => ucfirst($this->session),
        };
    }
}

