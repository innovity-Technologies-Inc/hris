<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$p = App\Models\Employee\EmployeeOtPlan::where('employee_id', 200)->first();
if ($p) {
    echo "Employee 200 OT Plan 'To' date: " . $p->to . "\n";
} else {
    echo "No OT plan found for 200\n";
}
