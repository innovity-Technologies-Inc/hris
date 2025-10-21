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
        Schema::create('employee_networks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');

            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->date('birth_date')->nullable();
            $table->string('relationship');
            $table->string('personal_mobile')->nullable();
            $table->string('personal_email')->nullable();
            $table->boolean('is_dependant')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_networks');
    }
};
