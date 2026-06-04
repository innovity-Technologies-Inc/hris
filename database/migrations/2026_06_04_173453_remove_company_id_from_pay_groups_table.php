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
        Schema::table('pay_groups', function (Blueprint $table) {
            $table->dropColumn('current_company_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pay_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('current_company_id')->nullable()->index()->after('id');
        });
    }
};
