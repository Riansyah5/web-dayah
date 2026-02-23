<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SidebarSetting extends Model
{
    use HasFactory;

    // Menentukan kolom mana saja yang boleh diisi secara massal
    protected $fillable = [
        'menu_key',
        'label',
        'is_active'
    ];

    // Opsional: Casting is_active agar selalu dianggap boolean oleh Laravel
    protected $casts = [
        'is_active' => 'boolean',
    ];
}
