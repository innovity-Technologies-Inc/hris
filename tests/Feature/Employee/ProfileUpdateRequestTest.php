<?php

use App\Models\User;
use App\Enums\UserType;
use App\Models\Employee\Employee;
use App\Models\Employee\ProfileUpdateRequest;
use App\Models\Employee\EmployeeEducationExperienceTraining;
use App\Models\Employee\EmployeeEmploymentHistory;
use App\Models\Employee\EmployeeNominee;
use Innovity\ApprovalEngine\Models\Workflow;
use Innovity\ApprovalEngine\Models\ApprovalRequest;
use Innovity\ApprovalEngine\Events\ApprovalCompleted;
use App\Listeners\Workflow\ProfileUpdateWorkflowListener;

beforeEach(function () {
    $this->employee = Employee::factory()->create([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'personal_mobile' => '123456789',
        'status' => 'active'
    ]);

    $this->user = User::factory()->create([
        'user_type' => UserType::Employee,
        'employee_id' => $this->employee->id,
        'name' => 'John Doe',
        'email' => 'john@example.com'
    ]);

    $this->employee->update(['user_id' => $this->user->id]);

    // Give permissions to submit profile update requests
    $permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'profile-update-requests.add', 'guard_name' => 'web']);
    $this->user->givePermissionTo($permission);

    // Create a dummy workflow for profile-update
    $this->workflow = Workflow::create([
        'name' => 'Profile Update Workflow',
        'module' => 'profile-update',
        'type' => 'sequential',
        'total_steps' => 1,
        'is_active' => true
    ]);

    $this->workflow->steps()->create([
        'name' => 'Step 1 - HR Review',
        'step_order' => 1,
        'type' => 'user-type',
        'required_user_type' => 'company'
    ]);
});

test('it can submit general section update request and apply changes upon approval', function () {
    $this->actingAs($this->user);

    $requestedData = [
        'first_name' => 'Johnny',
        'last_name' => 'Smith',
        'personal_mobile' => '987654321',
        'present_address' => [
            'address_line' => 'Flat 4B',
            'village' => 'Gulshan',
            'city' => 'Dhaka'
        ]
    ];

    $response = $this->postJson(route('profile_update_requests.store'), [
        'employee_id' => $this->employee->id,
        'section' => 'general',
        'requested_data' => $requestedData,
        'previous_data' => [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'personal_mobile' => '123456789',
            'present_address' => null
        ]
    ]);

    $response->assertStatus(200);
    $response->assertJsonPath('success', true);

    $requestRecord = ProfileUpdateRequest::where('employee_id', $this->employee->id)
        ->where('section', 'general')
        ->first();

    expect($requestRecord)->not->toBeNull();
    expect($requestRecord->status)->toBe('pending');
    expect($requestRecord->requested_data['first_name'])->toBe('Johnny');

    // Simulate approval workflow completed event
    $approvalRequest = ApprovalRequest::create([
        'workflow_id' => $this->workflow->id,
        'approvable_type' => ProfileUpdateRequest::class,
        'approvable_id' => $requestRecord->id,
        'status' => 'approved'
    ]);

    $event = new ApprovalCompleted($approvalRequest);
    $listener = new ProfileUpdateWorkflowListener();
    $listener->handleCompleted($event);

    // Verify changes applied to the Employee
    $this->employee->refresh();
    expect($this->employee->first_name)->toBe('Johnny');
    expect($this->employee->last_name)->toBe('Smith');
    expect($this->employee->personal_mobile)->toBe('987654321');
    expect($this->employee->present_address['village'])->toBe('Gulshan');

    // Verify request status is approved
    $requestRecord->refresh();
    expect($requestRecord->status)->toBe('approved');
});

test('it can submit education section update request and apply changes upon approval', function () {
    $this->actingAs($this->user);

    $requestedData = [
        'educations' => [
            [
                'education_title' => 'B.Sc. in CSE',
                'institute' => 'University of Dhaka',
                'passing_year' => '2022'
            ]
        ],
        'trainings' => [
            [
                'training_title' => 'Laravel Advanced',
                'institute' => 'LaraCasts'
            ]
        ]
    ];

    $response = $this->postJson(route('profile_update_requests.store'), [
        'employee_id' => $this->employee->id,
        'section' => 'education',
        'requested_data' => $requestedData,
        'previous_data' => [
            'educations' => [],
            'trainings' => []
        ]
    ]);

    $response->assertStatus(200);

    $requestRecord = ProfileUpdateRequest::where('employee_id', $this->employee->id)
        ->where('section', 'education')
        ->first();

    // Trigger Approval Completed Event
    $approvalRequest = ApprovalRequest::create([
        'workflow_id' => $this->workflow->id,
        'approvable_type' => ProfileUpdateRequest::class,
        'approvable_id' => $requestRecord->id,
        'status' => 'approved'
    ]);

    $event = new ApprovalCompleted($approvalRequest);
    $listener = new ProfileUpdateWorkflowListener();
    $listener->handleCompleted($event);

    // Verify changes applied to EmployeeEducationExperienceTraining
    $eduRecord = EmployeeEducationExperienceTraining::where('employee_id', $this->employee->id)->first();
    expect($eduRecord)->not->toBeNull();
    expect($eduRecord->educations[0]['education_title'])->toBe('B.Sc. in CSE');
    expect($eduRecord->trainings[0]['training_title'])->toBe('Laravel Advanced');
});

