<?php

use App\Models\User;
use App\Models\Company\MovementType;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create(['user_type' => \App\Enums\UserType::Group]);
    $this->withoutMiddleware();
});

it('can list movement types via AJAX', function () {
    MovementType::create(['name' => 'Promotion Type', 'status' => 'active']);
    MovementType::create(['name' => 'Demotion Type', 'status' => 'active']);

    $response = $this->actingAs($this->admin)
        ->get(route('movement_types.index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);

    $response->assertStatus(200);
    $response->assertSee('Promotion Type');
    $response->assertSee('Demotion Type');
});

it('can store a new movement type', function () {
    $data = [
        'name' => 'Transfer Type',
        'description' => 'Used for transfers',
        'status' => 'active',
    ];

    $response = $this->actingAs($this->admin)
        ->postJson(route('movement_types.store'), $data);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Movement Type saved successfully.'
        ]);

    $this->assertDatabaseHas('movement_types', [
        'name' => 'Transfer Type',
        'description' => 'Used for transfers',
        'status' => 'active',
    ]);
});

it('can edit a movement type', function () {
    $type = MovementType::create([
        'name' => 'Old Name',
        'status' => 'active'
    ]);

    $response = $this->actingAs($this->admin)
        ->getJson(route('movement_types.edit', $type->id));

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'data' => [
                'id' => $type->id,
                'name' => 'Old Name'
            ]
        ]);
});

it('can update a movement type', function () {
    $type = MovementType::create([
        'name' => 'Old Name',
        'status' => 'active'
    ]);

    $data = [
        'name' => 'Updated Name',
        'status' => 'inactive'
    ];

    $response = $this->actingAs($this->admin)
        ->putJson(route('movement_types.update', $type->id), $data);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Movement Type updated successfully.'
        ]);

    $this->assertDatabaseHas('movement_types', [
        'id' => $type->id,
        'name' => 'Updated Name',
        'status' => 'inactive',
    ]);
});

it('can delete a movement type', function () {
    $type = MovementType::create([
        'name' => 'To Delete',
        'status' => 'active'
    ]);

    $response = $this->actingAs($this->admin)
        ->deleteJson(route('movement_types.delete', $type->id));

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Movement Type deleted successfully.'
        ]);

    $this->assertDatabaseMissing('movement_types', [
        'id' => $type->id
    ]);
});
