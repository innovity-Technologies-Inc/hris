<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class OrganizationStructureSeeder extends Seeder
{
    private $faker;
    private $companies = [];
    private $groups = [];
    private $employees = [];

    // Board Member position templates
    private $boardPositions = [
        'group' => ['Group Chairman', 'Group CEO', 'Group CFO', 'Group Director', 'Group VP'],
        'company' => ['Chairman', 'Managing Director', 'CEO', 'CFO', 'Company Secretary', 'Director', 'Board Member']
    ];

    // Key Member position templates
    private $keyPositions = [
        'Managing Director',
        'CEO',
        'CFO',
        'COO',
        'CTO',
        'General Manager',
        'Senior Manager',
        'Manager',
        'Department Head',
        'Division Head',
        'Team Lead',
        'Supervisor'
    ];

    // Bangladesh male and female names
    private $bdMaleFirstNames = [
        'Mohammad', 'Abdul', 'Rahman', 'Karim', 'Zahid', 'Tanvir', 'Sakib', 'Rashed', 'Faruk', 'Arif',
        'Shahriar', 'Naim', 'Mahmud', 'Hasan', 'Hussain', 'Ali', 'Imran', 'Rakib', 'Sadiq', 'Ashraf'
    ];

    private $bdFemaleFirstNames = [
        'Fatema', 'Ayesha', 'Sumaiya', 'Tasnim', 'Nusrat', 'Sabrina', 'Rumana', 'Nazma', 'Sharmin', 'Farzana',
        'Tahmina', 'Rehana', 'Salma', 'Halima', 'Rabeya', 'Jasmine', 'Nasrin', 'Parveen', 'Shabnam', 'Munmun'
    ];

    private $bdLastNames = [
        'Hossain', 'Ahmed', 'Rahman', 'Khan', 'Islam', 'Ali', 'Haque', 'Chowdhury', 'Sarkar', 'Mia',
        'Begum', 'Akter', 'Sheikh', 'Mallick', 'Pradhan', 'Biswas', 'Das', 'Pal', 'Ghosh', 'Roy'
    ];

    private $bdCities = [
        'Dhaka', 'Chittagong', 'Khulna', 'Rajshahi', 'Sylhet', 'Rangpur', 'Barishal', 'Comilla',
        'Gazipur', 'Narayanganj', 'Mymensingh', 'Bogra', 'Jessore', "Cox's Bazar", 'Dinajpur'
    ];

    private $bdAreas = [
        'Mirpur', 'Uttara', 'Gulshan', 'Banani', 'Dhanmondi', 'Mohammadpur', 'Motijheel', 'Kakrail',
        'Tejgaon', 'Shantinagar', 'Badda', 'Rampura', 'Khilgaon', 'Mogbazar', 'Shahbag', 'Farmgate'
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->faker = class_exists(Faker::class) ? Faker::create() : null;

        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('organization_structure')->truncate();

        $this->loadOrgData();
        $this->seedBoardMembers();
        $this->seedKeyMembers();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('Organization Structure seeded successfully!');
    }

    private function loadOrgData()
    {
        // Load groups, companies, and ALL employees
        $this->groups = DB::table('groups')->select('id', 'name')->get()->toArray();
        $this->companies = DB::table('companies')->select('id', 'name', 'group_id')->get()->toArray();
        $this->employees = DB::table('employees')->select('id', 'first_name', 'last_name')->get()->toArray();

        $this->command->info("Loaded: " . count($this->groups) . " groups, " . count($this->companies) . " companies, " . count($this->employees) . " employees");
    }

    private function seedBoardMembers()
    {
        $this->command->info('Seeding Board Members...');
        $boardMembers = [];

        // Create 5-10 board members per company
        foreach ($this->companies as $company) {
            $boardMemberCount = rand(5, 10);

            for ($i = 0; $i < $boardMemberCount; $i++) {
                $isFemale = rand(0, 100) < 30; // 30% chance of female
                $firstName = $isFemale
                    ? $this->bdFemaleFirstNames[array_rand($this->bdFemaleFirstNames)]
                    : $this->bdMaleFirstNames[array_rand($this->bdMaleFirstNames)];
                $lastName = $this->bdLastNames[array_rand($this->bdLastNames)];
                $fullName = $firstName . ' ' . $lastName;

                // Select position from company board positions
                $position = $this->boardPositions['company'][array_rand($this->boardPositions['company'])];

                $boardMembers[] = [
                    'name' => $fullName,
                    'member_type' => 'Board Member',
                    'type' => 'Company',
                    'group_id' => $company->group_id,
                    'company_id' => $company->id,
                    'branch_unit_id' => null,
                    'division_id' => null,
                    'department_id' => null,
                    'section_id' => null,
                    'employee_id' => null,
                    'position' => $position,
                    'contact_no' => '+880 ' . $this->generateBDPhone(),
                    'email' => strtolower(str_replace(' ', '.', $fullName)) . '@' . $this->generateEmailDomain($company->name),
                    'address' => $this->generateBDAddress(),
                    'photo_path' => null,
                    'status' => rand(0, 100) < 90 ? 'Active' : 'Inactive', // 90% active
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert in chunks
        foreach (array_chunk($boardMembers, 100) as $chunk) {
            DB::table('organization_structure')->insert($chunk);
        }

        $this->command->info('Board Members seeded: ' . count($boardMembers));
    }

    private function seedKeyMembers()
    {
        $this->command->info('Seeding Key Members...');
        $keyMembers = [];

        // Shuffle employees to ensure random assignment
        shuffle($this->employees);
        $employeeIndex = 0;

        // Load organizational structure data
        $branches = DB::table('company_locations')->select('id', 'company_id', 'name')->get()->toArray();
        $divisions = DB::table('divisions')->select('id', 'company_id', 'location_id', 'name')->get()->toArray();
        $departments = DB::table('departments')->select('id', 'company_id', 'location_id', 'division_id', 'department_name')->get()->toArray();
        $sections = DB::table('sections')->select('id', 'company_id', 'location_id', 'division_id', 'department_id', 'name')->get()->toArray();

        // Create key members with full organizational hierarchy
        // 1. Company level key members (2-5 per company)
        foreach ($this->companies as $company) {
            $companyKeyMemberCount = rand(2, 5);

            for ($i = 0; $i < $companyKeyMemberCount; $i++) {
                if ($employeeIndex >= count($this->employees)) {
                    $this->command->warn('Not enough employees for all Key Member positions. Stopping at ' . count($keyMembers) . ' Key Members.');
                    break 2; // Exit both loops
                }

                $employee = $this->employees[$employeeIndex++];
                $position = $this->keyPositions[array_rand($this->keyPositions)];

                $keyMembers[] = [
                    'name' => $employee->first_name . ' ' . $employee->last_name,
                    'member_type' => 'Key Member',
                    'type' => 'Company',
                    'group_id' => null,
                    'company_id' => $company->id,
                    'branch_unit_id' => null,
                    'division_id' => null,
                    'department_id' => null,
                    'section_id' => null,
                    'employee_id' => $employee->id,
                    'position' => $position,
                    'contact_no' => '+880 ' . $this->generateBDPhone(),
                    'email' => strtolower($employee->first_name . '.' . $employee->last_name) . '@' . $this->generateEmailDomain($company->name),
                    'address' => $this->generateBDAddress(),
                    'photo_path' => null,
                    'status' => 'Active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // 2. Branch level key members (1-2 per branch)
        foreach ($branches as $branch) {
            $branchKeyMemberCount = rand(1, 2);

            for ($i = 0; $i < $branchKeyMemberCount; $i++) {
                if ($employeeIndex >= count($this->employees)) break 2;

                $employee = $this->employees[$employeeIndex++];
                $position = rand(0, 1) ? 'Branch Manager' : 'Branch Operations Manager';

                $keyMembers[] = [
                    'name' => $employee->first_name . ' ' . $employee->last_name,
                    'member_type' => 'Key Member',
                    'type' => 'Branch Unit',
                    'group_id' => null,
                    'company_id' => $branch->company_id,
                    'branch_unit_id' => $branch->id,
                    'division_id' => null,
                    'department_id' => null,
                    'section_id' => null,
                    'employee_id' => $employee->id,
                    'position' => $position,
                    'contact_no' => '+880 ' . $this->generateBDPhone(),
                    'email' => strtolower($employee->first_name . '.' . $employee->last_name) . '@company.com',
                    'address' => $this->generateBDAddress(),
                    'photo_path' => null,
                    'status' => 'Active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // 3. Division level key members (1 per division)
        foreach ($divisions as $division) {
            if ($employeeIndex >= count($this->employees)) break;

            $employee = $this->employees[$employeeIndex++];

            $keyMembers[] = [
                'name' => $employee->first_name . ' ' . $employee->last_name,
                'member_type' => 'Key Member',
                'type' => 'Division',
                'group_id' => null,
                'company_id' => $division->company_id,
                'branch_unit_id' => $division->location_id,
                'division_id' => $division->id,
                'department_id' => null,
                'section_id' => null,
                'employee_id' => $employee->id,
                'position' => 'Division Head',
                'contact_no' => '+880 ' . $this->generateBDPhone(),
                'email' => strtolower($employee->first_name . '.' . $employee->last_name) . '@company.com',
                'address' => $this->generateBDAddress(),
                'photo_path' => null,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // 4. Department level key members (1 per department)
        foreach ($departments as $department) {
            if ($employeeIndex >= count($this->employees)) break;

            $employee = $this->employees[$employeeIndex++];

            $keyMembers[] = [
                'name' => $employee->first_name . ' ' . $employee->last_name,
                'member_type' => 'Key Member',
                'type' => 'Department',
                'group_id' => null,
                'company_id' => $department->company_id,
                'branch_unit_id' => $department->location_id,
                'division_id' => $department->division_id,
                'department_id' => $department->id,
                'section_id' => null,
                'employee_id' => $employee->id,
                'position' => 'Department Head',
                'contact_no' => '+880 ' . $this->generateBDPhone(),
                'email' => strtolower($employee->first_name . '.' . $employee->last_name) . '@company.com',
                'address' => $this->generateBDAddress(),
                'photo_path' => null,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // 5. Section level key members (1 per section - 50% of sections)
        $selectedSections = array_rand($sections, min(count($sections), (int)(count($sections) * 0.5)));
        if (!is_array($selectedSections)) $selectedSections = [$selectedSections];

        foreach ($selectedSections as $index) {
            if ($employeeIndex >= count($this->employees)) break;

            $section = $sections[$index];
            $employee = $this->employees[$employeeIndex++];

            $keyMembers[] = [
                'name' => $employee->first_name . ' ' . $employee->last_name,
                'member_type' => 'Key Member',
                'type' => 'Section',
                'group_id' => null,
                'company_id' => $section->company_id,
                'branch_unit_id' => $section->location_id,
                'division_id' => $section->division_id,
                'department_id' => $section->department_id,
                'section_id' => $section->id,
                'employee_id' => $employee->id,
                'position' => rand(0, 1) ? 'Section Head' : 'Supervisor',
                'contact_no' => '+880 ' . $this->generateBDPhone(),
                'email' => strtolower($employee->first_name . '.' . $employee->last_name) . '@company.com',
                'address' => $this->generateBDAddress(),
                'photo_path' => null,
                'status' => 'Active',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert in chunks
        foreach (array_chunk($keyMembers, 100) as $chunk) {
            DB::table('organization_structure')->insert($chunk);
        }

        $this->command->info('Key Members seeded: ' . count($keyMembers));
    }

    private function generateBDPhone()
    {
        // Bangladesh mobile numbers: 1XXX-XXXXXX
        $operators = ['3', '4', '5', '6', '7', '8', '9']; // Grameenphone, Banglalink, Robi, Airtel
        return '1' . $operators[array_rand($operators)] . rand(10, 99) . '-' . rand(100000, 999999);
    }

    private function generateEmailDomain($companyName)
    {
        // Generate email domain from company name
        $cleanName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', explode(' ', $companyName)[0]));
        return $cleanName . '.com';
    }

    private function generateBDAddress()
    {
        $area = $this->bdAreas[array_rand($this->bdAreas)];
        $city = $this->bdCities[array_rand($this->bdCities)];
        $houseNo = rand(1, 999);
        $roadNo = rand(1, 50);

        return "House #{$houseNo}, Road #{$roadNo}, {$area}, {$city}, Bangladesh";
    }
}



