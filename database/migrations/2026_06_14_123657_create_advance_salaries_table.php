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
        Schema::create('advance_salaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('process_id');
            $table->unsignedBigInteger('employee_id');
            $table->string('batch_id');
            $table->decimal('amount', 15, 2);
            $table->string('deduction_month'); // Y-m
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'deducted', 'rejected'])->default('pending');
            $table->timestamps();

            $table->foreign('process_id')->references('id')->on('payroll_process')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advance_salaries');
    }
};
