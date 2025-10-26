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
        Schema::create('employee_education_experience_training', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');

            $table->json('employee_educations')->nullable();
            $table->json('employee_experiences')->nullable();
            $table->json('employee_trainings')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_education_experience_training');
    }
};
