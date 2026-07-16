<?php

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Innovity\ApprovalEngine\Models\Workflow as ApprovalWorkflow;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create a user and assign necessary permissions
    $this->user = User::factory()->create(['user_type' => 'employee']);
    $role = Role::create(['name' => 'Admin']);
    
    $permissions = [
        'approval-workflows.view',
        'approval-workflows.create',
        'approval-workflows.edit',
        'approval-workflows.delete',
    ];
    
    foreach ($permissions as $permission) {
        Permission::findOrCreate($permission, 'web');
    }
    
    $role->syncPermissions($permissions);
    $this->user->assignRole($role);
});

test('approval workflows index page is accessible', function () {
    $response = $this->actingAs($this->user)
        ->get(route('setting.approval_workflows.index'));

    $response->assertStatus(200);
    $response->assertViewIs('setting.approval_workflow.index');
});

test('approval workflows create page is accessible', function () {
    $response = $this->actingAs($this->user)
        ->get(route('setting.approval_workflows.create'));

    $response->assertStatus(200);
    $response->assertViewIs('setting.approval_workflow.create');
});

test('approval workflow store validation behavior', function () {
    // Empty steps should fail validation
    $response = $this->actingAs($this->user)
        ->postJson(route('setting.approval_workflows.store'), [
            'module_name' => 'leave',
            'type' => 'sequential',
            'steps' => [],
        ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['steps']);
});

test('approval workflow sequential steps authority validation behavior', function () {
    // Step 1 is Department (weight: 4), Step 2 is Section (weight: 5).
    // Section (5) has lower authority than Department (4), which goes down.
    // So this should fail sequential validation.
    $response = $this->actingAs($this->user)
        ->postJson(route('setting.approval_workflows.store'), [
            'module_name' => 'leave',
            'type' => 'sequential',
            'is_active' => '1',
            'scope_type' => 'all',
            'exclude_scope_type' => 'none',
            'steps' => [
                [
                    'type' => 'user-type',
                    'required_user_type' => 'department'
                ],
                [
                    'type' => 'user-type',
                    'required_user_type' => 'section'
                ]
            ]
        ]);

    $response->assertStatus(422);
    $response->assertJsonFragment([
        'errors' => [
            'steps' => [
                'Step 2 (level: section) cannot have a lower authority level than Step 1 (level: department) in a sequential workflow.'
            ]
        ]
    ]);
});

test('approval workflow CRUD operations and steps creation', function () {
    // 1. Create a workflow
    $response = $this->actingAs($this->user)
        ->postJson(route('setting.approval_workflows.store'), [
            'module_name' => 'leave',
            'type' => 'sequential',
            'is_active' => '1',
            'scope_type' => 'all',
            'exclude_scope_type' => 'none',
            'steps' => [
                [
                    'type' => 'user-type',
                    'required_user_type' => 'section'
                ],
                [
                    'type' => 'user-type',
                    'required_user_type' => 'department'
                ]
            ]
        ]);

    $response->assertStatus(200);
    $response->assertJson(['success' => true]);

    $this->assertDatabaseHas('approval_workflows', [
        'module' => 'leave',
        'type' => 'sequential',
        'is_active' => true,
    ]);

    $workflow = ApprovalWorkflow::where('module', 'leave')->first();
    $this->assertCount(2, $workflow->steps);

    // 2. Edit page accessibility
    $editResponse = $this->actingAs($this->user)
        ->get(route('setting.approval_workflows.edit', $workflow->id));
    $editResponse->assertStatus(200);

    // 3. Update the workflow
    $updateResponse = $this->actingAs($this->user)
        ->putJson(route('setting.approval_workflows.update', $workflow->id), [
            'module_name' => 'leave', // Keep same or change
            'type' => 'random',
            'required_approvals' => 1,
            'is_active' => '0',
            'scope_type' => 'all',
            'exclude_scope_type' => 'none',
            'steps' => [
                [
                    'type' => 'user-type',
                    'required_user_type' => 'company'
                ]
            ]
        ]);

    $updateResponse->assertStatus(200);
    $updateResponse->assertJson(['success' => true]);

    $this->assertDatabaseHas('approval_workflows', [
        'id' => $workflow->id,
        'type' => 'random',
        'is_active' => false,
        'required_approvals' => 1,
    ]);

    $workflow->refresh();
    $this->assertCount(1, $workflow->steps);

    // 4. Destroy the workflow
    $deleteResponse = $this->actingAs($this->user)
        ->deleteJson(route('setting.approval_workflows.destroy', $workflow->id));

    $deleteResponse->assertStatus(200);
    $deleteResponse->assertJson(['success' => true]);

    $this->assertDatabaseMissing('approval_workflows', [
        'id' => $workflow->id,
    ]);
});
