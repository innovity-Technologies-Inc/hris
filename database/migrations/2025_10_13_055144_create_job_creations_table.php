<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
     public function up()
    {
        Schema::create('job_creations', function (Blueprint $table) {
            $table->id()->index();
            $table->unsignedBigInteger('designation_id');
            $table->unsignedBigInteger('department_id');
            $table->string('job_ind');
            $table->string('display_designation');
            $table->string('display_serial');
            $table->enum('status', ['active', 'inactive']);
            $table->text('remarks')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_creations');
    }
};

