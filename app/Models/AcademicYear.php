<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcademicYear extends Model
{
    use HasFactory, HasUlids;

    protected $guarded = ['id'];

    // Scope untuk mengambil tahun ajaran yang sedang aktif saja
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

}
