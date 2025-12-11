<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory, HasUlids;
    protected $guarded = ['id'];

    // kamar ada di gedung mana?
    public function dorm(){
        return $this->belongsTo(Dorm::class);
    }

    // Siapa Wali Kamarnya? (Relasi ke tabel Pegawai)
    public function warden(){
        return $this->belongsTo(Pegawai::class, 'warden_id');
    }

    // Siapa saja santri yang pernah/sedang di sini?
    public function assignments()
    {
        return $this->hasMany(RoomAssignment::class);
    }

    // Helper: Hitung sisa kapasitas (berguna untuk validasi saat input santri)
    public function getRemainingCapacityAttribute()
    {
        // Logika: Kapasitas Total - Jumlah Santri Aktif di kamar ini
        // (Nanti bisa dikembangkan query-nya)
        return $this->capacity; 
    }
}
