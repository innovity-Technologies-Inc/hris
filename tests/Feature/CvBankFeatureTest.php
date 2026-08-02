<?php

use App\Models\Onboarding\CvBank;
use App\Models\User;
use App\Enums\UserType;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    Permission::firstOrCreate(['name' => 'cv-bank.view', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'cv-bank.create', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'cv-bank.edit', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'cv-bank.delete', 'guard_name' => 'web']);
});

test('user can access cv bank index and filter results', function () {
    $user = User::factory()->create(['user_type' => UserType::Group]);
    $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
    $role->syncPermissions(['cv-bank.view']);
    $user->assignRole($role);

    CvBank::create([
        'company_name' => 'Google',
        'designation' => 'SDE 3',
        'applicant_name' => 'Alice Smith',
        'career_level' => 'Senior',
        'cv_score' => 92,
    ]);

    $response = $this->actingAs($user)->get(route('cv_bank.index'));
    $response->assertStatus(200);

    // Ajax request
    $response = $this->actingAs($user)->get(route('cv_bank.index', ['_ajax' => true, 'career_level' => 'Senior']));
    $response->assertStatus(200);
    $response->assertSee('Alice Smith');
});

test('user can get cv bank analytics data', function () {
    $user = User::factory()->create(['user_type' => UserType::Group]);
    $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
    $role->syncPermissions(['cv-bank.view']);
    $user->assignRole($role);

    CvBank::create([
        'company_name' => 'Netflix',
        'designation' => 'DevOps',
        'applicant_name' => 'Bob Brown',
        'career_level' => 'Mid',
        'cv_score' => 75,
    ]);

    $response = $this->actingAs($user)->get(route('cv_bank.analytics'));
    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'data' => [
            'total_cvs' => 1,
            'average_score' => 75.0,
        ]
    ]);
});

test('user can store multiple cvs in bulk', function () {
    Storage::fake('public');
    
    $user = User::factory()->create(['user_type' => UserType::Group]);
    $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
    $role->syncPermissions(['cv-bank.create']);
    $user->assignRole($role);

    $cv1File = UploadedFile::fake()->create('cv1.pdf', 500);
    $cv2File = UploadedFile::fake()->create('cv2.docx', 800);

    $payload = [
        'cvs' => [
            [
                'applicant_name' => 'Jack Ma',
                'company_name' => 'Alibaba',
                'designation' => 'Product Owner',
                'career_level' => 'Executive',
                'cv_score' => 95,
                'attachment' => $cv1File,
            ],
            [
                'applicant_name' => 'Elon Musk',
                'company_name' => 'Tesla',
                'designation' => 'Chief Engineer',
                'career_level' => 'Executive',
                'cv_score' => 99,
                'attachment' => $cv2File,
            ],
        ]
    ];

    $response = $this->actingAs($user)->post(route('cv_bank.store'), $payload);
    $response->assertStatus(201);
    $response->assertJson([
        'success' => true,
        'message' => 'CVs created successfully.'
    ]);

    $this->assertDatabaseHas('cv_banks', [
        'applicant_name' => 'Jack Ma',
        'company_name' => 'Alibaba',
        'cv_score' => 95,
    ]);

    $this->assertDatabaseHas('cv_banks', [
        'applicant_name' => 'Elon Musk',
        'company_name' => 'Tesla',
        'cv_score' => 99,
    ]);
});

test('user can update a single cv', function () {
    $user = User::factory()->create(['user_type' => UserType::Group]);
    $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
    $role->syncPermissions(['cv-bank.edit']);
    $user->assignRole($role);

    $cv = CvBank::create([
        'company_name' => 'Meta',
        'designation' => 'Research Scientist',
        'applicant_name' => 'Grace Hopper',
        'career_level' => 'Senior',
        'cv_score' => 88,
    ]);

    $payload = [
        'applicant_name' => 'Grace Hopper Refactored',
        'company_name' => 'Meta Refactored',
        'designation' => 'Research Lead',
        'career_level' => 'Executive',
        'cv_score' => 94,
    ];

    $response = $this->actingAs($user)->post(route('cv_bank.update', $cv->id), $payload);
    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'message' => 'CV updated successfully.'
    ]);

    $this->assertDatabaseHas('cv_banks', [
        'id' => $cv->id,
        'applicant_name' => 'Grace Hopper Refactored',
        'designation' => 'Research Lead',
        'cv_score' => 94,
    ]);
});

test('user can delete a cv', function () {
    $user = User::factory()->create(['user_type' => UserType::Group]);
    $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
    $role->syncPermissions(['cv-bank.delete']);
    $user->assignRole($role);

    $cv = CvBank::create([
        'company_name' => 'Twitter',
        'designation' => 'Frontend Developer',
        'applicant_name' => 'Jack Dorsey',
        'career_level' => 'Senior',
        'cv_score' => 80,
    ]);

    $response = $this->actingAs($user)->delete(route('cv_bank.destroy', $cv->id));
    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'message' => 'CV deleted successfully.'
    ]);

    $this->assertDatabaseMissing('cv_banks', [
        'id' => $cv->id,
    ]);
});
