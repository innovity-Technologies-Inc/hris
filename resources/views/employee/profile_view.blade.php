@php
    // Single Employee Object with All Related Data
    $employee = (object) [
        // Basic Information
        'id' => 1,
        'applicant_id' => 'APP-2024-001',
        'system_id' => 'EMP-2024-001',
        'punch_card_no' => 'PC-12345',
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
        'personal_mobile' => '+1-555-123-4567',
        'home_phone' => '+1-555-987-6543',
        'work_mobile' => '+1-555-234-5678',
        'work_phone' => '+1-555-876-5432',
        'work_email' => 'john.doe@company.com',
        'personal_email' => 'john.doe@gmail.com',
        'photo_path' => 'storage/employees/photos/john_doe.jpg',
        'fingerprint_path' => 'storage/employees/fingerprints/john_doe.dat',
        'signature_path' => 'storage/employees/signatures/john_doe.png',
        'experience_attachment_path' => 'storage/employees/experience/john_doe_experience.pdf',
        'present_address' => (object) [
            'address_line' => '123 Main Street, Apartment 4B',
            'village' => 'Downtown',
            'post_office' => 'Central Post Office',
            'thana' => 'City Center',
            'district' => 'Metropolitan District',
            'state' => 'California',
            'zip_code' => '90001',
            'country' => 'United States',
        ],
        'permanent_address' => (object) [
            'address_line' => '456 Oak Avenue',
            'village' => 'Greenfield',
            'post_office' => 'Greenfield PO',
            'thana' => 'Greenfield Thana',
            'district' => 'Rural District',
            'state' => 'Texas',
            'zip_code' => '75001',
            'country' => 'United States',
        ],
        'reference_address' => (object) [
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

        // Office Information
        'office_info' => (object) [
            'emp_type' => 'Permanent',
            'grade_id' => 5,
            'hr_file_no' => 'HR-2024-001',
            'tofsil_id' => 1,
            'file_note' => 'Senior employee with excellent performance record',
            'joining_company_id' => 1,
            'joining_business_unit_id' => 1,
            'joining_division_id' => 1,
            'joining_department_id' => 3,
            'joining_section_id' => 5,
            'joining_designation_id' => 8,
            'date_of_join' => '2020-01-15',
            'current_company_id' => 1,
            'current_business_unit_id' => 1,
            'current_division_id' => 1,
            'current_department_id' => 3,
            'current_section_id' => 5,
            'current_designation_id' => 10,
            'orientation_required' => 1,
            'orientation_from' => '2020-01-15',
            'orientation_to' => '2020-01-22',
            'orientation_type' => 'In-house',
            'orientation_days' => 7,
            'confirmation_date' => '2020-07-15',
            'probation_duration' => 6,
            'next_promotion_date' => '2025-01-15',
            'promotion_cycle' => 24,
            'increment_cycle' => 12,
            'weekends' => ['Saturday', 'Sunday'],
            'alternate_off_day' => [],
            'ot_allowed' => 1,
            'pf_eligible' => 1,
            'salary_type' => 'Monthly',
            'transport_eligible' => 1,
            'can_apply_loan' => 1,
            'pf_effective_date' => '2020-07-15',
            'can_apply_advance' => 1,
            'gratuity_eligible' => 1,
        ],

        // Bank Accounts
        'bank_accounts' => [
            (object) [
                'bank_id' => 1,
                'bank_name' => 'First National Bank',
                'branch_id' => 5,
                'branch_name' => 'Downtown Branch',
                'account_holder_name' => 'John Michael Doe',
                'account_number' => '1234567890123',
                'status' => 'Active',
                'remarks' => 'Primary salary account',
            ],
            (object) [
                'bank_id' => 2,
                'bank_name' => 'United Commercial Bank',
                'branch_id' => 8,
                'branch_name' => 'Corporate Branch',
                'account_holder_name' => 'John Michael Doe',
                'account_number' => '9876543210987',
                'status' => 'Inactive',
                'remarks' => 'Secondary account',
            ],
        ],

        // Education
        'educations' => [
            (object) [
                'degree' => 'Bachelor of Science in Computer Science',
                'institution' => 'Stanford University',
                'board_university' => 'Stanford University',
                'passing_year' => '2012',
                'result' => 'GPA 3.85/4.00',
                'major' => 'Computer Science',
                'duration' => '4 years',
            ],
            (object) [
                'degree' => 'Master of Business Administration',
                'institution' => 'Harvard Business School',
                'board_university' => 'Harvard University',
                'passing_year' => '2015',
                'result' => 'GPA 3.92/4.00',
                'major' => 'Business Administration',
                'duration' => '2 years',
            ],
        ],

        // Experience
        'experiences' => [
            (object) [
                'company_name' => 'Tech Innovations Inc.',
                'designation' => 'Software Engineer',
                'department' => 'Engineering',
                'from_date' => '2012-07-01',
                'to_date' => '2015-12-31',
                'duration' => '3.5 years',
                'responsibilities' => 'Developed enterprise software solutions, led team of 5 developers',
                'salary' => '75000',
            ],
            (object) [
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

        // Training
        'trainings' => [
            (object) [
                'training_title' => 'Advanced Project Management',
                'training_institute' => 'Project Management Institute',
                'training_year' => '2018',
                'duration' => '3 months',
                'topics_covered' => 'Agile, Scrum, Risk Management, Stakeholder Management',
                'certificate' => 'PMP Certified',
            ],
            (object) [
                'training_title' => 'Leadership Development Program',
                'training_institute' => 'Leadership Academy',
                'training_year' => '2021',
                'duration' => '6 months',
                'topics_covered' => 'Team Leadership, Strategic Planning, Communication Skills',
                'certificate' => 'Leadership Certificate',
            ],
            (object) [
                'training_title' => 'Cloud Computing & DevOps',
                'training_institute' => 'AWS Training Center',
                'training_year' => '2022',
                'duration' => '2 months',
                'topics_covered' => 'AWS Services, CI/CD, Kubernetes, Docker',
                'certificate' => 'AWS Solutions Architect',
            ],
        ],

        // Nominees
        'nominees' => [
            (object) [
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
                'nid' => 'NID-987654321',
                'birth_reg_no' => 'BR-1992-54321',
                'phone' => '+1-555-111-2222',
                'mobile' => '+1-555-222-3333',
            ],
            (object) [
                'nominee_name' => 'Emily Doe',
                'father_name' => 'John Michael Doe',
                'mother_name' => 'Jane Doe',
                'gender' => 'Female',
                'date_of_birth' => '2015-03-10',
                'religion' => 'Christianity',
                'marital_status' => 'Single',
                'nationality' => 'American',
                'blood_group' => 'A+',
                'birth_reg_no' => 'BR-2015-11111',
            ],
        ],

        // Salary
        'salary' => (object) [
            'basic_salary' => 60000.0,
            'house_allowance' => 15000.0,
            'transport_allowance' => 8000.0,
            'food_allowance' => 5000.0,
            'medical_allowance' => 7000.0,
            'other_earnings' => 5000.0,
            'gross_salary' => 100000.0,
            'currency' => 'USD',
        ],

        // Plans
        'shift_plans' => [
            (object) [
                'plan_id' => 2,
                'plan_name' => 'Day Shift - General',
                'from' => '2024-07-01',
                'to' => '2025-12-31',
                'status' => 'Active',
            ],
        ],
        'meal_plans' => [
            (object) [
                'plan_id' => 1,
                'plan_name' => 'Standard Meal Plan',
                'from' => '2024-01-15',
                'to' => '2025-12-31',
                'status' => 'Active',
            ],
        ],
        'offday_plans' => [
            (object) [
                'plan_id' => 1,
                'plan_name' => 'Weekend Off-Day Plan',
                'from' => '2024-01-15',
                'to' => '2025-12-31',
                'status' => 'Active',
            ],
        ],
        'ot_plans' => [
            (object) [
                'plan_id' => 1,
                'plan_name' => 'Standard OT Plan',
                'from' => '2024-01-15',
                'to' => '2025-12-31',
                'status' => 'Active',
            ],
        ],
    ];
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Profile - {{ $employee->full_name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }

        .print-container {
            width: 210mm;
            min-height: 297mm;
            margin: 20px auto;
            background: white;
            padding: 20mm;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Header Section */
        .profile-header {
            display: flex;
            align-items: flex-start;
            padding-bottom: 20px;
            margin-bottom: 20px;
            border-bottom: 3px solid #333;
            page-break-inside: avoid;
        }

        .profile-photo {
            width: 120px;
            height: 140px;
            border: 2px solid #333;
            margin-right: 25px;
            background-color: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: #666;
            flex-shrink: 0;
        }

        .profile-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .header-info {
            flex: 1;
        }

        .employee-name {
            font-size: 26px;
            font-weight: bold;
            color: #000;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .employee-id {
            font-size: 14px;
            color: #555;
            margin-bottom: 15px;
        }

        .header-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
            font-size: 12px;
        }

        .header-details .detail-item {
            display: flex;
        }

        .header-details .detail-label {
            font-weight: 600;
            color: #000;
            min-width: 140px;
        }

        .header-details .detail-value {
            color: #444;
        }

        /* Section Styles */
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #000;
            background-color: #e8e8e8;
            padding: 8px 12px;
            margin-bottom: 12px;
            border-left: 4px solid #333;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            font-size: 12px;
        }

        .info-item {
            display: flex;
            padding: 6px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .info-label {
            font-weight: 600;
            color: #000;
            min-width: 180px;
            flex-shrink: 0;
        }

        .info-value {
            color: #444;
            flex: 1;
        }

        /* Address Section */
        .address-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            font-size: 11px;
        }

        .address-card {
            border: 1px solid #d0d0d0;
            padding: 12px;
            background-color: #fafafa;
        }

        .address-card h4 {
            font-size: 12px;
            font-weight: bold;
            color: #000;
            margin-bottom: 8px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ccc;
        }

        .address-card p {
            margin: 4px 0;
            color: #444;
            line-height: 1.5;
        }

        /* Table Styles */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-top: 10px;
        }

        .data-table thead {
            background-color: #333;
            color: white;
        }

        .data-table th {
            padding: 8px;
            text-align: left;
            font-weight: 600;
            border: 1px solid #555;
        }

        .data-table td {
            padding: 8px;
            border: 1px solid #d0d0d0;
            color: #444;
        }

        .data-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .data-table tbody tr:hover {
            background-color: #f0f0f0;
        }

        /* Salary Grid */
        .salary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 10px;
        }

        .salary-item {
            border: 1px solid #d0d0d0;
            padding: 10px;
            background-color: #fafafa;
            text-align: center;
        }

        .salary-item .label {
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
        }

        .salary-item .amount {
            font-size: 16px;
            font-weight: bold;
            color: #000;
        }

        .salary-item.total {
            background-color: #333;
            color: white;
            grid-column: span 3;
        }

        .salary-item.total .label {
            color: #ccc;
        }

        .salary-item.total .amount {
            color: white;
            font-size: 20px;
        }

        /* Plan Cards */
        .plan-cards {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-top: 10px;
        }

        .plan-card {
            border: 1px solid #d0d0d0;
            padding: 12px;
            background-color: #fafafa;
            font-size: 11px;
        }

        .plan-card h4 {
            font-size: 12px;
            font-weight: bold;
            color: #000;
            margin-bottom: 8px;
            padding-bottom: 5px;
            border-bottom: 1px solid #ccc;
        }

        .plan-card .plan-detail {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
            color: #444;
        }

        .plan-card .plan-detail .label {
            font-weight: 600;
            color: #000;
        }

        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            font-size: 10px;
            font-weight: 600;
            border-radius: 3px;
        }

        .status-active {
            background-color: #333;
            color: white;
        }

        .status-inactive {
            background-color: #e0e0e0;
            color: #666;
        }

        /* Print Styles */
        @media print {
            body {
                background: white;
            }

            .print-container {
                width: 100%;
                margin: 0;
                padding: 15mm;
                box-shadow: none;
            }

            .section {
                page-break-inside: avoid;
            }

            .profile-header {
                page-break-after: avoid;
            }

            @page {
                size: A4;
                margin: 10mm;
            }
        }

        /* No Print Elements */
        .no-print {
            text-align: center;
            margin: 20px 0;
        }

        .print-button {
            background-color: #333;
            color: white;
            border: none;
            padding: 12px 30px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 4px;
            font-weight: 600;
        }

        .print-button:hover {
            background-color: #000;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="no-print">
        <button class="print-button" onclick="window.print()">Print Profile</button>
    </div>

    <div class="print-container">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-photo">
                @if (!empty($employee->photo_path) && file_exists(public_path($employee->photo_path)))
                    <img src="{{ asset($employee->photo_path) }}" alt="Employee Photo">
                @else
                    <span>PHOTO</span>
                @endif
            </div>
            <div class="header-info">
                <div class="employee-name">{{ $employee->full_name }}</div>
                <div class="employee-id">
                    Employee ID: {{ $employee->system_id }} | Punch Card: {{ $employee->punch_card_no }}
                </div>
                <div class="header-details">
                    <div class="detail-item">
                        <span class="detail-label">Department:</span>
                        <span class="detail-value">Department
                            {{ $employee->office_info->current_department_id }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Designation:</span>
                        <span class="detail-value">Designation
                            {{ $employee->office_info->current_designation_id }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Employment Type:</span>
                        <span class="detail-value">{{ $employee->office_info->emp_type }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Date of Joining:</span>
                        <span
                            class="detail-value">{{ date('F d, Y', strtotime($employee->office_info->date_of_join)) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="section">
            <h2 class="section-title">Personal Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Full Name:</span>
                    <span class="info-value">{{ $employee->full_name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Date of Birth:</span>
                    <span class="info-value">{{ date('F d, Y', strtotime($employee->date_of_birth)) }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Father's Name:</span>
                    <span class="info-value">{{ $employee->father_name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Gender:</span>
                    <span class="info-value">{{ $employee->gender }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Mother's Name:</span>
                    <span class="info-value">{{ $employee->mother_name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Marital Status:</span>
                    <span class="info-value">{{ $employee->marital_status }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Spouse's Name:</span>
                    <span class="info-value">{{ $employee->spouse_name ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Religion:</span>
                    <span class="info-value">{{ $employee->religion }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Nationality:</span>
                    <span class="info-value">{{ $employee->nationality }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Blood Group:</span>
                    <span class="info-value">{{ $employee->blood_group ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Height:</span>
                    <span class="info-value">{{ $employee->height_feet }}' {{ $employee->height_inches }}"</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Number of Children:</span>
                    <span class="info-value">{{ $employee->children_count }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Birth Country:</span>
                    <span class="info-value">{{ $employee->birth_country }}</span>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="section">
            <h2 class="section-title">Contact Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Personal Mobile:</span>
                    <span class="info-value">{{ $employee->personal_mobile }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Work Mobile:</span>
                    <span class="info-value">{{ $employee->work_mobile }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Home Phone:</span>
                    <span class="info-value">{{ $employee->home_phone }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Work Phone:</span>
                    <span class="info-value">{{ $employee->work_phone }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Personal Email:</span>
                    <span class="info-value">{{ $employee->personal_email }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Work Email:</span>
                    <span class="info-value">{{ $employee->work_email }}</span>
                </div>
            </div>
        </div>

        <!-- Identification Documents -->
        <div class="section">
            <h2 class="section-title">Identification Documents</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">TIN:</span>
                    <span class="info-value">{{ $employee->tin }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Birth Registration No:</span>
                    <span class="info-value">{{ $employee->birth_reg_no }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Passport No:</span>
                    <span class="info-value">{{ $employee->passport_no }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Passport Expiry:</span>
                    <span class="info-value">{{ date('F d, Y', strtotime($employee->passport_expiry)) }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">License No:</span>
                    <span class="info-value">{{ $employee->license_no }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">License Expiry:</span>
                    <span class="info-value">{{ date('F d, Y', strtotime($employee->license_expiry)) }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Residency ID:</span>
                    <span class="info-value">{{ $employee->residency_id_number }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Work Permit Expiry:</span>
                    <span class="info-value">{{ date('F d, Y', strtotime($employee->work_expiry)) }}</span>
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div class="section">
            <h2 class="section-title">Address Information</h2>
            <div class="address-grid">
                <div class="address-card">
                    <h4>Present Address</h4>
                    <p><strong>{{ $employee->present_address->address_line }}</strong></p>
                    <p>Village: {{ $employee->present_address->village }}</p>
                    <p>Post Office: {{ $employee->present_address->post_office }}</p>
                    <p>Thana: {{ $employee->present_address->thana }}</p>
                    <p>District: {{ $employee->present_address->district }}</p>
                    <p>{{ $employee->present_address->state }}, {{ $employee->present_address->zip_code }}</p>
                    <p>{{ $employee->present_address->country }}</p>
                </div>
                <div class="address-card">
                    <h4>Permanent Address</h4>
                    <p><strong>{{ $employee->permanent_address->address_line }}</strong></p>
                    <p>Village: {{ $employee->permanent_address->village }}</p>
                    <p>Post Office: {{ $employee->permanent_address->post_office }}</p>
                    <p>Thana: {{ $employee->permanent_address->thana }}</p>
                    <p>District: {{ $employee->permanent_address->district }}</p>
                    <p>{{ $employee->permanent_address->state }}, {{ $employee->permanent_address->zip_code }}
                    </p>
                    <p>{{ $employee->permanent_address->country }}</p>
                </div>
                <div class="address-card">
                    <h4>Reference Address</h4>
                    <p><strong>{{ $employee->reference_address->address_line }}</strong></p>
                    <p>Village: {{ $employee->reference_address->village }}</p>
                    <p>Post Office: {{ $employee->reference_address->post_office }}</p>
                    <p>Thana: {{ $employee->reference_address->thana }}</p>
                    <p>District: {{ $employee->reference_address->district }}</p>
                    <p>{{ $employee->reference_address->state }}, {{ $employee->reference_address->zip_code }}
                    </p>
                    <p>{{ $employee->reference_address->country }}</p>
                </div>
            </div>
        </div>

        <!-- Office Information -->
        <div class="section">
            <h2 class="section-title">Office Information</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">HR File No:</span>
                    <span class="info-value">{{ $employee->office_info->hr_file_no }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Employment Type:</span>
                    <span class="info-value">{{ $employee->office_info->emp_type }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Date of Joining:</span>
                    <span
                        class="info-value">{{ date('F d, Y', strtotime($employee->office_info->date_of_join)) }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Confirmation Date:</span>
                    <span
                        class="info-value">{{ date('F d, Y', strtotime($employee->office_info->confirmation_date)) }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Probation Duration:</span>
                    <span class="info-value">{{ $employee->office_info->probation_duration }} months</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Salary Type:</span>
                    <span class="info-value">{{ $employee->office_info->salary_type }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Promotion Cycle:</span>
                    <span class="info-value">{{ $employee->office_info->promotion_cycle }} months</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Increment Cycle:</span>
                    <span class="info-value">{{ $employee->office_info->increment_cycle }} months</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Weekends:</span>
                    <span class="info-value">{{ implode(', ', $employee->office_info->weekends) }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">OT Allowed:</span>
                    <span class="info-value">{{ $employee->office_info->ot_allowed ? 'Yes' : 'No' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">PF Eligible:</span>
                    <span class="info-value">{{ $employee->office_info->pf_eligible ? 'Yes' : 'No' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Transport Eligible:</span>
                    <span class="info-value">{{ $employee->office_info->transport_eligible ? 'Yes' : 'No' }}</span>
                </div>
            </div>
        </div>

        <!-- Bank Account Details -->
        <div class="section">
            <h2 class="section-title">Bank Account Details</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Account Holder</th>
                        <th>Account Number</th>
                        <th>Bank Name</th>
                        <th>Branch Name</th>
                        <th>Status</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employee->bank_accounts as $account)
                        <tr>
                            <td>{{ $account->account_holder_name }}</td>
                            <td>{{ $account->account_number }}</td>
                            <td>{{ $account->bank_name ?? 'Bank ID: ' . $account->bank_id }}</td>
                            <td>{{ $account->branch_name ?? 'Branch ID: ' . $account->branch_id }}</td>
                            <td>
                                <span
                                    class="status-badge {{ $account->status === 'Active' ? 'status-active' : 'status-inactive' }}">
                                    {{ $account->status }}
                                </span>
                            </td>
                            <td>{{ $account->remarks }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Education -->
        <div class="section">
            <h2 class="section-title">Educational Qualifications</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Degree</th>
                        <th>Institution</th>
                        <th>Major</th>
                        <th>Passing Year</th>
                        <th>Result</th>
                        <th>Duration</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employee->educations as $education)
                        <tr>
                            <td>{{ $education->degree }}</td>
                            <td>{{ $education->institution }}</td>
                            <td>{{ $education->major }}</td>
                            <td>{{ $education->passing_year }}</td>
                            <td>{{ $education->result }}</td>
                            <td>{{ $education->duration }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Experience -->
        <div class="section">
            <h2 class="section-title">Work Experience</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Company</th>
                        <th>Designation</th>
                        <th>Department</th>
                        <th>Duration</th>
                        <th>Period</th>
                        <th>Responsibilities</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employee->experiences as $experience)
                        <tr>
                            <td>{{ $experience->company_name }}</td>
                            <td>{{ $experience->designation }}</td>
                            <td>{{ $experience->department }}</td>
                            <td>{{ $experience->duration }}</td>
                            <td>{{ date('M Y', strtotime($experience->from_date)) }} -
                                {{ date('M Y', strtotime($experience->to_date)) }}</td>
                            <td>{{ $experience->responsibilities }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Training -->
        <div class="section">
            <h2 class="section-title">Professional Training</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Training Title</th>
                        <th>Institute</th>
                        <th>Year</th>
                        <th>Duration</th>
                        <th>Topics Covered</th>
                        <th>Certificate</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employee->trainings as $training)
                        <tr>
                            <td>{{ $training->training_title }}</td>
                            <td>{{ $training->training_institute }}</td>
                            <td>{{ $training->training_year }}</td>
                            <td>{{ $training->duration }}</td>
                            <td>{{ $training->topics_covered }}</td>
                            <td>{{ $training->certificate }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Nominees -->
        <div class="section">
            <h2 class="section-title">Nominee Information</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Relation</th>
                        <th>Date of Birth</th>
                        <th>Gender</th>
                        <th>Blood Group</th>
                        <th>Contact</th>
                        <th>NID/Birth Reg</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employee->nominees as $nominee)
                        <tr>
                            <td>{{ $nominee->nominee_name }}</td>
                            <td>{{ isset($nominee->spouse_name) && $nominee->spouse_name ? 'Spouse' : 'Child' }}
                            </td>
                            <td>{{ date('M d, Y', strtotime($nominee->date_of_birth)) }}</td>
                            <td>{{ $nominee->gender }}</td>
                            <td>{{ $nominee->blood_group }}</td>
                            <td>{{ $nominee->mobile ?? 'N/A' }}</td>
                            <td>{{ $nominee->nid ?? $nominee->birth_reg_no }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Salary Breakdown -->
        <div class="section">
            <h2 class="section-title">Salary Breakdown</h2>
            <div class="salary-grid">
                <div class="salary-item">
                    <div class="label">Basic Salary</div>
                    <div class="amount">{{ number_format($employee->salary->basic_salary, 2) }}
                        {{ $employee->salary->currency }}</div>
                </div>
                <div class="salary-item">
                    <div class="label">House Allowance</div>
                    <div class="amount">{{ number_format($employee->salary->house_allowance, 2) }}
                        {{ $employee->salary->currency }}</div>
                </div>
                <div class="salary-item">
                    <div class="label">Transport Allowance</div>
                    <div class="amount">{{ number_format($employee->salary->transport_allowance, 2) }}
                        {{ $employee->salary->currency }}</div>
                </div>
                <div class="salary-item">
                    <div class="label">Food Allowance</div>
                    <div class="amount">{{ number_format($employee->salary->food_allowance, 2) }}
                        {{ $employee->salary->currency }}</div>
                </div>
                <div class="salary-item">
                    <div class="label">Medical Allowance</div>
                    <div class="amount">{{ number_format($employee->salary->medical_allowance, 2) }}
                        {{ $employee->salary->currency }}</div>
                </div>
                <div class="salary-item">
                    <div class="label">Other Earnings</div>
                    <div class="amount">{{ number_format($employee->salary->other_earnings, 2) }}
                        {{ $employee->salary->currency }}</div>
                </div>
                <div class="salary-item total">
                    <div class="label">Gross Salary</div>
                    <div class="amount">{{ number_format($employee->salary->gross_salary, 2) }}
                        {{ $employee->salary->currency }}</div>
                </div>
            </div>
        </div>

        <!-- Employee Plans -->
        <div class="section">
            <h2 class="section-title">Employee Plans</h2>
            <div class="plan-cards">
                @if (!empty($employee->shift_plans))
                    <div class="plan-card">
                        <h4>Shift Plan</h4>
                        @foreach ($employee->shift_plans as $plan)
                            <div class="plan-detail">
                                <span class="label">Plan Name:</span>
                                <span>{{ $plan->plan_name ?? 'Plan ID: ' . $plan->plan_id }}</span>
                            </div>
                            <div class="plan-detail">
                                <span class="label">Period:</span>
                                <span>{{ date('M d, Y', strtotime($plan->from)) }} -
                                    {{ date('M d, Y', strtotime($plan->to)) }}</span>
                            </div>
                            <div class="plan-detail">
                                <span class="label">Status:</span>
                                <span
                                    class="status-badge {{ $plan->status === 'Active' ? 'status-active' : 'status-inactive' }}">{{ $plan->status }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if (!empty($employee->meal_plans))
                    <div class="plan-card">
                        <h4>Meal Plan</h4>
                        @foreach ($employee->meal_plans as $plan)
                            <div class="plan-detail">
                                <span class="label">Plan Name:</span>
                                <span>{{ $plan->plan_name ?? 'Plan ID: ' . $plan->plan_id }}</span>
                            </div>
                            <div class="plan-detail">
                                <span class="label">Period:</span>
                                <span>{{ date('M d, Y', strtotime($plan->from)) }} -
                                    {{ date('M d, Y', strtotime($plan->to)) }}</span>
                            </div>
                            <div class="plan-detail">
                                <span class="label">Status:</span>
                                <span
                                    class="status-badge {{ $plan->status === 'Active' ? 'status-active' : 'status-inactive' }}">{{ $plan->status }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if (!empty($employee->offday_plans))
                    <div class="plan-card">
                        <h4>Off-Day Plan</h4>
                        @foreach ($employee->offday_plans as $plan)
                            <div class="plan-detail">
                                <span class="label">Plan Name:</span>
                                <span>{{ $plan->plan_name ?? 'Plan ID: ' . $plan->plan_id }}</span>
                            </div>
                            <div class="plan-detail">
                                <span class="label">Period:</span>
                                <span>{{ date('M d, Y', strtotime($plan->from)) }} -
                                    {{ date('M d, Y', strtotime($plan->to)) }}</span>
                            </div>
                            <div class="plan-detail">
                                <span class="label">Status:</span>
                                <span
                                    class="status-badge {{ $plan->status === 'Active' ? 'status-active' : 'status-inactive' }}">{{ $plan->status }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if (!empty($employee->ot_plans))
                    <div class="plan-card">
                        <h4>OT Plan</h4>
                        @foreach ($employee->ot_plans as $plan)
                            <div class="plan-detail">
                                <span class="label">Plan Name:</span>
                                <span>{{ $plan->plan_name ?? 'Plan ID: ' . $plan->plan_id }}</span>
                            </div>
                            <div class="plan-detail">
                                <span class="label">Period:</span>
                                <span>{{ date('M d, Y', strtotime($plan->from)) }} -
                                    {{ date('M d, Y', strtotime($plan->to)) }}</span>
                            </div>
                            <div class="plan-detail">
                                <span class="label">Status:</span>
                                <span
                                    class="status-badge {{ $plan->status === 'Active' ? 'status-active' : 'status-inactive' }}">{{ $plan->status }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="no-print">
        <button class="print-button" onclick="window.print()">Print Profile</button>
    </div>
</body>

</html>
