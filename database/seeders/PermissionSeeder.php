<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Clear existing
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $menus = [
            [
                'name' => 'Dashboard',
                'icon' => 'home',
                'route' => 'dashboard',
                'submenus' => []
            ],
            [
                'name' => 'Employees',
                'icon' => 'users',
                'submenus' => [
                    ['name' => 'Employee Information', 'route' => 'employees.index'],
                    ['name' => 'Search Employee', 'route' => 'search.employee'],
                    ['name' => 'Bulk Upload', 'route' => 'employees.import'],
                ]
            ],
            [
                'name' => 'Attendance',
                'icon' => 'clock',
                'submenus' => [
                    ['name' => 'Clock In / Out', 'route' => 'attendance.clock_in_out'],
                    ['name' => 'Create Attendance', 'route' => 'attendance.create'],
                    ['name' => 'Bulk Upload Attendance', 'route' => 'attendance.bulk-upload'],
                    ['name' => 'Records', 'route' => 'attendance.index'],
                ]
            ],
            [
                'name' => 'Leaves',
                'icon' => 'calendar',
                'submenus' => [
                    ['name' => 'Leave Application', 'route' => 'leaves.create'],
                    ['name' => 'Leave Logs', 'route' => 'leaves.index'],
                ]
            ],
            [
                'name' => 'Movement',
                'icon' => 'move',
                'submenus' => [
                    ['name' => 'Movement Application', 'route' => 'movement.create'],
                    ['name' => 'Movement Logs', 'route' => 'movement.index'],
                ]
            ],
            [
                'name' => 'Payroll',
                'icon' => 'dollar-sign',
                'submenus' => [
                    ['name' => 'Promotions', 'route' => 'promotion.index'],
                    ['name' => 'Increments', 'route' => 'increment.index'],
                    ['name' => 'Bonuses', 'route' => 'bonus.index'],
                    ['name' => 'Salary', 'route' => 'salary.index'],
                ]
            ],
            [
                'name' => 'Plans',
                'icon' => 'layers',
                'submenus' => [
                    ['name' => 'Meal Plans', 'route' => 'plans.meal_plans.index'],
                    ['name' => 'Shift Plans', 'route' => 'plans.shift_plans.index'],
                    ['name' => 'Leave Plans', 'route' => 'plans.leave_plans.index'],
                    ['name' => 'OT Plans', 'route' => 'plans.ot_plans.index'],
                    ['name' => 'Roster Plans', 'route' => 'plans.roster_plans.index'],
                    ['name' => 'Off-Day Work Plans', 'route' => 'plans.off_day_plans.index'],
                    ['name' => 'Bonus Plans', 'route' => 'plans.bonus_plans.index'],
                    ['name' => 'Allowance Plans', 'route' => 'plans.allowance_plans.index'],
                    ['name' => 'TA Plans', 'route' => 'plans.ta_plans.index'],
                    ['name' => 'DA Plans', 'route' => 'plans.da_plans.index'],
                    ['name' => 'Deduction Plan', 'route' => 'plans.deduction_plans.index'],
                ]
            ],
            [
                'name' => 'Company Info',
                'icon' => 'box',
                'submenus' => [
                    ['name' => 'Groups', 'route' => 'groups.index'],
                    ['name' => 'Company Types', 'route' => 'company_types.index'],
                    ['name' => 'Companies', 'route' => 'companies.index'],
                    ['name' => 'Company Branches', 'route' => 'company_locations.index'],
                    ['name' => 'Divisions', 'route' => 'divisions.index'],
                    ['name' => 'Departments', 'route' => 'departments.index'],
                    ['name' => 'Sections', 'route' => 'sections.index'],
                    ['name' => 'Designations', 'route' => 'designations.index'],
                    ['name' => 'Salary Acts', 'route' => 'tofsils.index'],
                    ['name' => 'Salary Grades', 'route' => 'salary_grades.index'],
                    ['name' => 'Banks', 'route' => 'banks.index'],
                    ['name' => 'Bank Branches', 'route' => 'branches.index'],
                    ['name' => 'Bank Accounts', 'route' => 'bank_accounts.index'],
                    ['name' => 'Holidays', 'route' => 'holidays.index'],
                    ['name' => 'Job Creations', 'route' => 'job_creations.index'],
                ]
            ],
            [
                'name' => 'Structure',
                'icon' => 'git-branch',
                'submenus' => [
                    ['name' => 'Structural View', 'route' => 'organization-structure.view'],
                    ['name' => 'Members', 'route' => 'organization-structure.index'],
                ]
            ],
            [
                'name' => 'Transport',
                'icon' => 'truck',
                'submenus' => [
                    ['name' => 'Vehicles', 'route' => 'transport.vehicles.index'],
                    ['name' => 'Assign Driver', 'route' => 'transport.vehicle_drivers.index'],
                    ['name' => 'Vehicle Requisition', 'route' => 'transport.vehicle_requisitions.index'],
                    ['name' => 'Employee Transport', 'route' => 'transport.employee_transports.index'],
                    ['name' => 'Vehicle Allocation', 'route' => 'transport.vehicle_allocations.dashboard'],
                ]
            ],
            [
                'name' => 'Settings',
                'icon' => 'settings',
                'submenus' => [
                    ['name' => 'General Settings', 'route' => 'settings.general_settings'],
                    ['name' => 'ID Card Design', 'route' => 'settings.id_design.index'],
                    ['name' => 'API Keys', 'route' => 'settings.api_keys'],
                    ['name' => 'SMTP', 'route' => 'settings.mail_settings'],
                    ['name' => 'DB Backup', 'route' => 'db_backup'],
                    ['name' => 'Role Management', 'route' => 'settings.roles.index'],
                ]
            ],
        ];

        $actions = ['view', 'create', 'edit', 'delete'];

        foreach ($menus as $index => $m) {
            $parent = Menu::create([
                'name' => $m['name'],
                'slug' => Str::slug($m['name']),
                'icon' => $m['icon'],
                'route' => $m['route'] ?? null,
                'order' => $index,
            ]);

            // Create permissions for parent
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => $parent->slug . '.' . $action, 'guard_name' => 'web']);
            }

            if (isset($m['submenus'])) {
                foreach ($m['submenus'] as $subIndex => $sm) {
                    $child = Menu::create([
                        'name' => $sm['name'],
                        'slug' => Str::slug($sm['name']),
                        'parent_id' => $parent->id,
                        'route' => $sm['route'],
                        'order' => $subIndex,
                    ]);

                    // Create permissions for child
                    foreach ($actions as $action) {
                        Permission::firstOrCreate(['name' => $child->slug . '.' . $action, 'guard_name' => 'web']);
                    }
                }
            }
        }

        // Create Super Admin role and assign all permissions
        $role = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $role->syncPermissions(Permission::all());
        
        // Ensure a default user is Super Admin if needed
        $user = \App\Models\User::where('email', 'admin@example.com')->first();
        if ($user) {
            $user->assignRole($role);
        }
    }
}
