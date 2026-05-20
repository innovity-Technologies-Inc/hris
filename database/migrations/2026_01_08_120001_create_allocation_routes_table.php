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
        Schema::create('allocation_routes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('vehicle_allocation_id');

            // Route Details
            $table->string('route_name');
            $table->string('start_point');
            $table->string('end_point');
            $table->text('waypoints')->nullable(); // JSON array of intermediate stops
            $table->decimal('distance_km', 10, 2)->nullable();
            $table->integer('estimated_duration_minutes')->nullable();

            // Schedule
            $table->time('departure_time')->nullable();
            $table->time('arrival_time')->nullable();
            $table->date('route_date')->nullable();

            // Additional Details
            $table->text('route_description')->nullable();
            $table->text('special_instructions')->nullable();

            $table->enum('status', ['Active', 'Completed', 'Cancelled'])->default('Active');
            $table->timestamps();

            // Indexes
            $table->index('vehicle_allocation_id');
            $table->index('route_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allocation_routes');
    }
};

