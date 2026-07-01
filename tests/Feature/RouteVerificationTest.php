<?php

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Artisan;

beforeEach(function () {
    // Seed general setting to avoid 500 error in views
    \App\Models\Setting\GeneralSetting::updateOrCreate(['id' => 1], [
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
    'plan.shift_plans.index',
    'plan.shift_plans.create',
    'plan.leave_plans.index',
    'plan.leave_plans.create',
    'plan.ot_plans.index',
    'plan.ot_plans.create',
    'plan.roster_plans.index',
    'plan.roster_plans.create',
    'plan.off_day_plans.index',
    'plan.off_day_plans.create',
    'plan.bonus_plans.index',
    'plan.bonus_plans.create',
    'plan.allowance_plans.index',
    'plan.allowance_plans.create',
    'organization-structure.index',
    'organization-structure.create',
    'setting.id_design.index',
    'setting.id_design.create',
]);

function getPermissionForRoute(string $routeName): ?string {
    $map = [
        'plan.shift_plans.index' => 'shift-plans.view',
        'plan.shift_plans.create' => 'shift-plans.create',
        'plan.leave_plans.index' => 'leave-plans.view',
        'plan.leave_plans.create' => 'leave-plans.create',
        'plan.ot_plans.index' => 'ot-plans.view',
        'plan.ot_plans.create' => 'ot-plans.create',
        'plan.roster_plans.index' => 'roster-plans.view',
        'plan.roster_plans.create' => 'roster-plans.create',
        'plan.off_day_plans.index' => 'off-day-work-plans.view',
        'plan.off_day_plans.create' => 'off-day-work-plans.create',
        'plan.bonus_plans.index' => 'bonus-plans.view',
        'plan.bonus_plans.create' => 'bonus-plans.create',
        'plan.allowance_plans.index' => 'allowance-plans.view',
        'plan.allowance_plans.create' => 'allowance-plans.create',
        'organization-structure.index' => 'members.view',
        'organization-structure.create' => 'members.create',
        'setting.id_design.index' => 'id-card-design.view',
        'setting.id_design.create' => 'id-card-design.create',
    ];

    return $map[$routeName] ?? null;
}

