<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cbt_student_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cbt_student_exam_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cbt_question_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cbt_option_id')->nullable()->constrained()->cascadeOnDelete(); // Jawaban PG
            $table->text('essay_answer')->nullable(); // Jawaban Essay
            $table->integer('question_order')->default(1); // Urutan soal acak khusus untuk santri ini
            $table->boolean('is_correct')->nullable(); // Benar / Salah
            $table->timestamps();
        });
    }

    public function down() { Schema::dropIfExists('cbt_student_answers'); }
};