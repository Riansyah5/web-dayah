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
        Schema::create('classrooms', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('academic_year_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('level_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('major_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name'); // 10 IPA 1
            $table->string('homeroom_teacher')->nullable(); // Wali Kelas
            $table->integer('capacity')->default(30);
            $table->timestamps();
        });

        Schema::create('classroom_student', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('classroom_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('student_id')->constrained('students')->cascadeOnDelete();
            $table->timestamps();

            // Mencegah duplikasi siswa di kelas yang sama
            $table->unique(['classroom_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classrooms_tables');
    }
};
