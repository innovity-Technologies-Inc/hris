<?php

/**
 * Single Employee Object with All Related Data
 * Access all employee information through: $employee->property
 * Generated: 2025-11-26
 * 
 * Usage Example:
 * $employee = require 'dummy_employee_data.php';
 * echo $employee['full_name'];                    // John Michael Doe
 * echo $employee['office_info']['emp_type'];      // Permanent
 * echo $employee['salary']['gross_salary'];       // 100000.00
 * echo $employee['bank_accounts'][0]['account_number'];
 * echo $employee['educations'][0]['degree'];
 */

return [
    // ========================================
    // BASIC INFORMATION
    // ========================================
    'id' => 1,
    'applicant_id' => 'APP-2024-001',
    'system_id' => 'EMP-2024-001',
    'punch_card_no' => 'PC-12345',
    
    // Personal Information
    'first_name' => 'John',
    'last_name' => 'Doe',
    'middle_name' => 'Michael',
    'full_name' => 'John Michael Doe',
    'father_name' => 'Robert Doe',
    'mother_name' => 'Mary Doe',
    'spouse_name' => 'Jane Doe',
    'marital_status' => 'Married',
    'gender' => 'Male',
    'religion' => 'Christianity',
    'nationality' => 'American',
    'height_feet' => 5,
    'height_inches' => 10,
    'children_count' => 2,
    
    // Identification Documents
    'tin' => '123-45-6789',
    'passport_no' => 'P12345678',
    'passport_expiry' => '2028-12-31',
    'license_no' => 'DL-987654321',
    'license_expiry' => '2027-06-30',
    'visa_expiry' => '2026-12-31',
    'work_expiry' => '2027-12-31',
    'residency_id_number' => 'RES-123456789',
    'date_of_birth' => '1990-05-15',
    'birth_country' => 'United States',
    'birth_reg_no' => 'BR-1990-12345',
    
    // Contact Information
    'personal_mobile' => '+1-555-123-4567',
    'home_phone' => '+1-555-987-6543',
    'work_mobile' => '+1-555-234-5678',
    'work_phone' => '+1-555-876-5432',
    'work_email' => 'john.doe@company.com',
    'personal_email' => 'john.doe@gmail.com',
    
    // File Attachments
    'photo_path' => 'storage/employees/photos/john_doe.jpg',
    'fingerprint_path' => 'storage/employees/fingerprints/john_doe.dat',
    'signature_path' => 'storage/employees/signatures/john_doe.png',
    'experience_attachment_path' => 'storage/employees/experience/john_doe_experience.pdf',
    
    // Address Information
    'present_address' => [
        'address_line' => '123 Main Street, Apartment 4B',
        'village' => 'Downtown',
        'post_office' => 'Central Post Office',
        'thana' => 'City Center',
        'district' => 'Metropolitan District',
        'state' => 'California',
        'zip_code' => '90001',
        'country' => 'United States',
    ],
    'permanent_address' => [
        'address_line' => '456 Oak Avenue',
        'village' => 'Greenfield',
        'post_office' => 'Greenfield PO',
        'thana' => 'Greenfield Thana',
        'district' => 'Rural District',
        'state' => 'Texas',
        'zip_code' => '75001',
        'country' => 'United States',
    ],
    'reference_address' => [
        'address_line' => '789 Pine Road',
        'village' => 'Riverside',
        'post_office' => 'Riverside PO',
        'thana' => 'Riverside Thana',
        'district' => 'Suburban District',
        'state' => 'New York',
        'zip_code' => '10001',
        'country' => 'United States',
    ],
    
    'created_at' => '2024-01-15 09:00:00',
    'updated_at' => '2024-11-26 10:30:00',

    // ========================================
    // OFFICE INFORMATION
    // ========================================
    'office_info' => [
        'emp_type' => 'Permanent',
        'grade_id' => 5,
        'hr_file_no' => 'HR-2024-001',
        'tofsil_id' => 1,
        'file_note' => 'Senior employee with excellent performance record',
        
        // Joining Information
        'joining_company_id' => 1,
        'joining_business_unit_id' => 1,
        'joining_division_id' => 1,
        'joining_department_id' => 3,
        'joining_section_id' => 5,
        'joining_designation_id' => 8,
        'date_of_join' => '2020-01-15',
        
        // Current Information
        'current_company_id' => 1,
        'current_business_unit_id' => 1,
        'current_division_id' => 1,
        'current_department_id' => 3,
        'current_section_id' => 5,
        'current_designation_id' => 10,
        
        // Orientation & Probation
        'orientation_required' => 1,
        'orientation_from' => '2020-01-15',
        'orientation_to' => '2020-01-22',
        'orientation_type' => 'In-house',
        'orientation_days' => 7,
        'confirmation_date' => '2020-07-15',
        'probation_duration' => 6,
        
        // Career Development
        'next_promotion_date' => '2025-01-15',
        'promotion_cycle' => 24,
        'increment_cycle' => 12,
        
        // Work Schedule
        'weekends' => ['Saturday', 'Sunday'],
        'alternate_off_day' => [],
        
        // Benefits & Eligibility
        'ot_allowed' => 1,
        'pf_eligible' => 1,
        'salary_type' => 'Monthly',
        'transport_eligible' => 1,
        'can_apply_loan' => 1,
        'pf_effective_date' => '2020-07-15',
        'can_apply_advance' => 1,
        'gratuity_eligible' => 1,
    ],

    // ========================================
    // BANK ACCOUNTS
    // ========================================
    'bank_accounts' => [
        [
            'bank_id' => 1,
            'branch_id' => 5,
            'account_holder_name' => 'John Michael Doe',
            'account_number' => '1234567890123',
            'status' => 'Active',
            'remarks' => 'Primary salary account',
            'created_at' => '2024-01-15 10:00:00',
            'updated_at' => '2024-01-15 10:00:00',
        ],
        [
            'bank_id' => 2,
            'branch_id' => 8,
            'account_holder_name' => 'John Michael Doe',
            'account_number' => '9876543210987',
            'status' => 'Inactive',
            'remarks' => 'Secondary account',
            'created_at' => '2024-02-01 11:00:00',
            'updated_at' => '2024-03-15 14:20:00',
        ],
    ],

    // ========================================
    // EDUCATION, EXPERIENCE & TRAINING
    // ========================================
    // Education Details
    'educations' => [
            [
                'degree' => 'Bachelor of Science in Computer Science',
                'institution' => 'Stanford University',
                'board_university' => 'Stanford University',
                'passing_year' => '2012',
                'result' => 'GPA 3.85/4.00',
                'major' => 'Computer Science',
                'duration' => '4 years',
            ],
            [
                'degree' => 'Master of Business Administration',
                'institution' => 'Harvard Business School',
                'board_university' => 'Harvard University',
                'passing_year' => '2015',
                'result' => 'GPA 3.92/4.00',
                'major' => 'Business Administration',
                'duration' => '2 years',
            ],
        ],
    
    // Experience Details
    'experiences' => [
        [
            'company_name' => 'Tech Innovations Inc.',
                'designation' => 'Software Engineer',
                'department' => 'Engineering',
                'from_date' => '2012-07-01',
                'to_date' => '2015-12-31',
                'duration' => '3.5 years',
                'responsibilities' => 'Developed enterprise software solutions, led team of 5 developers',
                'salary' => '75000',
            ],
            [
                'company_name' => 'Digital Solutions Corp.',
                'designation' => 'Senior Software Engineer',
                'department' => 'Product Development',
                'from_date' => '2016-01-15',
                'to_date' => '2019-12-31',
                'duration' => '4 years',
                'responsibilities' => 'Architected scalable systems, managed development team',
                'salary' => '95000',
            ],
        ],
    
    // Training Details
    'trainings' => [
        [
                'training_title' => 'Advanced Project Management',
                'training_institute' => 'Project Management Institute',
                'training_year' => '2018',
                'duration' => '3 months',
                'topics_covered' => 'Agile, Scrum, Risk Management, Stakeholder Management',
                'certificate' => 'PMP Certified',
            ],
            [
                'training_title' => 'Leadership Development Program',
                'training_institute' => 'Leadership Academy',
                'training_year' => '2021',
                'duration' => '6 months',
                'topics_covered' => 'Team Leadership, Strategic Planning, Communication Skills',
                'certificate' => 'Leadership Certificate',
            ],
            [
                'training_title' => 'Cloud Computing & DevOps',
                'training_institute' => 'AWS Training Center',
                'training_year' => '2022',
                'duration' => '2 months',
                'topics_covered' => 'AWS Services, CI/CD, Kubernetes, Docker',
                'certificate' => 'AWS Solutions Architect',
            ],
        ],

    // ========================================
    // NOMINEES
    // ========================================
    'nominees' => [
        [
            'nominee_name' => 'Jane Doe',
            'father_name' => 'William Smith',
            'mother_name' => 'Elizabeth Smith',
            'spouse_name' => 'John Michael Doe',
            'gender' => 'Female',
            'date_of_birth' => '1992-08-20',
            'religion' => 'Christianity',
            'marital_status' => 'Married',
            'nationality' => 'American',
            'blood_group' => 'O+',
            'photo_path' => 'storage/nominees/photos/jane_doe.jpg',
            'nid' => 'NID-987654321',
            'birth_reg_no' => 'BR-1992-54321',
            'phone' => '+1-555-111-2222',
            'mobile' => '+1-555-222-3333',
            'present_address_line' => '123 Main Street, Apartment 4B',
            'village' => 'Downtown',
            'post_office' => 'Central Post Office',
            'thana' => 'City Center',
            'district' => 'Metropolitan District',
            'state' => 'California',
            'zip_code' => '90001',
            'country' => 'United States',
            'created_at' => '2024-01-15 12:00:00',
            'updated_at' => '2024-01-15 12:00:00',
        ],
        [
            'nominee_name' => 'Emily Doe',
            'father_name' => 'John Michael Doe',
            'mother_name' => 'Jane Doe',
            'spouse_name' => null,
            'gender' => 'Female',
            'date_of_birth' => '2015-03-10',
            'religion' => 'Christianity',
            'marital_status' => 'Single',
            'nationality' => 'American',
            'blood_group' => 'A+',
            'photo_path' => 'storage/nominees/photos/emily_doe.jpg',
            'nid' => null,
            'birth_reg_no' => 'BR-2015-11111',
            'phone' => null,
            'mobile' => null,
            'present_address_line' => '123 Main Street, Apartment 4B',
            'village' => 'Downtown',
            'post_office' => 'Central Post Office',
            'thana' => 'City Center',
            'district' => 'Metropolitan District',
            'state' => 'California',
            'zip_code' => '90001',
            'country' => 'United States',
            'created_at' => '2024-01-15 12:15:00',
            'updated_at' => '2024-01-15 12:15:00',
        ],
    ],

    // ========================================
    // SALARY BREAKDOWN
    // ========================================
    'salary' => [
        'basic_salary' => 60000.00,
        'house_allowance' => 15000.00,
        'transport_allowance' => 8000.00,
        'food_allowance' => 5000.00,
        'medical_allowance' => 7000.00,
        'other_earnings' => 5000.00,
        'gross_salary' => 100000.00,
        'currency' => 'USD',
    ],

    // ========================================
    // SHIFT PLANS
    // ========================================
    'shift_plans' => [
        [
            'plan_id' => 1,
            'from' => '2024-01-01',
            'to' => '2024-06-30',
            'status' => 'Inactive',
            'created_at' => '2024-01-01 08:00:00',
            'updated_at' => '2024-07-01 08:00:00',
        ],
        [
            'plan_id' => 2,
            'from' => '2024-07-01',
            'to' => '2025-12-31',
            'status' => 'Active',
            'created_at' => '2024-07-01 08:00:00',
            'updated_at' => '2024-07-01 08:00:00',
        ],
    ],

    // ========================================
    // MEAL PLANS
    // ========================================
    'meal_plans' => [
        [
            'plan_id' => 1,
            'from' => '2024-01-15',
            'to' => '2025-12-31',
            'status' => 'Active',
            'created_at' => '2024-01-15 14:00:00',
            'updated_at' => '2024-01-15 14:00:00',
        ],
    ],

    // ========================================
    // OFF-DAY PLANS
    // ========================================
    'offday_plans' => [
        [
            'plan_id' => 1,
            'from' => '2024-01-15',
            'to' => '2025-12-31',
            'status' => 'Active',
            'created_at' => '2024-01-15 14:30:00',
            'updated_at' => '2024-01-15 14:30:00',
        ],
    ],

    // ========================================
    // OT (OVERTIME) PLANS
    // ========================================
    'ot_plans' => [
        [
            'plan_id' => 1,
            'from' => '2024-01-15',
            'to' => '2025-12-31',
            'status' => 'Active',
            'created_at' => '2024-01-15 15:00:00',
            'updated_at' => '2024-01-15 15:00:00',
        ],
    ],

    // ========================================
    // ROSTER PLANS
    // ========================================
    'roster_plans' => [
        [
            'plan_id' => 1,
            'from' => '2024-01-15',
            'to' => '2024-06-30',
            'status' => 'Inactive',
            'created_at' => '2024-01-15 15:30:00',
            'updated_at' => '2024-07-01 08:00:00',
        ],
        [
            'plan_id' => 2,
            'from' => '2024-07-01',
            'to' => '2025-12-31',
            'status' => 'Active',
            'created_at' => '2024-07-01 08:00:00',
            'updated_at' => '2024-07-01 08:00:00',
        ],
    ],

    // ========================================
    // ELIGIBLE PLANS (ALL PLANS ELIGIBILITY)
    // ========================================
    'eligible_plans' => [
        
        // Shift Plan
        'shift_plan_from' => '2024-01-15',
        'shift_plan_to' => '2025-12-31',
        'shift_plan_status' => 'Active',
        
        // Leave Plan
        'leave_plan_from' => '2024-01-15',
        'leave_plan_to' => '2025-12-31',
        'leave_plan_status' => 'Active',
        
        // OT Plan
        'ot_plan_from' => '2024-01-15',
        'ot_plan_to' => '2025-12-31',
        'ot_plan_status' => 'Active',
        
        // Attendance Bonus Plan
        'attendance_bonus_plan_from' => '2024-01-15',
        'attendance_bonus_plan_to' => '2025-12-31',
        'attendance_bonus_plan_status' => 'Active',
        
        // Day Off Work Plan
        'day_off_work_plan_from' => '2024-01-15',
        'day_off_work_plan_to' => '2025-12-31',
        'day_off_work_plan_status' => 'Active',
        
        // Roster Plans
        'roster_plans_from' => '2024-01-15',
        'roster_plans_to' => '2025-12-31',
        'roster_plans_status' => 'Active',
        
        // Bonus Plan
        'bonus_plan_from' => '2024-01-15',
        'bonus_plan_to' => '2025-12-31',
        'bonus_plan_status' => 'Active',
        
        // Allowance Plan
        'allowance_plan_from' => '2024-01-15',
        'allowance_plan_to' => '2025-12-31',
        'allowance_plan_status' => 'Active',
        
        // Late Deduction Plan
        'late_deduction_plan_from' => '2024-01-15',
        'late_deduction_plan_to' => '2025-12-31',
        'late_deduction_plan_status' => 'Active',
        
        // Production Plan
        'production_plan_from' => '2024-01-15',
        'production_plan_to' => '2025-12-31',
        'production_plan_status' => 'Active',
        
        // Early Out Deduction Plan
        'early_out_deduction_plan_from' => '2024-01-15',
        'early_out_deduction_plan_to' => '2025-12-31',
        'early_out_deduction_plan_status' => 'Active',
        
        // Salary Breakdown Plan
        'salary_breakdown_plan_from' => '2024-01-15',
        'salary_breakdown_plan_to' => '2025-12-31',
        'salary_breakdown_plan_status' => 'Active',
        
        // Medical Plan
        'medical_plan_from' => '2024-01-15',
        'medical_plan_to' => '2025-12-31',
        'medical_plan_status' => 'Active',
        
        // Night Bill Plan
        'night_bill_plan_from' => '2024-01-15',
        'night_bill_plan_to' => '2025-12-31',
        'night_bill_plan_status' => 'Active',
        
        // Tiffin Plan
        'tiffin_plan_from' => '2024-01-15',
        'tiffin_plan_to' => '2025-12-31',
        'tiffin_plan_status' => 'Active',
        
        // Dinner Plan
        'dinner_plan_from' => '2024-01-15',
        'dinner_plan_to' => '2025-12-31',
        'dinner_plan_status' => 'Active',
        
        // Breakfast Plan
        'breakfast_plan_from' => '2024-01-15',
        'breakfast_plan_to' => '2025-12-31',
        'breakfast_plan_status' => 'Active',
        
        // Food Compensation Plan
        'food_com_plan_from' => '2024-01-15',
        'food_com_plan_to' => '2025-12-31',
        'food_com_plan_status' => 'Active',
        
        // Excessive Late Plan
        'excessive_late_plan_from' => '2024-01-15',
        'excessive_late_plan_to' => '2025-12-31',
        'excessive_late_plan_status' => 'Active',
        
        // Lunch Plan
        'lunch_plan_from' => '2024-01-15',
        'lunch_plan_to' => '2025-12-31',
        'lunch_plan_status' => 'Active',
        
        // Snacks Plan
        'snacks_plan_from' => '2024-01-15',
        'snacks_plan_to' => '2025-12-31',
        'snacks_plan_status' => 'Active',
    ],
];

