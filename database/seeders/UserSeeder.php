<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        // --- 1. Data Admin ---
        DB::table('users')->insert([
            'id'                  => Str::ulid(),
            'name'                => 'Admin Utama',
            'no_hp'               => '081234567890', // Nomor HP statis untuk Admin
            'email_verified_at'   => Carbon::now(),
            'password'            => Hash::make('password'), // Hash untuk 'password'
            'role'                => 'Admin',
            'updated_by'          => 'Rian',
            'created_at'          => Carbon::now(),
            'updated_at'          => Carbon::now(),
        ]);

        // --- 2. Data Guru (5 Dummy Data) ---
        for ($i = 0; $i < 5; $i++) {
            DB::table('users')->insert([
                'id'                  => Str::ulid(),
                'name'                => $faker->name,
                'no_hp'               => $faker->unique()->numerify('08##########'), // 10-12 digit unik
                'email_verified_at'   => $faker->optional(0.8)->dateTimeThisYear(), // 80% kemungkinan terverifikasi
                'password'            => Hash::make('password'), // Hash untuk 'password'
                'role'                => 'Guru',
                'updated_by'          => 'Rian',
                'created_at'          => Carbon::now(),
                'updated_at'          => Carbon::now(),
            ]);
        }
    }
}