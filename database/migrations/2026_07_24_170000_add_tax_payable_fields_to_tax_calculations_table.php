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
        Schema::table('tax_calculations', function (Blueprint $table) {
            $table->decimal('tax_payable', 15, 2)->default(0.00);
            $table->decimal('tax_per_month', 15, 2)->default(0.00);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tax_calculations', function (Blueprint $table) {
            $table->dropColumn(['tax_payable', 'tax_per_month']);
        });
    }
};
