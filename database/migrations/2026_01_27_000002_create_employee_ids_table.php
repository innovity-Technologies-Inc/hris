<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Creates employee_ids table to store generated employee ID cards.
     * Each employee can have one ID card record linked to a specific design.
     */
    public function up(): void
    {
        Schema::create('employee_ids', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->index();
            $table->unsignedBigInteger('id_card_design_id')->index();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('pdf_path')->nullable(); // Path to generated PDF file
            $table->string('card_number')->nullable(); // Optional unique card identifier
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamps();

            // Indexes for faster queries
            $table->index('status');
            $table->index(['employee_id', 'status']);

            // Unique constraint: one active ID card per employee
            $table->unique(['employee_id', 'status'], 'employee_active_id_card_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_ids');
    }
};
