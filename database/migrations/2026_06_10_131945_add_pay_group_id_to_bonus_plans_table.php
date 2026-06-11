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
        Schema::table('bonus_plans', function (Blueprint $table) {
            $table->unsignedBigInteger('pay_group_id')->nullable()->after('id');
            $table->foreign('pay_group_id')->references('id')->on('pay_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bonus_plans', function (Blueprint $table) {
            $table->dropForeign(['pay_group_id']);
            $table->dropColumn('pay_group_id');
        });
    }
};
