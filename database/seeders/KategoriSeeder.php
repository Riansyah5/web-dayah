<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Kategori::create([
            'name' => 'TETAP',
            'created_at' => now(),
            'updated_at' => now(),
        ],[
            'name' => 'KONTRAK',
            'created_at' => now(),
            'updated_at' => now(),
        ],[
            'name' => 'TRAINING',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
