<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
// use Database\Seeders\JabatanSeeder;
// use Database\Seeders\KategoriSeeder;
// use Database\Seeders\QuranSurahSeeder;
// use Database\Seeders\StageSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Seeder lain (jika ada)
            KategoriSeeder::class,
            JabatanSeeder::class,
            QuranSurahSeeder::class,
            StageSeeder::class,
            DormSeeder::class,
            SuperAdminSeeder::class,
            TahfizhScheduleSeeder::class,
        ]);
    }
}
