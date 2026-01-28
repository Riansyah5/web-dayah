<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tahfizh_journals', function (Blueprint $table) {
            $table->string('latitude')->nullable()->after('note');
            $table->string('longitude')->nullable()->after('latitude');
        });
    }

    public function down()
    {
        Schema::table('tahfizh_journals', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
};