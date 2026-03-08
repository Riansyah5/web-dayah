<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cbt_student_exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cbt_account_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cbt_exam_id')->constrained()->cascadeOnDelete();
            $table->dateTime('started_at'); // Waktu mulai klik token
            $table->dateTime('finished_at')->nullable(); // Waktu klik selesai (atau habis waktu)
            $table->enum('status', ['working', 'finished'])->default('working');
            $table->decimal('score', 5, 2)->default(0); // Nilai akhir (Skala 100)
            $table->timestamps();
            
            // Mencegah santri mengerjakan ujian yang sama 2 kali
            $table->unique(['cbt_account_id', 'cbt_exam_id']); 
        });
    }

    public function down() { Schema::dropIfExists('cbt_student_exams'); }
};