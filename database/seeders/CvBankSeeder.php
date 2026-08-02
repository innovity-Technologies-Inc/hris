<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Onboarding\CvBank;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class CvBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Copy the dummy resume file to public storage
        $sourcePath = base_path('dummy/dummy_resume.pdf');
        $targetDir = storage_path('app/public/cv_bank');
        $targetPath = $targetDir . '/dummy_resume.pdf';

        if (File::exists($sourcePath)) {
            File::ensureDirectoryExists($targetDir);
            File::copy($sourcePath, $targetPath);
        }

        // 2. Truncate existing CVs
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        CvBank::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 3. Define list of elements to generate mock CVs
        $designations = [
            'Software Engineer', 'Senior Developer', 'Product Manager', 'UX Designer',
            'QA Engineer', 'DevOps Specialist', 'System Analyst', 'Data Scientist',
            'Frontend Engineer', 'Backend Developer', 'HR Executive', 'Recruiter',
            'Business Analyst', 'Solution Architect', 'Technical Writer', 'Scrum Master'
        ];

        $companies = [
            'Google', 'Microsoft', 'Meta', 'Amazon', 'Netflix', 'TechCorp',
            'Innovity Technologies', 'SoftSolutions', 'AppForge', 'WebWorks',
            'CloudSphere', 'DataMetrics'
        ];

        $careerLevels = ['Entry', 'Mid', 'Senior', 'Executive'];

        $firstNames = ['John', 'Jane', 'Michael', 'Emily', 'David', 'Sarah', 'James', 'Jessica', 'Robert', 'Karen', 'William', 'Ashley', 'Richard', 'Amanda', 'Thomas', 'Melissa'];
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Gonzalez', 'Wilson', 'Anderson', 'Thomas'];

        // Seed 30 entries
        for ($i = 1; $i <= 30; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $applicantName = $firstName . ' ' . $lastName;
            
            $designation = $designations[array_rand($designations)];
            $company = $companies[array_rand($companies)];
            $careerLevel = $careerLevels[array_rand($careerLevels)];
            $cvScore = rand(40, 100);

            CvBank::create([
                'company_name' => $company,
                'designation' => $designation,
                'applicant_name' => $applicantName,
                'career_level' => $careerLevel,
                'cv_score' => $cvScore,
                'attachment_path' => 'cv_bank/dummy_resume.pdf',
            ]);
        }

        $this->command->info('Seeded 30 CVs into the CV Bank successfully.');
    }
}
