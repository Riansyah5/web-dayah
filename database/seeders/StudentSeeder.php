<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buat 50 data santri random
        Student::factory(50)->create();

        // 2. Buat data spesifik (Opsional, untuk testing user tertentu)
        Student::create([
            'nis' => '12345678',
            'nisn' => '0012345678',
            'name' => 'Ahmad Contoh Santri',
            'gender' => 'L',
            'birth_place' => 'Banda Aceh',
            'birth_date' => '2010-01-01',
            'status' => 'active',
            'education_level' => 'MA',
            'class_group' => '10 IPA 1',
            'dormitory' => 'Al-Ghazali',
            'room' => '01'
        ]);
    }
}