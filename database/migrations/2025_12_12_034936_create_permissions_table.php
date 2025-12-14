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
        Schema::create('permissions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            // Relasi ke Santri (ULID)
            $table->foreignUlid('student_id')->constrained('students')->onDelete('cascade');

            // Relasi ke User (Siapa yang input/approve, opsional jika belum ada auth user)
            $table->foreignUlid('user_id')->nullable()->constrained('users'); 

            $table->enum('type', ['sakit', 'izin', 'pulang']); // Sakit di asrama, Izin Keluar sebentar, Pulang ke rumah
            $table->string('reason'); // Alasan izin

            $table->dateTime('start_date'); // Tanggal/Jam Keluar
            $table->dateTime('end_date');   // Rencana Tanggal/Jam Kembali

            $table->dateTime('returned_at')->nullable(); // Realisasi Tanggal Kembali (Diisi saat santri balik)

            $table->enum('status', ['pending', 'approved', 'rejected', 'returned', 'late'])->default('pending');

            $table->text('notes')->nullable(); // Catatan tambahan (misal: membawa laptop)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
