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
        // 1. Jenjang (SD/MI, SMP/MTS, SMA/MA)
        Schema::create('stages', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name'); // Madrasah Aliyah
            $table->string('code'); // MA
            $table->timestamps();
        });

        // 2. Tingkat (Kelas 7, Kelas 10, dll)
        Schema::create('levels', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('stage_id')->constrained('stages')->cascadeOnDelete();
            $table->string('name'); // Kelas 10
            $table->string('alias'); // 10
            $table->timestamps();
        });

        // 3. Jurusan (IPA, IPS, Umum)
        Schema::create('majors', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name'); // Ilmu Pengetahuan Alam
            $table->string('code'); // IPA
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_master_tables');
    }
};
