<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('violations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('student_id')->constrained('students')->onDelete('cascade');

            $table->date('violation_date');
            $table->string('academic_year'); // Contoh: "2024/2025"
            $table->enum('semester', ['Ganjil', 'Genap']);

            $table->string('category'); // Ringan, Sedang, Berat
            $table->integer('points')->default(0); // Poin pelanggaran
            $table->text('description'); // Deskripsi kejadian
            $table->text('punishment')->nullable(); // Hukuman/Takzir
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('violations');
    }
};
