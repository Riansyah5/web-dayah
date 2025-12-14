<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabel Tahun Ajaran
        Schema::create('academic_years', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name'); // Nama tahun akademik, contoh: "2023/2024"
            $table->enum('semester', ['Ganjil', 'Genap']); // Semester: Ganjil atau Genap
            $table->boolean('is_active')->default(false); // Hanya satu yg boleh true nanti
            $table->timestamps();
        });

        // Tabel Santri
        Schema::create('students', function (Blueprint $table) {
            $table->ulid('id')->primary();
            // --- Main Biodata ---
            // NIS (Nomor Induk Santri) - Unique identifier
            $table->string('nis')->unique();
            // NISN (National Student ID)
            $table->string('nisn')->nullable();
            $table->string('name');
            $table->enum('gender', ['L', 'P']); // Jenis Kelamin
            $table->string('birth_place'); // Tempat Lahir
            $table->date('birth_date'); // Tanggal Lahir
            $table->integer('child_order')->nullable(); // Anak Ke

            // --- Address (Alamat) ---
            $table->string('nik')->nullable(); // NIK (National Identity Number)
            $table->string('family_card_number')->nullable(); // Nomor KK
            $table->string('village')->nullable(); // Desa
            $table->string('district')->nullable(); // Kecamatan
            $table->string('regency')->nullable(); // Kabupaten
            $table->string('province')->nullable(); // Provinsi

            // --- Father (Ayah) ---
            $table->string('father_name')->nullable();
            $table->string('father_nik')->nullable();
            $table->string('father_occupation')->nullable(); // Pekerjaan Ayah
            $table->string('father_occupation_detail')->nullable(); // Penjelasan Pekerjaan
            $table->string('father_education')->nullable(); // Pendidikan Terakhir
            $table->string('father_phone')->nullable();

            // --- Mother (Ibu) ---
            $table->string('mother_name')->nullable();
            $table->string('mother_nik')->nullable();
            $table->string('mother_occupation')->nullable(); // Pekerjaan Ibu
            $table->string('mother_occupation_detail')->nullable(); // Penjelasan Pekerjaan
            $table->string('mother_education')->nullable(); // Pendidikan Terakhir
            $table->string('mother_phone')->nullable();

            // --- Guardian (Wali) ---
            $table->string('guardian_name')->nullable();
            $table->string('guardian_occupation')->nullable();
            $table->string('guardian_occupation_detail')->nullable();
            $table->string('guardian_phone')->nullable();

            // --- Academic (Akademik) ---
            $table->string('education_level')->nullable(); // Jenjang (e.g., Salafiyah Ulya)
            $table->string('major')->nullable(); // Jurusan (e.g., IPA)
            $table->string('class_group')->nullable(); // Rombel (e.g., Kelas 6 B)
            $table->string('previous_school')->nullable(); // Asal Sekolah
            $table->date('acceptance_date')->nullable(); // Tanggal Terima
            $table->string('accepted_in_grade')->nullable(); // Terima di Kelas
            // Status santri
            $table->enum('status', ['active', 'graduated', 'moved', 'suspended'])->default('active');

            // --- Boarding/Care (Pengasuhan) ---
            $table->string('dormitory')->nullable(); // Sakan
            $table->string('room')->nullable(); // Kamar

            $table->timestamps();
        });

        // Tabel Asrama & Kamar
        // Tabel Gedung Asrama (Misal: Gedung Umar, Gedung Aisyah)
        Schema::create('dorms', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->enum('gender', ['L', 'P']); // Gedung Putra/Putri
            $table->timestamps();
        });

        // Tabel Kamar
        Schema::create('rooms', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('dorm_id')->constrained('dorms')->cascadeOnDelete();
            $table->string('name'); // Misal: "Kamar 101"
            $table->integer('capacity'); // Misal: 10 orang
            
            // Relasi ke User (Wali Kamar/Musyrif)
            // Pastikan tabel 'users' sudah ada bawaan Laravel
            $table->foreignUlid('warden_id')->nullable()->constrained('pegawais')->nullOnDelete();
            
            $table->timestamps();
        });

        // Tabel Penempatan Kamar
        Schema::create('room_assignments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignUlid('room_id')->constrained('rooms')->cascadeOnDelete();
            $table->foreignUlid('academic_year_id')->constrained('academic_years');
            
            // Catatan tambahan misal: "Ketua Kamar"
            $table->enum('role_in_room', ['member', 'leader'])->default('member'); 
            
            $table->timestamps();
            
            // Mencegah duplikasi: 1 santri hanya boleh 1 kamar di tahun ajaran yg sama
            $table->unique(['student_id', 'academic_year_id']); 
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop tables in reverse order of creation to respect foreign key constraints
        // Schema::dropIfExists('student_permissions');
        Schema::dropIfExists('room_assignments');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('dorms');
        Schema::dropIfExists('students');
        Schema::dropIfExists('academic_years');
    }
};
