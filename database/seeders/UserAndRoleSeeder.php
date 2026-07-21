<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserAndRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure Permissions and Basic Roles exist first
        $this->call(PermissionSeeder::class);

        // 2. Clear users and roles associations
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 3. Load users from JSON dump
        $usersPath = database_path('seeders/data/users.json');
        if (file_exists($usersPath)) {
            $users = json_decode(file_get_contents($usersPath), true);
            $chunks = array_chunk($users, 100);
            foreach ($chunks as $chunk) {
                DB::table('users')->insert($chunk);
            }
        }

        // 4. Load roles associations from JSON dump
        $modelHasRolesPath = database_path('seeders/data/model_has_roles.json');
        if (file_exists($modelHasRolesPath)) {
            $associations = json_decode(file_get_contents($modelHasRolesPath), true);
            $chunks = array_chunk($associations, 100);
            foreach ($chunks as $chunk) {
                DB::table('model_has_roles')->insert($chunk);
            }
        }

        // 5. Ensure System Administrator always has Super Admin role
        $superAdminRole = \Spatie\Permission\Models\Role::where('name', 'Super Admin')->first();
        $adminUser = \App\Models\User::where('email', 'admin@example.com')->first();
        if ($adminUser && $superAdminRole) {
            $adminUser->assignRole($superAdminRole);
        }

        $this->command->info('Provisioned users and assigned roles from exported database json dumps.');
    }
}
