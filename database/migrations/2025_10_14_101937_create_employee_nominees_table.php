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
        Schema::create('employee_nominees', function (Blueprint $table) {
            $table->id()->index();
            $table->unsignedBigInteger('employee_id')->index();

            // Personal Details
            $table->string('nominee_name');
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('spouse_name')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('religion')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('nationality')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('photo_path')->nullable();

            // Identification
            $table->string('nid')->nullable();
            $table->string('birth_reg_no')->nullable();

            // Contact & Address
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('present_address_line')->nullable();
            $table->string('village')->nullable();
            $table->string('post_office')->nullable();
            $table->string('thana')->nullable();
            $table->string('district')->nullable();
            $table->string('state')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_nominees');
    }
};
