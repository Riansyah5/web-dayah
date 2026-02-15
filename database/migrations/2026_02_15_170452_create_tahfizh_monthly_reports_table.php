<?php
// database/migrations/2024_01_01_000000_create_teacher_monthly_reports_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tahfizh_monthly_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('teacher_id');
            $table->string('period', 7); // Format: YYYY-MM
            $table->integer('total_hours')->default(0);
            // $table->decimal('total_salary', 15, 2)->nullable(); // Persiapan untuk fitur gaji
            $table->timestamps();

            // Foreign key (sesuaikan 'users' jika nama tabel guru Anda berbeda)
            // $table->foreignUlid('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
            
            // Mencegah duplikasi data untuk guru yang sama di bulan yang sama
            $table->unique(['teacher_id', 'period']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('teacher_monthly_reports');
    }
};