test('it can submit employment history update request and apply changes upon approval', function () {
    $this->actingAs($this->user);

    $requestedData = [
        'histories' => [
            [
                'company_name' => 'Old Tech Inc',
                'designation' => 'Developer',
                'joining_date' => '2020-01-01',
                'end_date' => '2021-12-31'
            ]
        ]
    ];

    $response = $this->postJson(route('profile_update_requests.store'), [
        'employee_id' => $this->employee->id,
        'section' => 'employment_history',
        'requested_data' => $requestedData,
        'previous_data' => [
            'histories' => []
        ]
    ]);

    $response->assertStatus(200);

    $requestRecord = ProfileUpdateRequest::where('employee_id', $this->employee->id)
        ->where('section', 'employment_history')
        ->first();

    $approvalRequest = ApprovalRequest::create([
        'workflow_id' => $this->workflow->id,
        'approvable_type' => ProfileUpdateRequest::class,
        'approvable_id' => $requestRecord->id,
        'status' => 'approved'
    ]);

    $event = new ApprovalCompleted($approvalRequest);
    $listener = new ProfileUpdateWorkflowListener();
    $listener->handleCompleted($event);

    // Verify EmployeeEmploymentHistory updated
    $historyRecord = EmployeeEmploymentHistory::where('employee_id', $this->employee->id)->first();
    expect($historyRecord)->not->toBeNull();
    expect($historyRecord->histories[0]['company_name'])->toBe('Old Tech Inc');
});

test('it can submit emergency contact nominee update request and apply changes upon approval', function () {
    $this->actingAs($this->user);

    $requestedData = [
        'nominee_name' => 'Jane Doe',
        'relation' => 'Spouse',
        'mobile' => '987654321',
        'nid' => '1122334455'
    ];

    $response = $this->postJson(route('profile_update_requests.store'), [
        'employee_id' => $this->employee->id,
        'section' => 'emergency_contact',
        'requested_data' => $requestedData,
        'previous_data' => [
            'nominee_name' => '',
            'relation' => '',
            'mobile' => '',
            'nid' => ''
        ]
    ]);

    $response->assertStatus(200);

    $requestRecord = ProfileUpdateRequest::where('employee_id', $this->employee->id)
        ->where('section', 'emergency_contact')
        ->first();

    $approvalRequest = ApprovalRequest::create([
        'workflow_id' => $this->workflow->id,
        'approvable_type' => ProfileUpdateRequest::class,
        'approvable_id' => $requestRecord->id,
        'status' => 'approved'
    ]);

    $event = new ApprovalCompleted($approvalRequest);
    $listener = new ProfileUpdateWorkflowListener();
    $listener->handleCompleted($event);

    // Verify EmployeeNominee updated
    $nomineeRecord = EmployeeNominee::where('employee_id', $this->employee->id)->first();
    expect($nomineeRecord)->not->toBeNull();
    expect($nomineeRecord->nominee_name)->toBe('Jane Doe');
    expect($nomineeRecord->relation)->toBe('Spouse');
    expect($nomineeRecord->mobile)->toBe('987654321');
    expect($nomineeRecord->nid)->toBe('1122334455');
});

