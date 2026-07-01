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
            <h2 class="section-title">Emergency Contact Information</h2>
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
                        <h4>Overtime Plan</h4>
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

