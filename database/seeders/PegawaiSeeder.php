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
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $data = [];

        for ($i = 1; $i <= 50; $i++) {
            $jenisKelamin = $faker->randomElement(['Laki-laki', 'Perempuan']);

            $data[] = [
                'id' => (string) Str::ulid(),
                'user_id' => null, // isi jika ingin relasi ke users
                'nik' => $faker->unique()->numerify('################'), // 16 digit
                'nama' => $faker->name($jenisKelamin === 'Laki-laki' ? 'male' : 'female'),
                'jenis_kelamin' => $jenisKelamin,
                'tempat_lahir' => $faker->city,
                'tanggal_lahir' => $faker->dateTimeBetween('-55 years', '-20 years')->format('Y-m-d'),
                'status_perkawinan' => $faker->randomElement([
                    'Belum Menikah',
                    'Menikah',
                    'Cerai Hidup',
                    'Cerai Mati',
                ]),
                'no_kk' => $faker->boolean(70) ? $faker->numerify('################') : null,
                'no_hp' => $faker->boolean(85) ? $faker->numerify('08##########') : null,
                'desa' => $faker->boolean(80) ? $faker->streetName : null,
                'kecamatan' => $faker->boolean(80) ? $faker->citySuffix : null,
                'kabupaten' => $faker->boolean(80) ? $faker->city : null,
                'provinsi' => $faker->randomElement([
                    'Jawa Barat',
                    'Jawa Tengah',
                    'Jawa Timur',
                    'DKI Jakarta',
                    'Banten',
                ]),
                'kategori_pegawai' => $faker->randomElement([
                    'TETAP',
                    'KONTRAK',
                    'HONORER',
                ]),
                'status_pegawai' => $faker->randomElement([
                    'Aktif',
                    'Cuti',
                    'Non-aktif',
                    'Keluar'
                ]),
                'jabatan' => $faker->randomElement([
                    'Staff Administrasi',
                    'Operator',
                    'Supervisor',
                    'Kepala Seksi',
                    'Analis',
                ]),
                'terhitung_mulai_tanggal' => $faker->dateTimeBetween('-10 years', 'now')->format('Y-m-d'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('pegawais')->insert($data);
    }
}
