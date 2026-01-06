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
    // --- CHANGE MADE HERE ---
    private $totalEmployees = 2000;
    // ------------------------

    public function __construct()
    {
        $this->faker = Faker::create();
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks for clean insertion and truncation
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('employees')->truncate();
        DB::table('employee_office_infos')->truncate();
        DB::table('employee_eligible_plans')->truncate();
        DB::table('employee_education_experience_training')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->loadOrgData();
        $this->seedEmployees();
    }

    private function loadOrgData()
    {
        // Load necessary IDs from the organization/compensation seeders
        $this->orgData['companies'] = DB::table('companies')->pluck('id')->toArray();
        $this->orgData['divisions'] = DB::table('divisions')->pluck('id')->toArray();
        $this->orgData['departments'] = DB::table('departments')->pluck('id')->toArray();
        $this->orgData['sections'] = DB::table('sections')->pluck('id')->toArray();
        $this->orgData['designations'] = DB::table('designations')->pluck('id')->toArray();
        $this->orgData['tofsils'] = DB::table('tofsils')->pluck('id')->toArray();
        $this->orgData['salary_grades'] = DB::table('salary_grades')->pluck('id')->toArray();

        // Safety check to ensure dependent data exists
        if (empty($this->orgData['companies'])) {
            throw new \Exception("Cannot run EmployeeSeeder. Run LargerOrgAndBankSeeder first.");
        }
    }

    private function seedEmployees()
    {
        // Collect all data into separate arrays
        $employees = [];
        $officeInfos = [];
        $eligiblePlans = [];
        $eduExpTrainings = [];

        $employeeIdCounter = 1;

        for ($i = 0; $i < $this->totalEmployees; $i++) {
            $employee_id = $employeeIdCounter++;

            // Determine Role & Gender
            $gender = $this->faker->randomElement(['Male', 'Female']);
            $firstName = $this->faker->firstName($gender);
            $lastName = $this->faker->lastName;
            $maritalStatus = $this->faker->randomElement(['Single', 'Married', 'Divorced', 'Widowed']);
            $hasSpouse = $maritalStatus === 'Married';

            // 1. employees Table
            $employees[] = $this->generateEmployeeData($employee_id, $firstName, $lastName, $gender, $maritalStatus, $hasSpouse);

            // 2. employee_office_infos Table
            $officeInfos[] = $this->generateOfficeInfoData($employee_id);

            // 3. employee_eligible_plans Table (Will be chunked on insertion)
            $eligiblePlans[] = $this->generateEligiblePlansData($employee_id);

            // 4. employee_education_experience_training Table
            $eduExpTrainings[] = $this->generateEducationExperienceTrainingData($employee_id);
        }

        // --- INSERTION LOGIC: Chunking applied to all large tables ---
        $employeesChunkSize = 1000;
        $plansChunkSize = 500;

        // 1. Chunk and Insert employees (FIXED: Required chunking for 2000 rows x 44 columns)
        collect($employees)->chunk($employeesChunkSize)->each(function ($chunk) {
            DB::table('employees')->insert($chunk->toArray());
        });


        // 2. Insert office infos (40 columns - 2000 rows is 80,000. This must also be chunked.)
        $officeInfosChunkSize = 1000; // 40 * 1000 = 40,000
        collect($officeInfos)->chunk($officeInfosChunkSize)->each(function ($chunk) {
            DB::table('employee_office_infos')->insert($chunk->toArray());
        });


        // 3. Chunk and Insert Eligible Plans (Required due to the very large column count: 67 columns)
        collect($eligiblePlans)->chunk($plansChunkSize)->each(function ($chunk) {
            DB::table('employee_eligible_plans')->insert($chunk->toArray());
        });

        // 4. Insert Education/Experience (Should be safe, only 5 columns, but chunking for consistency is good)
        $eduExpTrainingsChunkSize = 2000; // 5 * 2000 = 10,000 (Safe)
        DB::table('employee_education_experience_training')->insert($eduExpTrainings);
    }

    private function generateEmployeeData($id, $firstName, $lastName, $gender, $maritalStatus, $hasSpouse): array
    {
        $dateOfBirth = $this->faker->dateTimeBetween('-45 years', '-20 years');
        $middleName = $this->faker->optional(0.3)->firstName($gender);

        $presentAddress = [
            'line_1' => $this->faker->streetAddress,
            'village' => $this->faker->optional(0.5)->word . ' Village',
            'post_office' => $this->faker->word . ' Post Office',
            'district' => $this->faker->city,
            'division' => $this->faker->state,
            'zip_code' => $this->faker->postcode,
            'state' => $this->faker->state,
            'country' => 'Bangladesh',
        ];

        // 70% chance permanent address is the same as present, otherwise different
        $isSameAddress = $this->faker->boolean(70);
        $permanentAddress = $isSameAddress ? $presentAddress : [
            'line_1' => $this->faker->streetAddress,
            'village' => $this->faker->optional(0.5)->word . ' Village',
            'post_office' => $this->faker->word . ' Post Office',
            'district' => $this->faker->city,
            'division' => $this->faker->state,
            'zip_code' => $this->faker->postcode,
            'state' => $this->faker->state,
            'country' => 'Bangladesh',
        ];

        // Reference address (50% chance of being included)
        $referenceAddress = $this->faker->boolean(50) ? [
            'emp_id' => 'SYS' . $this->faker->unique()->numberBetween(1000, 9999),
            'reference_name' => $this->faker->name,
            'reference_designation' => $this->faker->jobTitle,
            'phone' => $this->faker->phoneNumber,
            'mobile' => $this->faker->phoneNumber,
            'email' => $this->faker->safeEmail,
            'line_1' => $this->faker->streetAddress,
            'village' => $this->faker->optional(0.5)->word . ' Village',
            'post_office' => $this->faker->word . ' Post Office',
            'district' => $this->faker->city,
            'division' => $this->faker->state,
            'zip_code' => $this->faker->postcode,
            'state' => $this->faker->state,
            'country' => 'Bangladesh',
        ] : null;


        return [
            'id' => $id,
            'applicant_id' => 'APP' . str_pad($id, 6, '0', STR_PAD_LEFT),
            'system_id' => 'SYS' . str_pad($id, 6, '0', STR_PAD_LEFT),
            'punch_card_no' => 'PC' . $this->faker->unique()->numerify('#####'),

            'first_name' => $firstName,
            'last_name' => $lastName,
            'middle_name' => $middleName,
            'full_name' => trim("$firstName $middleName $lastName"),
            'father_name' => $this->faker->name('male'),
            'mother_name' => $this->faker->name('female'),
            'spouse_name' => $hasSpouse ? $this->faker->name($gender === 'Male' ? 'female' : 'male') : null,
            'marital_status' => $maritalStatus,
            'gender' => $gender,
            'religion' => $this->faker->randomElement(['Islam', 'Christianity', 'Hinduism', 'Buddhism']),
            'nationality' => 'Bangladeshi',
            'blood_group' => $this->faker->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            'height_feet' => $this->faker->optional(0.8)->numberBetween(5, 6),
            'height_inches' => $this->faker->optional(0.8)->numberBetween(0, 11),
            'children_count' => $hasSpouse ? $this->faker->numberBetween(0, 3) : 0,

            // --- CORRECTED ADDRESS FIELDS ---
            'present_address' => json_encode($presentAddress),
            'permanent_address' => json_encode($permanentAddress),
            'reference_address' => $referenceAddress ? json_encode($referenceAddress) : null,
            // ---------------------------------

            'tin' => $this->faker->optional(0.6)->numerify('##############'),
            'passport_no' => $this->faker->optional(0.4)->bothify('??########'),
            'passport_expiry' => $this->faker->optional(0.4)->dateTimeBetween('+1 year', '+8 years'),
            'license_no' => $this->faker->optional(0.5)->bothify('##-##-######'),
            'license_expiry' => $this->faker->optional(0.5)->dateTimeBetween('+1 year', '+5 years'),
            'visa_expiry' => $this->faker->optional(0.2)->dateTimeBetween('+1 year', '+5 years'),
            'work_expiry' => $this->faker->optional(0.2)->dateTimeBetween('+1 year', '+5 years'),
            'residency_id_number' => $this->faker->optional(0.3)->numerify('###########'),

            'date_of_birth' => $dateOfBirth->format('Y-m-d'),
            'birth_country' => $this->faker->country,
            'birth_reg_no' => $this->faker->optional(0.7)->numerify('################'),

            'personal_mobile' => $this->faker->unique()->phoneNumber,
            'home_phone' => $this->faker->optional(0.3)->phoneNumber,
            'work_mobile' => $this->faker->optional(0.7)->phoneNumber,
            'work_phone' => $this->faker->optional(0.6)->phoneNumber,
            'work_email' => strtolower(Str::slug($firstName . '.' . $lastName)) . '@corp.com',
            'personal_email' => $this->faker->unique()->safeEmail,

            'photo_path' => 'uploads/photos/' . $id . '.jpg',
            'fingerprint_path' => 'uploads/fingerprints/' . $id . '.dat',
            'signature_path' => 'uploads/signatures/' . $id . '.png',
            'experience_attachment_path' => $this->faker->optional(0.5)->word . '.pdf',

            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    private function generateOfficeInfoData($employeeId): array
    {
        $joiningDate = $this->faker->dateTimeBetween('-5 years', '-6 months');
        $isPermanent = $this->faker->boolean(70);

        // --- Randomly select linked IDs ---
        $companyId = $this->faker->randomElement($this->orgData['companies']);
        $divisionId = $this->faker->randomElement($this->orgData['divisions']);
        $departmentId = $this->faker->randomElement($this->orgData['departments']);
        $sectionId = $this->faker->randomElement($this->orgData['sections']);
        $designationId = $this->faker->randomElement($this->orgData['designations']);
        $tofsilId = $this->faker->randomElement($this->orgData['tofsils']);
        $gradeId = $this->faker->randomElement($this->orgData['salary_grades']);

        $promotionDate = (clone $joiningDate)->modify('+' . $this->faker->numberBetween(1, 3) . ' years');

        // Determine alternate day (10% chance of having one)
        $alternateDay = $this->faker->boolean(10) ? $this->faker->dayOfWeek : null;

        // FIX: Convert alternate day string into a JSON array or null
        $alternateOffDayJson = $alternateDay ? json_encode([$alternateDay]) : null;


        return [
            'employee_id' => $employeeId,
            'emp_type' => $isPermanent ? 'permanent' : 'contractual',
            'grade_id' => $gradeId,
            'hr_file_no' => 'HRF' . $this->faker->unique()->numerify('######'),
            'tofsil_id' => $tofsilId,
            'file_note' => $this->faker->optional(0.1)->sentence(5),

            // Joining Information
            'joining_company_id' => $companyId,
            'joining_business_unit_id' => $companyId,
            'joining_division_id' => $divisionId,
            'joining_department_id' => $departmentId,
            'joining_section_id' => $sectionId,
            'joining_designation_id' => $designationId,
            'date_of_join' => $joiningDate->format('Y-m-d'),

            // Current Posting Information
            'current_company_id' => $companyId,
            'current_business_unit_id' => $companyId,
            'current_division_id' => $divisionId,
            'current_department_id' => $departmentId,
            'current_section_id' => $sectionId,
            'current_designation_id' => $designationId,

            // Orientation Information
            'orientation_required' => $this->faker->boolean(50) ? 'yes' : 'no',
            'orientation_from' => $joiningDate->format('Y-m-d'),
            'orientation_to' => (clone $joiningDate)->modify('+' . $this->faker->numberBetween(3, 10) . ' days')->format('Y-m-d'),
            'orientation_type' => $this->faker->randomElement(['General', 'Technical', 'Safety']),
            'orientation_days' => $this->faker->numberBetween(3, 10),

            // Employment & Performance
            'confirmation_date' => $isPermanent ? (clone $joiningDate)->modify('+6 months')->format('Y-m-d') : null,
            'probation_duration' => $isPermanent ? 6 : null,
            'next_promotion_date' => $promotionDate->format('Y-m-d'),
            'promotion_cycle' => 'Annual',
            'increment_cycle' => 'Annual',

            // Attendance & Benefits
            'weekends' => $this->faker->randomElement([json_encode(['Friday']), json_encode(['Friday', 'Saturday']), json_encode(['Saturday'])]),
            'alternate_off_day' => $alternateOffDayJson,
            'ot_allowed' => $this->faker->boolean(60) ? 'yes' : 'no',
            'pf_eligible' => $isPermanent ? 'yes' : 'no',
            'salary_type' => $this->faker->randomElement(['monthly', 'hourly']),
            'transport_eligible' => $this->faker->boolean(40) ? 'yes' : 'no',

            // Loan & Benefits Eligibility
            'can_apply_loan' => $isPermanent ? 'yes' : 'no',
            'pf_effective_date' => $isPermanent ? (clone $joiningDate)->modify('+1 year')->format('Y-m-d') : null,
            'can_apply_advance' => $this->faker->boolean(80) ? 'yes' : 'no',
            'gratuity_eligible' => 'yes',

            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    private function generateEligiblePlansData($employeeId): array
    {
        $data = [
            'employee_id' => $employeeId,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // List of all plan fields to populate
        $planFields = [
            'shift_plan', 'leave_plan', 'ot_plan', 'attendance_bonus_plan',
            'day_off_work_plan', 'roster_plans', 'bonus_plan', 'allowance_plan',
            'late_deduction_plan', 'production_plan', 'early_out_deduction_plan',
            'salary_breakdown_plan', 'medical_plan', 'night_bill_plan',
            'tiffin_plan', 'dinner_plan', 'breakfast_plan', 'food_com_plan',
            'excessive_late_plan', 'lunch_plan', 'snacks_plan'
        ];

        foreach ($planFields as $field) {
            $isActive = $this->faker->boolean(70);

            if ($isActive) {
                $fromDate = $this->faker->dateTimeBetween('-2 years', 'now');
                $toDate = (clone $fromDate)->modify('+' . $this->faker->numberBetween(1, 3) . ' years');

                $data["{$field}_from"] = $fromDate->format('Y-m-d');
                $data["{$field}_to"] = $toDate->format('Y-m-d');
                $data["{$field}_status"] = 'active';
            } else {
                $data["{$field}_from"] = null;
                $data["{$field}_to"] = null;
                $data["{$field}_status"] = 'inactive';
            }
        }

        return $data;
    }

    private function generateEducationExperienceTrainingData($employeeId): array
    {
        $educations = [];
        $experiences = [];
        $trainings = [];

        // Educations (2 to 4 entries)
        $educationTitles = ['SSC', 'HSC', 'BSc', 'MSc', 'PhD'];
        for ($i = 0; $i < $this->faker->numberBetween(2, 4); $i++) {
            $educations[] = [
                'education_title' => $educationTitles[$i % count($educationTitles)],
                'institute' => $this->faker->company . ' Institute',
                'group_major' => $this->faker->randomElement(['Science', 'Business Studies', 'Arts', 'Engineering']),
                'board_university' => $this->faker->randomElement(['Dhaka University', 'BUET', 'National University']),
                'result_grade' => $this->faker->randomElement(['First Division', 'A+', 'Distinction']),
                'passing_year' => $this->faker->year(),
                'gpa_cgpa' => $this->faker->randomFloat(2, 2.5, 4.0),
            ];
        }

        // Experiences (0 to 3 entries)
        for ($i = 0; $i < $this->faker->numberBetween(0, 3); $i++) {
            $startDate = $this->faker->dateTimeBetween('-10 years', '-2 years');
            $endDate = (clone $startDate)->modify('+' . $this->faker->numberBetween(1, 4) . ' years');
            $duration = $startDate->diff($endDate)->format('%y years, %m months');

            $experiences[] = [
                'company' => $this->faker->company,
                'designation' => $this->faker->jobTitle,
                'department' => $this->faker->randomElement(['Finance', 'IT', 'Marketing', 'HR']),
                'date_from' => $startDate->format('Y-m-d'),
                'date_to' => $endDate->format('Y-m-d'),
                'duration' => $duration,
                'responsibility' => $this->faker->paragraph(2),
            ];
        }

        // Trainings (1 to 3 entries)
        for ($i = 0; $i < $this->faker->numberBetween(1, 3); $i++) {
            $fromDate = $this->faker->dateTimeBetween('-3 years', 'now');
            $toDate = (clone $fromDate)->modify('+' . $this->faker->numberBetween(2, 10) . ' days');

            $trainings[] = [
                'training_title' => $this->faker->randomElement(['Agile', 'DevOps', 'Cyber Security', 'Leadership', 'Financial Modeling']),
                'course_name' => $this->faker->word . ' Certification Course',
                'training_code' => 'TRN' . $this->faker->unique()->numerify('####'),
                'institute' => $this->faker->company . ' Training Institute',
                'country' => $this->faker->country,
                'location' => $this->faker->city,
                'duration' => $fromDate->diff($toDate)->days . ' days',
                'from_date' => $fromDate->format('Y-m-d'),
                'to_date' => $toDate->format('Y-m-d'),
            ];
        }

        return [
            'employee_id' => $employeeId,
            'educations' => json_encode($educations),
            'experiences' => json_encode($experiences),
            'trainings' => json_encode($trainings),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
