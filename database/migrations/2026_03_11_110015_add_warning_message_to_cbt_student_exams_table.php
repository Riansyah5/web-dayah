<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cbt_student_exams', function (Blueprint $table) {
            // Kolom untuk menampung pesan teguran dari pengawas
            $table->text('warning_message')->nullable()->after('last_active_at');
        });
    }

    public function down()
    {
        Schema::table('cbt_student_exams', function (Blueprint $table) {
            $table->dropColumn('warning_message');
        });
    }
};