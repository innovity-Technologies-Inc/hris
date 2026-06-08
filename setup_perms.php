<?php
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

$perm = Permission::firstOrCreate(['name' => 'audit-logs.view', 'guard_name' => 'web']);
$role = Role::where('name', 'Admin')->first();
if ($role) {
    $role->givePermissionTo($perm);
}
echo "Permission audit-logs.view added.\n";