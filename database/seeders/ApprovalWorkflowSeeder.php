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
        $hrManagerId = Role::where('name', 'HR Manager')->first()?->id;
        $managerId = Role::where('name', 'Manager')->first()?->id;

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
            ]
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
