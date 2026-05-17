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

        // Disable foreign key checks for truncation
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        \App\Models\Menu::truncate();
        \Illuminate\Support\Facades\DB::table('role_has_permissions')->truncate();
        \Illuminate\Support\Facades\DB::table('model_has_permissions')->truncate();
        \Illuminate\Support\Facades\DB::table('model_has_roles')->truncate();
        \Illuminate\Support\Facades\DB::table('roles')->truncate();
        \Illuminate\Support\Facades\DB::table('permissions')->truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();
        $menus = [
            [
                'name' => 'Dashboard',
                'icon' => 'home',
                'route' => 'dashboard',
                'actions' => ['view'],
                'submenus' => []
            ],
            [
                'name' => 'Employees',
                'icon' => 'users',
                'submenus' => [
                    ['name' => 'Employee Information', 'route' => 'employees.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import', 'export']],
                ]
            ],
            [
                'name' => 'Attendance',
                'icon' => 'clock',
                'actions' => ['view', 'create', 'edit', 'delete', 'import', 'export'],
                'submenus' => [],
            ],
            [
                'name' => 'Leaves',
                'icon' => 'calendar',
                'actions' => ['view', 'create', 'edit', 'delete', 'hr-approve', 'supervisor-approve'],
                'submenus' => []
            ],
            [
                'name' => 'Movement',
                'icon' => 'move',
                'actions' => ['view', 'create', 'edit', 'delete', 'hr-approve', 'supervisor-approve'],
                'submenus' => []
            ],
            [
                'name' => 'Payroll',
                'icon' => 'dollar-sign',
                'submenus' => [
                    ['name' => 'Promotions', 'route' => 'promotion.index', 'actions' => ['view', 'create', 'edit', 'delete']],
                    ['name' => 'Increments', 'route' => 'increment.index', 'actions' => ['view', 'create', 'edit', 'delete']],
                    ['name' => 'Bonuses', 'route' => 'bonus.index', 'actions' => ['view', 'create', 'edit', 'delete', 'hr-approve', 'management-approve']],
                    ['name' => 'Salary', 'route' => 'salary.index', 'actions' => ['view', 'create', 'edit', 'delete', 'hr-approve', 'management-approve']],
                ]
            ],
            [
                'name' => 'Plans',
                'icon' => 'layers',
                'submenus' => [
                    ['name' => 'Meal Plans', 'route' => 'plans.meal_plans.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'Shift Plans', 'route' => 'plans.shift_plans.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'Leave Plans', 'route' => 'plans.leave_plans.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'OT Plans', 'route' => 'plans.ot_plans.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'Roster Plans', 'route' => 'plans.roster_plans.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'Off-Day Work Plans', 'route' => 'plans.off_day_plans.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'Bonus Plans', 'route' => 'plans.bonus_plans.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'Allowance Plans', 'route' => 'plans.allowance_plans.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'TA Plans', 'route' => 'plans.ta_plans.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'DA Plans', 'route' => 'plans.da_plans.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'Deduction Plan', 'route' => 'plans.deduction_plans.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
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
                    ['name' => 'Structural View', 'route' => 'organization-structure.view', 'actions' => ['view']],
                    ['name' => 'Members', 'route' => 'organization-structure.index', 'actions' => ['view', 'create', 'edit', 'delete']],
                ]
            ],
            [
                'name' => 'Transport',
                'icon' => 'truck',
                'submenus' => [
                    ['name' => 'Vehicles', 'route' => 'transport.vehicles.index'],
                    ['name' => 'Assign Driver', 'route' => 'transport.vehicle_drivers.index'],
                    ['name' => 'Vehicle Requisition', 'route' => 'transport.vehicle_requisitions.index', 'actions' => ['view', 'create', 'edit', 'delete', 'hr-approve', 'supervisor-approve']],
                    ['name' => 'Employee Transport', 'route' => 'transport.employee_transports.index'],
                    ['name' => 'Vehicle Allocation', 'route' => 'transport.vehicle_allocations.dashboard', 'actions' => ['view', 'create', 'edit']],
                ]
            ],
            [
                'name' => 'Settings',
                'icon' => 'settings',
                'submenus' => [
                    ['name' => 'General Settings', 'route' => 'settings.general_settings', 'actions' => ['view', 'edit']],
                    ['name' => 'ID Card Design', 'route' => 'settings.id_design.index', 'actions' => ['view', 'create', 'edit', 'delete']],
                    ['name' => 'API Keys', 'route' => 'settings.api_keys', 'actions' => ['view', 'edit']],
                    ['name' => 'SMTP', 'route' => 'settings.mail_settings', 'actions' => ['view', 'edit']],
                    ['name' => 'DB Backup', 'route' => 'db_backup', 'actions' => ['download']],
                    ['name' => 'Role Management', 'route' => 'settings.roles.index'],
                ]
            ],
        ];

        $defaultActions = ['view', 'create', 'edit', 'delete'];

        foreach ($menus as $index => $m) {
            $parent = Menu::create([
                'name' => $m['name'],
                'slug' => Str::slug($m['name']),
                'icon' => $m['icon'],
                'route' => $m['route'] ?? null,
                'order' => $index,
            ]);

            $hasSubmenus = isset($m['submenus']) && count($m['submenus']) > 0;

            // Create permissions for parent ONLY if it has no submenus
            if (!$hasSubmenus) {
                $parentActions = $m['actions'] ?? $defaultActions;
                foreach ($parentActions as $action) {
                    Permission::firstOrCreate(['name' => $parent->slug . '.' . $action, 'guard_name' => 'web']);
                }
            }

            if ($hasSubmenus) {
                foreach ($m['submenus'] as $subIndex => $sm) {
                    $child = Menu::create([
                        'name' => $sm['name'],
                        'slug' => Str::slug($sm['name']),
                        'parent_id' => $parent->id,
                        'route' => $sm['route'],
                        'order' => $subIndex,
                    ]);

                    // Create permissions for child
                    $childActions = $sm['actions'] ?? $defaultActions;
                    foreach ($childActions as $action) {
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
