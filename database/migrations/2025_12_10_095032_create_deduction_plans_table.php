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
        Schema::create('deduction_plans', function (Blueprint $table) {
            $table->id();
            $table->decimal('late_deduction', 8, 2)->default(0.00);
            $table->decimal('early_out_deduction', 8, 2)->default(0.00);
            $table->decimal('excessive_late_deduction', 8, 2)->default(0.00);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deduction_plans');
    }
};
