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
        Schema::create('leave_encashment_plans', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('encashment_basis', ['basic', 'gross'])->default('basic');
            $table->integer('min_balance_to_maintain')->default(0);
            $table->integer('max_encashable_days_per_year')->nullable();
            $table->decimal('encashment_rate', 5, 2)->default(1.00)->comment('1.00 means full day salary, 0.5 means half');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_encashment_plans');
    }
};
