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
            $table->dropColumn('tofsil_id');
        });

        Schema::table('employee_office_infos', function (Blueprint $table) {
            $table->dropColumn(['tofsil_id', 'salary_type']);
        });

        Schema::dropIfExists('tofsils');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('tofsils', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive']);
            $table->timestamps();
        });

        Schema::table('salary_grades', function (Blueprint $table) {
            $table->unsignedBigInteger('tofsil_id')->nullable()->after('grade_name');
        });

        Schema::table('employee_office_infos', function (Blueprint $table) {
            $table->unsignedBigInteger('tofsil_id')->nullable()->after('hr_file_no');
            $table->string('salary_type')->nullable()->after('pf_eligible');
        });
    }
};
