<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserAndRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure Permissions and Basic Roles exist
        $this->call(PermissionSeeder::class);

        $superAdminRole = Role::where('name', 'Super Admin')->first();
        $hrManagerRole = Role::firstOrCreate(['name' => 'HR Manager', 'guard_name' => 'web']);
        $deptManagerRole = Role::firstOrCreate(['name' => 'Department Manager', 'guard_name' => 'web']);
        $employeeRole = Role::firstOrCreate(['name' => 'Employee', 'guard_name' => 'web']);

        // 2. Create/Update the "Group" Super Admin User
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('12345678'),
                'user_type' => 'Group',
                'status' => 'active',
            ]
        );
        $adminUser->assignRole($superAdminRole);

        $this->command->info('Super Admin user created with user_type: Group');

        $defaultPassword = Hash::make('12345678');

        // 3. Provision Login for All Employees
        // Using chunking for performance with ~2000 records
        Employee::with('officeInfo.getCurrentDepartment')->chunk(200, function ($employees) use ($hrManagerRole, $deptManagerRole, $employeeRole, $defaultPassword) {
            foreach ($employees as $employee) {
                $email = $employee->work_email ?? 'employee' . $employee->id . '@example.com';
                
                // Determine Role based on Department or other logic
                $targetRole = $employeeRole;
                $userType = 'Employee';

                $deptName = $employee->officeInfo?->getCurrentDepartment?->department_name ?? '';
                
                if (stripos($deptName, 'HR') !== false || stripos($deptName, 'Human Resource') !== false || stripos($deptName, 'Payroll') !== false) {
                    $targetRole = $hrManagerRole;
                    $userType = 'Department';
                } elseif (stripos($deptName, 'Manager') !== false || stripos($deptName, 'Head') !== false) {
                    $targetRole = $deptManagerRole;
                    $userType = 'Department';
                }

                $user = User::updateOrCreate(
                    ['email' => $email],
                    [
                        'name' => $employee->full_name,
                        'password' => $defaultPassword,
                        'user_type' => $userType,
                        'employee_id' => $employee->id,
                        'status' => 'active',
                    ]
                );

                $user->syncRoles([$targetRole->name]);

                // Link employee to user
                if ($employee->user_id !== $user->id) {
                    $employee->update(['user_id' => $user->id]);
                }
            }
        });

        $this->command->info('Provisioned employees with login info and roles.');
    }
}
