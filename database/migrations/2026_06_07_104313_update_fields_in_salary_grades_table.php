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
        Schema::table('salary_grades', function (Blueprint $table) {
            $table->renameColumn('name', 'grade_name');
            $table->string('grade_code')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salary_grades', function (Blueprint $table) {
            $table->renameColumn('grade_name', 'name');
            $table->dropColumn('grade_code');
        });
    }
};
