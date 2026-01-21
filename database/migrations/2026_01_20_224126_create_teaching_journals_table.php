<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('teaching_journals', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Jadwal & Guru
            $table->foreignId('lesson_schedule_id')->constrained()->cascadeOnDelete();
            
            // Guru yang 'SEBENARNYA' mengajar saat itu (Bisa guru asli, bisa guru badal)
            $table->foreignUlid('teacher_id')->constrained('teachers')->cascadeOnDelete();
            
            // Waktu & Materi
            $table->date('date'); // Tanggal Jurnal
            $table->dateTime('clock_in_time'); // Jam saat tombol ditekan
            $table->string('topic'); // Materi Pembelajaran
            $table->text('notes')->nullable(); // Catatan Guru
            
            // Kolom Validasi (GPS & Foto)
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('photo_proof')->nullable(); // Path Foto Bukti
            
            // Penanda Status
            $table->boolean('is_substitute')->default(false); // Apakah ini guru badal?
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('teaching_journals');
    }
};