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
        Schema::create('tax_policies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            
            $table->decimal('zero_tax_male', 15, 2)->default(0.00);
            $table->decimal('zero_tax_female', 15, 2)->default(0.00);
            $table->decimal('min_tax_amount', 15, 2)->default(0.00);
            
            $table->enum('exemption_type', ['fixed', 'exempt_allowance'])->default('fixed');
            $table->string('salary_ratio')->nullable();
            $table->decimal('fixed_amount', 15, 2)->nullable();
            $table->json('exempt_allowances')->nullable(); // stored as JSON array of fields e.g. ["house_allowance"]

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('tax_slabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tax_policy_id');
            $table->decimal('taxable_amount', 15, 2)->default(0.00);
            $table->decimal('tax_percentage', 5, 2)->default(0.00);
            $table->decimal('tax_amount', 15, 2)->default(0.00);
            
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('tax_policy_id')->references('id')->on('tax_policies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_slabs');
        Schema::dropIfExists('tax_policies');
    }
};
