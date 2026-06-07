<?php

use App\Models\Company\SalaryGrade;
use App\Models\Company\Tofsil;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create and assign permissions
    Permission::firstOrCreate(['name' => 'salary-grades.view', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'salary-grades.create', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'salary-grades.edit', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'salary-grades.delete', 'guard_name' => 'web']);
    
    $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
    $role->givePermissionTo(['salary-grades.view', 'salary-grades.create', 'salary-grades.edit', 'salary-grades.delete']);
    
    $this->admin = User::factory()->create(['user_type' => 'Group']);
    $this->admin->assignRole($role);
});

it('can list salary grades via ajax', function () {
    $this->withoutMiddleware();
    SalaryGrade::create([
        'grade_code' => 'G1',
        'grade_name' => 'Grade 1',
        'status' => 'active'
    ]);

    $response = $this->actingAs($this->admin)
        ->get(route('salary_grades.index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);

    $response->assertStatus(200)
        ->assertSee('G1')
        ->assertSee('Grade 1');
});

it('can store a new salary grade', function () {
    $this->withoutMiddleware();
    $data = [
        'grade_code' => 'G2',
        'grade_name' => 'Grade 2',
        'status' => 'active'
    ];

    $response = $this->actingAs($this->admin)
        ->postJson(route('salary_grades.store'), $data);

    $response->assertStatus(200)
        ->assertJson(['success' => true]);

    $this->assertDatabaseHas('salary_grades', ['grade_code' => 'G2']);
});

it('can update a salary grade', function () {
    $this->withoutMiddleware();
    $grade = SalaryGrade::create([
        'grade_code' => 'G3',
        'grade_name' => 'Old Grade',
        'status' => 'active'
    ]);

    $data = [
        'grade_code' => 'G3-NEW',
        'grade_name' => 'New Grade',
        'status' => 'inactive'
    ];

    $response = $this->actingAs($this->admin)
        ->putJson(route('salary_grades.update', $grade->id), $data);

    $response->assertStatus(200)
        ->assertJson(['success' => true]);

    $this->assertDatabaseHas('salary_grades', ['grade_code' => 'G3-NEW', 'status' => 'inactive']);
});

it('can delete a salary grade', function () {
    $this->withoutMiddleware();
    $grade = SalaryGrade::create([
        'grade_code' => 'G4',
        'grade_name' => 'To Be Deleted',
        'status' => 'active'
    ]);

    $response = $this->actingAs($this->admin)
        ->deleteJson(route('salary_grades.delete', $grade->id));

    $response->assertStatus(200)
        ->assertJson(['success' => true]);

    $this->assertDatabaseMissing('salary_grades', ['id' => $grade->id]);
});
