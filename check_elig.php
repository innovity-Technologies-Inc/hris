<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$empId = 200;
echo "--- ALL Eligibility Records for Employee 200 ---\n";
$elig = \App\Models\Employee\EmployeeEligiblePlan::where('employee_id', $empId)->get();
foreach($elig as $e) {
    echo "OT Status: {$e->ot_plan_status} | OT From: {$e->ot_plan_from}\n";
}

echo "\n--- ALL OT Plans (Link Table) for Employee 200 ---\n";
$otLinks = \App\Models\Employee\EmployeeOtPlan::where('employee_id', $empId)->get();
foreach($otLinks as $l) {
    echo "ID: {$l->id} | PlanID: {$l->plan_id} | Status: {$l->status} | From: {$l->from} | To: {$l->to}\n";
}
