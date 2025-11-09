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
        Schema::create('leave_plans', function (Blueprint $table) {
            $table->id()->index();
            $table->string('leave_name');
            $table->string('short_name');

            // Enum fields
            $table->enum('applicable_gender', ['Both', 'Male', 'Female'])->default('Both');
            $table->enum('day_type', ['Calculative', 'Fixed'])->default('Calculative');
            $table->enum('leave_type', ['Casual Leave', 'Sick Leave', 'Maternal Leave', 'Paternal Leave', 'Earned Leave', 'Comp Off'])->default('Casual Leave');

            // Numerical fields
            $table->integer('leave_limit')->default(0);
            $table->integer('max_no_of_days')->default(0);
            $table->integer('display_serial')->default(0);

            // Toggle/Checkbox fields as enum
            $table->enum('apply_limit', ['active', 'inactive'])->default('inactive');
            $table->enum('allow_fractional_leave', ['active', 'inactive'])->default('inactive');

            // Off Day Include
            $table->enum('off_day_include', ['Excluding', 'In Between', 'Succeeding'])->default('Excluding');

            // Active indicator
            $table->enum('active_ind', ['active', 'inactive'])->default('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_plans');
    }
};
