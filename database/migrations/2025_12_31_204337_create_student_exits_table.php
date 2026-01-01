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
        Schema::create('student_exits', function (Blueprint $table) {
            $table->ulid('id')->primary();
            // PENTING: Gunakan foreignUlid karena tabel students pakai ULID
            $table->foreignUlid('student_id')->constrained('students')->cascadeOnDelete();
            
            // Menyimpan jenis mutasi agar sinkron dengan status di tabel students
            // Values: 'graduated', 'moved', 'suspended', 'deceased'
            $table->string('category'); 
            
            // Detail Waktu & Legalitas
            $table->date('exit_date'); // Tanggal resmi keluar/lulus
            $table->string('exit_year', 4); // Tahun ajaran (misal: 2025)
            $table->string('sk_number')->nullable(); // No. Surat Keputusan / No. Ijazah / SKL
            
            // Detail Alasan & Tujuan
            $table->text('reason')->nullable(); // Alasan pindah/keluar
            $table->string('destination')->nullable(); // Sekolah/Pondok tujuan (untuk pindah)
            
            // Arsip Digital
            $table->string('file_path')->nullable(); // Upload Scan SKL/Surat Pindah
            
            // Nilai Akhir (Opsional, untuk data alumni)
            $table->decimal('final_score', 5, 2)->nullable(); 
            
            $table->text('note')->nullable(); // Catatan tambahan admin
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_exits');
    }
};