test('it creates admin profile update request for office info update and propagates upon approval', function () {
    $admin = User::factory()->create([
        'user_type' => UserType::Company,
    ]);

    $permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'employee-management.edit', 'guard_name' => 'web']);
    $admin->givePermissionTo($permission);

    $this->actingAs($admin);

    // Create office info workflow
    $officeWorkflow = Workflow::create([
        'name' => 'Office Info Workflow',
        'module' => 'office-information',
        'type' => 'sequential',
        'total_steps' => 1,
        'is_active' => true
    ]);

    $officeWorkflow->steps()->create([
        'name' => 'Step 1 - HR Approve',
        'step_order' => 1,
        'type' => 'user-type',
        'required_user_type' => 'company'
    ]);

    // Create initial office info for the employee
    $officeInfo = \App\Models\Employee\EmployeeOfficeInfo::create([
        'employee_id' => $this->employee->id,
        'hr_file_no' => 'F1234',
        'date_of_join' => '2026-01-01',
        'orientation_required' => 'no',
    ]);

    // Submit edit to office info
    $payload = [
        'employee_id' => $this->employee->id,
        'hr_file_no' => 'F9999',
        'date_of_join' => '2026-01-01',
        'orientation_required' => 'no',
    ];

    $response = $this->put(route('employee.office_informations.update', $this->employee->id), $payload);

    $response->assertRedirect();
    $response->assertSessionHas('message', 'Office Info update request submitted for approval.');

    // Assert request record created
    $requestRecord = ProfileUpdateRequest::where('employee_id', $this->employee->id)
        ->where('section', 'office-information')
        ->where('type', 'admin')
        ->first();

    expect($requestRecord)->not->toBeNull();
    expect($requestRecord->status)->toBe('pending');
    expect($requestRecord->requested_data['hr_file_no'])->toBe('F9999');

    // Live table should NOT be updated yet
    $officeInfo->refresh();
    expect($officeInfo->hr_file_no)->toBe('F1234');

    // Simulating approval
    $approvalRequest = ApprovalRequest::create([
        'workflow_id' => $officeWorkflow->id,
        'approvable_type' => ProfileUpdateRequest::class,
        'approvable_id' => $requestRecord->id,
        'status' => 'approved'
    ]);

    $event = new ApprovalCompleted($approvalRequest);
    $listener = new ProfileUpdateWorkflowListener();
    $listener->handleCompleted($event);

    // Verify propagation
    $officeInfo->refresh();
    expect($officeInfo->hr_file_no)->toBe('F9999');
});

test('it generates custom notification redirecting to profile_update_requests.show when approval step is created', function () {
    \Illuminate\Support\Facades\Notification::fake();

    $approver = User::factory()->create([
        'user_type' => UserType::Company,
    ]);

    // Create office info workflow
    $officeWorkflow = Workflow::create([
        'name' => 'Office Info Workflow',
        'module' => 'office-information',
        'type' => 'sequential',
        'total_steps' => 1,
        'is_active' => true
    ]);

    $step = $officeWorkflow->steps()->create([
        'name' => 'Step 1 - Company Approve',
        'step_order' => 1,
        'type' => 'user-type',
        'required_user_type' => 'company'
    ]);

    // Create ProfileUpdateRequest
    $requestRecord = ProfileUpdateRequest::create([
        'employee_id' => $this->employee->id,
        'section' => 'office-information',
        'type' => 'admin',
        'previous_data' => [],
        'requested_data' => ['hr_file_no' => 'F9999'],
        'status' => 'pending',
    ]);

    // Simulating ApprovalRequest creation (Approvable)
    $approvalRequest = ApprovalRequest::create([
        'workflow_id' => $officeWorkflow->id,
        'approvable_type' => ProfileUpdateRequest::class,
        'approvable_id' => $requestRecord->id,
        'status' => 'pending'
    ]);

    // Bind custom ApproverResolver so it resolves to our approver
    app()->bind(\Innovity\ApprovalEngine\Contracts\ApproverResolverInterface::class, function () use ($approver) {
        return new class($approver) implements \Innovity\ApprovalEngine\Contracts\ApproverResolverInterface {
            public function __construct(private $approver) {}
            public function resolve(string $stepId, $approvable): array {
                return [$this->approver->id];
            }
        };
    });

    // Create the step request (triggers the created event handler in AppServiceProvider)
    $stepRequest = $approvalRequest->stepRequests()->create([
        'workflow_step_id' => $step->id,
        'status' => 'pending',
    ]);

    // Verify a custom notification was written to DB with the correct url
    $notification = \App\Models\Setting\Notification::where('user_id', $approver->id)
        ->where('title', 'Approval Action Required')
        ->first();

    expect($notification)->not->toBeNull();
    expect($notification->data['url'])->toBe(route('profile_update_requests.show', $requestRecord->id, false));
    expect($notification->data['type'])->toBe('approval_request');

    // Verify Notification facade was sent ApprovalActionRequiredNotification
    \Illuminate\Support\Facades\Notification::assertSentTo(
        $approver,
        \App\Notifications\Approval\ApprovalActionRequiredNotification::class,
        function ($notification) use ($requestRecord) {
            // Check toArray
            $data = $notification->toArray($this->user);
            expect($data['url'])->toBe(route('profile_update_requests.show', $requestRecord->id, false));

            // Check toMail
            $mail = $notification->toMail($this->user);
            expect($mail->actionUrl)->toBe(route('profile_update_requests.show', $requestRecord->id));

            return true;
        }
    );
});
