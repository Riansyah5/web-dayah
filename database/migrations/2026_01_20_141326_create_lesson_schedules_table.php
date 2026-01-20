<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lesson_schedules', function (Blueprint $table) {
            $table->id();
            
            // Konteks Tahun Ajaran
            $table->foreignUlid('academic_year_id')->constrained()->cascadeOnDelete();
            
            // Relasi Utama
            $table->foreignUlid('classroom_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('subject_id')->constrained()->cascadeOnDelete(); // Mata Pelajaran
            $table->foreignUlid('teacher_id')->constrained()->cascadeOnDelete(); // Guru Pengampu

            // Waktu
            $table->tinyInteger('day_of_week'); // 1=Senin, 2=Selasa, dst
            $table->time('start_time');
            $table->time('end_time');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lesson_schedules');
    }
};