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
        Schema::table('employees', function (Blueprint $table) {
            $table->string('applicant_id')->nullable()->change();
            $table->string('system_id')->nullable()->change();
            $table->string('punch_card_no')->nullable()->change();
        });

        Schema::table('employee_salary_breakdowns', function (Blueprint $table) {
            $table->string('basic_salary')->nullable()->change();
            $table->string('basic_salary_percentage')->nullable()->change();
            $table->string('gross_salary')->nullable()->change();
        });

        Schema::table('employee_bank_accounts', function (Blueprint $table) {
            $table->unsignedBigInteger('bank_id')->nullable()->change();
            $table->string('account_holder_name')->nullable()->change();
            $table->string('account_number')->nullable()->change();
        });

        Schema::table('employee_nominees', function (Blueprint $table) {
            $table->string('nominee_name')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('applicant_id')->nullable(false)->change();
            $table->string('system_id')->nullable(false)->change();
            $table->string('punch_card_no')->nullable(false)->change();
        });

        Schema::table('employee_salary_breakdowns', function (Blueprint $table) {
            $table->string('basic_salary')->nullable(false)->change();
            $table->string('basic_salary_percentage')->nullable(false)->change();
            $table->string('gross_salary')->nullable(false)->change();
        });

        Schema::table('employee_bank_accounts', function (Blueprint $table) {
            $table->unsignedBigInteger('bank_id')->nullable(false)->change();
            $table->string('account_holder_name')->nullable(false)->change();
            $table->string('account_number')->nullable(false)->change();
        });

        Schema::table('employee_nominees', function (Blueprint $table) {
            $table->string('nominee_name')->nullable(false)->change();
        });
    }
};
