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
        Schema::table('employee_movements', function (Blueprint $table) {
            $table->dropColumn([
                'source_address',
                'source_lat',
                'source_lng',
                'destination_address',
                'dest_lat',
                'dest_lng',
                'reason'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_movements', function (Blueprint $table) {
            $table->string('source_address')->nullable();
            $table->decimal('source_lat', 10, 7)->nullable();
            $table->decimal('source_lng', 10, 7)->nullable();
            $table->string('destination_address')->nullable();
            $table->decimal('dest_lat', 10, 7)->nullable();
            $table->decimal('dest_lng', 10, 7)->nullable();
            $table->text('reason')->nullable();
        });
    }
};
