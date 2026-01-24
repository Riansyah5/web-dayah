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
        Schema::create('teacher_permission_details', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Pengajuan Izin
            $table->foreignId('teacher_permission_id')->constrained()->cascadeOnDelete();
            
            // Relasi ke Jadwal Pelajaran Spesifik
            $table->foreignId('lesson_schedule_id')->constrained()->cascadeOnDelete();
            
            $table->timestamps();

            // Mencegah duplikasi jadwal di satu izin
            $table->unique(['teacher_permission_id', 'lesson_schedule_id'], 'perm_sched_unique');
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_permission_details');
    }
};
