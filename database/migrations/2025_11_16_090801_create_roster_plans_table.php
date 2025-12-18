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
        Schema::create('roster_plans', function (Blueprint $table) {
            $table->id()->index();
            $table->string('name');
            $table->string('short_name')->nullable();
            $table->string('swapping')->nullable();
            $table->longText('description')->nullable();
            $table->enum('status', ['active', 'inactive']);
            $table->unsignedBigInteger('first_shift_id');
            $table->unsignedBigInteger('second_shift_id');
            $table->unsignedBigInteger('third_shift_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roster_plans');
    }
};
