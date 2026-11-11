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
        Schema::create('registrations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('registration_number', 30)->unique();
            $table->foreignUlid('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $table->string('target_stage', 50)->nullable(); // Misal: MTS, MA, Madin
            $table->string('target_major', 50)->nullable(); // Peminatan jika ada

            // --- Main Biodata Calon Santri ---
            $table->string('name');
            $table->enum('gender', ['L', 'P']);
            $table->string('nik', 16)->nullable()->index();
            $table->string('nisn', 30)->nullable();
            $table->string('birth_place');
            $table->date('birth_date');
            $table->integer('child_order')->nullable();
            $table->string('previous_school')->nullable(); // Asal Sekolah

            // --- Address (Alamat) ---
            $table->string('family_card_number', 16)->nullable();
            $table->string('village')->nullable();
            $table->string('district')->nullable();
            $table->string('regency')->nullable();
            $table->string('province')->nullable();

            // --- Father (Ayah) ---
            $table->enum('father_status', ['alive', 'deceased'])->default('alive');
            $table->string('father_name')->nullable();
            $table->string('father_nik', 16)->nullable();
            $table->string('father_phone', 25)->nullable();
            $table->string('father_education')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('father_occupation_detail')->nullable();

            // --- Mother (Ibu) ---
            $table->enum('mother_status', ['alive', 'deceased'])->default('alive');
            $table->string('mother_name')->nullable();
            $table->string('mother_nik', 16)->nullable();
            $table->string('mother_phone', 25)->nullable();
            $table->string('mother_education')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('mother_occupation_detail')->nullable();

            // --- Guardian (Wali) ---
            $table->string('guardian_name')->nullable();
            $table->string('guardian_phone', 25)->nullable();
            $table->string('guardian_education')->nullable();
            $table->string('guardian_occupation')->nullable();
            $table->string('guardian_occupation_detail')->nullable();

            // --- Seleksi & Status Pendaftaran ---
            // pending = baru mendaftar
            // verified = berkas/administrasi lengkap
            // passed = lulus seleksi / diterima
            // rejected = tidak diterima
            // enrolled = sudah dimigrasi menjadi santri aktif
            $table->enum('status', ['pending', 'verified', 'passed', 'rejected', 'enrolled'])->default('pending')->index();
            $table->float('selection_score')->nullable(); // Nilai tes/wawancara
            $table->text('notes')->nullable(); // Catatan panitia seleksi

            // Relasi ke data santri setelah status menjadi 'enrolled'
            $table->foreignUlid('student_id')->nullable()->constrained('students')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};

