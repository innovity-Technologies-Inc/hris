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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->enum('type', ['present', 'permanent', 'reference']);

            // Address Fields (Common for Present & Permanent)
            $table->string('address_line_1')->nullable();
            $table->string('village')->nullable();
            $table->string('post_office')->nullable();
            $table->string('thana')->nullable();
            $table->string('district')->nullable();
            $table->string('division')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('country')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();

            // Extra Fields for Reference Address
            $table->string('reference_emp_id')->nullable();
            $table->string('reference_name')->nullable();
            $table->string('reference_designation')->nullable();
            $table->string('reference_city')->nullable(); // City was in reference section
            $table->string('reference_email')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
