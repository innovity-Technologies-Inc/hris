<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vehicle_requisitions', function (Blueprint $table) {
            $table->id();

            // Basic Details
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->unsignedBigInteger('department')->nullable();

            // Trip Details
            $table->enum('trip_type', ['Official', 'Personal', 'Visitor']);
            $table->enum('trip_mode', ['One-way', 'Round-trip', 'Multi-stop']);
            $table->text('purpose_of_travel');

            // Schedule
            $table->dateTime('start_date_time');
            $table->dateTime('end_date_time');

            // Locations
            $table->string('pickup_location');
            $table->string('destination');
            $table->string('route')->nullable();

            // Vehicle & Passengers
            $table->unsignedInteger('no_of_passengers');
            $table->enum('vehicle_type_required', ['Car', 'Bus', 'Micro']);

            // Driver / Self Drive
            $table->boolean('driver_required')->default(true);
            $table->boolean('self_drive')->default(false);

            // Preferences
            $table->string('special_requirement')->nullable();
            $table->string('preferred_vehicle')->nullable();

            // Approval
            $table->enum('approval_status', ['Pending', 'Approved', 'Rejected'])
                  ->default('Pending');
            $table->text('approval_remarks')->nullable();

            // Assignment
            $table->unsignedBigInteger('assigned_vehicle_id')->nullable();
            $table->time('dispatch_time')->nullable();
            $table->time('expected_return_time')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicle_requisitions');
    }
};

