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
        Schema::create('employee_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');

            $table->datetime('from_date');
            $table->datetime('to_date');

            $table->string('source_address');
            $table->decimal('source_lat', 10, 7)->nullable();
            $table->decimal('source_lng', 10, 7)->nullable();

            $table->string('destination_address');
            $table->decimal('dest_lat', 10, 7)->nullable();
            $table->decimal('dest_lng', 10, 7)->nullable();

            $table->decimal('distance', 10, 2)->default(0); // km

            $table->unsignedBigInteger('ta_plan_id');
            $table->unsignedBigInteger('da_plan_id');

            $table->decimal('total_ta', 10, 2)->default(0);
            $table->decimal('total_da', 10, 2)->default(0);
            $table->integer('total_days')->default(1);
            $table->decimal('total_allowance', 10, 2)->default(0);

            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_movements');
    }
};
