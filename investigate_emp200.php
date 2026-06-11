<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$empId = 200;
$cutoffDate = '2026-06-10';

echo "--- Employee 200 OT Plan Assignment ---\n";
$otPlan = \App\Models\Employee\EmployeeOtPlan::where('employee_id', $empId)->first();
if ($otPlan) {
    echo "Plan ID: {$otPlan->plan_id} | Status: {$otPlan->status} | From: {$otPlan->from} | To: {$otPlan->to}\n";
} else {
    echo "No OT Plan found for Employee 200.\n";
}

echo "\n--- Attendance Records After {$cutoffDate} ---\n";
$attendance = \App\Models\Attendance\Attendance::where('employee_id', $empId)
    ->whereDate('in_time', '>', $cutoffDate)
    ->get();

if ($attendance->isEmpty()) {
    echo "No attendance records found after {$cutoffDate}.\n";
} else {
    foreach ($attendance as $record) {
        echo "ID: {$record->id} | Date: " . \Carbon\Carbon::parse($record->in_time)->format('Y-m-d') . " | OT Mints: {$record->overtime} | OT Plan ID: {$record->ot_id}\n";
    }
}
