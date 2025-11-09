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
        Schema::create('shift_plans', function (Blueprint $table) {
            $table->id()->index();
            $table->string('shift_name');
            $table->time('clock_in_time');
            $table->time('clock_out_time');
            $table->integer('treat_as_full_day_minutes');
            $table->integer('treat_as_half_day_minutes');
            $table->time('grace_time')->nullable();
            $table->integer('late_after_minutes')->nullable();
            $table->integer('excessive_late_after_minutes')->nullable();
            $table->integer('early_out_grace_minutes')->default(5);
            $table->time('early_out_before')->nullable();

            //Breakfast fields
            $table->enum('breakfast_status', ['active', 'inactive'])->default('inactive');
            $table->time('breakfast_start_time')->nullable();
            $table->time('breakfast_end_time')->nullable();

            // Lunch fields
            $table->enum('lunch_status', ['active', 'inactive'])->default('inactive');
            $table->time('lunch_start_time')->nullable();
            $table->time('lunch_end_time')->nullable();

            // Snacks fields
            $table->enum('snacks_status', ['active', 'inactive'])->default('inactive');
            $table->time('snacks_start_time')->nullable();
            $table->time('snacks_end_time')->nullable();

            // Dinner fields
            $table->enum('dinner_status', ['active', 'inactive'])->default('inactive');
            $table->time('dinner_start_time')->nullable();
            $table->time('dinner_end_time')->nullable();

            $table->enum('active_ind', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_plans');
    }
};
