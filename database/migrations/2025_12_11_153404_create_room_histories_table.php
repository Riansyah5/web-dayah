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
        Schema::create('room_histories', function (Blueprint $table) {
            $table->ulid('id')->primary();
            // Relasi ke Siswa (Pakai ulid karena table students pakai ulid)
            $table->foreignUlid('student_id')->constrained('students')->onDelete('cascade');

            // Relasi ke Kamar (Pastikan table rooms sudah ada)
            $table->foreignUlid('room_id')->constrained('rooms')->onDelete('cascade');

            $table->date('start_date'); // Tanggal Masuk Kamar
            $table->date('end_date')->nullable(); // Tanggal Keluar (Null = Masih di sini)
            $table->string('reason')->nullable(); // Alasan pindah (e.g. Kenaikan Kelas, Hukuman)
            $table->boolean('is_active')->default(true); // Penanda kamar aktif saat ini
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_histories');
    }
};
