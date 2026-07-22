<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            GeneralSettingSeeder::class,
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
    }
}
