<?php

use App\Models\User;
use App\Enums\UserType;
use App\Models\Setting\IDCardDesign;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create([
        'user_type' => UserType::Group,
        'name' => 'Test Admin'
    ]);

    // Force default disk to 'public' for consistency in testing
    config(['filesystems.default' => 'public']);

    // Give permissions to the test user
    $permissions = [
        'id-card-design.view',
        'id-card-design.create',
        'id-card-design.edit',
        'id-card-design.delete'
    ];
    foreach ($permissions as $p) {
        \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
    }
    $this->admin->givePermissionTo($permissions);
});

it('can store a custom uploaded ID card design template', function () {
    $this->actingAs($this->admin);
    Storage::fake('public');

    $file = UploadedFile::fake()->create('custom_design.blade.php', 100);

    $response = $this->post(route('setting.id_design.store'), [
        'theme_name' => 'Custom Design Template',
        'description' => 'A custom corporate template',
        'template_source' => 'upload',
        'design_file' => $file,
    ]);

    $response->assertRedirect(route('setting.id_design.index'));
    $response->assertSessionHas('message', 'ID Card Design created successfully');

    $design = IDCardDesign::where('theme_name', 'Custom Design Template')->first();
    expect($design)->not->toBeNull();
    expect($design->status)->toBe('inactive');
    
    // Assert file was saved in the public storage path
    Storage::disk('public')->assertExists($design->file_path);
});

it('can store a preloaded ID card design template', function () {
    $this->actingAs($this->admin);
    Storage::fake('public');

    $response = $this->post(route('setting.id_design.store'), [
        'theme_name' => 'Preloaded Theme 1',
        'description' => 'Preloaded corporate theme',
        'template_source' => 'preloaded',
        'preloaded_template' => 'design_1',
    ]);

    $response->assertRedirect(route('setting.id_design.index'));
    $response->assertSessionHas('message', 'ID Card Design created successfully');

    $design = IDCardDesign::where('theme_name', 'Preloaded Theme 1')->first();
    expect($design)->not->toBeNull();
    expect($design->status)->toBe('inactive');
    
    // Assert file was copied to public storage path
    Storage::disk('public')->assertExists($design->file_path);
});

it('fails validation when theme name is missing', function () {
    $this->actingAs($this->admin);

    $response = $this->post(route('setting.id_design.store'), [
        'template_source' => 'preloaded',
        'preloaded_template' => 'design_1',
    ]);

    $response->assertSessionHasErrors(['theme_name']);
});

it('can preview a design template', function () {
    $this->actingAs($this->admin);
    Storage::fake('public');

    // Create a design first via store
    $response = $this->post(route('setting.id_design.store'), [
        'theme_name' => 'Preview Test Theme',
        'description' => 'Preview test description',
        'template_source' => 'preloaded',
        'preloaded_template' => 'design_1',
    ]);

    $design = IDCardDesign::where('theme_name', 'Preview Test Theme')->first();
    expect($design)->not->toBeNull();

    // Request preview
    $previewResponse = $this->get(route('setting.id_design.preview', $design->id));
    $previewResponse->assertStatus(200);
});

it('can display the edit page', function () {
    $this->actingAs($this->admin);
    Storage::fake('public');

    $response = $this->post(route('setting.id_design.store'), [
        'theme_name' => 'Edit Test Theme',
        'description' => 'Original description',
        'template_source' => 'preloaded',
        'preloaded_template' => 'design_1',
    ]);

    $design = IDCardDesign::where('theme_name', 'Edit Test Theme')->first();
    expect($design)->not->toBeNull();

    $editResponse = $this->get(route('setting.id_design.edit', $design->id));
    $editResponse->assertStatus(200);
    $editResponse->assertSee($design->theme_name);
});

it('can update an ID card design metadata and file', function () {
    $this->actingAs($this->admin);
    Storage::fake('public');

    $response = $this->post(route('setting.id_design.store'), [
        'theme_name' => 'Original Theme',
        'description' => 'Original description',
        'template_source' => 'preloaded',
        'preloaded_template' => 'design_1',
    ]);

    $design = IDCardDesign::where('theme_name', 'Original Theme')->first();
    expect($design)->not->toBeNull();
    $oldFilePath = $design->file_path;

    // Update theme name, description and keep existing file
    $updateResponse = $this->put(route('setting.id_design.update', $design->id), [
        'theme_name' => 'Updated Theme Name',
        'description' => 'Updated description text',
        'template_source' => 'keep_existing',
    ]);

    $updateResponse->assertRedirect(route('setting.id_design.index'));
    $design->refresh();
    expect($design->theme_name)->toBe('Updated Theme Name');
    expect($design->description)->toBe('Updated description text');
    expect($design->file_path)->toBe($oldFilePath);

    // Update template source to another preloaded template
    $updateFileResponse = $this->put(route('setting.id_design.update', $design->id), [
        'theme_name' => 'Updated Theme Name',
        'description' => 'Updated description text',
        'template_source' => 'preloaded',
        'preloaded_template' => 'design_2',
    ]);

    $updateFileResponse->assertRedirect(route('setting.id_design.index'));
    $design->refresh();
    expect($design->file_path)->not->toBe($oldFilePath);
    Storage::disk('public')->assertExists($design->file_path);
    Storage::disk('public')->assertMissing($oldFilePath);
});

it('can regenerate and deactivate ID cards multiple times without unique constraint error', function () {
    $this->actingAs($this->admin);
    Storage::fake('public');

    // Create active design first
    $design = IDCardDesign::create([
        'theme_name' => 'Active Test Theme',
        'file_path' => 'upload/id_card_designs/designs/active_theme.php',
        'status' => 'active'
    ]);

    // Create a mock template file in fake storage so generator doesn't fail on missing file
    Storage::disk('public')->put($design->file_path, '<html>Mock Card</html>');

    // Create employee
    $employee = \App\Models\Employee\Employee::factory()->create();

    // Generate first card
    $service = app(\App\Services\Setting\IDCardService::class);
    $card1 = $service->generateIdCard($employee);
    expect($card1)->not->toBeNull();
    expect($card1->status)->toBe('active');

    // Generate second card (regenerate) -> should deactivate first card
    $card2 = $service->generateIdCard($employee);
    expect($card2)->not->toBeNull();
    expect($card2->status)->toBe('active');
    
    // Check first card is now inactive
    $card1->refresh();
    expect($card1->status)->toBe('inactive');

    // Generate third card (regenerate) -> should delete first card (inactive) and deactivate second card (active)
    $card3 = $service->generateIdCard($employee);
    expect($card3)->not->toBeNull();
    expect($card3->status)->toBe('active');

    // Check second card is now inactive, and first card is deleted
    $card2->refresh();
    expect($card2->status)->toBe('inactive');
    expect(\App\Models\Employee\EmployeeId::find($card1->id))->toBeNull();

    // Test deactivating active card
    $deactivated = $service->deactivateIdCard($employee);
    expect($deactivated)->toBeTrue();
    $card3->refresh();
    expect($card3->status)->toBe('inactive');
});
