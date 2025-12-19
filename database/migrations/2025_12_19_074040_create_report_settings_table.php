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
        Schema::create('report_settings', function (Blueprint $table) {
            $table->ulid('id')->primary();

            // Kunci Unik: Tahun Ajaran + Jenjang
            $table->foreignUlid('academic_year_id')->constrained()->cascadeOnDelete();
            $table->foreignUlid('stage_id')->constrained()->cascadeOnDelete(); // SD, SMP, SMA

            // Data Setting
            $table->string('headmaster_name');
            $table->string('headmaster_nip')->nullable();
            $table->date('report_date'); // Tanggal Rapor
            $table->string('city')->default('Kota Santri'); // Tempat ttd (Misal: Jakarta, Surabaya)

            $table->timestamps();

            // Mencegah duplikasi (1 Jenjang hanya punya 1 setting di tahun yg sama)
            $table->unique(['academic_year_id', 'stage_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_settings');
    }
};
