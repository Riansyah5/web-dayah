<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tahfizh_journals', function (Blueprint $table) {
            $table->ulid('id')->primary(); // Pakai ULID agar unik & random
            
            // Relasi Konteks
            $table->foreignId('tahfizh_halaqah_id')->constrained()->cascadeOnDelete();
            
            // Relasi ke Jadwal (Agar tahu ini sesi apa saat itu)
            $table->foreignId('tahfizh_schedule_id')->constrained();
            
            // Guru yang mengabsen (Bisa guru asli / badal)
            $table->foreignUlid('teacher_id')->constrained('teachers');
            
            // Waktu Realisasi
            $table->date('date')->index();
            $table->timestamp('clock_in')->nullable(); // Waktu tap absen
            
            // Bukti
            $table->string('photo_proof')->nullable(); // Foto halaqah
            $table->text('note')->nullable(); // Catatan harian
            
            $table->timestamps();

            // Mencegah duplikasi: 1 Halaqah hanya boleh 1 Jurnal per Sesi Jadwal per Tanggal
            $table->unique(['tahfizh_halaqah_id', 'tahfizh_schedule_id', 'date'], 'unique_halaqah_journal');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tahfizh_journals');
    }
};