<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('student_lesson_attendances', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Jurnal Guru
            $table->foreignId('teaching_journal_id')->constrained()->cascadeOnDelete();
            
            // Relasi ke Siswa (Sesuaikan tipe datanya, di sini pakai ULID sesuai request sebelumnya)
            $table->foreignUlid('student_id')->constrained('students')->cascadeOnDelete();
            
            // Status Kehadiran
            // H=Hadir, S=Sakit, I=Izin, A=Alpha/Tanpa Ket
            $table->enum('status', ['present', 'sick', 'permission', 'alpha'])->default('present');
            
            $table->string('note')->nullable(); // Ket tambahan: "Tidur di kelas", "Pulang cepat"
            
            $table->timestamps();

            // Mencegah duplikasi: 1 Siswa hanya punya 1 status per jurnal ini
            $table->unique(['teaching_journal_id', 'student_id'], 'unique_attendance');
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_lesson_attendances');
    }
};