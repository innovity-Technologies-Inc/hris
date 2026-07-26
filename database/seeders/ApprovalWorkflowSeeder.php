<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Innovity\ApprovalEngine\Models\Workflow;
use Spatie\Permission\Models\Role;

class ApprovalWorkflowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Resolve target Spatie roles
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        $hrManagerRole = Role::where('name', 'HR Manager')->first();
        $managerRole = Role::where('name', 'Manager')->first();

        $hrManagerId = $hrManagerRole?->id;
        $managerId = $managerRole?->id;
        $superAdminId = $superAdminRole?->id;

        $adminRoles = array_values(array_filter([$superAdminId ? (string)$superAdminId : null, $hrManagerId ? (string)$hrManagerId : null]));

        $workflows = [
            [
                'name' => 'Bonus Workflow',
                'module' => 'bonus',
                'type' => 'sequential',
                'required_approvals' => null,
                'is_active' => true,
                'steps' => [
                    [
                        'name' => 'Step 1 - Section',
                        'step_order' => 1,
                        'type' => 'user-type',
                        'required_user_type' => 'section',
                        'role_id' => null,
                        'user_id' => null,
                    ],
                    [
                        'name' => 'Step 2 - Company (HR Manager)',
                        'step_order' => 2,
                        'type' => 'role-user',
                        'required_user_type' => 'company',
                        'role_id' => $hrManagerId,
                        'user_id' => null,
                    ],
                    [
                        'name' => 'Step 3 - Company (Manager)',
                        'step_order' => 3,
                        'type' => 'role-user',
                        'required_user_type' => 'company',
                        'role_id' => $managerId,
                        'user_id' => null,
                    ],
                ]
            ],
            [
                'name' => 'Promotion Workflow',
                'module' => 'promotion',
                'type' => 'sequential',
                'required_approvals' => null,
                'is_active' => true,
                'steps' => [
                    [
                        'name' => 'Step 1 - Company (HR Manager)',
                        'step_order' => 1,
                        'type' => 'role-user',
                        'required_user_type' => 'company',
                        'role_id' => $hrManagerId,
                        'user_id' => null,
                    ],
                    [
                        'name' => 'Step 2 - Company (Manager)',
                        'step_order' => 2,
                        'type' => 'role-user',
                        'required_user_type' => 'company',
                        'role_id' => $managerId,
                        'user_id' => null,
                    ],
                ]
            ],
            [
                'name' => 'Demotion Workflow',
                'module' => 'demotion',
                'type' => 'sequential',
                'required_approvals' => null,
                'is_active' => true,
                'steps' => [
                    [
                        'name' => 'Step 1 - Section',
                        'step_order' => 1,
                        'type' => 'user-type',
                        'required_user_type' => 'section',
                        'role_id' => null,
                        'user_id' => null,
                    ],
                    [
                        'name' => 'Step 2 - Company (HR Manager)',
                        'step_order' => 2,
                        'type' => 'role-user',
                        'required_user_type' => 'company',
                        'role_id' => $hrManagerId,
                        'user_id' => null,
                    ],
                    [
                        'name' => 'Step 3 - Company (Manager)',
                        'step_order' => 3,
                        'type' => 'role-user',
                        'required_user_type' => 'company',
                        'role_id' => $managerId,
                        'user_id' => null,
                    ],
                ]
            ],
            [
                'name' => 'Career-movement Workflow',
                'module' => 'career-movement',
                'type' => 'sequential',
                'required_approvals' => null,
                'is_active' => true,
                'steps' => [
                    [
                        'name' => 'Step 1 - Section',
                        'step_order' => 1,
                        'type' => 'user-type',
                        'required_user_type' => 'section',
                        'role_id' => null,
                        'user_id' => null,
                    ],
                    [
                        'name' => 'Step 2 - Department',
                        'step_order' => 2,
                        'type' => 'user-type',
                        'required_user_type' => 'department',
                        'role_id' => null,
                        'user_id' => null,
                    ],
                    [
                        'name' => 'Step 3 - Company (HR Manager)',
                        'step_order' => 3,
                        'type' => 'role-user',
                        'required_user_type' => 'company',
                        'role_id' => $hrManagerId,
                        'user_id' => null,
                    ],
                    [
                        'name' => 'Step 4 - Company (Manager)',
                        'step_order' => 4,
                        'type' => 'role-user',
                        'required_user_type' => 'company',
                        'role_id' => $managerId,
                        'user_id' => null,
                    ],
                ]
            ],
            [
                'name' => 'Increment Workflow',
                'module' => 'increment',
                'type' => 'sequential',
                'required_approvals' => null,
                'is_active' => true,
                'steps' => [
                    [
                        'name' => 'Step 1 - Section',
                        'step_order' => 1,
                        'type' => 'user-type',
                        'required_user_type' => 'section',
                        'role_id' => null,
                        'user_id' => null,
                    ],
                    [
                        'name' => 'Step 2 - Company (HR Manager)',
                        'step_order' => 2,
                        'type' => 'role-user',
                        'required_user_type' => 'company',
                        'role_id' => $hrManagerId,
                        'user_id' => null,
                    ],
                ]
            ],
            [
                'name' => 'Decrement Workflow',
                'module' => 'decrement',
                'type' => 'sequential',
                'required_approvals' => null,
                'is_active' => true,
                'steps' => [
                    [
                        'name' => 'Step 1 - Section',
                        'step_order' => 1,
                        'type' => 'user-type',
                        'required_user_type' => 'section',
                        'role_id' => null,
                        'user_id' => null,
                    ],
                    [
                        'name' => 'Step 2 - Company (HR Manager)',
                        'step_order' => 2,
                        'type' => 'role-user',
                        'required_user_type' => 'company',
                        'role_id' => $hrManagerId,
                        'user_id' => null,
                    ],
                    [
                        'name' => 'Step 3 - Company (Manager)',
                        'step_order' => 3,
                        'type' => 'role-user',
                        'required_user_type' => 'company',
                        'role_id' => $managerId,
                        'user_id' => null,
                    ],
                ]
            ],
            [
                'name' => 'Leave Workflow',
                'module' => 'leave',
                'type' => 'random',
                'required_approvals' => 2,
                'is_active' => true,
                'steps' => [
                    [
                        'name' => 'Step 1 - Section',
                        'step_order' => 1,
                        'type' => 'user-type',
                        'required_user_type' => 'section',
                        'role_id' => null,
                        'user_id' => null,
                    ],
                    [
                        'name' => 'Step 2 - Department',
                        'step_order' => 2,
                        'type' => 'user-type',
                        'required_user_type' => 'department',
                        'role_id' => null,
                        'user_id' => null,
                    ],
                    [
                        'name' => 'Step 3 - Company (HR Manager)',
                        'step_order' => 3,
                        'type' => 'role-user',
                        'required_user_type' => 'company',
                        'role_id' => $hrManagerId,
                        'user_id' => null,
                    ],
                ]
            ],
            [
                'name' => 'Profile-update Workflow',
                'module' => 'profile-update',
                'type' => 'random',
                'required_approvals' => 1,
                'is_active' => true,
                'steps' => [
                    [
                        'name' => 'Step 1 - Company (HR Manager)',
                        'step_order' => 1,
                        'type' => 'role-user',
                        'required_user_type' => 'company',
                        'role_id' => $hrManagerId,
                        'user_id' => null,
                    ],
                    [
                        'name' => 'Step 2 - Section',
                        'step_order' => 2,
                        'type' => 'user-type',
                        'required_user_type' => 'section',
                        'role_id' => null,
                        'user_id' => null,
                    ],
                ]
            ],
            [
                'name' => 'Salary Workflow',
                'module' => 'salary',
                'type' => 'sequential',
                'required_approvals' => null,
                'is_active' => true,
                'steps' => [
                    [
                        'name' => 'Step 1 - Company (HR Manager)',
                        'step_order' => 1,
                        'type' => 'role-user',
                        'required_user_type' => 'company',
                        'role_id' => $hrManagerId,
                        'user_id' => null,
                    ],
                    [
                        'name' => 'Step 2 - Company (Manager)',
                        'step_order' => 2,
                        'type' => 'role-user',
                        'required_user_type' => 'company',
                        'role_id' => $managerId,
                        'user_id' => null,
                    ],
                ]
            ],
            [
                'name' => 'Travel-movement Workflow',
                'module' => 'travel-movement',
                'type' => 'random',
                'required_approvals' => 1,
                'is_active' => true,
                'steps' => [
                    [
                        'name' => 'Step 1 - Section',
                        'step_order' => 1,
                        'type' => 'user-type',
                        'required_user_type' => 'section',
                        'role_id' => null,
                        'user_id' => null,
                    ],
                    [
                        'name' => 'Step 2 - Company (HR Manager)',
                        'step_order' => 2,
                        'type' => 'role-user',
                        'required_user_type' => 'company',
                        'role_id' => $hrManagerId,
                        'user_id' => null,
                    ],
                ]
            ],
            [
                'name' => 'Claim-expense Workflow',
                'module' => 'claim-expense',
                'type' => 'sequential',
                'required_approvals' => null,
                'is_active' => true,
                'steps' => [
                    [
                        'name' => 'Step 1 - Company (HR Manager)',
                        'step_order' => 1,
                        'type' => 'role-user',
                        'required_user_type' => 'company',
                        'role_id' => $hrManagerId,
                        'user_id' => null,
                    ],
                    [
                        'name' => 'Step 2 - Company (Manager)',
                        'step_order' => 2,
                        'type' => 'role-user',
                        'required_user_type' => 'company',
                        'role_id' => $managerId,
                        'user_id' => null,
                    ],
                ]
            ],
            [
                'name' => 'Employee-bank-account Workflow',
                'module' => 'employee-bank-account',
                'type' => 'sequential',
                'required_approvals' => null,
                'is_active' => true,
                'exclude_role_ids' => $adminRoles,
                'steps' => [
                    [
                        'name' => 'Step 1 - HR Manager',
                        'step_order' => 1,
                        'type' => 'role',
                        'required_user_type' => null,
                        'role_id' => $hrManagerId,
                        'user_id' => null,
                    ],
                ]
            ],
            [
                'name' => 'Employee-policy Workflow',
                'module' => 'employee-policy',
                'type' => 'sequential',
                'required_approvals' => null,
                'is_active' => true,
                'exclude_role_ids' => $adminRoles,
                'steps' => [
                    [
                        'name' => 'Step 1 - HR Manager',
                        'step_order' => 1,
                        'type' => 'role',
                        'required_user_type' => null,
                        'role_id' => $hrManagerId,
                        'user_id' => null,
                    ],
                ]
            ],
            [
                'name' => 'Office-information Workflow',
                'module' => 'office-information',
                'type' => 'sequential',
                'required_approvals' => null,
                'is_active' => true,
                'exclude_role_ids' => $adminRoles,
                'steps' => [
                    [
                        'name' => 'Step 1 - HR Manager',
                        'step_order' => 1,
                        'type' => 'role',
                        'required_user_type' => null,
                        'role_id' => $hrManagerId,
                        'user_id' => null,
                    ],
                ]
            ],
            [
                'name' => 'Salary-breakdown Workflow',
                'module' => 'salary-breakdown',
                'type' => 'sequential',
                'required_approvals' => null,
                'is_active' => true,
                'exclude_role_ids' => $adminRoles,
                'steps' => [
                    [
                        'name' => 'Step 1 - HR Manager',
                        'step_order' => 1,
                        'type' => 'role',
                        'required_user_type' => null,
                        'role_id' => $hrManagerId,
                        'user_id' => null,
                    ],
                ]
            ],
            [
                'name' => 'Resignation Workflow',
                'module' => 'resignation',
                'type' => 'sequential',
                'required_approvals' => null,
                'is_active' => true,
                'steps' => [
                    [
                        'name' => 'Step 1 - Section',
                        'step_order' => 1,
                        'type' => 'user-type',
                        'required_user_type' => 'section',
                        'role_id' => null,
                        'user_id' => null,
                    ],
                    [
                        'name' => 'Step 2 - Company (HR Manager)',
                        'step_order' => 2,
                        'type' => 'role-user',
                        'required_user_type' => 'company',
                        'role_id' => $hrManagerId,
                        'user_id' => null,
                    ],
                    [
                        'name' => 'Step 3 - Company (Manager)',
                        'step_order' => 3,
                        'type' => 'role-user',
                        'required_user_type' => 'company',
                        'role_id' => $managerId,
                        'user_id' => null,
                    ],
                ]
            ],
            [
                'name' => 'Termination Workflow',
                'module' => 'termination',
                'type' => 'sequential',
                'required_approvals' => null,
                'is_active' => true,
                'steps' => [
                    [
                        'name' => 'Step 1 - Section',
                        'step_order' => 1,
                        'type' => 'user-type',
                        'required_user_type' => 'section',
                        'role_id' => null,
                        'user_id' => null,
                    ],
                    [
                        'name' => 'Step 2 - Company (HR Manager)',
                        'step_order' => 2,
                        'type' => 'role-user',
                        'required_user_type' => 'company',
                        'role_id' => $hrManagerId,
                        'user_id' => null,
                    ],
                    [
                        'name' => 'Step 3 - Company (Manager)',
                        'step_order' => 3,
                        'type' => 'role-user',
                        'required_user_type' => 'company',
                        'role_id' => $managerId,
                        'user_id' => null,
                    ],
                ]
            ],
            [
                'name' => 'Penalty Workflow',
                'module' => 'penalty',
                'type' => 'sequential',
                'required_approvals' => null,
                'is_active' => true,
                'steps' => [
                    [
                        'name' => 'Step 1 - Section',
                        'step_order' => 1,
                        'type' => 'user-type',
                        'required_user_type' => 'section',
                        'role_id' => null,
                        'user_id' => null,
                    ],
                    [
                        'name' => 'Step 2 - Company (HR Manager)',
                        'step_order' => 2,
                        'type' => 'role-user',
                        'required_user_type' => 'company',
                        'role_id' => $hrManagerId,
                        'user_id' => null,
                    ],
                ]
            ],
            [
                'name' => 'Advance-salary Workflow',
                'module' => 'advance-salary',
                'type' => 'sequential',
                'required_approvals' => null,
                'is_active' => true,
                'steps' => [
                    [
                        'name' => 'Step 1 - Section',
                        'step_order' => 1,
                        'type' => 'user-type',
                        'required_user_type' => 'section',
                        'role_id' => null,
                        'user_id' => null,
                    ],
                    [
                        'name' => 'Step 2 - Company (HR Manager)',
                        'step_order' => 2,
                        'type' => 'role-user',
                        'required_user_type' => 'company',
                        'role_id' => $hrManagerId,
                        'user_id' => null,
                    ],
                ]
            ],
            [
                'name' => 'Arrear Workflow',
                'module' => 'arrear',
                'type' => 'sequential',
                'required_approvals' => null,
                'is_active' => true,
                'steps' => [
                    [
                        'name' => 'Step 1 - Company (HR Manager)',
                        'step_order' => 1,
                        'type' => 'role-user',
                        'required_user_type' => 'company',
                        'role_id' => $hrManagerId,
                        'user_id' => null,
                    ],
                ]
            ],
        ];

        foreach ($workflows as $wfData) {
            $steps = $wfData['steps'];
            unset($wfData['steps']);

            $workflow = Workflow::updateOrCreate(
                ['module' => $wfData['module']],
                [
                    'name' => $wfData['name'],
                    'type' => $wfData['type'],
                    'total_steps' => count($steps),
                    'required_approvals' => $wfData['required_approvals'],
                    'is_active' => $wfData['is_active'],
                    'exclude_role_ids' => $wfData['exclude_role_ids'] ?? null,
                ]
            );

            // Clean up existing steps for safety
            $workflow->steps()->delete();

            foreach ($steps as $stepData) {
                $workflow->steps()->create($stepData);
            }
        }
    }
}
