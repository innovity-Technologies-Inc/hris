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
        if (!Schema::hasColumn('employee_education_experience_training', 'status')) {
            Schema::table('employee_education_experience_training', function (Blueprint $table) {
                $table->enum('status', ['active', 'inactive', 'incomplete', 'pending'])->default('incomplete')->after('trainings');
            });
        }

        if (!Schema::hasColumn('employee_nominees', 'status')) {
            Schema::table('employee_nominees', function (Blueprint $table) {
                $table->enum('status', ['active', 'inactive', 'incomplete', 'pending'])->default('incomplete')->after('country');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_education_experience_training', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('employee_nominees', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
