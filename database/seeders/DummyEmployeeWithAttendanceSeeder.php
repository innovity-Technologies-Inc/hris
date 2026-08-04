<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class DummyEmployeeWithAttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $companyId     = 1;
        $locationId    = 1;
        $divisionId    = 1;
        $departmentId  = 1;
        $sectionId     = 561;
        $designationId = 601;
        $shiftId       = 1;
        $offDayPlanId  = 4;

        $employeeRole = Role::where('name', 'Employee')->first();

        // ─── 1. User ──────────────────────────────────────────────────
        $userId = DB::table('users')->insertGetId([
            'name'       => 'John Doe',
            'email'      => 'johndoe@example.com',
            'user_type'  => 'employee',
            'status'     => 'active',
            'password'   => Hash::make('Password@123'),
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        // ─── 2. Employee ──────────────────────────────────────────────
        $employeeId = DB::table('employees')->insertGetId([
            'user_id'             => $userId,
            'applicant_id'        => 'APP-EMP-001',
            'system_id'           => 'SYS-EMP-001',
            'punch_card_no'       => 'PC-EMP-001',
            'first_name'          => 'John',
            'last_name'           => 'Doe',
            'full_name'           => 'John Doe',
            'father_name'         => 'James Doe',
            'mother_name'         => 'Jane Doe',
            'gender'              => 'Male',
            'religion'            => 'Islam',
            'nationality'         => 'Bangladeshi',
            'date_of_birth'       => '1995-03-20',
            'personal_mobile'     => '01900000001',
            'work_email'          => 'johndoe@example.com',
            'status'              => 'active',
            'general_info_status' => 'active',
            'present_address'     => json_encode([
                'line_1'      => '12 Mirpur Road',
                'village'     => 'Mirpur',
                'post_office' => 'Mirpur PO',
                'district'    => 'Dhaka',
                'division'    => 'Dhaka',
                'zip_code'    => '1216',
                'state'       => 'Dhaka',
                'country'     => 'Bangladesh',
            ]),
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        // ─── 3. Link employee_id back to user ────────────────────────
        DB::table('users')->where('id', $userId)->update(['employee_id' => $employeeId]);

        // ─── 4. Office info ───────────────────────────────────────────
        DB::table('employee_office_infos')->insert([
            'employee_id'              => $employeeId,
            'emp_type'                 => 'permanent',
            'joining_company_id'       => $companyId,
            'joining_business_unit_id' => $locationId,
            'joining_division_id'      => $divisionId,
            'joining_department_id'    => $departmentId,
            'joining_section_id'       => $sectionId,
            'joining_designation_id'   => $designationId,
            'date_of_join'             => '2023-01-15',
            'current_company_id'       => $companyId,
            'current_business_unit_id' => $locationId,
            'current_division_id'      => $divisionId,
            'current_department_id'    => $departmentId,
            'current_section_id'       => $sectionId,
            'current_designation_id'   => $designationId,
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        // ─── 5. Salary breakdown ──────────────────────────────────────
        DB::table('employee_salary_breakdowns')->insert([
            'employee_id'                    => $employeeId,
            'basic_salary'                   => 20000,
            'house_allowance'                => 8000,
            'transport_allowance'            => 3000,
            'food_allowance'                 => 2000,
            'medical_allowance'              => 2000,
            'other_earnings'                 => 0,
            'basic_salary_percentage'        => 57,
            'house_allowance_percentage'     => 23,
            'transport_allowance_percentage' => 9,
            'food_allowance_percentage'      => 6,
            'medical_allowance_percentage'   => 6,
            'other_earnings_percentage'      => 0,
            'gross_salary'                   => 35000,
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        // ─── 6. Assign Employee role ──────────────────────────────────
        if ($employeeRole) {
            DB::table('model_has_roles')->insertOrIgnore([
                'role_id'    => $employeeRole->id,
                'model_type' => 'App\Models\User',
                'model_id'   => $userId,
            ]);
        }

        // ─── 7. Assign shift plan ─────────────────────────────────────
        DB::table('employee_shift_plans')->insertOrIgnore([
            'employee_id' => $employeeId,
            'plan_id'     => $shiftId,
            'from'        => '2026-08-01',
            'to'          => null,
            'status'      => 'active',
            'created_at'  => now(),
            'updated_at'  => now(),
            'created_by'  => 1,
            'updated_by'  => 1,
        ]);

        // ─── 8. Assign off day plan ───────────────────────────────────
        DB::table('employee_offday_plans')->insertOrIgnore([
            'employee_id' => $employeeId,
            'plan_id'     => $offDayPlanId,
            'from'        => '2026-08-01',
            'to'          => null,
            'status'      => 'active',
            'created_at'  => now(),
            'updated_at'  => now(),
            'created_by'  => 1,
            'updated_by'  => 1,
        ]);

        $this->command->info("✅ Created employee: John Doe (User ID: {$userId}, Employee ID: {$employeeId})");

        // ─── 9. August 2026 Attendance ────────────────────────────────
        $offDayNames = ['Friday', 'Saturday'];

        // Cycling pattern for working days
        $patterns = [
            'present',
            'late',
            'late',
            'excessive_late',
            'early_out',
            'late_early_out',
            'overtime',
            'absent',
            'present',
            'late',
        ];

        $records        = [];
        $workingDayIdx  = 0;

        for ($day = 1; $day <= 31; $day++) {
            $date    = Carbon::create(2026, 8, $day);
            $dayName = $date->format('l');

            // ── Off day ──
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
                    'offday_id'         => $offDayPlanId,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                    'created_by'        => 1,
                    'updated_by'        => 1,
                ];
                continue;
            }

            $pattern = $patterns[$workingDayIdx % count($patterns)];
            $workingDayIdx++;

            switch ($pattern) {
                case 'late':
                    $lateMin = ($workingDayIdx % 2 === 0) ? 18 : 25;
                    $in  = $date->copy()->setTimeFromTimeString('09:00:00')->addMinutes($lateMin);
                    $out = $date->copy()->setTimeFromTimeString('18:00:00');
                    $records[] = $this->row($employeeId, $shiftId, $in, 'Late', $out, 'On-Time', 'Regular', $lateMin, 0, 0, 'late');
                    break;

                case 'excessive_late':
                    $in  = $date->copy()->setTimeFromTimeString('10:05:00');
                    $out = $date->copy()->setTimeFromTimeString('18:00:00');
                    $records[] = $this->row($employeeId, $shiftId, $in, 'Excessive-Late', $out, 'On-Time', 'Regular', 65, 0, 0, 'excessive_late');
                    break;

                case 'early_out':
                    $in  = $date->copy()->setTimeFromTimeString('09:02:00');
                    $out = $date->copy()->setTimeFromTimeString('17:20:00');
                    $records[] = $this->row($employeeId, $shiftId, $in, 'On-Time', $out, 'Early-Exit', 'Regular', 0, 40, 0, 'early_out');
                    break;

                case 'late_early_out':
                    $in  = $date->copy()->setTimeFromTimeString('09:22:00');
                    $out = $date->copy()->setTimeFromTimeString('17:45:00');
                    $records[] = $this->row($employeeId, $shiftId, $in, 'Late', $out, 'Early-Exit', 'Regular', 22, 15, 0, 'late');
                    break;

                case 'overtime':
                    $in  = $date->copy()->setTimeFromTimeString('09:00:00');
                    $out = $date->copy()->setTimeFromTimeString('21:00:00');
                    $records[] = $this->row($employeeId, $shiftId, $in, 'On-Time', $out, 'On-Time', 'Regular', 0, 0, 180, 'overtime');
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
                    $offset = ($workingDayIdx % 3); // 0-2 min variation
                    $in  = $date->copy()->setTimeFromTimeString('09:00:00')->addMinutes($offset);
                    $out = $date->copy()->setTimeFromTimeString('18:00:00');
                    $records[] = $this->row($employeeId, $shiftId, $in, 'On-Time', $out, 'On-Time', 'Regular', 0, 0, 0, 'present');
                    break;
            }
        }

        // Insert all attendance records
        foreach (array_chunk($records, 10) as $chunk) {
            DB::table('attendance')->insert($chunk);
        }

        $this->command->info("✅ Inserted " . count($records) . " attendance records for August 2026");
        $summary = array_count_values(array_column($records, 'attendance_status'));
        ksort($summary);
        foreach ($summary as $status => $count) {
            $this->command->line("   {$status}: {$count} day(s)");
        }
    }

    private function row(
        int $empId, int $shiftId,
        Carbon $inTime, string $inStatus,
        Carbon $outTime, string $outStatus,
        string $shiftType,
        int $lateCount, int $earlyOut, int $overtime,
        string $attStatus
    ): array {
        return [
            'employee_id'       => $empId,
            'in_time'           => $inTime->toDateTimeString(),
            'in_status'         => $inStatus,
            'out_time'          => $outTime->toDateTimeString(),
            'out_status'        => $outStatus,
            'shift_type'        => $shiftType,
            'working_time'      => $inTime->diffInMinutes($outTime),
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
