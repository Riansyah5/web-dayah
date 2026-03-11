<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cbt_student_exams', function (Blueprint $table) {
            // Kolom untuk merekam detak jantung (heartbeat) aplikasi santri
            $table->timestamp('last_active_at')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('cbt_student_exams', function (Blueprint $table) {
            $table->dropColumn('last_active_at');
        });
    }
};