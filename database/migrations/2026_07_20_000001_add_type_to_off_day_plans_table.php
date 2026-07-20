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
        Schema::table('off_day_plans', function (Blueprint $table) {
            $table->enum('type', ['Paid', 'comp-off'])->default('Paid')->after('short_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('off_day_plans', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
