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
        Schema::table('leaves', function (Blueprint $table) {
            $table->enum('leave_category_type', ['standard', 'compensatory'])->default('standard')->after('employee_id');
            DB::statement("ALTER TABLE leaves MODIFY COLUMN plan_id BIGINT UNSIGNED NULL");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropColumn('leave_category_type');
            DB::statement("ALTER TABLE leaves MODIFY COLUMN plan_id BIGINT UNSIGNED NOT NULL");
        });
    }
};
