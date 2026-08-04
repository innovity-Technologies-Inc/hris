<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AugustAttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $employeeId = 9;
        $shiftId    = 1;

        // Assign shift plan if not yet assigned
        if (!DB::table('employee_shift_plans')->where('employee_id', $employeeId)->exists()) {
            DB::table('employee_shift_plans')->insert([
                'employee_id' => $employeeId,
                'plan_id'     => 1,
                'from'        => '2026-08-01',
                'to'          => null,
                'status'      => 'active',
                'created_at'  => now(),
                'updated_at'  => now(),
                'created_by'  => 1,
                'updated_by'  => 1,
            ]);
        }

        // Assign off day plan if not yet assigned
        if (!DB::table('employee_offday_plans')->where('employee_id', $employeeId)->exists()) {
            DB::table('employee_offday_plans')->insert([
                'employee_id' => $employeeId,
                'plan_id'     => 4,
                'from'        => '2026-08-01',
                'to'          => null,
                'status'      => 'active',
                'created_at'  => now(),
                'updated_at'  => now(),
                'created_by'  => 1,
                'updated_by'  => 1,
            ]);
        }

        // Off days based on plan (Friday & Saturday for Bangladesh)
        $offDayNames = ['Friday', 'Saturday'];

        // Enum values:
        // in_status:  'On-Time', 'Late', 'Excessive-Late'
        // out_status: 'On-Time', 'Early-Exit'
        // shift_type: 'Regular', 'Roster', 'paid-off', 'comp-off'

        // Clear existing August records for this employee
        DB::table('attendance')
            ->where('employee_id', $employeeId)
            ->whereYear('in_time', 2026)
            ->whereMonth('in_time', 8)
            ->delete();
        // Also delete off_day records (no in_time)
        DB::table('attendance')
            ->where('employee_id', $employeeId)
            ->where('attendance_status', 'off_day')
            ->whereNull('in_time')
            ->delete();

        $records = [];

        // Pattern cycling for variety across working days
        $workingDayIndex = 0;
        $patterns = [
            'present',          // 0: On time, normal
            'late',             // 1: Late (within grace 15min)
            'late',             // 2: Late (beyond grace, not excessive)
            'excessive_late',   // 3: Excessive late
            'early_out',        // 4: Early exit
            'late_early_out',   // 5: Late + Early exit
            'overtime',         // 6: Overtime
            'absent',           // 7: Absent
            'present',          // 8: Normal full day
        ];

        for ($day = 1; $day <= 31; $day++) {
            $date    = Carbon::create(2026, 8, $day);
            $dayName = $date->format('l');

            // Off day
            if (in_array($dayName, $offDayNames)) {
                $records[] = [
                    'employee_id'       => $employeeId,
                    'in_time'           => null,
                    'in_status'         => null,
                    'out_time'          => null,
                    'out_status'        => null,
                    'shift_type'        => 'paid-off',
                    'working_time'      => 0,
                    'late_count'        => 0,
                    'early_out_count'   => 0,
                    'overtime'          => 0,
                    'attendance_status' => 'off_day',
                    'workstation'       => null,
                    'shift_id'          => $shiftId,
                    'ot_id'             => null,
                    'offday_id'         => 4,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                    'created_by'        => 1,
                    'updated_by'        => 1,
                ];
                continue;
            }

            $pattern = $patterns[$workingDayIndex % count($patterns)];
            $workingDayIndex++;

            switch ($pattern) {
                case 'late': // Late by 12–28 min
                    $lateMin = ($workingDayIndex % 2 === 0) ? 12 : 28;
                    $inTime  = $date->copy()->setTimeFromTimeString('09:00:00')->addMinutes($lateMin);
                    $outTime = $date->copy()->setTimeFromTimeString('18:00:00');
                    $records[] = $this->makeRecord($employeeId, $shiftId, $inTime, 'Late', $outTime, 'On-Time', 'Regular', $lateMin, 0, 0, 'late');
                    break;

                case 'excessive_late':
                    $inTime  = $date->copy()->setTimeFromTimeString('09:45:00');
                    $outTime = $date->copy()->setTimeFromTimeString('18:00:00');
                    $records[] = $this->makeRecord($employeeId, $shiftId, $inTime, 'Excessive-Late', $outTime, 'On-Time', 'Regular', 45, 0, 0, 'excessive_late');
                    break;

                case 'early_out':
                    $inTime  = $date->copy()->setTimeFromTimeString('09:05:00');
                    $outTime = $date->copy()->setTimeFromTimeString('17:30:00');
                    $records[] = $this->makeRecord($employeeId, $shiftId, $inTime, 'On-Time', $outTime, 'Early-Exit', 'Regular', 0, 30, 0, 'early_out');
                    break;

                case 'late_early_out':
                    $inTime  = $date->copy()->setTimeFromTimeString('09:20:00');
                    $outTime = $date->copy()->setTimeFromTimeString('17:40:00');
                    $records[] = $this->makeRecord($employeeId, $shiftId, $inTime, 'Late', $outTime, 'Early-Exit', 'Regular', 20, 20, 0, 'late');
                    break;

                case 'overtime':
                    $inTime  = $date->copy()->setTimeFromTimeString('09:00:00');
                    $outTime = $date->copy()->setTimeFromTimeString('20:30:00');
                    $records[] = $this->makeRecord($employeeId, $shiftId, $inTime, 'On-Time', $outTime, 'On-Time', 'Regular', 0, 0, 150, 'overtime');
                    break;

                case 'absent':
                    $records[] = [
                        'employee_id'       => $employeeId,
                        'in_time'           => null,
                        'in_status'         => null,
                        'out_time'          => null,
                        'out_status'        => null,
                        'shift_type'        => 'Regular',
                        'working_time'      => 0,
                        'late_count'        => 0,
                        'early_out_count'   => 0,
                        'overtime'          => 0,
                        'attendance_status' => 'absent',
                        'workstation'       => null,
                        'shift_id'          => $shiftId,
                        'ot_id'             => null,
                        'offday_id'         => null,
                        'created_at'        => now(),
                        'updated_at'        => now(),
                        'created_by'        => 1,
                        'updated_by'        => 1,
                    ];
                    break;

                case 'present':
                default:
                    $inOffset = rand(0, 5); // 0-5 mins after 09:00
                    $inTime   = $date->copy()->setTimeFromTimeString('09:00:00')->addMinutes($inOffset);
                    $outTime  = $date->copy()->setTimeFromTimeString('18:00:00');
                    $records[] = $this->makeRecord($employeeId, $shiftId, $inTime, 'On-Time', $outTime, 'On-Time', 'Regular', 0, 0, 0, 'present');
                    break;
            }
        }

        foreach (array_chunk($records, 10) as $chunk) {
            DB::table('attendance')->insert($chunk);
        }

        $this->command->info("✅ Inserted " . count($records) . " attendance records for employee {$employeeId} (August 2026)");
        $summary = array_count_values(array_column($records, 'attendance_status'));
        ksort($summary);
        foreach ($summary as $status => $count) {
            $this->command->line("   {$status}: {$count} day(s)");
        }
    }

    private function makeRecord(
        int $empId, int $shiftId,
        \Carbon\Carbon $inTime, string $inStatus,
        \Carbon\Carbon $outTime, string $outStatus,
        string $shiftType,
        int $lateCount, int $earlyOut, int $overtime,
        string $attStatus
    ): array {
        $workingMinutes = $inTime->diffInMinutes($outTime);
        return [
            'employee_id'       => $empId,
            'in_time'           => $inTime->toDateTimeString(),
            'in_status'         => $inStatus,
            'out_time'          => $outTime->toDateTimeString(),
            'out_status'        => $outStatus,
            'shift_type'        => $shiftType,
            'working_time'      => $workingMinutes,
            'late_count'        => $lateCount,
            'early_out_count'   => $earlyOut,
            'overtime'          => $overtime,
            'attendance_status' => $attStatus,
            'workstation'       => 'On-Site',
            'shift_id'          => $shiftId,
            'ot_id'             => null,
            'offday_id'         => null,
            'created_at'        => now(),
            'updated_at'        => now(),
            'created_by'        => 1,
            'updated_by'        => 1,
        ];
    }
}
