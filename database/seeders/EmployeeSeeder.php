<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class EmployeeSeeder extends Seeder
{
    private $faker;
    private $orgData = [];
    private $totalEmployees = 200;

    // Bangladesh-specific data arrays
    private $bdMaleFirstNames = ['Mohammad', 'Abdul', 'Rahman', 'Karim', 'Zahid', 'Tanvir', 'Sakib', 'Rashed', 'Faruk', 'Arif',
                                  'Shahriar', 'Naim', 'Mahmud', 'Hasan', 'Hussain', 'Ali', 'Imran', 'Rakib', 'Sadiq', 'Ashraf',
                                  'Jahangir', 'Shafiq', 'Kamal', 'Jamal', 'Rafiq', 'Sharif', 'Manjur', 'Mostafa', 'Selim', 'Habib'];

    private $bdFemaleFirstNames = ['Fatema', 'Ayesha', 'Sumaiya', 'Tasnim', 'Nusrat', 'Sabrina', 'Rumana', 'Nazma', 'Sharmin', 'Farzana',
                                    'Tahmina', 'Rehana', 'Salma', 'Halima', 'Rabeya', 'Jasmine', 'Nasrin', 'Parveen', 'Shabnam', 'Munmun',
                                    'Tania', 'Sumi', 'Rita', 'Mitu', 'Lipi', 'Shilpi', 'Mousumi', 'Nipa', 'Shikha', 'Rupa'];

    private $bdLastNames = ['Hossain', 'Ahmed', 'Rahman', 'Khan', 'Islam', 'Ali', 'Haque', 'Chowdhury', 'Sarkar', 'Mia',
                            'Begum', 'Akter', 'Sheikh', 'Mallick', 'Pradhan', 'Biswas', 'Das', 'Pal', 'Ghosh', 'Roy',
                            'Sikder', 'Talukdar', 'Majumdar', 'Siddiqui', 'Alam', 'Uddin', 'Kabir', 'Zaman', 'Howlader', 'Molla'];

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
        DB::table('employees')->truncate();
        DB::table('employee_office_infos')->truncate();
        DB::table('employee_eligible_plans')->truncate();
        DB::table('employee_education_experience_training')->truncate();
        DB::table('employee_nominees')->truncate();
        DB::table('employee_salary_breakdowns')->truncate();
        DB::table('employee_bank_accounts')->truncate();
        DB::table('employee_employment_histories')->truncate();
        
        // Link tables
        DB::table('employee_shift_plans')->truncate();
        DB::table('employee_roster_plans')->truncate();
        DB::table('employee_ot_plans')->truncate();
        DB::table('employee_offday_plans')->truncate();
        DB::table('employee_meal_plans')->truncate();
        DB::table('employee_leave_plans')->truncate();
        DB::table('employee_bonus_plans')->truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->loadOrgData();
        $this->seedEmployees();
    }

    private function loadOrgData()
    {
        $this->orgData['companies'] = DB::table('companies')->pluck('id')->toArray();
        $this->orgData['company_locations_by_company'] = DB::table('company_locations')->get()->groupBy('company_id')->map->pluck('id')->toArray();
        $this->orgData['divisions_by_location'] = DB::table('divisions')->get()->groupBy('location_id')->map->pluck('id')->toArray();
        $this->orgData['departments_by_division'] = DB::table('departments')->get()->groupBy('division_id')->map->pluck('id')->toArray();
        $this->orgData['sections_by_department'] = DB::table('sections')->get()->groupBy('department_id')->map->pluck('id')->toArray();
        $this->orgData['designations'] = DB::table('designations')->pluck('id')->toArray();
        $this->orgData['salary_grades'] = DB::table('salary_grades')->pluck('id')->toArray();
        $this->orgData['pay_scales'] = DB::table('pay_scales')->pluck('id')->toArray();
        
        $branches = DB::table('branches')->select('id', 'bank_id')->get();
        $this->orgData['bank_branches'] = $branches->pluck('id')->toArray();
        $this->orgData['branch_to_bank'] = $branches->pluck('bank_id', 'id')->toArray();

        // Plans
        $this->orgData['shift_plans'] = DB::table('shift_plans')->pluck('id')->toArray();
        $this->orgData['meal_plans'] = DB::table('meal_plans')->pluck('id')->toArray();
        $this->orgData['leave_plans'] = DB::table('leave_plans')->pluck('id')->toArray();
        $this->orgData['ot_plans'] = DB::table('ot_plans')->pluck('id')->toArray();
        $this->orgData['roster_plans'] = DB::table('roster_plans')->pluck('id')->toArray();
        $this->orgData['off_day_plans'] = DB::table('off_day_plans')->pluck('id')->toArray();
        $this->orgData['bonus_plans'] = DB::table('bonus_plans')->pluck('id')->toArray();
    }

    private function seedEmployees()
    {
        for ($i = 0; $i < $this->totalEmployees; $i++) {
            $employee_id = $i + 1;

            $gender = $this->faker->randomElement(['Male', 'Female']);
            $firstName = $gender === 'Male' ? $this->faker->randomElement($this->bdMaleFirstNames) : $this->faker->randomElement($this->bdFemaleFirstNames);
            $lastName = $this->faker->randomElement($this->bdLastNames);

            // employees
            DB::table('employees')->insert($this->generateEmployeeData($employee_id, $firstName, $lastName, $gender));

            // office info
            DB::table('employee_office_infos')->insert($this->generateOfficeInfoData($employee_id));

            // eligible plans
            DB::table('employee_eligible_plans')->insert($this->generateEligiblePlansData($employee_id));

            // edu/exp/training
            DB::table('employee_education_experience_training')->insert($this->generateEducationExperienceTrainingData($employee_id));

            // nominees
            DB::table('employee_nominees')->insert($this->generateNomineeData($employee_id));

            // salary
            DB::table('employee_salary_breakdowns')->insert($this->generateSalaryBreakdownData($employee_id));

            // bank account
            DB::table('employee_bank_accounts')->insert($this->generateBankAccountData($employee_id));

            // history
            DB::table('employee_employment_histories')->insert($this->generateEmploymentHistoryData($employee_id));

            // Link Plans
            $this->linkPlans($employee_id);
        }
        echo "Seeded " . $this->totalEmployees . " employees with comprehensive data.\n";
    }

    private function generateEmployeeData($id, $firstName, $lastName, $gender): array
    {
        return [
            'id' => $id,
            'applicant_id' => 'APP' . str_pad($id, 6, '0', STR_PAD_LEFT),
            'system_id' => 'SYS' . str_pad($id, 6, '0', STR_PAD_LEFT),
            'punch_card_no' => 'PC' . str_pad($id, 6, '0', STR_PAD_LEFT),
            'first_name' => $firstName,
            'last_name' => $lastName,
            'full_name' => "$firstName $lastName",
            'father_name' => $this->faker->name('male'),
            'mother_name' => $this->faker->name('female'),
            'gender' => $gender,
            'religion' => $this->faker->randomElement(['Islam', 'Hinduism', 'Christianity', 'Buddhism']),
            'nationality' => 'Bangladeshi',
            'present_address' => json_encode([
                'line_1' => $this->faker->streetAddress,
                'village' => $this->faker->city,
                'post_office' => $this->faker->state,
                'police_station' => $this->faker->city,
                'district' => $this->faker->state,
                'division' => $this->faker->state,
                'country' => 'Bangladesh',
                'zip_code' => $this->faker->postcode,
            ]),
            'date_of_birth' => $this->faker->dateTimeBetween('-45 years', '-20 years')->format('Y-m-d'),
            'personal_mobile' => $this->faker->phoneNumber,
            'status' => 'active',
            'general_info_status' => 'active',
            'created_at' => now(), 'updated_at' => now(),
        ];
    }

    private function generateOfficeInfoData($employeeId): array
    {
        $companyId = $this->faker->randomElement($this->orgData['companies']);
        return [
            'employee_id' => $employeeId,
            'current_company_id' => $companyId,
            'current_designation_id' => $this->faker->randomElement($this->orgData['designations']),
            'date_of_join' => $this->faker->dateTimeBetween('-5 years', '-1 year')->format('Y-m-d'),
            'created_at' => now(), 'updated_at' => now(),
        ];
    }

    private function generateEligiblePlansData($employeeId): array
    {
        return [
            'employee_id' => $employeeId,
            'shift_plan_status' => 'active',
            'shift_plan_from' => now()->subYear()->format('Y-m-d'),
            'leave_plan_status' => 'active',
            'leave_plan_from' => now()->subYear()->format('Y-m-d'),
            'meal_plan_status' => 'active',
            'meal_plan_from' => now()->subYear()->format('Y-m-d'),
            'ot_plan_status' => 'active',
            'ot_plan_from' => now()->subYear()->format('Y-m-d'),
            'bonus_plan_status' => 'active',
            'bonus_plan_from' => now()->subYear()->format('Y-m-d'),
            'created_at' => now(), 'updated_at' => now(),
        ];
    }

    private function generateEducationExperienceTrainingData($employeeId): array
    {
        return [
            'employee_id' => $employeeId,
            'educations' => json_encode([[
                'education_title' => 'Bachelor of Science',
                'institute' => 'University of Dhaka',
                'group_major' => 'Computer Science',
                'board_university' => 'Dhaka Board',
                'result_grade' => 'A+',
                'passing_year' => '2015',
                'gpa_cgpa' => '3.80'
            ]]),
            'trainings' => json_encode([[
                'training_title' => 'Advanced Laravel',
                'course_name' => 'Laravel Mastery',
                'training_code' => 'LAT-101',
                'institute' => 'Tech Academy',
                'country' => 'Bangladesh',
                'location' => 'Dhaka',
                'duration' => '3 months',
                'from_date' => $this->faker->dateTimeBetween('-2 years', '-1 year')->format('Y-m-d'),
                'to_date' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            ]]),
            'status' => 'active',
            'created_at' => now(), 'updated_at' => now(),
        ];
    }

    private function generateNomineeData($employeeId): array
    {
        return [
            'employee_id' => $employeeId,
            'nominee_name' => $this->faker->name,
            'status' => 'active',
            'created_at' => now(), 'updated_at' => now(),
        ];
    }

    private function generateEmploymentHistoryData($employeeId): array
    {
        return [
            'employee_id' => $employeeId,
            'histories' => json_encode([[
                'company_name' => 'Previous Solution Ltd',
                'designation' => 'Executive Developer',
                'joining_date' => $this->faker->dateTimeBetween('-10 years', '-5 years')->format('Y-m-d'),
                'end_date' => $this->faker->dateTimeBetween('-5 years', '-1 year')->format('Y-m-d'),
                'job_description' => 'Managed enterprise systems and databases.',
                'achievements' => 'Improved system performance by 30%.'
            ]]),
            'status' => 'active',
            'created_at' => now(), 'updated_at' => now(),
        ];
    }


    private function generateSalaryBreakdownData($employeeId): array
    {
        $payScaleId = $this->faker->randomElement($this->orgData['pay_scales']);
        $payScale = DB::table('pay_scales')->where('id', $payScaleId)->first();
        
        $min = $payScale ? (float)$payScale->min_salary : 30000;
        $max = $payScale ? (float)$payScale->max_salary : 100000;
        
        $gross = $this->faker->numberBetween($min, $max);
        return [
            'employee_id' => $employeeId,
            'pay_scale_id' => $payScaleId,
            'gross_salary' => (string)$gross,
            'basic_salary' => (string)($gross * 0.6),
            'basic_salary_percentage' => '60',
            'created_at' => now(), 'updated_at' => now(),
        ];
    }

    private function generateBankAccountData($employeeId): array
    {
        $branchId = $this->faker->randomElement($this->orgData['bank_branches']);
        return [
            'employee_id' => $employeeId,
            'bank_id' => $this->orgData['branch_to_bank'][$branchId],
            'branch_id' => $branchId,
            'account_holder_name' => $this->faker->name,
            'account_number' => $this->faker->numerify('################'),
            'status' => 'active',
            'created_at' => now(), 'updated_at' => now(),
        ];
    }

    private function linkPlans($employeeId)
    {
        $from = now()->subYear()->format('Y-m-d');
        $to = now()->addYear()->format('Y-m-d');

        if (!empty($this->orgData['shift_plans'])) {
            DB::table('employee_shift_plans')->insert([
                'employee_id' => $employeeId,
                'plan_id' => $this->faker->randomElement($this->orgData['shift_plans']),
                'from' => $from, 'to' => $to, 'status' => 'active',
                'created_at' => now(), 'updated_at' => now()
            ]);
        }

        if (!empty($this->orgData['roster_plans'])) {
            DB::table('employee_roster_plans')->insert([
                'employee_id' => $employeeId,
                'plan_id' => $this->faker->randomElement($this->orgData['roster_plans']),
                'from' => $from, 'to' => $to, 'status' => 'active',
                'created_at' => now(), 'updated_at' => now()
            ]);
        }

        if (!empty($this->orgData['ot_plans'])) {
            DB::table('employee_ot_plans')->insert([
                'employee_id' => $employeeId,
                'plan_id' => $this->faker->randomElement($this->orgData['ot_plans']),
                'from' => $from, 'to' => $to, 'status' => 'active',
                'created_at' => now(), 'updated_at' => now()
            ]);
        }

        if (!empty($this->orgData['off_day_plans'])) {
            DB::table('employee_offday_plans')->insert([
                'employee_id' => $employeeId,
                'plan_id' => $this->faker->randomElement($this->orgData['off_day_plans']),
                'from' => $from, 'to' => $to, 'status' => 'active',
                'created_at' => now(), 'updated_at' => now()
            ]);
        }

        if (!empty($this->orgData['meal_plans'])) {
            DB::table('employee_meal_plans')->insert([
                'employee_id' => $employeeId,
                'plan_id' => $this->faker->randomElement($this->orgData['meal_plans']),
                'from' => $from, 'to' => $to, 'status' => 'active',
                'created_at' => now(), 'updated_at' => now()
            ]);
        }

        if (!empty($this->orgData['leave_plans'])) {
            DB::table('employee_leave_plans')->insert([
                'employee_id' => $employeeId,
                'plan_id' => $this->faker->randomElement($this->orgData['leave_plans']),
                'created_at' => now(), 'updated_at' => now()
            ]);
        }

        if (!empty($this->orgData['bonus_plans'])) {
            DB::table('employee_bonus_plans')->insert([
                'employee_id' => $employeeId,
                'plan_id' => $this->faker->randomElement($this->orgData['bonus_plans']),
                'created_at' => now(), 'updated_at' => now()
            ]);
        }
    }
}
