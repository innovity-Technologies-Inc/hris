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

            /**
             * Overtime Rate:
             * - type = 'multiplier' → 1.5x base rate
             * - type = 'per_hour' → $10/hour
             */
            $table->enum('overtime_rate_type', ['multiplier', 'per_hour'])->default('multiplier');
            $table->decimal('overtime_rate', 8, 2)->default(1.50);

            // Hours
            $table->decimal('minimum_overtime_hours', 6, 2)->default(0.00);
            $table->decimal('maximum_overtime_hours', 6, 2)->nullable();

            // Applicable Time Range
            $table->time('overtime_start_time')->nullable();
            $table->time('overtime_end_time')->nullable();

            // OT Limits
            $table->decimal('max_ot_limit', 8, 2)->nullable();
            $table->enum('max_ot_period', ['daily', 'weekly', 'monthly', 'yearly'])->nullable();

            // Notes
            $table->text('notes')->nullable();

            // Status
            $table->enum('active_ind', ['active', 'inactive'])->default('active');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ot_plans');
    }
}
