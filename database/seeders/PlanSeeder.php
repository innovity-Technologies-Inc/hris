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
        $this->seedHolidays();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function seedHolidays()
    {
        DB::table('holidays')->truncate();
        $holidays = [
            ['title' => 'New Year\'s Day', 'start_date' => '2026-01-01', 'end_date' => '2026-01-01'],
            ['title' => 'Shab-e-Meraj', 'start_date' => '2026-01-16', 'end_date' => '2026-01-16'],
            ['title' => 'Shaheed Dibash (Language Martyrs\' Day)', 'start_date' => '2026-02-21', 'end_date' => '2026-02-21'],
            ['title' => 'Shab-e-Barat', 'start_date' => '2026-03-04', 'end_date' => '2026-03-04'],
            ['title' => 'Sheikh Mujibur Rahman\'s Birthday', 'start_date' => '2026-03-17', 'end_date' => '2026-03-17'],
            ['title' => 'Independence Day', 'start_date' => '2026-03-26', 'end_date' => '2026-03-26'],
            ['title' => 'Eid-ul-Fitr', 'start_date' => '2026-03-20', 'end_date' => '2026-03-22'],
            ['title' => 'Bengali New Year (Pohela Boishakh)', 'start_date' => '2026-04-14', 'end_date' => '2026-04-14'],
            ['title' => 'May Day', 'start_date' => '2026-05-01', 'end_date' => '2026-05-01'],
            ['title' => 'Buddha Purnima', 'start_date' => '2026-05-01', 'end_date' => '2026-05-01'],
            ['title' => 'Eid-ul-Adha', 'start_date' => '2026-05-27', 'end_date' => '2026-05-29'],
            ['title' => 'Ashura', 'start_date' => '2026-07-26', 'end_date' => '2026-07-26'],
            ['title' => 'National Mourning Day', 'start_date' => '2026-08-15', 'end_date' => '2026-08-15'],
            ['title' => 'Janmashtami', 'start_date' => '2026-08-27', 'end_date' => '2026-08-27'],
            ['title' => 'Eid-e-Milad-un-Nabi', 'start_date' => '2026-08-26', 'end_date' => '2026-08-26'],
            ['title' => 'Durga Puja (Dashami)', 'start_date' => '2026-10-21', 'end_date' => '2026-10-21'],
            ['title' => 'Victory Day', 'start_date' => '2026-12-16', 'end_date' => '2026-12-16'],
            ['title' => 'Christmas Day', 'start_date' => '2026-12-25', 'end_date' => '2026-12-25'],
        ];
        foreach ($holidays as $holiday) {
            DB::table('holidays')->insert(array_merge($holiday, ['status' => 'active', 'created_at' => now(), 'updated_at' => now()]));
        }
    }

    private function seedShiftPlans()
    {
        DB::table('shift_plans')->truncate();
        $shifts = [
            [
                'id' => 1,
                'name' => 'Morning Shift (General)',
                'clock_in_time' => '09:00:00',
                'clock_out_time' => '18:00:00',
                'treat_as_full_day_minutes' => 480,
                'treat_as_half_day_minutes' => 240,
                'grace_time' => 15,
                'excessive_late_after_minutes' => 30,
                'early_out_grace_minutes' => 15,
                'lunch_status' => 'active',
                'lunch_start_time' => '13:00:00',
                'lunch_end_time' => '14:00:00',
                'active_ind' => 'active',
            ],
            [
                'id' => 2,
                'name' => 'Evening Shift',
                'clock_in_time' => '14:00:00',
                'clock_out_time' => '22:00:00',
                'treat_as_full_day_minutes' => 420,
                'treat_as_half_day_minutes' => 210,
                'grace_time' => 15,
                'excessive_late_after_minutes' => 30,
                'early_out_grace_minutes' => 15,
                'dinner_status' => 'active',
                'dinner_start_time' => '20:00:00',
                'dinner_end_time' => '21:00:00',
                'active_ind' => 'active',
            ],
            [
                'id' => 3,
                'name' => 'Night Shift',
                'clock_in_time' => '22:00:00',
                'clock_out_time' => '06:00:00',
                'treat_as_full_day_minutes' => 420,
                'treat_as_half_day_minutes' => 210,
                'grace_time' => 15,
                'excessive_late_after_minutes' => 30,
                'early_out_grace_minutes' => 15,
                'snacks_status' => 'active',
                'snacks_start_time' => '02:00:00',
                'snacks_end_time' => '02:30:00',
                'active_ind' => 'active',
            ],
        ];

        foreach ($shifts as $shift) {
            DB::table('shift_plans')->insert(array_merge($shift, ['created_at' => now(), 'updated_at' => now()]));
        }
    }

    private function seedOTPlans()
    {
        DB::table('ot_plans')->truncate();
        $ots = [
            [
                'name' => 'Standard OT (1.5x)',
                'description' => 'Standard overtime at 1.5 times basic rate',
                'ot_config_type' => 'Salary Based',
                'salary_rate_type' => 'Multiplier',
                'overtime_multiplier' => 1.5,
                'maximum_overtime' => 4.00,
                'status' => 'active'
            ],
            [
                'name' => 'Premium OT (2.0x)',
                'description' => 'Double rate overtime for special requirements',
                'ot_config_type' => 'Salary Based',
                'salary_rate_type' => 'Multiplier',
                'overtime_multiplier' => 2.0,
                'maximum_overtime' => 4.00,
                'status' => 'active'
            ],
            [
                'name' => 'Fixed Rate OT',
                'description' => 'Fixed 200 BDT per hour overtime',
                'ot_config_type' => 'Custom',
                'salary_rate_type' => null,
                'custom_overtime_rate' => 200.00,
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
                'name' => '2-Shift Rotating Roster',
                'short_name' => '2SRR',
                'description' => 'Rotating between Morning and Evening shifts',
                'swapping' => 'weekly',
                'status' => 'active',
                'first_shift_id' => 1,
                'second_shift_id' => 2,
                'third_shift_id' => null,
            ],
            [
                'name' => '3-Shift Full Roster',
                'short_name' => '3SFR',
                'description' => 'Rotating between Morning, Evening and Night shifts',
                'swapping' => 'bi-weekly',
                'status' => 'active',
                'first_shift_id' => 1,
                'second_shift_id' => 2,
                'third_shift_id' => 3,
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
                'name' => 'Standard OffDay (Fixed)',
                'short_name' => 'SOF',
                'status' => 'active',
                'shift_id' => 1,
                'offday_config_type' => 'Custom',
                'salary_rate_type' => null,
                'custom_offday_rate' => 500.00
            ],
            [
                'name' => 'OffDay Multiplier (1.5x)',
                'short_name' => 'SOM',
                'status' => 'active',
                'shift_id' => 1,
                'offday_config_type' => 'Salary Based',
                'salary_rate_type' => 'Multiplier',
                'offday_multiplier' => 1.5
            ],
        ];
        foreach ($offDays as $offDay) {
            DB::table('off_day_plans')->insert(array_merge($offDay, ['created_at' => now(), 'updated_at' => now()]));
        }
    }

    private function seedBonusPlans()
    {
        DB::table('bonus_plans')->truncate();
        
        // Fetch a couple of pay groups to vary the assignments
        $payGroups = DB::table('pay_groups')->get();
        $pg1 = $payGroups->first()->id ?? null;
        $pg2 = $payGroups->count() > 1 ? $payGroups->last()->id : $pg1;
        
        $bonuses = [
            [
                'pay_group_id' => $pg1,
                'name' => 'Eid-ul-Fitr Bonus',
                'description' => '100% Basic Salary Bonus',
                'status' => 'active',
                'bonus_type' => 'festival',
                'bonus_config_type' => 'Salary Based',
                'salary_rate_type' => 'Multiplier',
                'multiplier' => 1.0,
                'custom_rate' => 0
            ],
            [
                'pay_group_id' => $pg1,
                'name' => 'Eid-ul-Adha Bonus',
                'description' => '100% Basic Salary Bonus',
                'status' => 'active',
                'bonus_type' => 'festival',
                'bonus_config_type' => 'Salary Based',
                'salary_rate_type' => 'Multiplier',
                'multiplier' => 1.0,
                'custom_rate' => 0
            ],
            [
                'pay_group_id' => $pg2,
                'name' => 'Yearly Performance Bonus',
                'description' => 'Fixed performance bonus',
                'status' => 'active',
                'bonus_type' => 'performance',
                'bonus_config_type' => 'Custom',
                'salary_rate_type' => null,
                'multiplier' => 0,
                'custom_rate' => 5000.00
            ],
            [
                'pay_group_id' => $pg2,
                'name' => 'Project Completion Incentive',
                'description' => 'Incentive for successful project delivery',
                'status' => 'active',
                'bonus_type' => 'incentive',
                'bonus_config_type' => 'Custom',
                'salary_rate_type' => null,
                'multiplier' => 0,
                'custom_rate' => 3000.00
            ],
        ];
        foreach ($bonuses as $bonus) {
            DB::table('bonus_plans')->insert(array_merge($bonus, ['created_at' => now(), 'updated_at' => now()]));
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
            [
                'name' => 'Sick Leave',
                'short_name' => 'SL',
                'leave_type' => 'Sick',
                'leave_limit' => 14,
                'max_no_of_days' => 14,
                'active_ind' => 'active'
            ],
            [
                'name' => 'Earned Leave',
                'short_name' => 'EL',
                'leave_type' => 'Earned',
                'leave_limit' => 18,
                'max_no_of_days' => 18,
                'active_ind' => 'active'
            ],
        ];
        foreach ($leaves as $leave) {
            DB::table('leave_plans')->insert(array_merge($leave, ['created_at' => now(), 'updated_at' => now()]));
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

    private function seedPenaltyPlans()
    {
        DB::table('penalty_plans')->truncate();
        $penalties = [
            [
                'title' => 'Property Damage', 
                'description' => 'Penalty for damaging company equipment or property due to negligence', 
                'penalty_amount' => 2000, 
                'status' => 'active'
            ],
            [
                'title' => 'Brawl with Colleague', 
                'description' => 'Disciplinary penalty for fighting or severe altercation with a coworker', 
                'penalty_amount' => 5000, 
                'status' => 'active'
            ],
            [
                'title' => 'Misbehave with Supervisor', 
                'description' => 'Penalty for insubordination or severe misbehavior towards management', 
                'penalty_amount' => 3000, 
                'status' => 'active'
            ],
            [
                'title' => 'Security Policy Violation', 
                'description' => 'Penalty for sharing credentials or leaving sensitive data exposed', 
                'penalty_amount' => 1500, 
                'status' => 'active'
            ],
        ];
        foreach ($penalties as $penalty) {
            DB::table('penalty_plans')->insert(array_merge($penalty, ['created_at' => now(), 'updated_at' => now()]));
        }
    }

    private function seedLeaveEncashmentPlans()
    {
        DB::table('leave_encashment_plans')->truncate();
        $plans = [
            ['title' => 'Standard Leave Encashment', 'description' => 'Standard policy', 'encashment_basis' => 'basic', 'min_balance_to_maintain' => 10, 'max_encashable_days_per_year' => 30, 'encashment_rate' => 1.00, 'status' => 'active'],
        ];
        foreach ($plans as $plan) {
            DB::table('leave_encashment_plans')->insert(array_merge($plan, ['created_at' => now(), 'updated_at' => now()]));
        }
    }
}
