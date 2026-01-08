<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Jabatan::create([
            'name' => 'Kepala Salafiyah Ulya',
            'description' => 'Manages team and projects',
            'created_at' => now(),
            'updated_at' => now(),
        ],[
            'name' => 'Kepala Salafiyah Wustha',
            'description' => 'Assists the head in management tasks',
            'created_at' => now(),
            'updated_at' => now(),
        ],[
            'name' => 'Kurikulum Sekolah Wustha',
            'description' => 'Assists the head in management tasks',
            'created_at' => now(),
            'updated_at' => now(),
        ],[
            'name' => 'Kurikulum Sekolah Ulya',
            'description' => 'Assists the head in management tasks',
            'created_at' => now(),
            'updated_at' => now(),
        ],[
            'name' => 'Kepala Pengasuhan',
            'description' => 'Assists the head in management tasks',
            'created_at' => now(),
            'updated_at' => now(),
        ],[
            'name' => "Kurikulum Qur'an Ulya",
            'description' => 'Assists the head in management tasks',
            'created_at' => now(),
            'updated_at' => now(),
        ],[
            'name' => "Kurikulum Qur'an Wustha",
            'description' => 'Assists the head in management tasks',
            'created_at' => now(),
            'updated_at' => now(),
        ],[
            'name' => 'Pengasuhan',
            'description' => 'Assists the head in management tasks',
            'created_at' => now(),
            'updated_at' => now(),
        ],[
            'name' => "Guru Qur'an",
            'description' => 'Assists the head in management tasks',
            'created_at' => now(),
            'updated_at' => now(),
        ],[
            'name' => "Guru Syar'i",
            'description' => 'Assists the head in management tasks',
            'created_at' => now(),
            'updated_at' => now(),
        ],[
            'name' => "Satpam",
            'description' => 'Assists the head in management tasks',
            'created_at' => now(),
            'updated_at' => now(),
        ],[
            'name' => "Pegawai Dapur",
            'description' => 'Assists the head in management tasks',
            'created_at' => now(),
            'updated_at' => now(),
        ],[
            'name' => "Pegawai Laundry",
            'description' => 'Assists the head in management tasks',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
