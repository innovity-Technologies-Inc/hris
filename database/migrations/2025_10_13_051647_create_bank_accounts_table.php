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
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id()->index();
            $table->unsignedBigInteger('bank_id')->index();
            $table->unsignedBigInteger('branch_id')->index();
            $table->string('account_no')->index();
            $table->string('holder_name')->index();
            $table->enum('account_type', ['current', 'savings', 'credit'])->index();
            $table->string('contact_person');
            $table->string('contact_person_no');
            $table->string('email');
            $table->enum('status', ['active', 'inactive']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};

