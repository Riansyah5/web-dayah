<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Tabel Izin Guru
        Schema::create('teacher_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('teacher_id')->constrained()->cascadeOnDelete();
            
            $table->date('date'); // Tanggal Izin
            $table->enum('type', ['sick', 'permit', 'duty']); // Sakit, Izin, Dinas
            $table->text('reason'); // Alasan
            $table->string('attachment')->nullable(); // Foto Surat
            
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignUlid('approved_by')->nullable()->constrained('users'); // Admin yg menyetujui
            
            $table->timestamps();
        });

        // 2. Tabel Guru Pengganti (Badal)
        Schema::create('schedule_substitutes', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('lesson_schedule_id')->constrained()->cascadeOnDelete(); // Jadwal Asli
            $table->foreignUlid('substitute_teacher_id')->constrained('teachers')->cascadeOnDelete(); // Guru Pengganti
            
            $table->date('date'); // Berlaku hanya pada tanggal ini
            $table->text('note')->nullable(); // Pesan dari piket: "Ambil tugas di meja"
            
            $table->foreignUlid('assigned_by')->constrained('users'); // Admin Piket
            
            $table->timestamps();
            
            // Mencegah duplikasi badal di jadwal yang sama pada tanggal yang sama
            $table->unique(['lesson_schedule_id', 'date']); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('schedule_substitutes');
        Schema::dropIfExists('teacher_permissions');
    }
};