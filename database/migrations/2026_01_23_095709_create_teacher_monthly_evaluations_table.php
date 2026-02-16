<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('teacher_monthly_evaluations', function (Blueprint $table) {
            $table->id();
            
            // Konteks Guru & Waktu
            $table->foreignUlid('teacher_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('month'); // 1 - 12
            $table->year('year'); // 2025
            
            // Snapshot Data Kuantitatif (Disimpan agar jadi arsip tetap)
            // Meskipun dihitung otomatis, kita simpan angkanya di sini saat 'Tutup Buku'
            $table->integer('total_teaching_hours')->default(0); // Total Jam Mengajar
            $table->integer('total_substitute_hours')->default(0); // Total Jam Badal
            $table->integer('total_late_minutes')->default(0); // Total menit keterlambatan (Opsional)
            $table->integer('total_absent_days')->default(0); // Total hari izin
            $table->integer('total_hours')->default(0); // Total jam terhitung
            
            // Data Kualitatif (Inputan Kepala Sekolah)
            $table->text('headmaster_note')->nullable(); // Catatan Evaluasi: "Tingkatkan kedisiplinan"
            $table->tinyInteger('rating')->nullable(); // Skala 1-5 (Bintang)
            
            // Status Penggajian
            $table->boolean('is_approved')->default(false); // Jika true, Bendahara bisa cairkan gaji
            $table->foreignUlid('approved_by')->nullable()->constrained('users'); // Siapa yg menyetujui (Kepsek)
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();

            // Mencegah duplikasi evaluasi untuk guru yg sama di bulan yg sama
            $table->unique(['teacher_id', 'month', 'year'], 'monthly_eval_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('teacher_monthly_evaluations');
    }
};