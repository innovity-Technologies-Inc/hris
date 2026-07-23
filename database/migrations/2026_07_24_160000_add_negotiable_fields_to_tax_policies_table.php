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
            $table->decimal('min_negotiable_tax_limit', 15, 2)->default(0.00);
            $table->decimal('tax_payable_percentage', 5, 2)->default(100.00);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tax_policies', function (Blueprint $table) {
            $table->dropColumn(['min_negotiable_tax_limit', 'tax_payable_percentage']);
        });
    }
};
