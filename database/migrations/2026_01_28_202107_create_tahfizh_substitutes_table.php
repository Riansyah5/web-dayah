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
        Schema::create('tahfizh_substitutes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahfizh_halaqah_id')->constrained();
            $table->foreignId('tahfizh_schedule_id')->constrained();
            $table->foreignUlid('original_teacher_id')->constrained('teachers');
            $table->foreignUlid('substitute_teacher_id')->constrained('teachers'); // Guru Badal
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahfizh_substitutes');
    }
};
