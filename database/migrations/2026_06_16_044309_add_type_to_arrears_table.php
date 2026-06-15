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
        Schema::table('arrears', function (Blueprint $table) {
            $table->enum('type', ['Salary Adjustment', 'Overtime', 'Off Day Work', 'Bonus & Reward', 'others'])->default('others')->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('arrears', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
