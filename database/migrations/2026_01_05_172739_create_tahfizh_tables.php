<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // 1. Tabel Referensi Surat (Data Master Al-Qur'an)
        Schema::create('quran_surahs', function (Blueprint $table) {
            $table->id(); // Ini akan menjadi Nomor Surat (1-114)
            $table->string('name_latin'); // Contoh: Al-Fatihah
            $table->string('name_arabic')->nullable(); // Contoh: الفاتحة
            $table->integer('total_verses'); // Contoh: 7
            $table->timestamps();
        });

        // 2. Tabel Halaqah (Kelompok Belajar)
        Schema::create('tahfizh_halaqahs', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('academic_year_id')->constrained()->cascadeOnDelete();

            // Asumsi tabel guru bernama 'teachers', sesuaikan jika beda
            $table->foreignUlid('teacher_id')->nullable()->constrained('teachers')->nullOnDelete();

            $table->string('name'); // Contoh: Halaqah Abu Bakar
            $table->enum('gender', ['L', 'P']); // Kelompok Putra/Putri
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 3. Tabel Anggota Halaqah (Pivot: Siswa masuk kelompok mana)
        Schema::create('tahfizh_students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahfizh_halaqah_id')->constrained()->cascadeOnDelete();

            // PENTING: Gunakan foreignUlid karena tabel students Anda pakai ULID
            $table->foreignUlid('student_id')->constrained('students')->cascadeOnDelete();

            $table->timestamps();

            // Mencegah duplikasi: 1 siswa hanya boleh ada di 1 halaqah
            $table->unique('student_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tahfizh_students');
        Schema::dropIfExists('tahfizh_halaqahs');
        Schema::dropIfExists('quran_surahs');
    }
};
