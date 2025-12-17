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
    // 1. Tabel MATA PELAJARAN
    Schema::create('subjects', function (Blueprint $table) {
        $table->ulid('id')->primary();
        $table->string('name'); // Matematika
        $table->string('code')->unique(); // MTK
        $table->enum('group', ['A', 'B', 'C', 'Diniyah', 'Mulok'])->default('A'); 
        $table->timestamps();
    });

    // 2. Tabel GURU
    Schema::create('teachers', function (Blueprint $table) {
        $table->ulid('id')->primary();
        $table->string('name'); // Budi Santoso
        $table->string('nip')->nullable(); 
        $table->string('title')->nullable(); // S.Pd
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects_and_teachers');
    }
};
