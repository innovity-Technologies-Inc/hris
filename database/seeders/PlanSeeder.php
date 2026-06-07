<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PlanSeeder extends Seeder
{
    private $faker;

    public function __construct()
    {
        $this->faker = Faker::create();
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        $this->seedShiftPlans();
        $this->seedMealPlans();
        $this->seedLeavePlans();
        $this->seedOTPlans();
        $this->seedRosterPlans();
        $this->seedOffDayPlans();
        $this->seedBonusPlans();
        $this->seedAllowancePlans();
        $this->seedTAPlans();
        $this->seedDAPlans();
        $this->seedDeductionPlans();
        $this->seedPenaltyPlans();
        $this->seedLeaveEncashmentPlans();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function seedLeaveEncashmentPlans()
    {
        DB::table('leave_encashment_plans')->truncate();
        $plans = [
            ['title' => 'Standard Leave Encashment', 'description' => 'Standard policy with 10 days min balance', 'encashment_basis' => 'basic', 'min_balance_to_maintain' => 10, 'max_encashable_days_per_year' => 30, 'encashment_rate' => 1.00, 'status' => 'active'],
            ['title' => 'Executive Encashment Policy', 'description' => 'Premium policy on gross salary', 'encashment_basis' => 'gross', 'min_balance_to_maintain' => 5, 'max_encashable_days_per_year' => 60, 'encashment_rate' => 1.00, 'status' => 'active'],
        ];
        foreach ($plans as $plan) {
            DB::table('leave_encashment_plans')->insert(array_merge($plan, ['created_at' => now(), 'updated_at' => now()]));
        }
    }

    private function seedPenaltyPlans()
    {
        DB::table('penalty_plans')->truncate();
        $penalties = [
            ['title' => 'Late Attendance Penalty', 'description' => 'Fixed penalty for repeated late attendance', 'penalty_amount' => 500, 'status' => 'active'],
            ['title' => 'Misconduct Penalty', 'description' => 'Penalty for minor policy violations', 'penalty_amount' => 1000, 'status' => 'active'],
            ['title' => 'Property Damage Penalty', 'description' => 'Recovery for minor office property damage', 'penalty_amount' => 2000, 'status' => 'inactive'],
        ];
        foreach ($penalties as $penalty) {
            DB::table('penalty_plans')->insert(array_merge($penalty, ['created_at' => now(), 'updated_at' => now()]));
        }
    }

    private function seedShiftPlans()
    {
        DB::table('shift_plans')->truncate();
        $shifts = [
            [
                'id' => 1,
                'name' => 'General Shift',
                'clock_in_time' => '09:00:00',
                'clock_out_time' => '18:00:00',
                'treat_as_full_day_minutes' => 480,
                'treat_as_half_day_minutes' => 240,
                'grace_time' => 15,
                'lunch_status' => 'active',
                'lunch_start_time' => '13:00:00',
                'lunch_end_time' => '14:00:00',
            ],
            [
                'id' => 2,
                'name' => 'Morning Shift',
                'clock_in_time' => '06:00:00',
                'clock_out_time' => '14:00:00',
                'treat_as_full_day_minutes' => 480,
                'treat_as_half_day_minutes' => 240,
                'grace_time' => 15,
                'breakfast_status' => 'active',
                'breakfast_start_time' => '08:00:00',
                'breakfast_end_time' => '08:30:00',
            ],
        ];

        foreach ($shifts as $shift) {
            DB::table('shift_plans')->insert(array_merge($shift, ['created_at' => now(), 'updated_at' => now()]));
        }
    }

    private function seedMealPlans()
    {
        DB::table('meal_plans')->truncate();
        $meals = [
            [
                'name' => 'Standard Lunch',
                'type' => 'lunch',
                'description' => 'Daily standard lunch for employees',
                'cost' => '100',
                'start_time' => '13:00:00',
                'end_time' => '14:00:00',
                'status' => 'active'
            ],
        ];
        foreach ($meals as $meal) {
            DB::table('meal_plans')->insert(array_merge($meal, ['created_at' => now(), 'updated_at' => now()]));
        }
    }

    private function seedLeavePlans()
    {
        DB::table('leave_plans')->truncate();
        $leaves = [
            [
                'name' => 'Casual Leave',
                'short_name' => 'CL',
                'leave_type' => 'Casual',
                'leave_limit' => 10,
                'max_no_of_days' => 3,
                'active_ind' => 'active'
            ],
        ];
        foreach ($leaves as $leave) {
            DB::table('leave_plans')->insert(array_merge($leave, ['created_at' => now(), 'updated_at' => now()]));
        }
    }

    private function seedOTPlans()
    {
        DB::table('ot_plans')->truncate();
        $ots = [
            [
                'name' => 'Standard Overtime',
                'ot_config_type' => 'Salary Based',
                'salary_rate_type' => 'Multiplier',
                'overtime_multiplier' => 1.5,
                'maximum_overtime' => 4.00,
                'status' => 'active'
            ],
        ];
        foreach ($ots as $ot) {
            DB::table('ot_plans')->insert(array_merge($ot, ['created_at' => now(), 'updated_at' => now()]));
        }
    }

    private function seedRosterPlans()
    {
        DB::table('roster_plans')->truncate();
        $rosters = [
            [
                'name' => 'Standard Roster',
                'status' => 'active',
                'first_shift_id' => 1,
                'second_shift_id' => 2,
            ],
        ];
        foreach ($rosters as $roster) {
            DB::table('roster_plans')->insert(array_merge($roster, ['created_at' => now(), 'updated_at' => now()]));
        }
    }

    private function seedOffDayPlans()
    {
        DB::table('off_day_plans')->truncate();
        $offDays = [
            [
                'name' => 'Standard OffDay',
                'status' => 'active',
                'offday_config_type' => 'Custom',
                'custom_offday_rate' => 500.00
            ],
        ];
        foreach ($offDays as $offDay) {
            DB::table('off_day_plans')->insert(array_merge($offDay, ['created_at' => now(), 'updated_at' => now()]));
        }
    }

    private function seedBonusPlans()
    {
        DB::table('bonus_plans')->truncate();
        $bonuses = [
            [
                'name' => 'Festival Bonus',
                'status' => 'active',
                'bonus_type' => 'festival',
                'bonus_config_type' => 'Salary Based',
                'salary_rate_type' => 'Multiplier',
                'multiplier' => 1.0
            ],
        ];
        foreach ($bonuses as $bonus) {
            DB::table('bonus_plans')->insert(array_merge($bonus, ['created_at' => now(), 'updated_at' => now()]));
        }
    }

    private function seedAllowancePlans()
    {
        DB::table('allowance_plans')->truncate();
        $allowances = [
            ['name' => 'Travel Allowance', 'amount' => 2000, 'status' => 'active'],
        ];
        foreach ($allowances as $allowance) {
            DB::table('allowance_plans')->insert(array_merge($allowance, ['created_at' => now(), 'updated_at' => now()]));
        }
    }

    private function seedTAPlans()
    {
        DB::table('ta_plans')->truncate();
        $tas = [
            ['name' => 'Standard TA', 'status' => 'active'],
        ];
        foreach ($tas as $ta) {
            DB::table('ta_plans')->insert(array_merge($ta, ['created_at' => now(), 'updated_at' => now()]));
        }
    }

    private function seedDAPlans()
    {
        DB::table('da_plans')->truncate();
        $das = [
            ['name' => 'Standard DA', 'status' => 'active'],
        ];
        foreach ($das as $da) {
            DB::table('da_plans')->insert(array_merge($da, ['created_at' => now(), 'updated_at' => now()]));
        }
    }

    private function seedDeductionPlans()
    {
        DB::table('deduction_plans')->truncate();
        $deductions = [
            [
                'late_deduction_days' => 3,
                'late_salary_deduction_rate' => 0.5,
                'early_out_deduction_days' => 3,
                'early_out_salary_deduction_rate' => 0.5,
                'calculation_type' => 'basic_salary'
            ],
        ];
        foreach ($deductions as $deduction) {
            DB::table('deduction_plans')->insert(array_merge($deduction, ['created_at' => now(), 'updated_at' => now()]));
        }
    }
}
