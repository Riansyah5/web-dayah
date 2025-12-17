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
        // 1. TABEL KBM / PLOTTING (Course)
        // Menentukan: Di Kelas 7A, Mapel Fiqih, Gurunya Siapa, KKM-nya berapa.
        Schema::create('courses', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('classroom_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('teacher_id')->nullable()->constrained()->nullOnDelete(); // Bisa null jika belum ada guru

            $table->integer('kkm')->default(70); // KKM Spesifik per mapel di kelas ini
            $table->timestamps();

            // Constraint: Satu kelas tidak boleh punya 2 data untuk mapel yang sama
            $table->unique(['classroom_id', 'subject_id']);
        });

        // 2. TABEL NILAI (Grades)
        Schema::create('grades', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('course_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('student_id')->constrained('students')->cascadeOnDelete();

            // Komponen Nilai (Float agar support desimal)
            $table->float('score_harian')->default(0);  // Rata-rata PH
            $table->float('score_uts')->default(0);     // PTS
            $table->float('score_uas')->default(0);     // PAS
            $table->float('score_final')->default(0);   // Nilai Rapor (Rumus)

            $table->string('grade_letter')->nullable(); // A, B, C, D
            $table->text('description')->nullable();    // Deskripsi Otomatis/Manual

            $table->timestamps();

            // Satu siswa hanya punya 1 nilai per mapel di kelas tersebut
            $table->unique(['course_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grading_system_tables');
    }
};
