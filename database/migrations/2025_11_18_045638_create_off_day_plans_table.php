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
        Schema::create('off_day_plans', function (Blueprint $table) {
            $table->id()->index();
            $table->string('name')->index();
            $table->string('short_name')->nullable();

            $table->unsignedBigInteger('shift_id')->nullable();

            // Configuration fields
            $table->enum('offday_config_type', ['Salary Based', 'Custom'])->default('Custom');
            $table->enum('salary_rate_type', ['Basic Rate', 'Multiplier'])->nullable();
            $table->decimal('offday_multiplier', 8, 2)->nullable();
            $table->decimal('custom_offday_rate', 10, 2)->nullable();

            $table->enum('status', ['active', 'inactive']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('off_day_plans');
    }
};

