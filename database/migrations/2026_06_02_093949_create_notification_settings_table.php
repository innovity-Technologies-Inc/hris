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
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('birthday_days')->default(0);
            $table->integer('visa_days')->default(0);
            $table->integer('work_permit_days')->default(0);
            $table->integer('passport_days')->default(0);
            $table->integer('license_days')->default(0);
            $table->integer('probation_days')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_settings');
    }
};
