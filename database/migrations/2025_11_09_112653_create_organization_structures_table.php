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
    Schema::create('organization_structure', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->string('name', 150);

        // Member type: Board Member or Key Member
        $table->enum('member_type', ['Board Member', 'Key Member'])->default('Board Member');

        // Unified type for both entities
        $table->enum('type', [
            'Group',
            'Company',
            'Branch Unit',
            'Division',
            'Department',
            'Section'
        ]);

        // Related foreign keys
        $table->unsignedBigInteger('group_id')->nullable();
        $table->unsignedBigInteger('company_id')->nullable();
        $table->unsignedBigInteger('branch_unit_id')->nullable();
        $table->unsignedBigInteger('division_id')->nullable();
        $table->unsignedBigInteger('department_id')->nullable();
        $table->unsignedBigInteger('section_id')->nullable();
        $table->unsignedBigInteger('employee_id')->nullable();

        // Shared information
        $table->string('position', 100)->nullable();
        $table->string('contact_no', 20)->nullable();
        $table->string('email', 150)->nullable();
        $table->text('address')->nullable();
        $table->string('photo_path', 255)->nullable();
        $table->enum('status', ['Active', 'Inactive'])->default('Active');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_structures');
    }
};
