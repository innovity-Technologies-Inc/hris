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
        Schema::create('employee_transports', function (Blueprint $table) {
            $table->id();

            // Type (company hierarchy level)
            $table->enum('type', ['company', 'branch', 'division', 'department', 'section'])->default('company');

            // Company Hierarchy (Transport service for company employees)
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('division_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('section_id')->nullable();

            // Transport Service Details
            $table->string('service_name'); // e.g., "Morning Shuttle", "Evening Drop"
            $table->enum('transport_type', ['Daily Commute', 'Shuttle Service', 'Special Transport', 'Field Work']);
            $table->text('purpose');

            // Schedule
            $table->date('start_date');
            $table->date('end_date');
            $table->time('pickup_time')->nullable();
            $table->time('drop_time')->nullable();

            // Route Information
            $table->string('pickup_location')->nullable();
            $table->string('drop_location')->nullable();
            $table->text('route_details')->nullable();

            // Capacity & Assignment
            $table->integer('estimated_passengers')->nullable();
            $table->text('special_requirements')->nullable();
            $table->text('remarks')->nullable();

            // Status & Approval
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Cancelled'])->default('Pending');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_remarks')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('type');
            $table->index('company_id');
            $table->index('branch_id');
            $table->index('division_id');
            $table->index('department_id');
            $table->index('section_id');
            $table->index('status');
            $table->index('start_date');
            $table->index('end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_transports');
    }
};
