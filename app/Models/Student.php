<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    // Relasi: 1 Santri punya banyak surat izin
    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class)->latest();
    }

    // Cek apakah santri sedang di luar?
    public function isOut()
    {
        return $this->permissions()
            ->where('status', 'approved')
            ->whereNull('returned_at')
            ->exists();
    }

    public function violations() {
        return $this->hasMany(Violation::class)->orderBy('violation_date', 'desc');
    }

    public function classrooms(): BelongsToMany
    {
        return $this->belongsToMany(Classroom::class, 'classroom_student')->withTimestamps();
    }

    // 1. RELASI KE TABEL DETAIL MUTASI
    public function exitDetail()
    {
        return $this->hasOne(StudentExit::class);
    }

    // 2. SCOPE (Filter Cepat)
    
    // Cara pakai: Student::active()->get();
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Cara pakai: Student::alumni()->get();
    public function scopeAlumni($query)
    {
        return $query->whereIn('status', ['graduated', 'moved', 'suspended']);
    }
    
    // Cara pakai: Student::graduated()->get();
    public function scopeGraduated($query)
    {
        return $query->where('status', 'graduated');
    }
    
    // 3. RELASI MANY-TO-MANY KE TAHFIZH HALAQAH
    public function tahfizhHalaqahs() {
        return $this->belongsToMany(TahfizhHalaqah::class, 'tahfizh_students', 'student_id', 'tahfizh_halaqah_id');
    }
}
