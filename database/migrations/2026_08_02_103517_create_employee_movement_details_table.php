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
        Schema::create('employee_movement_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_movement_id')->constrained('employee_movements')->onDelete('cascade');
            $table->string('source_address')->nullable();
            $table->decimal('source_lat', 10, 7)->nullable();
            $table->decimal('source_lng', 10, 7)->nullable();
            $table->string('destination_address')->nullable();
            $table->decimal('dest_lat', 10, 7)->nullable();
            $table->decimal('dest_lng', 10, 7)->nullable();
            $table->decimal('distance', 10, 2)->default(0);
            $table->text('reason')->nullable();
            $table->string('attachment_path')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::table('employee_movements', function (Blueprint $table) {
            $table->string('source_address')->nullable()->change();
            $table->string('destination_address')->nullable()->change();
            $table->unsignedBigInteger('ta_plan_id')->nullable()->change();
            $table->unsignedBigInteger('da_plan_id')->nullable()->change();
            $table->text('reason')->nullable()->change();
            
            $table->decimal('custom_ta', 10, 2)->nullable()->after('total_ta');
            $table->decimal('custom_da', 10, 2)->nullable()->after('total_da');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_movements', function (Blueprint $table) {
            $table->dropColumn(['custom_ta', 'custom_da']);
        });
        Schema::dropIfExists('employee_movement_details');
    }
};
