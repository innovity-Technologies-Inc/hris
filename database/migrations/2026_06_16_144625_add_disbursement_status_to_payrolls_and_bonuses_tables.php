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
        Schema::table('payrolls', function (Blueprint $table) {
            $table->enum('disbursement_status', ['pending', 'paid'])->default('pending')->after('total_salary');
        });

        Schema::table('bonuses', function (Blueprint $table) {
            $table->enum('disbursement_status', ['pending', 'paid'])->default('pending')->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropColumn('disbursement_status');
        });

        Schema::table('bonuses', function (Blueprint $table) {
            $table->dropColumn('disbursement_status');
        });
    }
};
