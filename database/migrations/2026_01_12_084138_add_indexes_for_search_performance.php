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
        // Add indexes to employee_office_infos for faster joins
        Schema::table('employee_office_infos', function (Blueprint $table) {
            $table->index('employee_id', 'idx_empoffice_employee_id');
            $table->index('current_company_id', 'idx_empoffice_company');
            $table->index('current_business_unit_id', 'idx_empoffice_branch');
            $table->index('current_division_id', 'idx_empoffice_division');
            $table->index('current_department_id', 'idx_empoffice_department');
            $table->index('current_section_id', 'idx_empoffice_section');
        });

        // Add indexes to employees table for search fields
        Schema::table('employees', function (Blueprint $table) {
            $table->index('system_id', 'idx_emp_system_id');
            $table->index('applicant_id', 'idx_emp_applicant_id');
            $table->index('full_name', 'idx_emp_full_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_office_infos', function (Blueprint $table) {
            $table->dropIndex('idx_empoffice_employee_id');
            $table->dropIndex('idx_empoffice_company');
            $table->dropIndex('idx_empoffice_branch');
            $table->dropIndex('idx_empoffice_division');
            $table->dropIndex('idx_empoffice_department');
            $table->dropIndex('idx_empoffice_section');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropIndex('idx_emp_system_id');
            $table->dropIndex('idx_emp_applicant_id');
            $table->dropIndex('idx_emp_full_name');
        });
    }
};
