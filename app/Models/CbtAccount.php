<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // Ubah ini!

class CbtAccount extends Authenticatable
{
    protected $guarded = ['id'];
    
    // Sembunyikan password saat data dipanggil
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi ke tabel Siswa Utama
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}