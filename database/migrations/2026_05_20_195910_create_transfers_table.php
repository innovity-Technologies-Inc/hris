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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            
            // Current Office Info Snapshot
            $table->foreignId('current_company_id')->nullable()->constrained('companies');
            $table->foreignId('current_business_unit_id')->nullable()->constrained('company_locations');
            $table->foreignId('current_division_id')->nullable()->constrained('divisions');
            $table->foreignId('current_department_id')->nullable()->constrained('departments');
            $table->foreignId('current_section_id')->nullable()->constrained('sections');
            $table->foreignId('current_designation_id')->nullable()->constrained('designations');

            // Requested Office Info
            $table->foreignId('requested_company_id')->constrained('companies');
            $table->foreignId('requested_business_unit_id')->nullable()->constrained('company_locations');
            $table->foreignId('requested_division_id')->nullable()->constrained('divisions');
            $table->foreignId('requested_department_id')->nullable()->constrained('departments');
            $table->foreignId('requested_section_id')->nullable()->constrained('sections');
            $table->foreignId('requested_designation_id')->nullable()->constrained('designations');

            $table->enum('status', ['pending', 'approved', 'completed', 'rejected'])->default('pending');
            $table->integer('approval_count_required')->default(0);
            $table->integer('current_approval_count')->default(0);
            $table->text('remarks')->nullable();
            
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('completed_by')->nullable()->constrained('users');
            $table->timestamp('completed_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
