<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cbt_question_banks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete(); // Guru pembuat soal
            $table->string('subject_name'); // Nama Mata Pelajaran (Misal: Nahwu, Fiqih)
            $table->string('level'); // Kelas (Misal: 1 Utsman, 2 Ali)
            $table->string('bank_code')->unique(); // Kode Bank Soal (Misal: NHW-01)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cbt_question_banks');
    }
};