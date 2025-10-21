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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            // System Identifiers
            $table->string('applicant_id')->unique();
            $table->string('system_id')->unique();
            $table->string('punch_card_no')->unique();

            // Personal Information
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('spouse_name')->nullable();
            $table->enum('marital_status', ['Single', 'Married', 'Divorced', 'Widowed'])->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->string('religion');
            $table->string('nationality');
            $table->unsignedInteger('height_feet')->nullable();
            $table->unsignedInteger('height_inches')->nullable();
            $table->unsignedInteger('children_count')->default(0);

            $table->json('present_address');
            $table->json('permanent_address')->nullable();
            $table->json('reference_address')->nullable();



            // Document Information
            $table->string('tin')->nullable();
            $table->string('passport_no')->nullable();
            $table->date('passport_expiry')->nullable();
            $table->string('license_no')->nullable();
            $table->date('license_expiry')->nullable();
            $table->string('bgmea_id')->nullable();
            $table->date('visa_expiry')->nullable();
            $table->date('work_expiry')->nullable();
            $table->string('residency_id_number')->nullable();

            // Birth Information
            $table->date('date_of_birth');
            $table->string('birth_country')->nullable();
            $table->string('birth_reg_no')->nullable();

            // Contact Information
            $table->string('personal_mobile');
            $table->string('home_phone')->nullable();
            $table->string('work_mobile')->nullable();
            $table->string('work_phone')->nullable();
            $table->string('work_email')->nullable();
            $table->string('personal_email')->nullable();

            // File Upload Paths
            $table->string('photo_path')->nullable();
            $table->string('fingerprint_path')->nullable();
            $table->string('signature_path')->nullable();
            $table->string('experience_attachment_path')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
