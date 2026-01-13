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
        Schema::create('employee_promotions', function (Blueprint $table) {
            $table->id()->index();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('previous_designation');
            $table->unsignedBigInteger('new_designation');
            $table->enum('increment_base', ['basic_salary', 'gross_salary']);
            $table->enum('increment_method', ['fixed', 'percentage']);
            $table->decimal('increment_amount', 10, 2);
            $table->date('effective_from');
            $table->date('effective_to')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_promotions');
    }
};
