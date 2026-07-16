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
        Schema::table('employee_transports', function (Blueprint $table) {
            $table->unsignedBigInteger('route_map_id')->nullable()->after('purpose');
            $table->foreign('route_map_id')->references('id')->on('route_maps')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_transports', function (Blueprint $table) {
            $table->dropForeign(['route_map_id']);
            $table->dropColumn('route_map_id');
        });
    }
};
