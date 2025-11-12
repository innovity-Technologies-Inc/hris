<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtPlansTable extends Migration
{
    public function up(): void
    {
        Schema::create('ot_plans', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Basic Info
            $table->string('name')->index();
            $table->text('description')->nullable();

            // Overtime Type
            $table->enum('ot_type', ['regular', 'holiday', 'night_shift', 'weekend', 'other'])
                  ->default('regular');

            $table->enum('ot_config_type', ['salary_based', 'custom'])->default('salary_based');
            $table->enum('salary_rate_type', ['basic_rate', 'multiplier'])->default('multiplier')->nullable();
            $table->decimal('overtime_multiplier', 8, 2)->nullable();
            $table->decimal('custom_overtime_rate', 8, 2)->nullable();

            // Hours
            $table->decimal('minimum_overtime_hours', 6, 2)->default(0.00);
            $table->decimal('maximum_overtime_hours', 6, 2)->nullable();

            // Applicable Time Range
            $table->time('overtime_start_time')->nullable();
            $table->time('overtime_end_time')->nullable();



            // Status
            $table->enum('active_ind', ['active', 'inactive'])->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ot_plans');
    }
}
