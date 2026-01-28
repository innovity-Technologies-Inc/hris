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
        Schema::create('id_card_designs', function (Blueprint $table) {
            $table->id();
            $table->string('theme_name')->unique();
            $table->string('file_path'); // Path to uploaded blade file
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->text('description')->nullable();
            $table->string('preview_front_card')->nullable(); // Front card preview
            $table->string('preview_back_card')->nullable(); // Back card preview
            $table->timestamps();

            // Index for faster status queries
            $table->index('status');
        });

        // Add unique constraint to ensure only one active design
        // This is handled at application level for better control
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('id_card_designs');
    }
};
