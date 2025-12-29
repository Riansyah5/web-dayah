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
        Schema::create('academic_calendars', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('academic_year_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->date('start_date'); // Judul: "Wisuda Angkatan 5"
            $table->date('end_date'); // Jika null, berarti acara 1 hari
            $table->string('hijri_date')->nullable(); // Tanggal Hijriyah dalam format string "5 Rajab 1445 H"
            // Kategori untuk pewarnaan di frontend
            // holiday (Merah), academic (Biru), islamic (Hijau), boarding (Kuning)
            $table->enum('category', ['holiday', 'academic', 'islamic', 'boarding'])->default('academic');
            $table->text('description')->nullable();
            $table->boolean('is_holiday')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_calendars');
    }
};
