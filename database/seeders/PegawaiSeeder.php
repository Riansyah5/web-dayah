<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Carbon\Carbon;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Inisialisasi Faker dengan locale Indonesia
        $faker = Faker::create('id_ID');

        // Jumlah data yang ingin dibuat
        $limit = 10;

        for ($i = 0; $i < $limit; $i++) {
            // Tentukan jenis kelamin
            $jenis_kelamin = $faker->randomElement(['Laki-laki', 'Perempuan']);
            
            // Tentukan tanggal lahir (usia antara 25-45 tahun)
            $tanggal_lahir = $faker->dateTimeBetween('-45 years', '-25 years')->format('Y-m-d');
            
            // Tentukan status pegawai
            $status_pegawai = $faker->randomElement(['Tetap', 'Kontrak', 'Honorer']);

            // Contoh untuk user_id: Anda harus memastikan ada data di tabel 'users' 
            // atau biarkan null seperti skema
            $user_id = null; // Biarkan null sesuai skema

            DB::table('pegawais')->insert([
                'id'                      => Str::ulid(), // Menggunakan ULID
                'user_id'                 => $user_id,
                'nik'                     => $faker->unique()->numerify('################'), // 16 digit angka unik
                'nama'                    => $faker->name($jenis_kelamin == 'Laki-laki' ? 'male' : 'female'),
                'jenis_kelamin'           => $jenis_kelamin,
                'tempat_lahir'            => $faker->city,
                'tanggal_lahir'           => $tanggal_lahir,
                'status_perkawinan'       => $faker->randomElement(['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati']),
                'no_kk'                   => $faker->optional()->numerify('################'), // Opsional 16 digit angka
                'no_hp'                   => $faker->optional()->phoneNumber, // Opsional nomor telepon
                
                // Alamat opsional
                'desa'                    => $faker->optional()->citySuffix,
                'kecamatan'               => $faker->optional()->streetName,
                'kabupaten'               => $faker->optional()->city,
                'provinsi'                => $faker->optional()->state,
                
                'status_pegawai'          => $status_pegawai,
                'jabatan'                 => $faker->jobTitle,
                'terhitung_mulai_tanggal' => $faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
                'created_at'              => Carbon::now(),
                'updated_at'              => Carbon::now(),
            ]);
        }
    }
}