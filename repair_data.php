<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Employee\EmployeeOtPlan;
use App\Models\Attendance\Attendance;
use App\Services\Attendance\AttendanceServices;

$attendanceService = new AttendanceServices();

echo "--- Step 1: Reactivating OT Eligibility for Employee 200 ---\n";
$elig = \App\Models\Employee\EmployeeEligiblePlan::where('employee_id', 200)->first();
if ($elig) {
    $elig->update(['ot_plan_status' => 'active']);
    echo "OT Status set to 'active'\n";
}

echo "\n--- Step 2: Repairing Attendance Data ---\n";
$allAttendance = Attendance::all();
$fixedCount = 0;

foreach ($allAttendance as $record) {
    $clockIn = \Carbon\Carbon::parse($record->in_time);
    $correctOtId = $attendanceService->getOverTimeDetails($record->employee_id, $clockIn);
    
    if ($record->ot_id != $correctOtId) {
        $record->ot_id = $correctOtId;
        if ($correctOtId === null) {
            $record->overtime = 0;
        } else {
            // If it should have OT but seeder set it to 0, we can't easily recalculate without shift end time here.
            // But for now, we just want to ensure the ID is correct according to the plan range.
        }
        $record->save();
        $fixedCount++;
    }
}

echo "Repair Complete. Fixed {$fixedCount} attendance records.\n";
