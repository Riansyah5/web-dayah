<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tahfizh_schedules', function (Blueprint $table) {
            $table->id();
            
            // Nama Sesi: 'Qabla Shubuh', 'Bada Shubuh', 'Dhuha'
            $table->string('session_name'); 
            
            // Hari: 1 (Senin) - 7 (Minggu)
            $table->tinyInteger('day_of_week'); 
            
            // Waktu Mulai & Selesai (Bisa diedit admin tiap bulan)
            $table->time('start_time');
            $table->time('end_time');
            
            // Urutan tampilan di dashboard (1, 2, 3)
            $table->tinyInteger('order_index')->default(1);
            
            // Status aktif (jika ada sesi yang ditiadakan sementara)
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tahfizh_schedules');
    }
};