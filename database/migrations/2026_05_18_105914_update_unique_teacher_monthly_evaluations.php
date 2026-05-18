<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teacher_monthly_evaluations', function (Blueprint $table) {

            // buat index biasa untuk teacher_id
            $table->index('teacher_id', 'tme_teacher_id_index');
        });

        Schema::table('teacher_monthly_evaluations', function (Blueprint $table) {

            // hapus unique lama
            $table->dropUnique('monthly_eval_unique');

            // buat unique baru
            $table->unique(
                ['teacher_id', 'month', 'year', 'level'],
                'monthly_eval_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('teacher_monthly_evaluations', function (Blueprint $table) {

            $table->dropUnique('monthly_eval_unique');

            $table->unique(
                ['teacher_id', 'month', 'year'],
                'monthly_eval_unique'
            );

            $table->dropIndex('tme_teacher_id_index');
        });
    }
};