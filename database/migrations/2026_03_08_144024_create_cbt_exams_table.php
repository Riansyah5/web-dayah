<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cbt_exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cbt_question_bank_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // Misal: "UTS Ganjil Nahwu Kelas 1"
            $table->dateTime('start_time'); // Waktu ujian bisa mulai diakses
            $table->dateTime('end_time'); // Batas akhir ujian ditutup
            $table->integer('duration'); // Durasi pengerjaan dalam menit (Misal: 90)
            $table->string('token', 10)->unique(); // Token rahasia (Misal: X7B9K)
            $table->boolean('randomize_questions')->default(true); // Acak urutan soal?
            $table->boolean('randomize_options')->default(true); // Acak opsi A,B,C,D?
            $table->boolean('show_result')->default(false); // Tampilkan nilai setelah selesai?
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cbt_exams');
    }
};