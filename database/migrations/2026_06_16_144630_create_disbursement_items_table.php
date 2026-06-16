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
        Schema::create('disbursement_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('disbursement_id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('record_id'); // ID of the Payroll or Bonus record
            $table->decimal('amount', 15, 2);
            $table->timestamps();

            $table->foreign('disbursement_id')->references('id')->on('disbursements')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disbursement_items');
    }
};
