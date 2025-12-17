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
        Schema::create('attandance', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id');
            $table->date('date');
            $table->datetime('in_time');
            $table->string('in_status');
            $table->datetime('out_time');
            $table->string('out_status');
            $table->decimal('working_hours', 3, 2)->nullable();
            $table->decimal('late_count', 3, 2)->nullable();
            $table->decimal('early_out_count', 3, 2)->nullable();
            $table->decimal('overtime_hours', 3, 2)->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attandance');
    }
};
