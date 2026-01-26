<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    // Mengubah kolom ENUM untuk menambahkan opsi 'late'
    // Catatan: Syntax ini tergantung database driver (MySQL/PostgreSQL)
    // Ini contoh aman menggunakan Raw Query untuk MySQL
    DB::statement("ALTER TABLE student_lesson_attendances MODIFY COLUMN status ENUM('present', 'sick', 'permission', 'alpha', 'late') DEFAULT 'present'");
}

public function down()
{
    // Kembalikan ke semula (Opsional)
    DB::statement("ALTER TABLE student_lesson_attendances MODIFY COLUMN status ENUM('present', 'sick', 'permission', 'alpha') DEFAULT 'present'");
}
};
