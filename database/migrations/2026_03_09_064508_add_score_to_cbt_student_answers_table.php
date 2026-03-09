<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cbt_student_answers', function (Blueprint $table) {
            // Tambahkan kolom score untuk menyimpan nilai mutlak tiap soal (terutama essay)
            $table->decimal('score', 5, 2)->nullable()->after('is_correct');
        });
    }

    public function down()
    {
        Schema::table('cbt_student_answers', function (Blueprint $table) {
            $table->dropColumn('score');
        });
    }
};