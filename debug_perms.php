<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\Permission\Models\Role;
use App\Models\User;

echo "--- ROLES AND PERMISSIONS ---" . PHP_EOL;
foreach(Role::all() as $r) {
    echo "Role: " . $r->name . PHP_EOL;
    echo "Permissions: " . implode(', ', $r->permissions->pluck('name')->toArray()) . PHP_EOL . PHP_EOL;
}

echo "--- USERS AND ROLES ---" . PHP_EOL;
foreach(User::all() as $u) {
    echo "User: " . $u->name . " (" . $u->email . ") - Type: " . $u->user_type . PHP_EOL;
    echo "Roles: " . implode(', ', $u->getRoleNames()->toArray()) . PHP_EOL;
    echo "Direct Permissions: " . implode(', ', $u->getPermissionNames()->toArray()) . PHP_EOL . PHP_EOL;
}
