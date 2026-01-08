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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->enum('vehicle_category', ['Car', 'Bus', 'Micro Bus', 'Truck', 'Bike', 'Van', 'Airplane', 'Ship']);
            $table->string('model_number');
            $table->year('manufacture_year');
            $table->string('body_type')->nullable();
            $table->enum('fuel_type', ['Petrol', 'Diesel', 'CNG', 'Electric']);
            $table->string('engine_capacity')->nullable(); // CC
            $table->integer('seating_capacity')->nullable();
            $table->string('color')->nullable();
            $table->decimal('mileage', 8, 2)->nullable(); // KM/L
            $table->string('license_number')->nullable();
            $table->string('license_document')->nullable(); // file path
            $table->string('vehicle_image')->nullable(); // file path
            $table->enum('purchase_type', ['Purchase', 'Lease', 'Rent']);
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_price', 15, 2)->nullable();
            $table->string('purchase_document')->nullable(); // invoice/purchase document file path
            $table->enum('ownership_type', ['Company-owned', 'Third-party']);
            $table->string('third_party_name')->nullable();
            $table->boolean('is_allocated')->default(0); // 0 = not allocated, 1 = allocated
            $table->string('allocation_purpose')->nullable(); // purpose of allocation
            $table->enum('allocation_type', ['trip', 'transport'])->nullable(); // Type of allocation
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
