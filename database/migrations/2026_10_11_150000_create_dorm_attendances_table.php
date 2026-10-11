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
        // 1. Header Sesi Absensi Asrama
        Schema::create('dorm_attendances', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->foreignUlid('dorm_id')->constrained('dorms')->cascadeOnDelete();
            $table->foreignUlid('room_id')->nullable()->constrained('rooms')->nullOnDelete();
            $table->date('date')->index();
            $table->string('session', 50); // subuh, maghrib, isya, malam, dll.
            $table->foreignUlid('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Mencegah duplikasi sesi di asrama / kamar yang sama pada tanggal & sesi yang sama
            $table->unique(['dorm_id', 'room_id', 'date', 'session'], 'dorm_att_session_unique');
        });

        // 2. Detail Status Kehadiran per Santri
        Schema::create('dorm_attendance_details', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('dorm_attendance_id')->constrained('dorm_attendances')->cascadeOnDelete();
            $table->foreignUlid('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignUlid('room_id')->nullable()->constrained('rooms')->nullOnDelete();
            $table->enum('status', ['present', 'sick', 'permission', 'alpha'])->default('present');
            $table->foreignUlid('permission_id')->nullable()->constrained('permissions')->nullOnDelete();
            $table->string('notes')->nullable();
            $table->timestamps();

            // 1 santri hanya boleh 1 status per sesi absensi asrama
            $table->unique(['dorm_attendance_id', 'student_id'], 'dorm_att_detail_student_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dorm_attendance_details');
        Schema::dropIfExists('dorm_attendances');
    }
};

