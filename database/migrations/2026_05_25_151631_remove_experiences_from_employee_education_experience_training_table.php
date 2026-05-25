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
        Schema::table('employee_education_experience_training', function (Blueprint $table) {
            if (Schema::hasColumn('employee_education_experience_training', 'experiences')) {
                $table->dropColumn('experiences');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_education_experience_training', function (Blueprint $table) {
            $table->json('experiences')->nullable();
        });
    }
};
