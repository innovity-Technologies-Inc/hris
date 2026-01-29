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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id()->index();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('previous_designation');
            $table->unsignedBigInteger('new_designation');
            $table->enum('increment_base', ['basic_salary', 'gross_salary']);
            $table->enum('increment_method', ['fixed', 'percentage']);
            $table->decimal('salary_increase_amount', 10, 2);
            $table->decimal('increment_amount_value', 10, 2);
            $table->decimal('previous_basic_salary', 10, 2);
            $table->decimal('previous_gross_salary', 10, 2);
            $table->decimal('new_gross_salary', 10, 2);
            $table->date('effective_from');
            $table->date('effective_to')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->tinyInteger('is_adjustment')->default(0)->comment('0-rejected, 1-Pending for adjustment, 2-Adjusted');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
