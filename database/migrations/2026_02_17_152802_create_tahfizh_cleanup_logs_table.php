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
        Schema::create('tahfizh_cleanup_logs', function (Blueprint $table) {
            $table->id();
            $table->string('cleanup_type'); // 'photos' atau 'logs'
            $table->integer('total_deleted');
            $table->string('period_threshold'); // '3 months', '1 year', dsb
            $table->foreignUlid('admin_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahfizh_cleanup_logs');
    }
};
