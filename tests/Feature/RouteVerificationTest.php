<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Artisan;

beforeEach(function () {
    // Seed general setting to avoid 500 error in views
    \App\Models\GeneralSetting::updateOrCreate(['id' => 1], [
        'name' => 'Test Company',
        'company_name' => 'Test Company',
        'branch_status' => 1,
        'currency' => 'USD',
    ]);
});

test('plan routes return 200 ok', function (string $routeName) {
    $user = User::factory()->create();
    
    // Dynamically create the permission if it doesn't exist to satisfy middleware
    // Note: The middleware might check for specific permissions.
    // We can just bypass the middleware by acting as a user and perhaps disabling it if needed,
    // but better to just give the permission.
    
    $this->actingAs($user);

    // Some routes might need parameters, but 'index' and 'create' usually don't.
    $response = $this->get(route($routeName));

    if ($response->status() === 403) {
        // If forbidden, try to add the permission and retry
        // This is a bit hacky but ensures we test the route accessibility
        $permissionName = getPermissionForRoute($routeName);
        if ($permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
            $user->givePermissionTo($permissionName);
            $response = $this->get(route($routeName));
        }
    }

    $response->assertStatus(200);
    $response->assertDontSee('404');
})->with([
    'plans.shift_plans.index',
    'plans.shift_plans.create',
    'plans.leave_plans.index',
    'plans.leave_plans.create',
    'plans.ot_plans.index',
    'plans.ot_plans.create',
    'plans.roster_plans.index',
    'plans.roster_plans.create',
    'plans.off_day_plans.index',
    'plans.off_day_plans.create',
    'plans.bonus_plans.index',
    'plans.bonus_plans.create',
    'plans.allowance_plans.index',
    'plans.allowance_plans.create',
    'organization-structure.index',
    'organization-structure.create',
    'settings.id_design.index',
    'settings.id_design.create',
]);

function getPermissionForRoute(string $routeName): ?string {
    $map = [
        'plans.shift_plans.index' => 'shift-plans.view',
        'plans.shift_plans.create' => 'shift-plans.create',
        'plans.leave_plans.index' => 'leave-plans.view',
        'plans.leave_plans.create' => 'leave-plans.create',
        'plans.ot_plans.index' => 'ot-plans.view',
        'plans.ot_plans.create' => 'ot-plans.create',
        'plans.roster_plans.index' => 'roster-plans.view',
        'plans.roster_plans.create' => 'roster-plans.create',
        'plans.off_day_plans.index' => 'off-day-work-plans.view',
        'plans.off_day_plans.create' => 'off-day-work-plans.create',
        'plans.bonus_plans.index' => 'bonus-plans.view',
        'plans.bonus_plans.create' => 'bonus-plans.create',
        'plans.allowance_plans.index' => 'allowance-plans.view',
        'plans.allowance_plans.create' => 'allowance-plans.create',
        'organization-structure.index' => 'members.view',
        'organization-structure.create' => 'members.create',
        'settings.id_design.index' => 'id-card-design.view',
        'settings.id_design.create' => 'id-card-design.create',
    ];

    return $map[$routeName] ?? null;
}
