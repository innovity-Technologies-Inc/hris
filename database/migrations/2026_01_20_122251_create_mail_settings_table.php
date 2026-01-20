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
        Schema::create('mail_settings', function (Blueprint $table) {
            $table->id()->index();
            $table->string('app_name')->nullable();
            $table->string('mail_host')->nullable();
            $table->string('encryption_type')->nullable();
            $table->string('sender_email')->nullable();
            $table->string('password')->nullable();
            $table->integer('port')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mail_settings');
    }
};
