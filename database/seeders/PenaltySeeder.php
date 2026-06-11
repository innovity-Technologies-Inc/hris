<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Carbon\Carbon;

class PenaltySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('employee_penalties')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = Faker::create();
        $employees = DB::table('employees')->pluck('id')->toArray();
        $penaltyPlans = DB::table('penalty_plans')->get();

        if (empty($employees) || $penaltyPlans->isEmpty()) {
            return;
        }

        $startDate = Carbon::now()->subMonths(4)->startOfMonth();
        $endDate = Carbon::now();

        $penaltiesData = [];
        
        // Let's assign penalties to about 20% of employees
        $targetEmployeesCount = (int)(count($employees) * 0.20);
        $selectedEmployees = $faker->randomElements($employees, $targetEmployeesCount);

        foreach ($selectedEmployees as $employeeId) {
            // Give each selected employee 1 to 3 penalties over the 4 months
            $numPenalties = rand(1, 3);
            
            for ($i = 0; $i < $numPenalties; $i++) {
                $plan = $faker->randomElement($penaltyPlans);
                
                $penaltiesData[] = [
                    'employee_id' => $employeeId,
                    'penalty_plan_id' => $plan->id,
                    'occurrence_date' => $faker->dateTimeBetween($startDate, $endDate)->format('Y-m-d'),
                    'cause' => $faker->sentence(),
                    'penalty_amount' => $plan->penalty_amount, // Use the amount defined in the plan
                    'status' => 'approved',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (count($penaltiesData) > 0) {
            DB::table('employee_penalties')->insert($penaltiesData);
        }
        
        echo "Seeded " . count($penaltiesData) . " approved penalties.\n";
    }
}
