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
        Schema::create('vehicle_allocations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_id');
            $table->string('name'); // Allocation name/identifier
            $table->enum('allocation_type', ['employee_transport', 'trip_based'])->nullable();
            $table->string('allocation_purpose')->nullable();
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('allocated_to')->nullable(); // Employee name or department

            // Reference to source application (vehicle_requisition or employee_transport)
            $table->string('reference_type')->nullable(); // 'vehicle_requisition' or 'employee_transport'
            $table->unsignedBigInteger('reference_id')->nullable();

            $table->enum('status', ['Active', 'Inactive', 'Completed'])->default('Active');

            // Approval
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_remarks')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('vehicle_id');
            $table->index(['reference_type', 'reference_id']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_allocations');
    }
};

