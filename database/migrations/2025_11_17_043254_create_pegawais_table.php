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
        Schema::create('pegawais', function (Blueprint $table) {
            // Id: Kunci utama (primary key) auto-increment
            $table->ulid('id')->primary();
            $table->foreignUlid('user_id')->nullable()->constrained('users')->onDelete('set null');

            // Nik: string 16 karakter, dan unik (tidak boleh ada yang sama)
            $table->string('nik', 16)->unique();

            // Nama: string
            $table->string('nama');

            // Jenis_kelamin: Pilihan 'Laki-laki' atau 'Perempuan'
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);

            // Tempat_lahir: string
            $table->string('tempat_lahir');

            // Tanggal_lahir: tipe data tanggal
            $table->date('tanggal_lahir');

            // Status_perkawinan: string
            $table->string('status_perkawinan');

            // No_KK: string 16 karakter, bisa null (opsional)
            $table->string('no_kk', 16)->nullable();
            // No_HP: string, bisa null (opsional)
            $table->string('no_hp')->nullable();

            // Kolom Alamat (Desa, Kecamatan, Kabupaten, Provinsi)
            // Dibuat nullable() artinya boleh kosong
            $table->string('desa')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('provinsi')->nullable();

            // Status_pegawai: string (mis: "Tetap", "Kontrak")
            $table->string('status_pegawai');

            // Jabatan: string
            $table->string('jabatan');

            // Terhitung_mulai_tanggal: tipe data tanggal
            $table->date('terhitung_mulai_tanggal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};
