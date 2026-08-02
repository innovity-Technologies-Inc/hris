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
        Schema::create('cv_banks', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('designation');
            $table->string('applicant_name');
            $table->string('career_level'); // Entry, Mid, Senior, Executive
            $table->integer('cv_score'); // 0-100 score
            $table->string('attachment_path')->nullable(); // CV PDF upload
            
            // Userstamps & Timestamps
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cv_banks');
    }
};
