<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

$permissions = [
    'approval-workflows.view',
    'approval-workflows.create',
    'approval-workflows.edit',
    'approval-workflows.delete',
    'expense-types.view',
    'expense-types.create',
    'expense-types.edit',
    'expense-types.delete',
    'claim-expenses.view',
    'claim-expenses.create',
    'claim-expenses.delete',
];

foreach ($permissions as $permission) {
    Permission::firstOrCreate(['name' => $permission]);
}

$superAdmin = Role::where('name', 'Super Admin')->first();
if ($superAdmin) {
    $superAdmin->givePermissionTo($permissions);
    echo "Permissions attached to Super Admin.\n";
} else {
    echo "Super Admin role not found.\n";
}
