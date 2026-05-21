<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transfer_settings', function (Blueprint $table) {
            $table->id();
            // Levels: company, business_unit, division, department, section
            $table->string('employee_transfer_level')->default('company');
            $table->string('supervisor_transfer_level')->default('company');
            $table->timestamps();
        });

        // Insert default record
        DB::table('transfer_settings')->insert([
            'employee_transfer_level' => 'company',
            'supervisor_transfer_level' => 'company',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('transfer_settings');
    }
};
