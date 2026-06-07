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
        Schema::create('employee_penalties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('penalty_plan_id')->constrained('penalty_plans')->cascadeOnDelete();
            $table->date('occurrence_date');
            $table->text('cause')->nullable();
            $table->decimal('penalty_amount', 15, 2)->default(0);
            $table->enum('status', ['pending', 'approved', 'deducted'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_penalties');
    }
};
