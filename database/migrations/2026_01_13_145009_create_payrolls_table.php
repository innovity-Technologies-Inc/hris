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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->longText('batch_id')->unique();
            $table->unsignedBigInteger('process_id');
            $table->decimal('salary', 15, 2);
            $table->decimal('deduction_amount', 15, 2);
            $table->integer('leaves_count');
            $table->integer('offday_work_count');
            $table->integer('absent_count');
            $table->json('absent_dates')->nullable();
            $table->integer('late_count');
            $table->integer('excessive_late_count');
            $table->integer('early_exit_count');
            $table->integer('overtime_count');
            $table->decimal('overtime_amount', 15, 2);
            $table->decimal('offday_work_salary', 15, 2);
            $table->decimal('bonus_amount', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
