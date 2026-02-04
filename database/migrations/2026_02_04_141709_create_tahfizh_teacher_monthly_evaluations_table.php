<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tahfizh_teacher_monthly_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('teacher_id')->constrained()->cascadeOnDelete();
            
            // Periode Evaluasi (Disimpan per tanggal 1 bulan tersebut)
            $table->date('month'); 

            // Statistik (Disimpan sebagai angka mati/snapshot)
            $table->integer('hadir_count')->default(0); // Mengajar kelas sendiri
            $table->integer('badal_count')->default(0); // Menggantikan orang lain
            $table->integer('izin_count')->default(0);
            $table->integer('alpha_count')->default(0);
            $table->integer('late_count')->default(0); // Berapa kali telat

            // Penilaian Kualitatif
            $table->text('notes')->nullable(); // Catatan Admin
            $table->boolean('is_locked')->default(false); // Status Tutup Buku

            $table->timestamps();

            // Mencegah duplikasi evaluasi untuk guru yang sama di bulan yang sama
            $table->unique(['teacher_id', 'month'], 'eval_tahfizh_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tahfizh_teacher_monthly_evaluations');
    }
};