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
        Schema::create('employee_comp_off_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('leave_id')->nullable()->constrained('leaves')->onDelete('set null');
            $table->enum('type', ['earned', 'used']);
            $table->decimal('days', 8, 2);
            $table->decimal('previous_balance', 8, 2)->default(0.00);
            $table->decimal('new_balance', 8, 2)->default(0.00);
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_comp_off_histories');
    }
};
