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
        Schema::create('demotions', function (Blueprint $table) {
            $table->id()->index();
            $table->unsignedBigInteger('employee_id');
            $table->foreignId('pay_scale_id')->nullable()->constrained('pay_scales');
            $table->unsignedBigInteger('previous_designation');
            $table->unsignedBigInteger('new_designation');
            $table->enum('decrement_base', ['basic_salary', 'gross_salary']);
            $table->enum('decrement_method', ['fixed', 'percentage']);
            $table->decimal('salary_decrease_amount', 10, 2);
            $table->decimal('decrement_amount_value', 10, 2);
            $table->decimal('previous_basic_salary', 10, 2);
            $table->decimal('previous_gross_salary', 10, 2);
            $table->decimal('new_gross_salary', 10, 2);
            $table->date('effective_from');
            $table->date('effective_to')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->tinyInteger('is_adjustment')->default(0)->comment('0-rejected, 1-Pending for adjustment, 2-Adjusted');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demotions');
    }
};
