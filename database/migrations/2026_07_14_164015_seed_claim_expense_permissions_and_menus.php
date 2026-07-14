<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Setting\Menu;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create permissions
        $permissions = [
            'expense-types.view',
            'expense-types.create',
            'expense-types.edit',
            'expense-types.delete',
            'claim-expenses.view',
            'claim-expenses.create',
            'claim-expenses.edit',
            'claim-expenses.delete',
        ];

        foreach ($permissions as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
        }

        // Assign to Super Admin
        $superAdmin = Role::where('name', 'Super Admin')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo($permissions);
        }

        // 2. Add sub-menu for Expense Types under Company Info parent menu
        $companyInfoMenu = Menu::where('name', 'Company Info')->first();
        if ($companyInfoMenu) {
            Menu::firstOrCreate([
                'parent_id' => $companyInfoMenu->id,
                'slug' => 'expense-types',
            ], [
                'name' => 'Expense Types',
                'route' => 'expense_types.index',
                'order' => 17,
            ]);
        }

        // 3. Add Claim Expense parent menu
        Menu::firstOrCreate([
            'slug' => 'claim-expenses',
        ], [
            'name' => 'Claim Expense',
            'icon' => 'dollar-sign',
            'order' => 8,
        ]);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Menu::where('slug', 'claim-expenses')->delete();
        Menu::where('slug', 'expense-types')->delete();
        Permission::whereIn('name', [
            'expense-types.view',
            'expense-types.create',
            'expense-types.edit',
            'expense-types.delete',
            'claim-expenses.view',
            'claim-expenses.create',
            'claim-expenses.edit',
            'claim-expenses.delete',
        ])->delete();

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
};
