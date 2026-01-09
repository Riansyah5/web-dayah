<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tahfizh_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('academic_year_id')->constrained()->cascadeOnDelete();
            
            $table->string('city'); // Lhokseumawe
            $table->date('distribution_date'); // Tanggal Rapor
            // $table->string('headmaster_name'); // Nama Kepala Tahfizh
            // $table->string('headmaster_niy')->nullable(); // NIY/NIP (Opsional)
            
            $table->timestamps();

            // 1 Tahun Ajaran hanya punya 1 Setting
            $table->unique('academic_year_id'); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('tahfizh_settings');
    }
};