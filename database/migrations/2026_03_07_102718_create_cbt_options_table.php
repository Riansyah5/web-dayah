<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cbt_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cbt_question_id')->constrained()->cascadeOnDelete();
            $table->text('option_text'); // Teks jawaban (Bisa Arab juga)
            $table->string('image_file')->nullable(); // Kadang opsi jawaban berupa gambar
            $table->boolean('is_correct')->default(false); // Penanda kunci jawaban
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cbt_options');
    }
};