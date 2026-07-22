<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movement\EmployeeMovement;
use App\Models\Employee\Employee;
use App\Models\Plan\TAPlan;
use App\Models\Plan\DAPlan;
use Carbon\Carbon;

class EmployeeMovementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Fetch or create travel plans
        $taPlan = TAPlan::firstOrCreate(
            ['name' => 'Standard Travel Plan'],
            [
                'short_name' => 'STD_TA',
                'remuneration' => 12.50,
                'status' => 'active'
            ]
        );

        $daPlan = DAPlan::firstOrCreate(
            ['name' => 'Standard Daily Plan'],
            [
                'short_name' => 'STD_DA',
                'remuneration' => 150.00,
                'status' => 'active'
            ]
        );

        // 2. Fetch some employees
        $employees = Employee::limit(10)->get();
        if ($employees->isEmpty()) {
            // Create a default employee if none exists
            $employee = Employee::create([
                'first_name' => 'Test',
                'last_name' => 'Employee',
                'full_name' => 'Test Employee',
                'father_name' => 'Father',
                'mother_name' => 'Mother',
                'nationality' => 'Bangladeshi',
                'religion' => 'Islam',
                'present_address' => json_encode(['address' => 'Dhaka']),
                'personal_mobile' => '01799999999',
                'date_of_birth' => '1990-01-01',
                'system_id' => 'SYS999',
                'applicant_id' => 'APP999',
                'punch_card_no' => 'P999',
                'gender' => 'Male',
                'marital_status' => 'Single',
                'blood_group' => 'O+',
                'status' => 'active',
            ]);
            $employees = collect([$employee]);
        }

        // 3. Create at least 50 travel movements
        for ($i = 1; $i <= 55; $i++) {
            $employee = $employees[$i % $employees->count()];
            EmployeeMovement::create([
                'employee_id' => $employee->id,
                'from_date' => Carbon::now()->addDays($i)->setHour(9)->setMinute(0)->format('Y-m-d H:i:s'),
                'to_date' => Carbon::now()->addDays($i)->setHour(18)->setMinute(0)->format('Y-m-d H:i:s'),
                'source_address' => "Office Hub " . (($i % 3) + 1),
                'source_lat' => 23.8103 + ($i * 0.001),
                'source_lng' => 90.4125 + ($i * 0.001),
                'destination_address' => "On-site Client Location " . $i,
                'dest_lat' => 23.8203 + ($i * 0.001),
                'dest_lng' => 90.4225 + ($i * 0.001),
                'distance' => 5 + ($i * 1.5),
                'ta_plan_id' => $taPlan->id,
                'da_plan_id' => $daPlan->id,
                'total_days' => 1,
                'total_ta' => 50 + ($i * 10),
                'total_da' => 150,
                'total_allowance' => 200 + ($i * 10),
                'reason' => "Client support visit #{$i} and system audit",
                'status' => $i % 3 === 0 ? 'approved' : ($i % 3 === 1 ? 'pending' : 'rejected'),
                'payment_status' => $i % 2 === 0 ? 'paid' : 'unpaid'
            ]);
        }
    }
}
