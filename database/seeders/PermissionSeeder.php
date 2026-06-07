<?php

namespace Database\Seeders;

use App\Models\Setting\Menu;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
        Schema::disableForeignKeyConstraints();
        Menu::truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();
        Schema::enableForeignKeyConstraints();

        $menus = [
            [
                'name' => 'Dashboard',
                'icon' => 'gauge',
                'actions' => ['view'],
            ],
            [
                'name' => 'Employee Management',
                'icon' => 'users',
                'actions' => ['view', 'create', 'edit', 'delete', 'profile-review', 'import', 'nid-verification', 'analytics']
            ],

            [
                'name' => 'Attendance',
                'icon' => 'clock',
                'actions' => ['view', 'clock-in-out', 'create', 'edit', 'delete', 'import']
            ],

            [
                'name' => 'Leaves',
                'icon' => 'calendar-days',
                'actions' => ['view', 'create', 'edit', 'delete', 'hr-approve', 'supervisor-approve']
            ],

            [
                'name' => 'Movement',
                'icon' => 'person-walking-arrow-right',
                'actions' => ['view', 'create', 'edit', 'delete', 'hr-approve', 'supervisor-approve']
            ],

            [
                'name' => 'Transfers',
                'icon' => 'shuffle',
                'actions' => ['view', 'create', 'edit', 'delete', 'approve']
            ],

            [
                'name' => 'Payroll',
                'icon' => 'money-bill-wave',
                'submenus' => [
                    ['name' => 'Promotions', 'route' => 'promotion.index', 'actions' => ['view', 'create', 'edit', 'delete']],
                    ['name' => 'Increments', 'route' => 'increment.index', 'actions' => ['view', 'create', 'edit', 'delete']],
                    ['name' => 'Bonuses', 'route' => 'bonus.index', 'actions' => ['view', 'create', 'edit', 'delete', 'hr-approve', 'management-approve']],
                    ['name' => 'Penalty Management', 'slug' => 'penalty-management', 'route' => 'payroll.penalty.index', 'actions' => ['view', 'create', 'edit', 'delete']],
                    ['name' => 'Salary', 'route' => 'salary.index', 'actions' => ['view', 'create', 'edit', 'delete', 'hr-approve', 'management-approve']],
                ]
            ],
            [
                'name' => 'Plans',
                'icon' => 'layer-group',
                'submenus' => [
                    ['name' => 'Meal Plans', 'slug' => 'meal-plans', 'route' => 'plans.meal_plans.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'Shift Plans', 'slug' => 'shift-plans', 'route' => 'plans.shift_plans.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'Leave Plans', 'slug' => 'leave-plans', 'route' => 'plans.leave_plans.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'OT Plans', 'slug' => 'ot-plans', 'route' => 'plans.ot_plans.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'Roster Plans', 'slug' => 'roster-plans', 'route' => 'plans.roster_plans.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'Off-Day Work Plans', 'slug' => 'off-day-work-plans', 'route' => 'plans.off_day_plans.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'Bonus & Reward Plans', 'slug' => 'bonus-plans', 'route' => 'plan.bonus_plans.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'Penalty Plans', 'slug' => 'penalty-plans', 'route' => 'plan.penalty_plans.index', 'actions' => ['view', 'create', 'edit', 'delete']],
                    ['name' => 'Leave Encashment Plans', 'slug' => 'leave-encashment-plans', 'route' => 'plan.leave_encashment_plans.index', 'actions' => ['view', 'create', 'edit']],
                    ['name' => 'Allowance Plans', 'slug' => 'allowance-plans', 'route' => 'plan.allowance_plans.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'TA Plans', 'slug' => 'ta-plans', 'route' => 'plans.ta_plans.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'DA Plans', 'slug' => 'da-plans', 'route' => 'plans.da_plans.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'Deduction Plan', 'slug' => 'deduction-plan', 'route' => 'plans.deduction_plans.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                ]
            ],
            [
                'name' => 'Company Info',
                'icon' => 'building-columns',
                'submenus' => [
                    ['name' => 'Groups', 'route' => 'groups.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'Company Types', 'route' => 'company_types.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'Companies', 'route' => 'companies.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'Company Branches', 'route' => 'company_locations.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'Divisions', 'route' => 'divisions.index','actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'Departments', 'route' => 'departments.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'Sections', 'route' => 'sections.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'Designations', 'route' => 'designations.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'Pay Groups', 'route' => 'pay_groups.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'Pay Scales', 'route' => 'pay_scales.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'Salary Grades', 'route' => 'salary_grades.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'Banks', 'route' => 'banks.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'Bank Branches', 'route' => 'branches.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'Bank Accounts', 'route' => 'bank_accounts.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'Holidays', 'route' => 'holidays.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'Gazette Locations', 'route' => 'gazette_locations.index', 'actions' => ['view', 'create', 'edit', 'delete', 'import']],
                    ['name' => 'Job Creations', 'route' => 'job_creations.index','actions' => ['view', 'create', 'edit', 'delete', 'import']],
                ]
            ],
            [
                'name' => 'Structure',
                'icon' => 'sitemap',
                'submenus' => [
                    ['name' => 'Structural View', 'route' => 'organization-structure.view', 'actions' => ['view']],
                    ['name' => 'Members', 'route' => 'organization-structure.index', 'actions' => ['view', 'create', 'edit', 'delete']],
                ]
            ],
            [
                'name' => 'Transport',
                'icon' => 'truck-fast',
                'submenus' => [
                    ['name' => 'Vehicles', 'route' => 'transport.vehicles.index'],
                    ['name' => 'Assign Driver', 'route' => 'transport.vehicle_drivers.index'],
                    ['name' => 'Vehicle Requisition', 'route' => 'transport.vehicle_requisitions.index', 'actions' => ['view', 'create', 'edit', 'delete', 'hr-approve', 'supervisor-approve']],
                    ['name' => 'Employee Transport', 'route' => 'transport.employee_transports.index', 'actions' => ['view', 'create', 'edit', 'delete',]],
                    ['name' => 'Vehicle Allocation', 'route' => 'transport.vehicle_allocations.dashboard', 'actions' => ['view', 'create', 'edit', 'delete']],
                ]
            ],
            [
                'name' => 'Settings',
                'icon' => 'sliders',
                'submenus' => [
                    ['name' => 'General Settings', 'route' => 'settings.general_settings', 'actions' => ['view', 'edit']],
                    ['name' => 'Transfer Settings', 'route' => 'setting.transfer.index', 'actions' => ['view', 'edit']],
                    ['name' => 'ID Card Design', 'route' => 'settings.id_design.index', 'actions' => ['view', 'create', 'edit', 'delete']],
                    ['name' => 'API Keys', 'route' => 'settings.api_keys', 'actions' => ['view', 'edit', 'delete']],
                    ['name' => 'SMTP', 'route' => 'settings.mail_settings', 'actions' => ['view', 'edit']],
                    ['name' => 'DB Backup', 'route' => 'db_backup', 'actions' => ['download']],
                    ['name' => 'Role Management', 'route' => 'settings.roles.index', 'actions' => ['view', 'create', 'edit', 'delete',]],
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
                        'slug' => $sm['slug'] ?? Str::slug($sm['name']),
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
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdminRole->syncPermissions(Permission::all());

        // Create HR Manager role and assign HR-specific permissions
        $hrManagerRole = Role::firstOrCreate(['name' => 'HR Manager', 'guard_name' => 'web']);
        
        $hrPermissions = Permission::where(function($q) {
            $q->where('name', 'like', 'employee-management.%')
              ->orWhere('name', 'like', 'attendance.%')
              ->orWhere('name', 'like', 'leaves.%')
              ->orWhere('name', 'like', 'movement.%')
              ->orWhere('name', 'like', 'transfers.%')
              ->orWhere('name', 'like', 'promotions.%')
              ->orWhere('name', 'like', 'increments.%')
              ->orWhere('name', 'like', 'bonuses.%')
              ->orWhere('name', 'like', 'salary.%')
              ->orWhere('name', 'like', '%-plans.%')
              ->orWhere('name', 'like', '%-plan.%')
              ->orWhere('name', 'like', 'groups.%')
              ->orWhere('name', 'like', 'company%')
              ->orWhere('name', 'like', 'divisions.%')
              ->orWhere('name', 'like', 'departments.%')
              ->orWhere('name', 'like', 'sections.%')
              ->orWhere('name', 'like', 'designations.%')
              ->orWhere('name', 'like', 'pay-%')
              ->orWhere('name', 'like', 'salary-grades.%')
              ->orWhere('name', 'like', 'banks.%')
              ->orWhere('name', 'like', 'bank-%')
              ->orWhere('name', 'like', 'branches.%')
              ->orWhere('name', 'like', 'holidays.%')
              ->orWhere('name', 'like', 'gazette-locations.%')
              ->orWhere('name', 'like', 'job-creations.%')
              ->orWhere('name', 'like', 'structural-view.%')
              ->orWhere('name', 'like', 'members.%');
        })->get();
        $hrManagerRole->syncPermissions($hrPermissions);

        // Create Employee role and assign specific permissions
        $employeeRole = Role::firstOrCreate(['name' => 'Employee', 'guard_name' => 'web']);
        $employeeRole->syncPermissions([
            'employee-management.view',
            'employee-management.create',
            'employee-management.edit',
            'leaves.create',
            'leaves.view',
            'attendance.create',
            'attendance.view',
            'attendance.clock-in-out',
            'movement.create',
            'movement.view',
            'transfers.create',
            'transfers.view',
        ]);

        // Ensure a default user is Super Admin if needed
        $user = \App\Models\User::where('email', 'admin@example.com')->first();
        if ($user) {
            $user->assignRole($superAdminRole);
        }
    }
}
