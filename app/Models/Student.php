<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory, HasUlids;

    protected $guarded = ['id'];
    protected $casts = [
        'birth_date' => 'date',
        'acceptance_date' => 'date',
    ];

    // Relasi: 1 Santri punya banyak riwayat penempatan kamar
    public function roomAssignments(): HasMany
    {
        return $this->hasMany(RoomAssignment::class);
    }

    // Helper: Mengambil kamar santri SAAT INI (Tahun Ajaran Aktif)
    public function currentRoomAssignment(): HasOne
    {
        return $this->hasOne(RoomAssignment::class)->latestOfMany()
            ->whereHas('academicYear', function ($q) {
                $q->where('is_active', true);
            });
    }

    // Relasi: 1 Santri punya banyak surat izin
    public function permissions(): HasMany
    {
        return $this->hasMany(StudentPermission::class);
    }

    // Relasi ke History
    public function roomHistories()
    {
        return $this->hasMany(RoomHistory::class)->orderBy('start_date', 'desc');
    }

    // Relasi ke Kamar Saat Ini (Shortcut)
    public function currentRoom()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
