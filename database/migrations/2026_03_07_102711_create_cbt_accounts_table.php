<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cbt_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('student_id')->constrained()->cascadeOnDelete(); // Relasi ke tabel students utama
            $table->string('username')->unique(); // Misal: CBT-250199
            $table->string('password'); // Password yang sudah di-hash
            $table->string('raw_pin')->nullable(); // PIN asli (hanya untuk dicetak di kartu, bisa dikosongkan setelah ujian)
            $table->boolean('is_active')->default(true); // Fitur blokir jika belum bayar SPP
            $table->rememberToken(); // Untuk fitur "Remember Me"
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cbt_accounts');
    }
};