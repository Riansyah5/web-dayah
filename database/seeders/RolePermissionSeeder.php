<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache Spatie (Wajib agar tidak error saat seeding ulang)
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. DAFTAR HAK AKSES (PERMISSIONS)
        // Dikelompokkan berdasarkan Modul dari routes/web.php
        $permissions = [
            // --- PENGATURAN & MASTER DATA ---
            'kelola-pengaturan-sistem', // Sidebar, Maintenance
            'kelola-master-data',       // Tahun akademik, mapel, tingkat, dll
            'kelola-user-pegawai',      // CRUD User, Jabatan, Pegawai
            
            // --- KESISWAAN & ASRAMA ---
            'lihat-data-santri',
            'kelola-data-santri',       // CRUD Siswa, Import, Mutasi, Promosi, Kelulusan
            'lihat-asrama',
            'kelola-asrama-kamar',      // CRUD Dorm, Room, Assignment
            'kelola-pelanggaran',       // Input & pantau pelanggaran
            'kelola-perizinan-santri',  // Perizinan keluar/pulang santri
            
            // --- AKADEMIK & JADWAL ---
            'lihat-kelas',
            'kelola-kelas',             // CRUD kelas, wali kelas, jurusan
            'lihat-jadwal-pelajaran',
            'kelola-jadwal-pelajaran',  // Plotting jadwal, kalender akademik
            'kelola-piket-badal',       // Assign badal, ACC izin guru
            'ajukan-izin-guru',         // Guru mengajukan izin
            'isi-jurnal-guru',          // Jurnal mengajar & absen kelas
            
            // --- RAPOR & PENILAIAN ---
            'isi-nilai-mapel',          // Guru mapel input nilai
            'kelola-leger-rapor',       // Wali kelas kelola leger & cetak rapor
            'kelola-setting-rapor',     // Admin atur ttd, tanggal rapor, dll
            
            // --- TAHFIZH ---
            'kelola-jadwal-tahfizh',    // Master schedule, cleanup tahfizh
            'pantau-tahfizh-admin',     // Monitoring, laporan rekap, assign badal tahfizh
            'lihat-halaqah',           // lihat anggota halaqah
            'kelola-halaqah',           // Plotting anggota halaqah
            'isi-jurnal-tahfizh',       // Absensi tahfizh harian
            'isi-setoran-tahfizh',      // Input setoran hafalan
            'kelola-rapor-tahfizh',     // Assessment & cetak rapor tahfizh
            
            // --- CBT (COMPUTER BASED TEST) ---
            'kelola-akun-cbt',          // Admin generate akun, reset PIN santri
            'kelola-jadwal-ujian-cbt',  // Admin atur jadwal & token
            'pantau-ujian-cbt',         // Admin live monitoring & force finish
            'kelola-bank-soal',         // Guru buat bank soal & input pertanyaan
            'koreksi-hasil-ujian',      // Guru koreksi essay & cetak nilai
        ];

        // Masukkan semua permission ke database
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 2. PEMBUATAN ROLE & ASSIGN PERMISSION

        // ==========================================
        // ROLE: GURU
        // ==========================================
        $roleGuru = Role::firstOrCreate(['name' => 'Guru']);
        $roleGuru->syncPermissions([
            // Hak akses standar seorang guru
            'lihat-data-santri',
            'lihat-jadwal-pelajaran',
            'ajukan-izin-guru',
            'isi-jurnal-guru',
            'isi-nilai-mapel',
            'lihat-kelas',
            
            // Hak akses Tahfizh (Bisa jadi semua guru adalah guru tahfizh, atau nanti diatur via direct permission)
            'lihat-halaqah',
            'isi-jurnal-tahfizh',
            'isi-setoran-tahfizh',
            'kelola-rapor-tahfizh',

            'lihat-asrama',
            
            // Hak akses CBT (Guru Mapel)
            'kelola-bank-soal',
            'koreksi-hasil-ujian',
        ]);

        // ==========================================
        // ROLE: ADMIN
        // ==========================================
        $roleAdmin = Role::firstOrCreate(['name' => 'Admin']);
        $roleAdmin->syncPermissions([
            'kelola-master-data',
            'kelola-user-pegawai',
            'lihat-data-santri',
            'kelola-data-santri',
            'kelola-asrama-kamar',
            'kelola-pelanggaran',
            'kelola-perizinan-santri',
            'lihat-jadwal-pelajaran',
            'kelola-jadwal-pelajaran',
            'kelola-piket-badal',
            'kelola-setting-rapor',
            'kelola-jadwal-tahfizh',
            'pantau-tahfizh-admin',
            'kelola-halaqah',
            'kelola-akun-cbt',
            'kelola-jadwal-ujian-cbt',
            'pantau-ujian-cbt',
            'kelola-kelas',
        ]);

        // ==========================================
        // ROLE: SUPERADMIN
        // ==========================================
        $roleSuperadmin = Role::firstOrCreate(['name' => 'Superadmin']);
        // Memberikan semua hak akses ke Superadmin
        $roleSuperadmin->syncPermissions(Permission::all());
    }
}