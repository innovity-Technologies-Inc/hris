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
        Schema::create('employee_ot_plans', function (Blueprint $table) {
            $table->id()->index();
            $table->unsignedBigInteger('employee_id')->index();
            $table->unsignedBigInteger('plan_id')->index();
            $table->date('from');
            $table->date('to');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_ot_plans');
    }
};
