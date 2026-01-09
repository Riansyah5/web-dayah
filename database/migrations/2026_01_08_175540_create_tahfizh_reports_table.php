<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tahfizh_reports', function (Blueprint $table) {
            $table->id();
            
            // Relasi Utama
            $table->foreignUlid('academic_year_id')->constrained()->cascadeOnDelete();
            // PENTING: Pakai foreignUlid karena tabel students Anda pakai ULID
            $table->foreignUlid('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignUlid('teacher_id')->nullable()->constrained('teachers')->nullOnDelete(); // Penilai (Musyrif)

            // A. Hafalan (Tahfizh)
            // Kita simpan array nilai juz di sini. Contoh data: [{"juz": 30, "score": 90}, {"juz": 29, "score": 85}]
            $table->json('juz_scores')->nullable(); 
            $table->string('total_hafalan')->nullable(); // Cth: "5 Juz"
            $table->integer('score_tahriri')->nullable(); // Ujian Tulis (0-100)

            // B. Kualitas Bacaan (Tahsin) - Skala 0-100
            $table->integer('score_makhraj')->nullable();
            $table->integer('score_ghunnah')->nullable();
            $table->integer('score_mad')->nullable();
            $table->integer('score_fasohah')->nullable();
            $table->integer('score_kelancaran')->nullable();

            // C & D. Catatan
            $table->text('note_student')->nullable(); // Untuk Anak
            $table->text('note_parent')->nullable();  // Untuk Orang Tua

            // E. Kehadiran
            $table->integer('sick')->default(0);       // Sakit
            $table->integer('permission')->default(0); // Izin
            $table->integer('alpha')->default(0);      // Alpa

            // Status System
            $table->boolean('is_locked')->default(false); // Agar nilai tidak berubah setelah cetak
            
            $table->timestamps();
            
            // Mencegah duplikasi: 1 Santri hanya punya 1 Rapor per Semester
            $table->unique(['academic_year_id', 'student_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tahfizh_reports');
    }
};