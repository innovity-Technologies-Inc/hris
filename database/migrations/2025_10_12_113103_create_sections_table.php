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
        Schema::create('sections', function (Blueprint $table) {
            $table->id()->index();
            $table->string('name');
            $table->string('short_name');
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('division_id');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('company_id');
            $table->enum('status', ['active', 'inactive']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
