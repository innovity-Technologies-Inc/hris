<?php

namespace Tests\Feature\Setting;

use App\Models\User;
use App\Models\Company\Department;
use App\Enums\UserType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create([
            'user_type' => UserType::Group,
            'name' => 'Test Admin'
        ]);
        
        // Give permission so they can view the logs
        $permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'audit-logs.view', 'guard_name' => 'web']);
        $this->admin->givePermissionTo($permission);
    }

    /** @test */
    public function it_automatically_tracks_created_by_and_updated_by()
    {
        // Act as admin
        $this->actingAs($this->admin);

        // Create a department
        $department = Department::create([
            'department_name' => 'IT Department',
            'short_name' => 'IT',
            'status' => 'active'
        ]);

        $this->assertEquals($this->admin->id, $department->created_by);
        $this->assertEquals($this->admin->id, $department->updated_by);

        // Update the department
        $department->update(['department_name' => 'Information Technology']);

        $this->assertEquals($this->admin->id, $department->updated_by);
    }

    /** @test */
    public function it_creates_an_activity_log_when_a_model_is_created_or_updated()
    {
        $this->actingAs($this->admin);

        // Create
        $department = Department::create([
            'department_name' => 'HR',
            'short_name' => 'HR',
            'status' => 'active'
        ]);

        $createActivity = Activity::where('subject_type', get_class($department))
            ->where('subject_id', $department->id)
            ->where('event', 'created')
            ->first();

        $this->assertNotNull($createActivity);
        $this->assertEquals($this->admin->id, $createActivity->causer_id);
        $this->assertEquals('HR', $createActivity->properties['attributes']['department_name']);

        // Update
        $department->update(['department_name' => 'Human Resources']);

        $updateActivity = Activity::where('subject_type', get_class($department))
            ->where('subject_id', $department->id)
            ->where('event', 'updated')
            ->first();

        $this->assertNotNull($updateActivity);
        $this->assertEquals('HR', $updateActivity->properties['old']['department_name']);
        $this->assertEquals('Human Resources', $updateActivity->properties['attributes']['department_name']);
    }

    /** @test */
    public function admin_can_view_the_audit_logs_page()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('audit_logs.index'));
        $response->assertStatus(200);
        $response->assertViewIs('setting.audit_logs.index');
    }
}
