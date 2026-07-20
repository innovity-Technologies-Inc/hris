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
                'name' => 'Announcements',
                'icon' => 'megaphone',
                'slug' => 'announcements',
                'route' => 'announcements.index',
                'actions' => ['view', 'create', 'edit', 'delete']
            ],
            [
                'name' => 'Employee Management',
                'icon' => 'users',
                'actions' => ['view', 'create', 'edit', 'delete', 'profile-review', 'import', 'nid-verification', 'analytics']
            ],
            [
                'name' => 'Profile Update Requests',
                'icon' => 'file-pen',
                'slug' => 'profile-update-requests',
                'route' => 'profile_update_requests.index',
                'actions' => ['view', 'add', 'delete']
            ],

            [
                'name' => 'Attendance',
                'icon' => 'clock',
                'actions' => ['view', 'clock-in-out', 'create', 'edit', 'delete', 'import']
            ],

            [
                'name' => 'Leaves',
                'icon' => 'calendar-days',
                'actions' => ['view', 'create', 'edit', 'delete']
            ],

            [
                'name' => 'Movement',
                'icon' => 'person-walking-arrow-right',
                'actions' => ['view', 'create', 'edit', 'delete']
            ],

            [
                'name' => 'Transfers',
                'icon' => 'shuffle',
                'actions' => ['view', 'create', 'edit', 'delete']
            ],

            [
                'name' => 'Offboarding',
                'icon' => 'user-x',
                'slug' => 'offboardings',
                'submenus' => [
                    [
                        'name' => 'Resignation',
                        'slug' => 'resignations',
                        'route' => 'offboarding.resignation.index',
                        'actions' => ['view', 'create', 'edit', 'delete', 'approve']
                    ],
                    [
                        'name' => 'Termination',
                        'slug' => 'terminations',
                        'route' => 'offboarding.termination.index',
                        'actions' => ['view', 'create', 'edit', 'delete', 'approve']
                    ],
                ]
            ],

            [
                'name' => 'Claim Expense',
                'icon' => 'dollar-sign',
                'slug' => 'claim-expenses',
                'submenus' => [
                    ['name' => 'Application', 'slug' => 'claim-expense-application', 'route' => 'claim_expenses.create', 'actions' => ['create']],
                    ['name' => 'Logs', 'slug' => 'claim-expense-logs', 'route' => 'claim_expenses.index', 'actions' => ['view', 'edit', 'delete']],
                ]
            ],

            [
                'name' => 'Payroll',
                'icon' => 'money-bill-wave',
                'submenus' => [
                    ['name' => 'Promotions', 'route' => 'promotion.index', 'actions' => ['view', 'create', 'edit', 'delete']],
                    ['name' => 'Increments', 'route' => 'increment.index', 'actions' => ['view', 'create', 'edit', 'delete']],
                    ['name' => 'Bonuses', 'route' => 'bonus.index', 'actions' => ['view', 'create', 'edit', 'delete']],
                    ['name' => 'Penalty Management', 'slug' => 'penalty-management', 'route' => 'payroll.penalty.index', 'actions' => ['view', 'create', 'edit', 'delete']],
                    ['name' => 'Advance Salary', 'route' => 'advance-salary.index', 'actions' => ['view', 'create', 'edit', 'delete']],
                    ['name' => 'Arrear Management', 'slug' => 'arrear', 'route' => 'arrear.index', 'actions' => ['view', 'create', 'edit', 'delete']],
                    ['name' => 'Salary', 'route' => 'salary.index', 'actions' => ['view', 'create', 'edit', 'delete']],
                    ['name' => 'Disbursement', 'slug' => 'disbursement', 'route' => 'disbursement.index', 'actions' => ['view', 'process']],
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
                    ['name' => 'Expense Types', 'slug' => 'expense-types', 'route' => 'expense_types.index', 'actions' => ['view', 'create', 'edit', 'delete']],
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
                    ['name' => 'Vehicle Requisition', 'route' => 'transport.vehicle_requisitions.index', 'actions' => ['view', 'create', 'edit', 'delete']],
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
                    ['name' => 'Approval Workflows', 'slug' => 'approval-workflows', 'route' => 'setting.approval_workflows.index', 'actions' => ['view', 'create', 'edit', 'delete']],
                    ['name' => 'Audit Logs', 'route' => 'audit_logs.index', 'actions' => ['view']],
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

        // Create HR Manager role and assign HR-specific permissions exactly as in DB
        $hrManagerRole = Role::firstOrCreate(['name' => 'HR Manager', 'guard_name' => 'web']);
        $hrPermissions = Permission::whereNotIn('name', [
            'dashboard.view',
            'structural-view.view',
            'members.view',
            'members.create',
            'members.edit',
            'members.delete',
            'api-keys.view',
            'api-keys.edit',
            'api-keys.delete',
            'smtp.view',
            'smtp.edit',
            'db-backup.download',
            'role-management.view',
            'role-management.create',
            'role-management.edit',
            'role-management.delete',
        ])->get();
        $hrManagerRole->syncPermissions($hrPermissions);

        // Create Employee role and assign specific permissions exactly as in DB
        $employeeRole = Role::firstOrCreate(['name' => 'Employee', 'guard_name' => 'web']);
        $employeeRole->syncPermissions([
            'employee-management.view',
            'employee-management.create',
            'profile-update-requests.view',
            'profile-update-requests.add',
            'attendance.view',
            'attendance.clock-in-out',
            'attendance.create',
            'leaves.view',
            'leaves.create',
            'movement.view',
            'movement.create',
            'transfers.view',
            'transfers.create',
        ]);

        // Create Manager role and assign specific permissions exactly as in DB
        $managerRole = Role::firstOrCreate(['name' => 'Manager', 'guard_name' => 'web']);
        $managerRole->syncPermissions([
            'employee-management.view',
            'employee-management.create',
            'employee-management.edit',
            'employee-management.profile-review',
            'employee-management.nid-verification',
            'employee-management.analytics',
            'profile-update-requests.view',
            'profile-update-requests.add',
            'attendance.view',
            'attendance.clock-in-out',
            'attendance.create',
            'attendance.edit',
            'leaves.view',
            'leaves.create',
            'leaves.edit',
            'movement.view',
            'movement.create',
            'movement.edit',
            'transfers.view',
            'transfers.create',
            'transfers.edit',
            'promotions.view',
            'promotions.create',
            'promotions.edit',
            'increments.view',
            'increments.create',
            'increments.edit',
            'bonuses.view',
            'bonuses.create',
            'bonuses.edit',
            'penalty-management.view',
            'penalty-management.create',
            'penalty-management.edit',
            'advance-salary.view',
            'advance-salary.create',
            'advance-salary.edit',
            'arrear.view',
            'arrear.create',
            'arrear.edit',
            'salary.view',
            'salary.create',
            'salary.edit',
            'disbursement.view',
            'disbursement.process',
            'vehicles.view',
            'vehicles.create',
            'vehicles.edit',
            'assign-driver.view',
            'assign-driver.create',
            'assign-driver.edit',
            'vehicle-requisition.view',
            'vehicle-requisition.create',
            'vehicle-requisition.edit',
            'employee-transport.view',
            'employee-transport.create',
            'employee-transport.edit',
            'vehicle-allocation.view',
            'vehicle-allocation.create',
            'vehicle-allocation.edit',
        ]);

        // Ensure a default user is Super Admin if needed
        $user = \App\Models\User::where('email', 'admin@example.com')->first();
        if ($user) {
            $user->assignRole($superAdminRole);
        }
    }
}
