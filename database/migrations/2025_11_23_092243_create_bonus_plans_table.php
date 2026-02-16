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
        Schema::create('bonus_plans', function (Blueprint $table) {
            $table->id();
            // Basic Info
            $table->string('name')->index();
            $table->text('description')->nullable();

            // Overtime Type
            $table->enum('bonus_type', ['festival', 'performance', 'annual', 'incentive', 'retention', 'other'])
                ->default('festival');

            $table->enum('bonus_config_type', ['Salary Based', 'Custom'])->default('Salary Based');
            $table->enum('salary_rate_type', ['Basic Rate', 'Multiplier'])->default('Basic Rate')->nullable();
            $table->decimal('multiplier', 8, 2)->nullable();
            $table->decimal('custom_rate', 8, 2)->nullable();

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
        Schema::dropIfExists('bonus_plans');
    }
};
