<?php

namespace Database\Seeders;

use App\Models\Dorm;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Dorm::create([
            'name' => 'Wustha Ikhwan',
            'gender' => 'L'
        ]);

        Dorm::create([
            'name' => 'Wustha Akhwat',
            'gender' => 'P'
        ]);

        Dorm::create([
            'name' => 'Ulya Ikhwan',
            'gender' => 'L'
        ]);

        Dorm::create([
            'name' => 'Ulya Akhwat',
            'gender' => 'P'
        ]);
    }
}
