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
        Schema::table('leave_plans', function (Blueprint $table) {
            $table->string('off_day_include')->default('0')->change();
        });

        // Map existing 0 -> no, 1 -> yes
        \Illuminate\Support\Facades\DB::table('leave_plans')->where('off_day_include', '0')->update(['off_day_include' => 'no']);
        \Illuminate\Support\Facades\DB::table('leave_plans')->where('off_day_include', '1')->update(['off_day_include' => 'yes']);
        \Illuminate\Support\Facades\DB::table('leave_plans')->whereNotIn('off_day_include', ['no', 'yes'])->update(['off_day_include' => 'yes']);

        Schema::table('leave_plans', function (Blueprint $table) {
            $table->enum('off_day_include', ['yes', 'no'])->default('no')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_plans', function (Blueprint $table) {
            $table->string('off_day_include')->default('0')->change();
        });

        \Illuminate\Support\Facades\DB::table('leave_plans')->where('off_day_include', 'yes')->update(['off_day_include' => '1']);
        \Illuminate\Support\Facades\DB::table('leave_plans')->where('off_day_include', 'no')->update(['off_day_include' => '0']);

        Schema::table('leave_plans', function (Blueprint $table) {
            $table->integer('off_day_include')->default(0)->change();
        });
    }
};
