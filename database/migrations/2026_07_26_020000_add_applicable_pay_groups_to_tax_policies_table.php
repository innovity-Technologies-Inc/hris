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
        Schema::table('tax_policies', function (Blueprint $table) {
            $table->json('applicable_pay_groups')->nullable()->after('total_tax_month');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tax_policies', function (Blueprint $table) {
            $table->dropColumn('applicable_pay_groups');
        });
    }
};
