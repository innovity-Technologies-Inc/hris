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
        Schema::create('pay_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('current_company_id')->nullable()->index();
            $table->string('title');
            $table->enum('payroll_frequency', ['Hourly', 'Monthly', 'Weekly']);
            $table->string('salary_processing_day'); // Stores day of month (1-31) or day of week (Monday...)
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pay_groups');
    }
};
