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
            $table->integer('late_deduction_days')->default(0);
            $table->decimal('late_salary_deduction_rate', 8, 2)->default(0.00);
            $table->integer('early_out_deduction_days')->default(0);
            $table->decimal('early_out_salary_deduction_rate', 8, 2)->default(0.00);
            $table->integer('excessive_late_deduction_days')->default(0);
            $table->decimal('excessive_late_salary_deduction_rate', 8, 2)->default(0.00);
            $table->enum('calculation_type', ['gross_salary', 'basic_salary'])->default('gross_salary');
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
