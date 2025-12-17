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
        Schema::create('report_cards', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignUlid('classroom_id')->constrained('classrooms')->cascadeOnDelete();

            // Kehadiran (Diinput Wali Kelas / Tarik dari Absensi)
            $table->integer('sick')->default(0);       // Sakit
            $table->integer('permission')->default(0); // Izin
            $table->integer('absent')->default(0);     // Alpa

            // Catatan & Status
            $table->text('notes')->nullable(); // Catatan Wali Kelas
            $table->string('status')->default('Naik Kelas'); // Naik / Tinggal / Lulus
            $table->integer('ranking')->nullable(); // Ranking di kelas (Otomatis)

            $table->timestamps();

            // Satu siswa hanya punya 1 rekap per kelas (per semester)
            $table->unique(['student_id', 'classroom_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_cards');
    }
};
