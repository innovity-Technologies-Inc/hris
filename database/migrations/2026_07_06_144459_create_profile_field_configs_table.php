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
        Schema::create('profile_field_configs', function (Blueprint $table) {
            $table->id();
            $table->string('section')->index();
            $table->string('field_name')->index();
            $table->string('label');
            $table->boolean('is_required')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_field_configs');
    }
};
