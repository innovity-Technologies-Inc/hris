<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For MySQL, we need to explicitly redeclare the ENUM with all values
        Schema::table('employees', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive', 'incomplete', 'pending'])
                ->default('active')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])
                ->default('active')
                ->change();
        });
    }
};

