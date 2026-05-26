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
        Schema::table('employee_nominees', function (Blueprint $table) {
            $table->string('relation')->nullable()->after('nominee_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_nominees', function (Blueprint $table) {
            $table->dropColumn('relation');
        });
    }
};
