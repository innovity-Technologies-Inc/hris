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
        Schema::create('companies', function (Blueprint $table) {
            $table->id()->index();
            $table->string('name');
            $table->string('short_name');
            $table->tinyInteger('type_id');
            $table->tinyInteger('group_id');
            $table->longText('address');
            $table->string('fax')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->string('logo')->nullable();
            $table->enum('status', ['active', 'inactive']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
