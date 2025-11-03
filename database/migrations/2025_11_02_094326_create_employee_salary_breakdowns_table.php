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
        Schema::create('employee_salary_breakdowns', function (Blueprint $table) {
            $table->id()->index();
            $table->unsignedBigInteger('employee_id');
            $table->string('basic_salary');
            $table->string('house_allowance')->nullable();
            $table->string('transport_allowance')->nullable();
            $table->string('food_allowance')->nullable();
            $table->string('medical_allowance')->nullable();
            $table->string('other_earnings')->nullable();
            $table->string('gross_salary');
            $table->string('currency')->default('BDT');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_salary_breakdowns');
    }
};
