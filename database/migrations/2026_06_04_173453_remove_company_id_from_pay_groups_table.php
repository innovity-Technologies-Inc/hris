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
        Schema::table('pay_groups', function (Blueprint $table) {
            if (Schema::hasColumn('pay_groups', 'current_company_id')) {
                // To avoid sqlite dropping index error, we can try to drop index if it exists
                // But better yet, we just check if it's sqlite and ignore index dropping? No, laravel handles it if we do dropIndex.
                // Actually in sqlite, dropping a column that is indexed requires dropIndex first since laravel 8+.
                // Let's just drop index.
                $table->dropIndex(['current_company_id']);
                $table->dropColumn('current_company_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pay_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('current_company_id')->nullable()->index()->after('id');
        });
    }
};
