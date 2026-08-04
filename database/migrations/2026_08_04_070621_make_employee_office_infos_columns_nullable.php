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
        Schema::table('employee_office_infos', function (Blueprint $table) {
            $table->enum('orientation_required', ['yes', 'no'])->nullable()->default('no')->change();
            $table->enum('ot_allowed', ['yes', 'no'])->nullable()->default('no')->change();
            $table->enum('pf_eligible', ['yes', 'no'])->nullable()->default('no')->change();
            $table->enum('transport_eligible', ['yes', 'no'])->nullable()->default('no')->change();
            $table->enum('can_apply_loan', ['yes', 'no'])->nullable()->default('no')->change();
            $table->enum('can_apply_advance', ['yes', 'no'])->nullable()->default('no')->change();
            $table->enum('gratuity_eligible', ['yes', 'no'])->nullable()->default('no')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_office_infos', function (Blueprint $table) {
            $table->enum('orientation_required', ['yes', 'no'])->nullable(false)->default('no')->change();
            $table->enum('ot_allowed', ['yes', 'no'])->nullable(false)->default('no')->change();
            $table->enum('pf_eligible', ['yes', 'no'])->nullable(false)->default('no')->change();
            $table->enum('transport_eligible', ['yes', 'no'])->nullable(false)->default('no')->change();
            $table->enum('can_apply_loan', ['yes', 'no'])->nullable(false)->default('no')->change();
            $table->enum('can_apply_advance', ['yes', 'no'])->nullable(false)->default('no')->change();
            $table->enum('gratuity_eligible', ['yes', 'no'])->nullable(false)->default('no')->change();
        });
    }
};
