<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tahfizh_attendances', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Header Jurnal
            $table->foreignUlid('tahfizh_journal_id')->constrained('tahfizh_journals')->cascadeOnDelete();
            
            // Relasi ke Siswa
            $table->foreignUlid('student_id')->constrained('students')->cascadeOnDelete();
            
            // Status Kehadiran
            $table->enum('status', ['present', 'sick', 'permission', 'alpha', 'late'])->default('present');
            
            $table->string('note')->nullable(); // Alasan sakit/izin
            
            $table->timestamps();
            
            // Mencegah duplikasi data siswa di satu jurnal
            $table->unique(['tahfizh_journal_id', 'student_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tahfizh_attendances');
    }
};