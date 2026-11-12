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
        Schema::table('registrations', function (Blueprint $table) {
            $table->string('payment_receipt')->nullable()->after('selection_score');
            $table->string('payment_sender_name')->nullable()->after('payment_receipt');
            $table->string('payment_bank_name')->nullable()->after('payment_sender_name');
            $table->unsignedBigInteger('payment_amount')->nullable()->after('payment_bank_name');
            $table->date('payment_date')->nullable()->after('payment_amount');
            $table->enum('payment_status', ['unverified', 'verified', 'rejected'])->default('unverified')->after('payment_date');
            $table->text('payment_notes')->nullable()->after('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn([
                'payment_receipt',
                'payment_sender_name',
                'payment_bank_name',
                'payment_amount',
                'payment_date',
                'payment_status',
                'payment_notes',
            ]);
        });
    }
};
