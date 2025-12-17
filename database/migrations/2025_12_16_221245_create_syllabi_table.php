<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('syllabi', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('level_id')->constrained()->cascadeOnDelete(); // Materi Kelas 7 beda dgn Kelas 8
            $table->enum('semester', ['Ganjil', 'Genap']);

            // Inti materi: "Thaharah, Wudhu, Sholat"
            $table->text('topics');

            // Kompetensi Dasar (Opsional, untuk rapor K13/Merdeka)
            $table->text('competency')->nullable();

            $table->timestamps();

            // Mencegah duplikasi input
            $table->unique(['subject_id', 'level_id', 'semester']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('syllabi');
    }
};
