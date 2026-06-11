<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class OrganizationSeeder extends Seeder
{
    private $faker;

    // Bangladesh-specific data arrays
    private $bdDivisions = ['Dhaka', 'Chittagong', 'Rajshahi', 'Khulna', 'Barishal', 'Sylhet', 'Rangpur', 'Mymensingh'];

    private $bdDistricts = [
        'Dhaka' => ['Dhaka', 'Gazipur', 'Narayanganj', 'Tangail', 'Munshiganj', 'Manikganj', 'Narsingdi', 'Faridpur'],
        'Chittagong' => ['Chittagong', 'Comilla', 'Feni', 'Brahmanbaria', "Cox's Bazar", 'Rangamati', 'Noakhali', 'Chandpur'],
        'Rajshahi' => ['Rajshahi', 'Bogra', 'Pabna', 'Sirajganj', 'Natore', 'Naogaon', 'Chapainawabganj', 'Joypurhat'],
        'Khulna' => ['Khulna', 'Jessore', 'Satkhira', 'Bagerhat', 'Narail', 'Magura', 'Kushtia', 'Chuadanga'],
        'Barishal' => ['Barishal', 'Patuakhali', 'Bhola', 'Pirojpur', 'Jhalokati', 'Barguna'],
        'Sylhet' => ['Sylhet', 'Moulvibazar', 'Habiganj', 'Sunamganj'],
        'Rangpur' => ['Rangpur', 'Dinajpur', 'Kurigram', 'Gaibandha', 'Nilphamari', 'Lalmonirhat', 'Thakurgaon', 'Panchagarh'],
        'Mymensingh' => ['Mymensingh', 'Jamalpur', 'Sherpur', 'Netrokona'],
    ];

    private $bdCities = [
        'Dhaka', 'Chittagong', 'Khulna', 'Rajshahi', 'Sylhet', 'Rangpur', 'Barishal', 'Comilla',
        'Gazipur', 'Narayanganj', 'Mymensingh', 'Bogra', 'Jessore', "Cox's Bazar", 'Dinajpur', 'Brahmanbaria'
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

        $this->seedGroupsAndTypes();
        $this->seedOrgStructure();
        $this->seedBankingStructure();
        $this->seedCompensationStructure();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function seedGroupsAndTypes()
    {
        // --- GROUPS (Bangladesh Business Groups) ---
        DB::table('groups')->truncate();
        DB::table('groups')->insert([
            ['id' => 1, 'name' => 'Bangladesh Corporate Group', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // --- COMPANY TYPES (Bangladesh Industry Sectors) ---
        DB::table('company_types')->truncate();
        DB::table('company_types')->insert([
            ['id' => 1, 'name' => 'Information Technology', 'short_name' => 'IT', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Readymade Garments & Textile', 'short_name' => 'RMG', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Banking & Financial Services', 'short_name' => 'FIN', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'name' => 'Pharmaceuticals', 'short_name' => 'PHARMA', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'name' => 'Construction & Real Estate', 'short_name' => 'CONST', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    private function seedOrgStructure()
    {
        // --- 1. COMPANIES (10 Bangladesh Companies) ---
        DB::table('companies')->truncate();
        $companies = [];
        $companyData = [
            ['name' => 'Technosis Software Limited', 'type' => 1],
            ['name' => 'Grameenphone IT Limited', 'type' => 1],
            ['name' => 'Beximco Pharmaceuticals Limited', 'type' => 4],
            ['name' => 'Square Textiles Limited', 'type' => 2],
            ['name' => 'Walton Hi-Tech Industries Limited', 'type' => 1],
            ['name' => 'ACI Limited', 'type' => 4],
            ['name' => 'Bashundhara Group', 'type' => 5],
            ['name' => 'PRAN-RFL Group', 'type' => 2],
            ['name' => 'Summit Power Limited', 'type' => 5],
            ['name' => 'BRAC IT Services Limited', 'type' => 1],
        ];

        foreach ($companyData as $i => $data) {
            $id = 101 + $i;
            $companies[] = [
                'id' => $id,
                'name' => $data['name'],
                'short_name' => strtoupper(Str::limit(explode(' ', $data['name'])[0], 4, '')),
                'type_id' => $data['type'],
                'group_id' => 1,
                'address' => $this->getBangladeshAddress(),
                'fax' => '+880-2-' . $this->faker->numerify('########'),
                'telephone' => '+880-2-' . $this->faker->numerify('########'),
                'email' => strtolower(Str::slug(explode('(', $data['name'])[1] ?? explode(' ', $data['name'])[0], '.')) . '@company.com.bd',
                'logo' => 'logo_' . $id . '.png',
                'status' => 'active', 'created_at' => now(), 'updated_at' => now()
            ];
        }
        DB::table('companies')->insert($companies);


        // --- 2. COMPANY LOCATIONS (20 Entries - Bangladesh Cities) ---
        DB::table('company_locations')->truncate();
        $locations = [];
        $bdLocations = [
            'Head Office, Gulshan, Dhaka',
            'Motijheel Branch, Dhaka',
            'Chittagong Office, Chittagong',
            'Gazipur Factory, Gazipur',
            'Narayanganj Unit, Narayanganj',
            'Savar Production Unit, Savar',
            'Sylhet Branch, Sylhet',
            'Rajshahi Office, Rajshahi',
            'Khulna Branch, Khulna',
            'Comilla Unit, Comilla',
            'Bogra Branch, Bogra',
            'Jessore Office, Jessore',
            'Barishal Branch, Barishal',
            'Rangpur Branch, Rangpur',
            'Mymensingh Office, Mymensingh',
            'Tongi Factory, Tongi',
            'Uttara Branch, Dhaka',
            'Banani Office, Dhaka',
            'Mirpur Unit, Dhaka',
            'Dhanmondi Branch, Dhaka',
        ];

        for ($i = 0; $i < 20; $i++) {
            $id = 201 + $i;
            $company_id = 101 + ($i % 10);
            $locations[] = [
                'id' => $id,
                'company_id' => $company_id,
                'name' => $bdLocations[$i],
                'location_address' => $this->getBangladeshAddress(),
                'status' => 'active', 'created_at' => now(), 'updated_at' => now()
            ];
        }
        DB::table('company_locations')->insert($locations);


        // --- 3. DIVISIONS (30 Entries - Bangladesh Corporate Divisions) ---
        DB::table('divisions')->truncate();
        $divisions = [];
        $divNames = [
            'Software Development',
            'Human Resources',
            'Finance & Accounts',
            'Marketing',
            'Production',
            'Quality Control',
            'Supply Chain',
            'Customer Service',
            'Research & Development',
            'Administration',
        ];

        for ($i = 0; $i < 30; $i++) {
            $id = 301 + $i;
            $location_id = 201 + ($i % 20);
            $company_id = DB::table('company_locations')->where('id', $location_id)->value('company_id');
            $divName = $divNames[$i % 10];
            $divisions[] = [
                'id' => $id,
                'company_id' => $company_id,
                'location_id' => $location_id,
                'name' => $divName . ' - Unit ' . ($i + 1),
                'short_name' => strtoupper(Str::limit($divName, 3, '') . $i),
                'remarks' => null,
                'status' => 'active', 'created_at' => now(), 'updated_at' => now()
            ];
        }
        DB::table('divisions')->insert($divisions);


        // --- 4. DEPARTMENTS (40 Entries - Bangladesh Corporate Departments) ---
        DB::table('departments')->truncate();
        $departments = [];
        $deptNames = [
            'Backend Development',
            'Frontend Development',
            'Recruitment & Training',
            'Payroll & Benefits',
            'Audit',
            'Procurement',
            'Sales',
            'Logistics',
        ];

        for ($i = 0; $i < 40; $i++) {
            $id = 401 + $i;
            $division_id = 301 + ($i % 30);
            $div_data = DB::table('divisions')->where('id', $division_id)->first();
            $deptName = $deptNames[$i % 8];
            $departments[] = [
                'id' => $id,
                'division_id' => $division_id,
                'location_id' => $div_data ? $div_data->location_id : null,
                'company_id' => $div_data ? $div_data->company_id : null,
                'department_name' => $deptName . ' Dept ' . ($i + 1),
                'short_name' => strtoupper(Str::limit($deptName, 3, '') . $i),
                'status' => 'active', 'created_at' => now(), 'updated_at' => now()
            ];
        }
        DB::table('departments')->insert($departments);

        // --- 5. SECTIONS (60 Entries) ---
        DB::table('sections')->truncate();
        $sections = [];
        $secNames = [
            'Project Team-A',
            'Project Team-B',
            'Support Team',
            'Maintenance',
            'Quality Assurance',
            'Data Entry',
        ];

        for ($i = 0; $i < 60; $i++) {
            $id = 501 + $i;
            $department_id = 401 + ($i % 40);
            $dept_data = DB::table('departments')->where('id', $department_id)->first();
            $secName = $secNames[$i % 6];

            if ($dept_data) {
                $div_data = DB::table('divisions')->where('id', $dept_data->division_id)->first();
                // Use a location from company_locations table
                $location_id = 201 + ($i % 20);
                $sections[] = [
                    'id' => $id,
                    'department_id' => $department_id,
                    'division_id' => $dept_data->division_id,
                    'location_id' => $location_id,
                    'company_id' => $div_data ? $div_data->company_id : 101,
                    'name' => $secName . ' ' . str_pad($i + 1, 2, '0', STR_PAD_LEFT),
                    'short_name' => strtoupper(Str::limit($secName, 3, '') . ($i % 10)),
                    'status' => 'active', 'created_at' => now(), 'updated_at' => now()
                ];
            }
        }
        DB::table('sections')->insert($sections);

        // --- 6. DESIGNATIONS (50 Entries - Bangladesh Corporate Titles) ---
        DB::table('designations')->truncate();
        $designations = [];
        $titles = [
            'Software Engineer',
            'Senior Software Engineer',
            'Project Manager',
            'HR Officer',
            'Accountant',
            'Marketing Officer',
            'Production Supervisor',
            'Quality Controller',
        ];
        $levels = [
            'Entry Level',
            'Junior',
            'Mid Level',
            'Senior',
            'Team Lead',
            'Manager',
        ];

        for ($i = 0; $i < 50; $i++) {
            $id = 601 + $i;
            $division_id = 301 + ($i % 30);
            $division_data = DB::table('divisions')->where('id', $division_id)->first();

            if ($division_data) {
                $designations[] = [
                    'id' => $id,
                    'designation_level' => $levels[$i % 6],
                    'company_designation' => $levels[$i % 6] . ' - ' . $titles[$i % 8],
                    'status' => 'active', 'created_at' => now(), 'updated_at' => now()
                ];
            }
        }
        DB::table('designations')->insert($designations);

        // --- 7. JOB CREATIONS (500 Entries) ---
        DB::table('job_creations')->truncate();
        $jobCreations = [];
        for ($i = 0; $i < 500; $i++) {
            $id = 701 + $i;
            $designation_id = 601 + ($i % 50);
            $department_id = 401 + ($i % 40);

            $dept_code = 'DEPT' . $department_id;  // Generate code since job_number_code doesn't exist
            $designation_title = DB::table('designations')->where('id', $designation_id)->value('company_designation');

            $jobCreations[] = [
                'id' => $id,
                'designation_id' => $designation_id,
                'department_id' => $department_id,
                'job_ind' => $dept_code . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'display_designation' => Str::limit($designation_title, 30),
                'display_serial' => str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'status' => $this->faker->randomElement(['active', 'inactive']),
                'remarks' => $this->faker->optional(0.2)->sentence(4),
                'created_at' => now(), 'updated_at' => now()
            ];
        }
        DB::table('job_creations')->insert($jobCreations);
    }

    private function getBangladeshAddress(): string
    {
        $division = $this->faker->randomElement($this->bdDivisions);
        $district = $this->faker->randomElement($this->bdDistricts[$division]);
        $areas = ['Mohakhali', 'Gulshan', 'Banani', 'Uttara', 'Mirpur', 'Dhanmondi', 'Motijheel', 'Kakrail', 'Tejgaon', 'Shantinagar'];
        $roads = ['Road No', 'Street No', 'Lane No', 'Alley No'];

        return $this->faker->randomElement($areas) . ', ' .
               $this->faker->randomElement($roads) . ' ' . $this->faker->numberBetween(1, 50) . ', ' .
               'House No ' . $this->faker->numberBetween(1, 500) . ', ' .
               $district . ', ' . $division . ', Bangladesh';
    }

    private function seedBankingStructure()
    {
        // --- 8. BANKS (Bangladesh Banks - Mix of Government, Private, and Foreign) ---
        DB::table('banks')->truncate();
        $banks = [];
        $bankData = [
            ['name' => 'Sonali Bank Limited', 'short' => 'SBL', 'code' => 'SONALI'],
            ['name' => 'Janata Bank Limited', 'short' => 'JBL', 'code' => 'JANATA'],
            ['name' => 'Agrani Bank Limited', 'short' => 'ABL', 'code' => 'AGRANI'],
            ['name' => 'Rupali Bank Limited', 'short' => 'RBL', 'code' => 'RUPALI'],
            ['name' => 'BRAC Bank Limited', 'short' => 'BBL', 'code' => 'BRAC'],
            ['name' => 'Dutch-Bangla Bank Limited', 'short' => 'DBBL', 'code' => 'DBBL'],
            ['name' => 'Islami Bank Bangladesh Limited', 'short' => 'IBBL', 'code' => 'IBBL'],
            ['name' => 'Prime Bank Limited', 'short' => 'PBL', 'code' => 'PRIME'],
            ['name' => 'City Bank Limited', 'short' => 'CBL', 'code' => 'CITY'],
            ['name' => 'Eastern Bank Limited', 'short' => 'EBL', 'code' => 'EBL'],
        ];

        foreach ($bankData as $i => $data) {
            $id = 801 + $i;
            $banks[] = [
                'id' => $id,
                'name' => $data['name'],
                'short_name' => $data['short'],
                'bank_code' => $data['code'],
                'contact_no' => '+880-2-' . $this->faker->numerify('########'),
                'contact_person' => $this->getBangladeshiName(),
                'contact_person_no' => '+880-1' . $this->faker->randomElement(['7', '8', '9', '6', '5']) . $this->faker->numerify('########'),
                'status' => 'active', 'created_at' => now(), 'updated_at' => now()
            ];
        }
        DB::table('banks')->insert($banks);

        // --- 9. BRANCHES (30 Entries - Bangladesh Bank Branches) ---
        DB::table('branches')->truncate();
        $branches = [];
        $branchLocations = [
            'Motijheel Main Branch',
            'Gulshan Branch',
            'Uttara Branch',
            'Mirpur Branch',
            'Dhanmondi Branch',
            'Banani Branch',
            'Chittagong Main Branch',
            'Khulna Branch',
            'Rajshahi Branch',
            'Sylhet Branch',
            'Barishal Branch',
            'Rangpur Branch',
            'Comilla Branch',
            'Gazipur Branch',
            'Narayanganj Branch',
        ];

        for ($i = 0; $i < 30; $i++) {
            $id = 901 + $i;
            $bank_id = 801 + ($i % 10);
            $branches[] = [
                'id' => $id,
                'bank_id' => $bank_id,
                'name' => $branchLocations[$i % 15],
                'address' => $this->getBangladeshAddress(),
                'routing_no' => $this->faker->numerify('#########'),
                'swift_code' => strtoupper($this->faker->lexify('????')) . 'BDDH' . strtoupper($this->faker->lexify('???')),
                'remarks' => null,
                'status' => 'active', 'created_at' => now(), 'updated_at' => now()
            ];
        }
        DB::table('branches')->insert($branches);

        // --- 10. BANK ACCOUNTS (50 Entries) ---
        DB::table('bank_accounts')->truncate();
        $accounts = [];
        $accountTypes = ['current', 'savings', 'credit'];
        for ($i = 0; $i < 50; $i++) {
            $id = 1001 + $i;
            $branch_id = 901 + ($i % 30);
            $bank_id = DB::table('branches')->where('id', $branch_id)->value('bank_id');

            $accounts[] = [
                'id' => $id,
                'bank_id' => $bank_id,
                'branch_id' => $branch_id,
                'account_no' => $this->faker->numerify('################'),
                'holder_name' => $this->faker->company . ' Payroll Account',
                'account_type' => $accountTypes[$i % 3],
                'contact_person' => $this->getBangladeshiName(),
                'contact_person_no' => '+880-1' . $this->faker->randomElement(['7', '8', '9', '6', '5']) . $this->faker->numerify('########'),
                'email' => $this->faker->companyEmail,
                'status' => $this->faker->randomElement(['active', 'inactive']),
                'created_at' => now(), 'updated_at' => now()
            ];
        }
        DB::table('bank_accounts')->insert($accounts);
    }

    private function getBangladeshiName(): string
    {
        $firstNamesMale = ['Mohammad', 'Abdul', 'Rahman', 'Karim', 'Zahid', 'Tanvir', 'Sakib', 'Rashed', 'Faruk', 'Arif', 'Shahriar', 'Naeem'];
        $firstNamesFemale = ['Fatema', 'Ayesha', 'Sumaiya', 'Tasnim', 'Nusrat', 'Sabrina', 'Rumana', 'Nazma', 'Sharmin', 'Farzana'];
        $lastNames = ['Hossain', 'Ahmed', 'Rahman', 'Khan', 'Islam', 'Ali', 'Haque', 'Chowdhury', 'Sarkar', 'Mia', 'Begum', 'Akter'];

        $isMale = $this->faker->boolean(60);
        $firstName = $isMale ? $this->faker->randomElement($firstNamesMale) : $this->faker->randomElement($firstNamesFemale);
        $lastName = $this->faker->randomElement($lastNames);

        return $firstName . ' ' . $lastName;
    }

    private function seedCompensationStructure()
    {
        // --- SALARY GRADES ---
        DB::table('salary_grades')->truncate();
        $salaryGrades = [];
        
        for ($i = 1; $i <= 20; $i++) {
            $salaryGrades[] = [
                'id' => $i,
                'grade_code' => 'G' . $i,
                'grade_name' => 'Salary Grade ' . $i,
                'status' => 'active',
                'created_at' => now(), 
                'updated_at' => now(),
            ];
        }
        DB::table('salary_grades')->insert($salaryGrades);

        // --- PAY GROUPS ---
        DB::table('pay_groups')->truncate();
        $payGroups = [
            ['id' => 1, 'title' => 'Monthly Staff', 'payroll_frequency' => 'Monthly', 'salary_processing_day' => '25', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'title' => 'Weekly Labor', 'payroll_frequency' => 'Weekly', 'salary_processing_day' => 'Sunday', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'title' => 'Hourly Workers', 'payroll_frequency' => 'Hourly', 'salary_processing_day' => 'Daily', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'title' => 'Daily Field Worker', 'payroll_frequency' => 'Daily', 'salary_processing_day' => '1', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('pay_groups')->insert($payGroups);

        // --- PAY SCALES ---
        DB::table('pay_scales')->truncate();
        $payScales = [];
        foreach ([1, 2, 3, 4] as $groupId) {
            $groupTitle = match($groupId) {
                1 => 'Monthly Staff',
                2 => 'Weekly Labor',
                3 => 'Hourly Workers',
                4 => 'Daily Field Worker'
            };
            
            // Create more pay scales for Monthly Staff
            $limit = $groupId == 1 ? 15 : 5;
            
            for ($i = 1; $i <= $limit; $i++) {
                if ($groupId == 1) { // Monthly
                    $min = 10000 + ($i * 5000);
                    $max = $min + 20000;
                } elseif ($groupId == 2) { // Weekly
                    $min = 2500 + ($i * 1000);
                    $max = $min + 5000;
                } elseif ($groupId == 3) { // Hourly
                    $min = 100 + ($i * 50);
                    $max = $min + 200;
                } else { // Daily
                    $min = 500 + ($i * 200);
                    $max = $min + 1000;
                }
                
                $gradeId = ($i > 20) ? 20 : $i;

                $payScales[] = [
                    'title' => "$groupTitle - Scale $i",
                    'grade_id' => $gradeId,
                    'pay_group_id' => $groupId,
                    'min_salary' => $min,
                    'max_salary' => $max,
                    'status' => 'active',
                    'created_at' => now(), 
                    'updated_at' => now(),
                ];
            }
        }
        DB::table('pay_scales')->insert($payScales);
    }
}

