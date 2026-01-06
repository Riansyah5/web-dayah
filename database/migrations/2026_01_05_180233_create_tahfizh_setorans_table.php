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
        Schema::create('tahfizh_setorans', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Siswa & Guru Penenyimak
            $table->foreignUlid('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignUlid('teacher_id')->nullable()->constrained('teachers')->nullOnDelete();
            
            $table->date('date'); // Tanggal Setoran
            $table->enum('type', ['ziyadah', 'murajaah']); // Hafalan Baru / Mengulang
            
            // Lokasi Hafalan
            $table->integer('juz'); // 1-30
            
            // Mulai (Start)
            $table->foreignId('surah_start_id')->constrained('quran_surahs');
            $table->integer('ayat_start');
            
            // Selesai (End) - Bisa beda surat (misal: akhir Al-Fil sambung ke Quraysh)
            $table->foreignId('surah_end_id')->constrained('quran_surahs');
            $table->integer('ayat_end');
            
            // Kualitas Bacaan
            $table->enum('quality', ['lancar', 'kurang', 'ulang'])->default('lancar');
            $table->text('note')->nullable(); // Catatan Tajwid/Makhraj

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahfizh_setorans');
    }
};
