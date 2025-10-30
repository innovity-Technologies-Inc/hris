<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class OrganizationSeeder extends Seeder
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
        // --- GROUPS ---
        DB::table('groups')->truncate();
        DB::table('groups')->insert([
            ['id' => 1, 'name' => 'Global Conglomerate Holdings', 'status' => 'active', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // --- COMPANY TYPES ---
        DB::table('company_types')->truncate();
        DB::table('company_types')->insert([
            ['id' => 1, 'name' => 'Technology Sector', 'short_name' => 'TECH', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'Financial Services', 'short_name' => 'FIN', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'Industrial Operations', 'short_name' => 'IND', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    private function seedOrgStructure()
    {
        // --- 1. COMPANIES (10 Entries) ---
        DB::table('companies')->truncate();
        $companies = [];
        $companyNames = [
            'Tech Innovations Corp', 'Global Logistics Partners', 'Apex Financial Services',
            'Aurora Energy Solutions', 'Pinnacle Retail Group', 'HealthSphere Medical',
            'AgriFutures International', 'Urban Development Trust', 'Digital Media Hub', 'Quantum Manufacturing Inc.'
        ];
        foreach ($companyNames as $i => $name) {
            $id = 101 + $i;
            $companies[] = [
                'id' => $id,
                'name' => $name,
                'short_name' => strtoupper(Str::limit($name, 3, '')),
                'type_id' => (($i % 3) + 1),
                'group_id' => 1,
                'address' => $this->faker->address,
                'fax' => $this->faker->numerify('###-###-####'),
                'telephone' => $this->faker->numerify('###-###-####'),
                'email' => strtolower(Str::slug($name)) . '@corp.com',
                'logo' => 'logo_' . $id . '.png',
                'status' => 'active', 'created_at' => now(), 'updated_at' => now()
            ];
        }
        DB::table('companies')->insert($companies);


        // --- 2. COMPANY LOCATIONS (20 Entries) ---
        DB::table('company_locations')->truncate();
        $locations = [];
        for ($i = 0; $i < 20; $i++) {
            $id = 201 + $i;
            $company_id = 101 + ($i % 10);
            $unit_name = ($i % 2 == 0) ? 'HQ: ' . $this->faker->city : 'Reg: ' . $this->faker->city;
            $locations[] = [
                'id' => $id,
                'company_id' => $company_id,
                'unit_name' => $unit_name,
                'location_address' => $this->faker->streetAddress . ', ' . $this->faker->city,
                'status' => 'active', 'created_at' => now(), 'updated_at' => now()
            ];
        }
        DB::table('company_locations')->insert($locations);


        // --- 3. DIVISIONS (30 Entries) ---
        DB::table('divisions')->truncate();
        $divisions = [];
        $divNames = ['Platform Engineering', 'Global Product Strategy', 'Corporate Finance', 'Human Resources Ops', 'Supply Chain & Logistics', 'Digital Marketing', 'Enterprise Sales', 'R&D', 'Manufacturing Operations', 'Regulatory Compliance'];
        for ($i = 0; $i < 30; $i++) {
            $id = 301 + $i;
            $location_id = 201 + ($i % 20);
            $company_id = DB::table('company_locations')->where('id', $location_id)->value('company_id');
            $divName = $divNames[$i % 10];
            $divisions[] = [
                'id' => $id,
                'company_id' => $company_id,
                'location_id' => $location_id,
                'division_name' => $divName . ' - Unit ' . ($i + 1),
                'short_name' => strtoupper(Str::limit($divName, 3, '') . $i),
                'remarks' => null,
                'status' => 'active', 'created_at' => now(), 'updated_at' => now()
            ];
        }
        DB::table('divisions')->insert($divisions);


        // --- 4. DEPARTMENTS (40 Entries) ---
        DB::table('departments')->truncate();
        $departments = [];
        $deptNames = ['Software Development', 'Quality Assurance', 'Financial Modeling', 'Internal Audit', 'Talent Acquisition', 'Employee Training', 'Fleet Management', 'Brand Advertising'];
        for ($i = 0; $i < 40; $i++) {
            $id = 401 + $i;
            $division_id = 301 + ($i % 30);
            $deptName = $deptNames[$i % 8];
            $departments[] = [
                'id' => $id,
                'division_id' => $division_id,
                'department_name' => $deptName . ' Dept ' . ($i + 1),
                'short_name' => strtoupper(Str::limit($deptName, 3, '') . $i),
                'job_number_code' => 'JNC-' . $id,
                'status' => 'active', 'created_at' => now(), 'updated_at' => now()
            ];
        }
        DB::table('departments')->insert($departments);

        // --- 5. SECTIONS (60 Entries) ---
        DB::table('sections')->truncate();
        $sections = [];
        $secNames = ['Front-End Team', 'Back-End Team', 'DevOps', 'Client Support', 'Regulatory Filing', 'Data Analytics'];
        for ($i = 0; $i < 60; $i++) {
            $id = 501 + $i;
            $department_id = 401 + ($i % 40);

            $secName = $secNames[$i % 6] . ' Group';
            $sections[] = [
                'id' => $id,
                'department_id' => $department_id,
                'section_name' => $secName . ' ' . str_pad($i + 1, 2, '0', STR_PAD_LEFT),
                'short_name' => strtoupper(Str::limit($secName, 3, '') . ($i % 10)),
                'status' => 'active', 'created_at' => now(), 'updated_at' => now()
            ];
        }
        DB::table('sections')->insert($sections);

        // --- 6. DESIGNATIONS (50 Entries) ---
        DB::table('designations')->truncate();
        $designations = [];
        $titles = ['Software Engineer', 'Project Manager', 'Financial Analyst', 'HR Coordinator', 'Logistics Specialist', 'Marketing Director', 'Sales Associate', 'Research Scientist'];
        $levels = ['Entry Level', 'Junior', 'Mid', 'Senior', 'Lead', 'VP'];
        for ($i = 0; $i < 50; $i++) {
            $id = 601 + $i;
            $division_id = 301 + ($i % 30);
            $division_data = DB::table('divisions')->where('id', $division_id)->first();

            if ($division_data) {
                $designations[] = [
                    'id' => $id,
                    'company_id' => $division_data->company_id,
                    'location_id' => $division_data->location_id,
                    'division_id' => $division_id,
                    'designation_level' => $levels[$i % 6],
                    'company_designation' => $levels[$i % 6] . ' ' . $titles[$i % 8],
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

            $dept_code = DB::table('departments')->where('id', $department_id)->value('job_number_code');
            $designation_title = DB::table('designations')->where('id', $designation_id)->value('company_designation');

            $jobCreations[] = [
                'id' => $id,
                'designation_id' => $designation_id,
                'department_id' => $department_id,
                'job_ind' => $dept_code . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'display_designation' => Str::limit($designation_title, 20),
                'display_serial' => str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'status' => $this->faker->randomElement(['active', 'inactive']),
                'remarks' => $this->faker->optional(0.2)->sentence(4),
                'created_at' => now(), 'updated_at' => now()
            ];
        }
        DB::table('job_creations')->insert($jobCreations);
    }

    private function seedBankingStructure()
    {
        // --- 8. BANKS (7 Entries) ---
        DB::table('banks')->truncate();
        $banks = [];
        $bankNames = ['First National Bank', 'Global Finance Corp', 'Regional Trust', 'Capital One Asia', 'Zenith Bank PLC', 'Pacific Reserve', 'EuroFund Bank'];
        foreach ($bankNames as $i => $name) {
            $id = 801 + $i;
            $banks[] = [
                'id' => $id,
                'name' => $name,
                'short_name' => strtoupper(Str::limit($name, 4, '')),
                'bank_code' => 'BC' . $id,
                'contact_no' => $this->faker->phoneNumber,
                'contact_person' => $this->faker->name,
                'contact_person_no' => $this->faker->phoneNumber,
                'status' => 'active', 'created_at' => now(), 'updated_at' => now()
            ];
        }
        DB::table('banks')->insert($banks);

        // --- 9. BRANCHES (30 Entries) ---
        DB::table('branches')->truncate();
        $branches = [];
        for ($i = 0; $i < 30; $i++) {
            $id = 901 + $i;
            $bank_id = 801 + ($i % 7);
            $branches[] = [
                'id' => $id,
                'bank_id' => $bank_id,
                'name' => $this->faker->city . ' Branch',
                'address' => $this->faker->address,
                'routing_no' => $this->faker->numerify('#########'),
                'swift_code' => strtoupper(Str::random(10)),
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
                'account_no' => $this->faker->numerify('############'),
                'holder_name' => $this->faker->company . ' Payroll Acct',
                'account_type' => $accountTypes[$i % 3],
                'contact_person' => $this->faker->name('male'),
                'contact_person_no' => $this->faker->phoneNumber,
                'email' => $this->faker->companyEmail,
                'status' => $this->faker->randomElement(['active', 'inactive']),
                'created_at' => now(), 'updated_at' => now()
            ];
        }
        DB::table('bank_accounts')->insert($accounts);
    }

    private function seedCompensationStructure()
    {
        // --- TOFSILS (Types of Functional Levels/Segments) ---
        DB::table('tofsils')->truncate();
        $tofsils = [
            ['id' => 1, 'name' => 'Executive Management', 'description' => 'Top-tier management roles.', 'status' => 'active'],
            ['id' => 2, 'name' => 'Professional Staff', 'description' => 'Specialized roles requiring degrees/certifications.', 'status' => 'active'],
            ['id' => 3, 'name' => 'Technical Support', 'description' => 'Skilled trades and support staff.', 'status' => 'active'],
            ['id' => 4, 'name' => 'Administrative', 'description' => 'General office and clerical roles.', 'status' => 'active'],
        ];
        DB::table('tofsils')->insert($tofsils);

        // --- SALARY GRADES (Linked to Tofsils) ---
        DB::table('salary_grades')->truncate();
        $salaryGrades = [];
        $gradeLetters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
        $tofsilIds = [1, 2, 3, 4];

        foreach ($tofsilIds as $tofsil_id) {
            foreach ($gradeLetters as $grade) {
                $salaryGrades[] = [
                    'name' => 'G-' . $tofsil_id . $grade,
                    'tofsil_id' => $tofsil_id,
                    'status' => 'active',
                    'created_at' => now(), 'updated_at' => now(),
                ];
            }
        }
        DB::table('salary_grades')->insert($salaryGrades);
    }
}
