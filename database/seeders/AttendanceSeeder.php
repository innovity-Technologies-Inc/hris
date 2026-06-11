<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Log;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('attendance')->truncate();
        
        // The user requested each employee have an overtime plan assigned.
        // We'll clear existing assignments to ensure consistency during this "perfect" seed.
        DB::table('employee_ot_plans')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $employees = DB::table('employees')->pluck('id')->toArray();
        $shiftPlans = DB::table('shift_plans')->get()->keyBy('id');
        $otPlans = DB::table('ot_plans')->get();
        $otPlanIds = $otPlans->pluck('id')->toArray();
        $offDayPlans = DB::table('off_day_plans')->get()->keyBy('id');
        $rosterPlans = DB::table('roster_plans')->get()->keyBy('id');

        $startDate = Carbon::now()->subMonths(6)->startOfMonth();
        $endDate = Carbon::now();
        $period = CarbonPeriod::create($startDate, $endDate);

        // 1. Assign OT Plans to ALL employees with effective dates
        $employeeOtPlans = [];
        foreach ($employees as $employeeId) {
            if (empty($otPlanIds)) break;
            
            $employeeOtPlans[] = [
                'employee_id' => $employeeId,
                'plan_id' => $otPlanIds[array_rand($otPlanIds)],
                'from' => $startDate->copy()->subMonths(3)->format('Y-m-d'),
                'to' => $endDate->copy()->addYear()->format('Y-m-d'),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        if (!empty($employeeOtPlans)) {
            DB::table('employee_ot_plans')->insert($employeeOtPlans);
        }

        // Fetch everything back for fast lookup in the main loop
        $employeeOts = DB::table('employee_ot_plans')->get()->groupBy('employee_id');
        $employeeShifts = DB::table('employee_shift_plans')->get()->groupBy('employee_id');
        $employeeRosters = DB::table('employee_roster_plans')->get()->groupBy('employee_id');
        $employeeOffDays = DB::table('employee_offday_plans')->get()->groupBy('employee_id');
        $employeeOfficeInfos = DB::table('employee_office_infos')->get()->keyBy('employee_id');

        $attendanceData = [];
        $batchSize = 500;

        foreach ($employees as $employeeId) {
            $shiftId = null;
            $rosterId = null;
            
            if (isset($employeeShifts[$employeeId]) && $employeeShifts[$employeeId]->isNotEmpty()) {
                $shiftId = $employeeShifts[$employeeId]->first()->plan_id;
            } elseif (isset($employeeRosters[$employeeId]) && $employeeRosters[$employeeId]->isNotEmpty()) {
                $rosterId = $employeeRosters[$employeeId]->first()->plan_id;
            } else {
                $shiftId = 1; // Default fallback
            }
            
            $otId = isset($employeeOts[$employeeId]) ? $employeeOts[$employeeId]->first()->plan_id : null;
            $offDayPlanId = isset($employeeOffDays[$employeeId]) ? $employeeOffDays[$employeeId]->first()->plan_id : null;
            
            $officeInfo = $employeeOfficeInfos[$employeeId] ?? null;
            $weekends = $officeInfo && $officeInfo->weekends ? json_decode($officeInfo->weekends, true) : ['Friday', 'Saturday'];

            foreach ($period as $date) {
                $isWeekend = in_array($date->format('l'), $weekends);
                $shiftType = $isWeekend ? 'Off-Day' : 'Regular';
                
                // Determine active shift for the day (considering rosters)
                $activeShiftId = $shiftId;
                if ($rosterId) {
                    $roster = $rosterPlans[$rosterId] ?? null;
                    if ($roster) {
                        $weekNum = $date->weekOfYear;
                        if ($roster->swapping == 'weekly') {
                            $activeShiftId = ($weekNum % 2 == 0) ? $roster->second_shift_id : $roster->first_shift_id;
                        } else {
                            $activeShiftId = $roster->first_shift_id;
                        }
                    }
                }
                
                $shift = $shiftPlans[$activeShiftId] ?? $shiftPlans->first();
                if (!$shift) continue;

                $statusChance = rand(1, 100);
                
                // Handling Off-Day Work
                if ($isWeekend) {
                    if (!$offDayPlanId || $statusChance > 20) { // 20% chance to work on weekend if they have a plan
                        continue;
                    }
                }
                
                // Handling Absents
                if (!$isWeekend && $statusChance <= 2) { // 2% chance of being absent
                    $attendanceData[] = [
                        'employee_id' => $employeeId,
                        'attendance_status' => 'Absent',
                        'in_time' => null,
                        'in_status' => null,
                        'out_time' => null,
                        'out_status' => null,
                        'shift_type' => 'Regular',
                        'working_time' => 0,
                        'late_count' => 0,
                        'early_out_count' => 0,
                        'overtime' => 0,
                        'workstation' => 'On-Site',
                        'shift_id' => $activeShiftId,
                        'ot_id' => null,
                        'offday_id' => null,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ];
                } else {
                    // Successful presence
                    $clockIn = Carbon::parse($date->format('Y-m-d') . ' ' . $shift->clock_in_time);
                    $clockOut = Carbon::parse($date->format('Y-m-d') . ' ' . $shift->clock_out_time);
                    if ($clockOut < $clockIn) {
                        $clockOut->addDay();
                    }
                    
                    $actualIn = clone $clockIn;
                    $actualOut = clone $clockOut;
                    $inStatus = 'On-Time';
                    $outStatus = 'On-Time';
                    $lateCount = 0;
                    $earlyOutCount = 0;

                    // Random lateness/early exit for regular workdays
                    if (!$isWeekend) {
                        if ($statusChance > 98) { 
                            $actualIn->addMinutes(rand(31, 120));
                            $inStatus = 'Excessive-Late';
                            $lateCount = 1;
                        } elseif ($statusChance > 92) { 
                            $actualIn->addMinutes(rand(16, 30));
                            $inStatus = 'Late';
                            $lateCount = 1;
                        } else { 
                            $actualIn->addMinutes(rand(-20, 10)); // Can be early too
                        }

                        if ($statusChance > 85 && $statusChance <= 90) { 
                            $actualOut->subMinutes(rand(16, 60));
                            $outStatus = 'Early-Exit';
                            $earlyOutCount = 1;
                        } else {
                            $actualOut->addMinutes(rand(0, 15));
                        }
                    } else {
                        // On weekends, usually punctual or early
                        $actualIn->addMinutes(rand(-10, 5));
                        $actualOut->addMinutes(rand(0, 10));
                    }

                    // GENERATE OVERTIME
                    $overtimeMinutes = 0;
                    $appliedOtId = null;
                    
                    // 40% chance of doing OT if plan is active
                    if ($otId && $statusChance <= 40) {
                        $otHours = rand(1, 4); // Random 1-4 hours
                        $actualOut->addHours($otHours);
                        $overtimeMinutes = $otHours * 60;
                        $appliedOtId = $otId;
                    }

                    $workingMinutes = abs($actualOut->diffInMinutes($actualIn));
                    $appliedOffDayId = $isWeekend ? $offDayPlanId : null;

                    $attendanceData[] = [
                        'employee_id' => $employeeId,
                        'in_time' => $actualIn,
                        'in_status' => $inStatus,
                        'out_time' => $actualOut,
                        'out_status' => $outStatus,
                        'shift_type' => $shiftType,
                        'working_time' => $workingMinutes,
                        'late_count' => $lateCount,
                        'early_out_count' => $earlyOutCount,
                        'overtime' => $overtimeMinutes,
                        'attendance_status' => 'Present',
                        'workstation' => 'On-Site',
                        'shift_id' => $activeShiftId,
                        'ot_id' => $appliedOtId,
                        'offday_id' => $appliedOffDayId,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ];
                }

                // Batch insert for performance
                if (count($attendanceData) >= $batchSize) {
                    DB::table('attendance')->insert($attendanceData);
                    $attendanceData = [];
                }
            }
        }

        // Final batch
        if (count($attendanceData) > 0) {
            DB::table('attendance')->insert($attendanceData);
        }
    }
}
