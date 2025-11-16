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
            $table->string('name')->index();
            $table->string('short_name');

            // Enum fields
            $table->enum('applicable_gender', ['Both', 'Male', 'Female'])->default('Both');
            $table->enum('day_type', ['Calculative', 'Fixed'])->default('Calculative');
            $table->string('leave_type');

            // Numerical fields
            $table->integer('leave_limit')->default(0);
            $table->integer('max_no_of_days')->default(0);
            $table->integer('display_serial')->default(0);

            // Toggle/Checkbox fields as enum
            $table->integer('apply_limit')->default(0);
            $table->enum('allow_fractional_leave', ['active', 'inactive'])->default('inactive');

            // Off Day Include
            $table->integer('off_day_include')->default(0);

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
