<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('teacher_permission_tahfizh_details', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke Header Izin Utama (Tabel yang sama dengan KBM)
            $table->foreignId('teacher_permission_id')->constrained()->cascadeOnDelete();
            
            // Relasi ke Master Jadwal Tahfizh (Qabla, Ba'da, Dhuha)
            $table->foreignId('tahfizh_schedule_id')->constrained();
            
            $table->timestamps();

            // Mencegah duplikasi
            $table->unique(['teacher_permission_id', 'tahfizh_schedule_id'], 'perm_tahfizh_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('teacher_permission_tahfizh_details');
    }
};