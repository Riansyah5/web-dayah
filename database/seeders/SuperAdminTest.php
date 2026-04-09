<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Pastikan import model User
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminTest extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data yang ingin ditambah
        $users = [
            [
                'name' => 'Rian',
                'username' => 'rian',
                'email' => 'rian@gmail.com',
                'password' => Hash::make('bismillah@24434'),
                'role' => 'Superadmin',
                'status' => 'Aktif',
                'updated_by' => 'Rian',
            ],
        ];

        foreach ($users as $userData) {
            // firstOrCreate( [kolom_untuk_dicek], [data_tambahan_jika_tidak_ada] )
            User::firstOrCreate(
                ['email' => $userData['email']], // Cek berdasarkan email
                [
                    'id' => (string) Str::ulid(), // Hanya dibuat jika user baru
                    'name' => $userData['name'],
                    'username' => $userData['username'],
                    'password' => $userData['password'],
                    'role' => $userData['role'],
                    'status' => $userData['status'],
                    'updated_by' => $userData['updated_by'],
                ]
            );
        }
    }
}