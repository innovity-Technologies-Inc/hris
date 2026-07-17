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
        Schema::table('leaves', function (Blueprint $table) {
            $table->decimal('leave_count', 8, 2)->change();
        });

        Schema::table('leaves_count', function (Blueprint $table) {
            $table->decimal('leave_taken', 8, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->integer('leave_count')->change();
        });

        Schema::table('leaves_count', function (Blueprint $table) {
            $table->integer('leave_taken')->change();
        });
    }
};
