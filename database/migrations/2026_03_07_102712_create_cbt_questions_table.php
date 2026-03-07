<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cbt_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cbt_question_bank_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['multiple_choice', 'essay'])->default('multiple_choice');
            $table->text('question_text'); // Soal teks (Bisa panjang dan pakai format Arab)
            $table->string('image_file')->nullable(); // Lampiran gambar
            $table->string('audio_file')->nullable(); // Lampiran audio (Listening)
            $table->integer('score_weight')->default(1); // Bobot nilai soal (Misal PG = 1, Essay = 5)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cbt_questions');
    }
};