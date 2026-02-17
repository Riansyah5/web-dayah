<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahfizhCleanupLog extends Model
{
    use HasFactory;

    // Tentukan kolom mana saja yang boleh diisi massal
    protected $fillable = [
        'cleanup_type', 
        'total_deleted', 
        'period_threshold', 
        'admin_id'
    ];

    /**
     * Relasi ke model User (Admin yang melakukan cleanup)
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Helper untuk format tampilan jenis cleanup agar lebih rapi
     */
    public function getTypeTextAttribute()
    {
        return $this->cleanup_type == 'photos' ? 'Hanya Foto' : 'Semua Data & Foto';
    }
}