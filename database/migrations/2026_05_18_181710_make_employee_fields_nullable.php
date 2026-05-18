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
            $table->string('first_name')->nullable()->change();
            $table->string('full_name')->nullable()->change();
            $table->string('father_name')->nullable()->change();
            $table->string('mother_name')->nullable()->change();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable()->change();
            $table->string('religion')->nullable()->change();
            $table->string('nationality')->nullable()->change();
            $table->json('present_address')->nullable()->change();
            $table->date('date_of_birth')->nullable()->change();
            $table->string('personal_mobile')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('first_name')->nullable(false)->change();
            $table->string('full_name')->nullable(false)->change();
            $table->string('father_name')->nullable(false)->change();
            $table->string('mother_name')->nullable(false)->change();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable(false)->change();
            $table->string('religion')->nullable(false)->change();
            $table->string('nationality')->nullable(false)->change();
            $table->json('present_address')->nullable(false)->change();
            $table->date('date_of_birth')->nullable(false)->change();
            $table->string('personal_mobile')->nullable(false)->change();
        });
    }
};
