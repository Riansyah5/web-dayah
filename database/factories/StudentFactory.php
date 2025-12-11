<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        // Tentukan gender dulu untuk menyesuaikan nama
        $gender = fake()->randomElement(['L', 'P']);
        $genderFaker = $gender == 'L' ? 'male' : 'female';

        return [
            // --- Biodata ---
            'nis' => fake()->unique()->numerify('2024####'), // Contoh: 20240001
            'nisn' => fake()->numerify('00########'),
            'name' => fake('id_ID')->name($genderFaker),
            'gender' => $gender,
            'birth_place' => fake('id_ID')->city(),
            'birth_date' => fake()->dateTimeBetween('-18 years', '-12 years')->format('Y-m-d'),
            'child_order' => fake()->numberBetween(1, 5),

            // --- Address (Data Dummy Indonesia) ---
            'nik' => fake()->numerify('11##############'), // 16 digit, 11 kode Aceh/Sumatra
            'family_card_number' => fake()->numerify('11##############'),
            'village' => fake('id_ID')->streetName(), // Faker id_ID tidak punya village spesifik, streetName mirip
            'district' => fake('id_ID')->citySuffix(), // Mirip nama kecamatan
            'regency' => fake('id_ID')->city(),
            'province' => fake('id_ID')->state(),

            // --- Father ---
            'father_name' => fake('id_ID')->name('male'),
            'father_nik' => fake()->numerify('11##############'),
            'father_occupation' => fake('id_ID')->jobTitle(),
            'father_phone' => fake('id_ID')->phoneNumber(),
            'father_education' => fake()->randomElement(['SD', 'SMP', 'SMA', 'S1', 'S2', 'Pesantren']),

            // --- Mother ---
            'mother_name' => fake('id_ID')->name('female'),
            'mother_nik' => fake()->numerify('11##############'),
            'mother_occupation' => fake('id_ID')->jobTitle(),
            'mother_phone' => fake('id_ID')->phoneNumber(),
            'mother_education' => fake()->randomElement(['SD', 'SMP', 'SMA', 'S1', 'Pesantren']),

            // --- Academic ---
            'education_level' => fake()->randomElement(['MTS', 'MA']),
            'major' => fake()->randomElement(['IPA', 'IPS', 'Keagamaan']),
            'class_group' => fake()->randomElement(['7A', '7B', '8A', '10 IPA 1', '11 IPS 2']),
            'acceptance_date' => fake()->dateTimeBetween('-3 years', 'now')->format('Y-m-d'),
            'status' => fake()->randomElement(['active', 'active', 'active', 'graduated', 'moved']), // Lebih banyak active

            // --- Boarding ---
            'dormitory' => fake()->randomElement(['Al-Ghazali', 'Ibnu Sina', 'Ar-Raniry', 'Imam Syafii']),
            'room' => fake()->numberBetween(1, 20),
        ];
    }
}