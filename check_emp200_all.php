<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$empId = 200;
echo "--- ALL OT Plans for Employee 200 ---\n";
$otPlans = \App\Models\Employee\EmployeeOtPlan::where('employee_id', $empId)->get();
foreach ($otPlans as $p) {
    echo "ID: {$p->id} | PlanID: {$p->plan_id} | Status: {$p->status} | From: {$p->from} | To: {$p->to}\n";
}

echo "\n--- Attendance Records for Employee 200 ---\n";
$attendance = \App\Models\Attendance\Attendance::where('employee_id', $empId)->get();
foreach ($attendance as $record) {
    echo "ID: {$record->id} | In: {$record->in_time} | OT Mints: {$record->overtime} | OT ID: {$record->ot_id} | Shift ID: {$record->shift_id}\n";
}
