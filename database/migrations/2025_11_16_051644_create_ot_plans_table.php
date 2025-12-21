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
        Schema::create('ot_plans', function (Blueprint $table) {
            $table->id()->index();

            // Basic Info
            $table->string('name')->index();
            $table->text('description')->nullable();


            $table->enum('ot_config_type', ['Salary Based', 'Custom'])->default('Salary Based');
            $table->enum('salary_rate_type', ['Basic Rate', 'Multiplier'])->default('Basic Rate')->nullable();
            $table->decimal('overtime_multiplier', 8, 2)->nullable();
            $table->decimal('custom_overtime_rate', 8, 2)->nullable();

            // Hours
            $table->decimal('maximum_overtime_hours', 6, 2)->nullable();

            // Status
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ot_plans');
    }
};
