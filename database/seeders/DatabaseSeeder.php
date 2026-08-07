<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure the default organization exists
        DB::table('organizations')->updateOrInsert(
            ['id' => 1],
            [
                'name' => 'Default Organization',
                'slug' => 'default-org',
                'logo' => null,
                'email' => 'info@defaultorg.com',
                'phone' => '+880-123456789',
                'address' => 'Dhaka, Bangladesh',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        // 2. Call existing seeders
        $this->call([
            GeneralSettingSeeder::class,
            TaxPolicySeeder::class,
            OrganizationSeeder::class,
            PlanSeeder::class,
            EmployeeSeeder::class,
            OrganizationStructureSeeder::class,
            UserAndRoleSeeder::class,
            AttendanceSeeder::class,
            PenaltySeeder::class,
            ApprovalWorkflowSeeder::class,
            ProfileFieldConfigSeeder::class,
            TransportSeeder::class,
            EmployeeMovementSeeder::class,
        ]);

        // 3. Update all tables containing organization_id to point to the default organization (ID 1)
        $dbName = DB::connection()->getDatabaseName();
        $tablesQuery = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = ?", [$dbName]);
        $tables = array_map(function ($t) {
            return $t->table_name ?? $t->TABLE_NAME;
        }, $tablesQuery);

        foreach ($tables as $table) {
            if ($table !== 'organizations' && Schema::hasColumn($table, 'organization_id')) {
                DB::table($table)->whereNull('organization_id')->update(['organization_id' => 1]);
            }
        }

        // 4. Ensure Super Admin user (admin@example.com) has organization_id = null
        DB::table('users')->where('email', 'admin@example.com')->update(['organization_id' => null]);
    }
}
