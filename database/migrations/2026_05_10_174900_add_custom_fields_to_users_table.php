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
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_type')->nullable()->after('email');
            $table->unsignedBigInteger('employee_id')->nullable()->after('user_type');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('employee_id');
            
            // If we want to strictly enforce the types mentioned:
            // Group, Company, Business Unit, Division, Department, Section, employee
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['user_type', 'employee_id', 'status']);
        });
    }
};
