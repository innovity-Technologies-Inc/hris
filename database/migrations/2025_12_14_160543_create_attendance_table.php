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
        Schema::create('attendance', function (Blueprint $table) {
            $table->id()->index();
            $table->bigInteger('employee_id');
            $table->dateTime('in_time')->nullable();
            $table->enum('in_status', ['On-Time', 'Late', 'Excessive-Late'])->nullable();
            $table->dateTime('out_time')->nullable();
            $table->enum('out_status', ['On-Time', 'Early-Exit'])->nullable();
            $table->enum('shift_type', ['Regular', 'Roster', 'Off-Day'])->nullable();
            $table->decimal('working_time', 8, 2)->nullable();
            $table->decimal('late_count', 8, 2)->nullable();
            $table->decimal('early_out_count', 8, 2)->nullable();
            $table->decimal('overtime', 8, 2)->nullable();
            $table->string('attendance_status')->nullable();
            $table->enum('workstation', ['Remote', 'On-Site', 'Work-From-Home'])->nullable();
            $table->unsignedBigInteger('shift_id')->nullable();
            $table->unsignedBigInteger('ot_id')->nullable();
            $table->unsignedBigInteger('offday_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};

