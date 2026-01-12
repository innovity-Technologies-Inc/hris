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
    private $totalEmployees = 2000;  // Reduced for testing - change back to 2000 after successful run
    // ------------------------

    // Bangladesh-specific data arrays
    private $bdDivisions = ['Dhaka', 'Chittagong', 'Rajshahi', 'Khulna', 'Barishal', 'Sylhet', 'Rangpur', 'Mymensingh'];

    private $bdDistricts = [
        'Dhaka' => ['Dhaka', 'Gazipur', 'Narayanganj', 'Tangail', 'Munshiganj', 'Manikganj', 'Narsingdi', 'Faridpur', 'Kishoreganj', 'Madaripur'],
        'Chittagong' => ['Chittagong', 'Comilla', 'Feni', 'Brahmanbaria', "Cox's Bazar", 'Rangamati', 'Noakhali', 'Chandpur', 'Lakshmipur', 'Khagrachari'],
        'Rajshahi' => ['Rajshahi', 'Bogra', 'Pabna', 'Sirajganj', 'Natore', 'Naogaon', 'Chapainawabganj', 'Joypurhat', 'Nawabganj'],
        'Khulna' => ['Khulna', 'Jessore', 'Satkhira', 'Bagerhat', 'Narail', 'Magura', 'Kushtia', 'Chuadanga', 'Meherpur', 'Jhenaidah'],
        'Barishal' => ['Barishal', 'Patuakhali', 'Bhola', 'Pirojpur', 'Jhalokati', 'Barguna'],
        'Sylhet' => ['Sylhet', 'Moulvibazar', 'Habiganj', 'Sunamganj'],
        'Rangpur' => ['Rangpur', 'Dinajpur', 'Kurigram', 'Gaibandha', 'Nilphamari', 'Lalmonirhat', 'Thakurgaon', 'Panchagarh'],
        'Mymensingh' => ['Mymensingh', 'Jamalpur', 'Sherpur', 'Netrokona'],
    ];

    private $bdAreas = ['Mirpur', 'Uttara', 'Gulshan', 'Banani', 'Dhanmondi', 'Mohammadpur', 'Motijheel', 'Kakrail', 'Tejgaon', 'Shantinagar',
                        'Badda', 'Rampura', 'Khilgaon', 'Mogbazar', 'Shahbag', 'Farmgate', 'Agargaon', 'Paltan', 'New Market', 'Lalbagh'];

    private $bdMaleFirstNames = ['Mohammad', 'Abdul', 'Rahman', 'Karim', 'Zahid', 'Tanvir', 'Sakib', 'Rashed', 'Faruk', 'Arif',
                                  'Shahriar', 'Naim', 'Mahmud', 'Hasan', 'Hussain', 'Ali', 'Imran', 'Rakib', 'Sadiq', 'Ashraf',
                                  'Jahangir', 'Shafiq', 'Kamal', 'Jamal', 'Rafiq', 'Sharif', 'Manjur', 'Mostafa', 'Selim', 'Habib'];

    private $bdFemaleFirstNames = ['Fatema', 'Ayesha', 'Sumaiya', 'Tasnim', 'Nusrat', 'Sabrina', 'Rumana', 'Nazma', 'Sharmin', 'Farzana',
                                    'Tahmina', 'Rehana', 'Salma', 'Halima', 'Rabeya', 'Jasmine', 'Nasrin', 'Parveen', 'Shabnam', 'Munmun',
                                    'Tania', 'Sumi', 'Rita', 'Mitu', 'Lipi', 'Shilpi', 'Mousumi', 'Nipa', 'Shikha', 'Rupa'];

    private $bdLastNames = ['Hossain', 'Ahmed', 'Rahman', 'Khan', 'Islam', 'Ali', 'Haque', 'Chowdhury', 'Sarkar', 'Mia',
                            'Begum', 'Akter', 'Sheikh', 'Mallick', 'Pradhan', 'Biswas', 'Das', 'Pal', 'Ghosh', 'Roy',
                            'Sikder', 'Talukdar', 'Majumdar', 'Siddiqui', 'Alam', 'Uddin', 'Kabir', 'Zaman', 'Howlader', 'Molla'];

    private $bdUniversities = [
        'University of Dhaka',
        'Bangladesh University of Engineering & Technology (BUET)',
        'National University of Bangladesh',
        'Jahangirnagar University',
        'University of Rajshahi',
        'University of Chittagong',
        'Khulna University',
        'BRAC University',
        'North South University',
        'East West University',
        'American International University-Bangladesh (AIUB)',
        'Daffodil International University',
        'Independent University Bangladesh',
        'Shahjalal University of Science & Technology (SUST)',
        'Bangladesh Agricultural University (BAU)',
    ];

    private $bdEducationBoards = [
        'Dhaka Board',
        'Chittagong Board',
        'Rajshahi Board',
        'Jessore Board',
        'Comilla Board',
        'Sylhet Board',
        'Dinajpur Board',
        'Barishal Board',
        'Mymensingh Board',
        'Madrasah Board',
        'Technical Board',
    ];

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
        
        // Load company_locations grouped by company_id for proper assignment
        $this->orgData['company_locations_by_company'] = DB::table('company_locations')
            ->select('id', 'company_id')
            ->get()
            ->groupBy('company_id')
            ->map(function($locations) {
                return $locations->pluck('id')->toArray();
            })
            ->toArray();
        
        // Load divisions grouped by location_id (branch)
        $this->orgData['divisions_by_location'] = DB::table('divisions')
            ->select('id', 'location_id')
            ->get()
            ->groupBy('location_id')
            ->map(function($divisions) {
                return $divisions->pluck('id')->toArray();
            })
            ->toArray();
        
        // Load departments grouped by division_id
        $this->orgData['departments_by_division'] = DB::table('departments')
            ->select('id', 'division_id')
            ->get()
            ->groupBy('division_id')
            ->map(function($departments) {
                return $departments->pluck('id')->toArray();
            })
            ->toArray();
        
        // Load sections grouped by department_id
        $this->orgData['sections_by_department'] = DB::table('sections')
            ->select('id', 'department_id')
            ->get()
            ->groupBy('department_id')
            ->map(function($sections) {
                return $sections->pluck('id')->toArray();
            })
            ->toArray();
            
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
        // Process employees in batches to avoid memory exhaustion
        $batchSize = 100; // Process 100 employees at a time
        $employeeIdCounter = 1;

        for ($batch = 0; $batch < ceil($this->totalEmployees / $batchSize); $batch++) {
            $employees = [];
            $officeInfos = [];
            $eligiblePlans = [];
            $eduExpTrainings = [];

            $batchStart = $batch * $batchSize;
            $batchEnd = min(($batch + 1) * $batchSize, $this->totalEmployees);

            for ($i = $batchStart; $i < $batchEnd; $i++) {
                $employee_id = $employeeIdCounter++;

                // Determine Role & Gender
                $gender = $this->faker->randomElement(['Male', 'Female']);
                $firstName = $gender === 'Male'
                    ? $this->faker->randomElement($this->bdMaleFirstNames)
                    : $this->faker->randomElement($this->bdFemaleFirstNames);
                $lastName = $this->faker->randomElement($this->bdLastNames);
                $maritalStatus = $this->faker->randomElement(['Single', 'Married', 'Divorced', 'Widowed']);
                $hasSpouse = $maritalStatus === 'Married';

                // 1. employees Table
                $employees[] = $this->generateEmployeeData($employee_id, $firstName, $lastName, $gender, $maritalStatus, $hasSpouse);

                // 2. employee_office_infos Table
                $officeInfos[] = $this->generateOfficeInfoData($employee_id);

                // 3. employee_eligible_plans Table
                $eligiblePlans[] = $this->generateEligiblePlansData($employee_id);

                // 4. employee_education_experience_training Table
                $eduExpTrainings[] = $this->generateEducationExperienceTrainingData($employee_id);
            }

            // Insert this batch
            DB::table('employees')->insert($employees);
            DB::table('employee_office_infos')->insert($officeInfos);
            DB::table('employee_eligible_plans')->insert($eligiblePlans);
            DB::table('employee_education_experience_training')->insert($eduExpTrainings);

            // Clear memory
            unset($employees, $officeInfos, $eligiblePlans, $eduExpTrainings);

            // Show progress
            echo "Seeded employees " . ($batchStart + 1) . " to " . $batchEnd . " of " . $this->totalEmployees . "\n";
        }
    }

    private function generateBangladeshAddress(): array
    {
        $division = $this->faker->randomElement($this->bdDivisions);
        $district = $this->faker->randomElement($this->bdDistricts[$division]);
        $area = $this->faker->randomElement($this->bdAreas);

        return [
            'line_1' => 'House ' . $this->faker->numberBetween(1, 500) . ', Road ' . $this->faker->numberBetween(1, 50) . ', ' . $area,
            'village' => $this->faker->optional(0.4)->randomElement(['East Para', 'West Para', 'North Para', 'South Para', 'Middle Para']),
            'post_office' => $district . ' Sadar Post Office',
            'district' => $district,
            'division' => $division,
            'zip_code' => $this->faker->numerify('####'),
            'state' => $division,
            'country' => 'Bangladesh',
        ];
    }

    private function generateBangladeshPhoneNumber(): string
    {
        $operators = ['17', '18', '19', '16', '15', '13', '14'];
        return '+880' . $this->faker->randomElement($operators) . $this->faker->numerify('########');
    }

    private function generateNID(): string
    {
        // Bangladesh NID can be 10, 13, or 17 digits
        $format = $this->faker->randomElement(['10', '17']);
        if ($format === '10') {
            return $this->faker->numerify('##########');
        }
        return $this->faker->numerify('#################');
    }

    private function generateEmployeeData($id, $firstName, $lastName, $gender, $maritalStatus, $hasSpouse): array
    {
        $dateOfBirth = $this->faker->dateTimeBetween('-45 years', '-20 years');
        $middleName = $this->faker->optional(0.3)->randomElement($gender === 'Male' ? $this->bdMaleFirstNames : $this->bdFemaleFirstNames);

        $presentAddress = $this->generateBangladeshAddress();

        // 70% chance permanent address is the same as present, otherwise different
        $isSameAddress = $this->faker->boolean(70);
        $permanentAddress = $isSameAddress ? $presentAddress : $this->generateBangladeshAddress();

        // Reference address (50% chance of being included)
        $referenceAddress = $this->faker->boolean(50) ? [
            'emp_id' => 'SYS' . $this->faker->unique()->numberBetween(1000, 9999),
            'reference_name' => $this->faker->randomElement($this->bdMaleFirstNames) . ' ' . $this->faker->randomElement($this->bdLastNames),
            'reference_designation' => $this->faker->randomElement(['Manager', 'Senior Officer', 'Project Lead', 'Department Head', 'HR Manager']),
            'phone' => '+880-2-' . $this->faker->numerify('########'),
            'mobile' => $this->generateBangladeshPhoneNumber(),
            'email' => strtolower(Str::slug($this->faker->randomElement($this->bdMaleFirstNames))) . '@company.com.bd',
            'line_1' => $this->faker->randomElement($this->bdAreas) . ', Road ' . $this->faker->numberBetween(1, 30),
            'village' => null,
            'post_office' => 'Dhaka Sadar Post Office',
            'district' => 'Dhaka',
            'division' => 'Dhaka',
            'zip_code' => $this->faker->numerify('####'),
            'state' => 'Dhaka',
            'country' => 'Bangladesh',
        ] : null;

        // Generate spouse name based on gender
        $spouseName = null;
        if ($hasSpouse) {
            $spouseName = $gender === 'Male'
                ? $this->faker->randomElement($this->bdFemaleFirstNames) . ' ' . $this->faker->randomElement($this->bdLastNames)
                : $this->faker->randomElement($this->bdMaleFirstNames) . ' ' . $this->faker->randomElement($this->bdLastNames);
        }

        return [
            'id' => $id,
            'applicant_id' => 'APP' . str_pad($id, 6, '0', STR_PAD_LEFT),
            'system_id' => 'SYS' . str_pad($id, 6, '0', STR_PAD_LEFT),
            'punch_card_no' => 'PC' . $this->faker->unique()->numerify('#####'),

            'first_name' => $firstName,
            'last_name' => $lastName,
            'middle_name' => $middleName,
            'full_name' => trim("$firstName $middleName $lastName"),
            'father_name' => $this->faker->randomElement($this->bdMaleFirstNames) . ' ' . $this->faker->randomElement($this->bdLastNames),
            'mother_name' => $this->faker->randomElement($this->bdFemaleFirstNames) . ' ' . $this->faker->randomElement($this->bdLastNames),
            'spouse_name' => $spouseName,
            'marital_status' => $maritalStatus,
            'gender' => $gender,
            'religion' => $this->faker->randomElement(['Islam', 'Hinduism', 'Buddhism', 'Christianity']),
            'nationality' => 'Bangladeshi',
            'blood_group' => $this->faker->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            'height_feet' => $this->faker->optional(0.8)->numberBetween(5, 6),
            'height_inches' => $this->faker->optional(0.8)->numberBetween(0, 11),
            'children_count' => $hasSpouse ? $this->faker->numberBetween(0, 4) : 0,

            // --- CORRECTED ADDRESS FIELDS ---
            'present_address' => json_encode($presentAddress),
            'permanent_address' => json_encode($permanentAddress),
            'reference_address' => $referenceAddress ? json_encode($referenceAddress) : null,
            // ---------------------------------

            'tin' => $this->faker->optional(0.6)->numerify('############'),
            'passport_no' => $this->faker->optional(0.3)->bothify('??########'),
            'passport_expiry' => $this->faker->optional(0.3)->dateTimeBetween('+1 year', '+8 years'),
            'license_no' => $this->faker->optional(0.4)->numerify('##-##-######'),
            'license_expiry' => $this->faker->optional(0.4)->dateTimeBetween('+1 year', '+5 years'),
            'visa_expiry' => $this->faker->optional(0.1)->dateTimeBetween('+1 year', '+5 years'),
            'work_expiry' => $this->faker->optional(0.1)->dateTimeBetween('+1 year', '+5 years'),
            'residency_id_number' => $this->generateNID(), // Bangladesh NID

            'date_of_birth' => $dateOfBirth->format('Y-m-d'),
            'birth_country' => 'Bangladesh',
            'birth_reg_no' => $this->faker->optional(0.7)->numerify('################'),

            'personal_mobile' => $this->generateBangladeshPhoneNumber(),
            'home_phone' => $this->faker->optional(0.3)->numerify('+880-2-########'),
            'work_mobile' => $this->faker->optional(0.7)->numerify('+880-1#-########'),
            'work_phone' => $this->faker->optional(0.6)->numerify('+880-2-########'),
            'work_email' => strtolower(Str::slug($firstName . '.' . $lastName)) . '@company.com.bd',
            'personal_email' => strtolower(Str::slug($firstName . $this->faker->numberBetween(1, 999))) . '@gmail.com',

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

        // --- Select organizational IDs following the hierarchy: Company -> Branch -> Division -> Department -> Section ---
        
        // 1. Select Company
        $companyId = $this->faker->randomElement($this->orgData['companies']);
        
        // 2. Select a Business Unit (Branch) that belongs to the selected company
        $businessUnitId = null;
        if (isset($this->orgData['company_locations_by_company'][$companyId]) && 
            !empty($this->orgData['company_locations_by_company'][$companyId])) {
            $businessUnitId = $this->faker->randomElement($this->orgData['company_locations_by_company'][$companyId]);
        }
        
        // 3. Select a Division that belongs to the selected branch
        $divisionId = null;
        if ($businessUnitId && isset($this->orgData['divisions_by_location'][$businessUnitId]) && 
            !empty($this->orgData['divisions_by_location'][$businessUnitId])) {
            $divisionId = $this->faker->randomElement($this->orgData['divisions_by_location'][$businessUnitId]);
        }
        
        // 4. Select a Department that belongs to the selected division
        $departmentId = null;
        if ($divisionId && isset($this->orgData['departments_by_division'][$divisionId]) && 
            !empty($this->orgData['departments_by_division'][$divisionId])) {
            $departmentId = $this->faker->randomElement($this->orgData['departments_by_division'][$divisionId]);
        }
        
        // 5. Select a Section that belongs to the selected department
        $sectionId = null;
        if ($departmentId && isset($this->orgData['sections_by_department'][$departmentId]) && 
            !empty($this->orgData['sections_by_department'][$departmentId])) {
            $sectionId = $this->faker->randomElement($this->orgData['sections_by_department'][$departmentId]);
        }
        
        $designationId = $this->faker->randomElement($this->orgData['designations']);
        $tofsilId = $this->faker->randomElement($this->orgData['tofsils']);
        $gradeId = $this->faker->randomElement($this->orgData['salary_grades']);

        $promotionDate = (clone $joiningDate)->modify('+' . $this->faker->numberBetween(1, 3) . ' years');

        // Determine alternate day (10% chance of having one) - Bangladesh style (Friday/Saturday)
        $alternateDays = ['Friday', 'Saturday'];
        $alternateDay = $this->faker->boolean(10) ? $this->faker->randomElement($alternateDays) : null;

        // FIX: Convert alternate day string into a JSON array or null
        $alternateOffDayJson = $alternateDay ? json_encode([$alternateDay]) : null;


        return [
            'employee_id' => $employeeId,
            'emp_type' => $isPermanent ? 'permanent' : 'contractual',
            'grade_id' => $gradeId,
            'hr_file_no' => 'HRF' . $this->faker->unique()->numerify('######'),
            'tofsil_id' => $tofsilId,
            'file_note' => $this->faker->optional(0.1)->randomElement(['Regular Employee', 'Probationary', 'Experienced Worker', 'New Hire']),

            // Joining Information
            'joining_company_id' => $companyId,
            'joining_business_unit_id' => $businessUnitId,
            'joining_division_id' => $divisionId,
            'joining_department_id' => $departmentId,
            'joining_section_id' => $sectionId,
            'joining_designation_id' => $designationId,
            'date_of_join' => $joiningDate->format('Y-m-d'),

            // Current Posting Information
            'current_company_id' => $companyId,
            'current_business_unit_id' => $businessUnitId,
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

            // Attendance & Benefits - Bangladesh standard (Friday off, some Friday-Saturday)
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

        // List of all plan fields to populate (matching migration schema)
        $planFields = [
            'shift_plan', 'leave_plan', 'ot_plan', 'day_off_work_plan',
            'roster_plans', 'bonus_plan', 'allowance_plan', 'late_deduction_plan',
            'early_out_deduction_plan', 'medical_plan', 'excessive_late_plan', 'meal_plan'
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

        // Bangladesh Education System
        $educationLevels = [
            ['title' => 'SSC (Secondary School Certificate)', 'group' => ['Science', 'Commerce', 'Humanities']],
            ['title' => 'HSC (Higher Secondary Certificate)', 'group' => ['Science', 'Commerce', 'Humanities']],
            ['title' => 'Bachelor Degree', 'group' => ['Computer Science & Engineering (CSE)', 'Business Administration (BBA)', 'English', 'Economics', 'Accounting']],
            ['title' => 'Masters Degree', 'group' => ['Computer Science & Engineering (CSE)', 'Business Administration (MBA)', 'English', 'Economics', 'Accounting']],
            ['title' => 'PhD', 'group' => ['Computer Science', 'Business Administration', 'Economics']],
        ];

        $bdColleges = [
            'Dhaka College',
            'Notre Dame College',
            'Holy Cross College',
            'RAJUK Uttara Model College',
            'Viqarunnisa Noon School & College',
            'St. Joseph Higher Secondary School',
            'Ideal School & College',
            'Government Laboratory High School',
        ];

        $bdResults = ['First Division', 'A+', 'A', 'A-', 'B+', 'Golden A+'];

        // Educations (2 to 4 entries) - Bangladesh Education System
        $numEducations = $this->faker->numberBetween(2, 4);
        for ($i = 0; $i < $numEducations; $i++) {
            $eduLevel = $educationLevels[$i % count($educationLevels)];
            $isSchool = $i < 2; // SSC/HSC from school/college

            $educations[] = [
                'education_title' => $eduLevel['title'],
                'institute' => $isSchool ? $this->faker->randomElement($bdColleges) : $this->faker->randomElement($this->bdUniversities),
                'group_major' => $this->faker->randomElement($eduLevel['group']),
                'board_university' => $isSchool ? $this->faker->randomElement($this->bdEducationBoards) : $this->faker->randomElement($this->bdUniversities),
                'result_grade' => $this->faker->randomElement($bdResults),
                'passing_year' => $this->faker->numberBetween(2000, 2023),
                'gpa_cgpa' => $this->faker->randomFloat(2, 3.0, 5.0),
            ];
        }

        // Bangladesh Companies for Experience
        $bdCompanies = [
            'Grameenphone Ltd.',
            'Robi Axiata Ltd.',
            'Banglalink Digital Communications',
            'Beximco Group',
            'Square Group',
            'Walton Group',
            'PRAN-RFL Group',
            'Bashundhara Group',
            'ACI Limited',
            'Incepta Pharmaceuticals',
            'BRAC Bank',
            'Dutch-Bangla Bank',
            'Islami Bank Bangladesh',
        ];

        $bdDesignations = [
            'Software Engineer',
            'Senior Software Engineer',
            'Junior Executive',
            'Executive',
            'Senior Executive',
            'Officer',
            'Assistant Manager',
            'Manager',
            'Senior Manager',
        ];

        $bdDepartments = [
            'IT',
            'HR',
            'Finance & Accounts',
            'Marketing',
            'Sales',
            'Production',
            'Quality Control',
            'Customer Service',
        ];

        // Experiences (0 to 3 entries) - Bangladesh Companies
        for ($i = 0; $i < $this->faker->numberBetween(0, 3); $i++) {
            $startDate = $this->faker->dateTimeBetween('-10 years', '-2 years');
            $endDate = (clone $startDate)->modify('+' . $this->faker->numberBetween(1, 4) . ' years');
            $years = $startDate->diff($endDate)->y;
            $months = $startDate->diff($endDate)->m;
            $duration = $years . ' years, ' . $months . ' months';

            $experiences[] = [
                'company' => $this->faker->randomElement($bdCompanies),
                'designation' => $this->faker->randomElement($bdDesignations),
                'department' => $this->faker->randomElement($bdDepartments),
                'date_from' => $startDate->format('Y-m-d'),
                'date_to' => $endDate->format('Y-m-d'),
                'duration' => $duration,
                'responsibility' => $this->faker->randomElement([
                    'Project Development and Maintenance',
                    'Team Management and Supervision',
                    'Client Communication and Reporting',
                    'Data Analysis and Reporting',
                    'System Design and Implementation',
                ]),
            ];
        }

        // Bangladesh Training Institutes
        $bdTrainingInstitutes = [
            'Bangladesh Institute of Management (BIM)',
            'BASIS Institute of Technology',
            'Bangladesh Computer Council (BCC)',
            'ICT Division',
            'BRAC Learning Center',
            'BITAC',
            'HRDI',
        ];

        $bdTrainingTitles = [
            'Professional Software Development',
            'Project Management',
            'Digital Marketing',
            'Financial Management',
            'Leadership Training',
            'Communication Skills',
            'Cyber Security',
            'Data Analytics',
            'HR Management',
            'Agile Methodology',
        ];

        // Trainings (1 to 3 entries) - Bangladesh Training
        for ($i = 0; $i < $this->faker->numberBetween(1, 3); $i++) {
            $fromDate = $this->faker->dateTimeBetween('-3 years', 'now');
            $toDate = (clone $fromDate)->modify('+' . $this->faker->numberBetween(2, 10) . ' days');
            $days = $fromDate->diff($toDate)->days;

            $trainings[] = [
                'training_title' => $this->faker->randomElement($bdTrainingTitles),
                'course_name' => $this->faker->randomElement($bdTrainingTitles) . ' Certification Course',
                'training_code' => 'TRN' . $this->faker->unique()->numerify('####'),
                'institute' => $this->faker->randomElement($bdTrainingInstitutes),
                'country' => 'Bangladesh',
                'location' => $this->faker->randomElement(['Dhaka', 'Chittagong', 'Rajshahi', 'Khulna', 'Sylhet']),
                'duration' => $days . ' days',
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
