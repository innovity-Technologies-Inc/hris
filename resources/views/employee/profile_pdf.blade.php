<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Profile - {{ $employee->full_name }}</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 8.5px;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 25px;
            background-color: #fff;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #974063;
            margin-bottom: 12px;
            padding-bottom: 8px;
        }
        .company-name {
            font-size: 15px;
            font-weight: bold;
            color: #974063;
            margin: 0;
        }
        .company-details {
            font-size: 7.5px;
            color: #666;
            margin: 0;
        }
        .profile-photo {
            width: 65px;
            height: 65px;
            border: 2px solid #f0f0f0;
            border-radius: 4px;
            object-fit: cover;
        }
        .section-title {
            font-size: 10px;
            font-weight: bold;
            color: #fff;
            background-color: #974063;
            padding: 3px 6px;
            margin: 10px 0 5px 0;
            border-radius: 2px;
            text-transform: uppercase;
        }
        .info-grid {
            width: 100%;
            border-collapse: collapse;
        }
        .info-grid td {
            padding: 3px 0;
            border-bottom: 1px solid #f9f9f9;
            vertical-align: top;
        }
        .label {
            font-weight: bold;
            color: #555;
            width: 20%;
        }
        .value {
            color: #222;
            width: 30%;
        }
        .address-box {
            background-color: #fcfcfc;
            padding: 8px;
            border: 1px solid #eee;
            border-radius: 2px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 7px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
        .badge {
            display: inline-block;
            padding: 1px 4px;
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            border-radius: 2px;
            margin-right: 2px;
            font-size: 7px;
        }
        .row-table {
            width: 100%;
            table-layout: fixed;
            border-spacing: 10px 0;
            margin-left: -10px;
            margin-right: -10px;
        }
        .row-table td {
            vertical-align: top;
            padding: 0 10px;
        }
        .full-width-section {
            width: 100%;
            margin-bottom: 8px;
        }
        .entry-card {
            border: 1px solid #eee;
            background-color: #f9f9f9;
            padding: 6px;
            margin-bottom: 6px;
            border-radius: 2px;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="width: 70%;">
                <h1 class="company-name">{{ $companyInfo->name }}</h1>
                <p class="company-details">{{ $companyInfo->address }}</p>
                <p class="company-details">Phone: {{ $companyInfo->phone }} | Email: {{ $companyInfo->email }}</p>
                <h2 style="margin-top: 5px; color: #444; font-size: 11px;">EMPLOYEE DETAILED PROFILE</h2>
            </td>
            <td style="width: 30%; text-align: right;">
                @if($employee->photo_path)
                    <img src="{{ public_path('storage/' . $employee->photo_path) }}" class="profile-photo" alt="Photo">
                @else
                    <div style="width: 65px; height: 65px; background-color: #eee; border-radius: 4px; display: inline-block; line-height: 65px; text-align: center; color: #999;">NO PHOTO</div>
                @endif
            </td>
        </tr>
    </table>

    <!-- Personal Information -->
    <div class="section-title">Personal Information</div>
    <table class="info-grid">
        <tr>
            <td class="label">Full Name</td><td class="value">{{ $employee->full_name }}</td>
            <td class="label">Employee ID</td><td class="value">{{ $employee->applicant_id }}</td>
        </tr>
        <tr>
            <td class="label">Father's Name</td><td class="value">{{ $employee->father_name }}</td>
            <td class="label">System ID</td><td class="value">{{ $employee->system_id }}</td>
        </tr>
        <tr>
            <td class="label">Mother's Name</td><td class="value">{{ $employee->mother_name }}</td>
            <td class="label">Punch Card No</td><td class="value">{{ $employee->punch_card_no ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Date of Birth</td><td class="value">{{ \Carbon\Carbon::parse($employee->date_of_birth)->format('d M, Y') }}</td>
            <td class="label">Gender</td><td class="value">{{ $employee->gender }}</td>
        </tr>
        <tr>
            <td class="label">Marital Status</td><td class="value">{{ $employee->marital_status ?? 'N/A' }}</td>
            <td class="label">Religion</td><td class="value">{{ $employee->religion }}</td>
        </tr>
        <tr>
            <td class="label">Nationality</td><td class="value">{{ $employee->nationality }}</td>
            <td class="label">Blood Group</td><td class="value">{{ $employee->blood_group ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Birth Country</td><td class="value">{{ $employee->birth_country ?? 'N/A' }}</td>
            <td class="label">Birth Reg No</td><td class="value">{{ $employee->birth_reg_no ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Height</td><td class="value">{{ $employee->height_feet ? $employee->height_feet . "' " . $employee->height_inches . "\"" : 'N/A' }}</td>
            <td class="label">Status</td><td class="value">{{ $employee->status }}</td>
        </tr>
    </table>

    <!-- Documents Information (Full Row) -->
    <div class="section-title">Documents & Identifiers</div>
    <div class="address-box">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 25%;"><strong>TIN:</strong> {{ $employee->tin ?? 'N/A' }}</td>
                <td style="width: 25%;"><strong>Passport:</strong> {{ $employee->passport_no ?? 'N/A' }}</td>
                <td style="width: 25%;"><strong>Pass Exp:</strong> {{ $employee->passport_expiry ? \Carbon\Carbon::parse($employee->passport_expiry)->format('d M, Y') : 'N/A' }}</td>
                <td style="width: 25%;"><strong>NID / ResID:</strong> {{ $employee->residency_id_number ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding-top: 5px;"><strong>License:</strong> {{ $employee->license_no ?? 'N/A' }}</td>
                <td style="padding-top: 5px;"><strong>Lic Exp:</strong> {{ $employee->license_expiry ? \Carbon\Carbon::parse($employee->license_expiry)->format('d M, Y') : 'N/A' }}</td>
                <td style="padding-top: 5px;"><strong>Visa Exp:</strong> {{ $employee->visa_expiry ? \Carbon\Carbon::parse($employee->visa_expiry)->format('d M, Y') : 'N/A' }}</td>
                <td style="padding-top: 5px;"><strong>Work Exp:</strong> {{ $employee->work_expiry ? \Carbon\Carbon::parse($employee->work_expiry)->format('d M, Y') : 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <!-- Present Address (Full Row) -->
    <div class="section-title">Present Address</div>
    <div class="address-box">
        @php $present = (object) $employee->present_address; @endphp
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td colspan="4"><strong>Line:</strong> {{ $present->line_1 ?? $present->address_line ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="width: 25%; padding-top: 3px;"><strong>Village:</strong> {{ $present->village ?? 'N/A' }}</td>
                <td style="width: 25%; padding-top: 3px;"><strong>PO:</strong> {{ $present->post_office ?? 'N/A' }}</td>
                <td style="width: 25%; padding-top: 3px;"><strong>Thana:</strong> {{ $present->thana ?? 'N/A' }}</td>
                <td style="width: 25%; padding-top: 3px;"><strong>District:</strong> {{ $present->district ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding-top: 3px;"><strong>Division:</strong> {{ $present->division ?? 'N/A' }}</td>
                <td style="padding-top: 3px;"><strong>State:</strong> {{ $present->state ?? 'N/A' }}</td>
                <td style="padding-top: 3px;"><strong>Zip:</strong> {{ $present->zip_code ?? 'N/A' }}</td>
                <td style="padding-top: 3px;"><strong>Country:</strong> {{ $present->country ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <!-- Permanent Address (Full Row) -->
    <div class="section-title">Permanent Address</div>
    <div class="address-box">
        @php 
            $perm = (object) ($employee->permanent_address ?? $employee->present_address); 
            if (empty($employee->permanent_address)) $perm = (object) $employee->present_address;
        @endphp
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td colspan="4"><strong>Line:</strong> {{ $perm->line_1 ?? $perm->address_line ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="width: 25%; padding-top: 3px;"><strong>Village:</strong> {{ $perm->village ?? 'N/A' }}</td>
                <td style="width: 25%; padding-top: 3px;"><strong>PO:</strong> {{ $perm->post_office ?? 'N/A' }}</td>
                <td style="width: 25%; padding-top: 3px;"><strong>Thana:</strong> {{ $perm->thana ?? 'N/A' }}</td>
                <td style="width: 25%; padding-top: 3px;"><strong>District:</strong> {{ $perm->district ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding-top: 3px;"><strong>Division:</strong> {{ $perm->division ?? 'N/A' }}</td>
                <td style="padding-top: 3px;"><strong>State:</strong> {{ $perm->state ?? 'N/A' }}</td>
                <td style="padding-top: 3px;"><strong>Zip:</strong> {{ $perm->zip_code ?? 'N/A' }}</td>
                <td style="padding-top: 3px;"><strong>Country:</strong> {{ $perm->country ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <!-- Office Information -->
    @if($officeInfo)
    <div class="section-title">Office Information</div>
    <table class="info-grid">
        <tr>
            <td class="label">Company</td><td class="value">{{ $officeInfo->getCurrentCompany?->name ?? 'N/A' }}</td>
            <td class="label">Designation</td><td class="value">{{ $officeInfo->getCurrentDesignation?->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Department</td><td class="value">{{ $officeInfo->getCurrentDepartment?->name ?? 'N/A' }}</td>
            <td class="label">Division</td><td class="value">{{ $officeInfo->getCurrentDivision?->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Section</td><td class="value">{{ $officeInfo->getCurrentSection?->name ?? 'N/A' }}</td>
            <td class="label">Date of Join</td><td class="value">{{ $officeInfo->date_of_join ? \Carbon\Carbon::parse($officeInfo->date_of_join)->format('d M, Y') : 'N/A' }}</td>
        </tr>
    </table>
    @endif

    <!-- Education Information (Full Row List) -->
    @if($employee->educationInfo && count($employee->educationInfo->educations ?? []) > 0)
    <div class="full-width-section">
        <div class="section-title">Education Information</div>
        @foreach($employee->educationInfo->educations as $edu)
            <div class="entry-card">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 35%;"><strong>Title:</strong> {{ $edu['education_title'] ?? 'N/A' }}</td>
                        <td style="width: 35%;"><strong>Institute:</strong> {{ $edu['institute'] ?? 'N/A' }}</td>
                        <td style="width: 30%;"><strong>Board:</strong> {{ $edu['board_university'] ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td style="padding-top: 4px;"><strong>Major:</strong> {{ $edu['group_major'] ?? 'N/A' }}</td>
                        <td style="padding-top: 4px;"><strong>Year:</strong> {{ $edu['passing_year'] ?? 'N/A' }}</td>
                        <td style="padding-top: 4px;"><strong>Result:</strong> {{ $edu['result_grade'] ?? 'N/A' }} {{ isset($edu['gpa_cgpa']) ? '('.$edu['gpa_cgpa'].')' : '' }}</td>
                    </tr>
                </table>
            </div>
        @endforeach
    </div>
    @endif

    <!-- Training Information (Full Row List) -->
    @if($employee->educationInfo && count($employee->educationInfo->trainings ?? []) > 0)
    <div class="full-width-section">
        <div class="section-title">Training Information</div>
        @foreach($employee->educationInfo->trainings as $trn)
            <div class="entry-card">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 50%;"><strong>Title:</strong> {{ $trn['training_title'] ?? 'N/A' }}</td>
                        <td style="width: 50%;"><strong>Course:</strong> {{ $trn['course_name'] ?? 'N/A' }} (Code: {{ $trn['training_code'] ?? 'N/A' }})</td>
                    </tr>
                    <tr>
                        <td style="padding-top: 4px;"><strong>Institute:</strong> {{ $trn['institute'] ?? 'N/A' }} ({{ $trn['location'] ?? 'N/A' }})</td>
                        <td style="padding-top: 4px;"><strong>Duration:</strong> {{ $trn['duration'] ?? 'N/A' }} | <strong>Dates:</strong> {{ $trn['from_date'] ?? 'N/A' }} to {{ $trn['to_date'] ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        @endforeach
    </div>
    @endif

    <!-- Employment History (Full Row List) -->
    @if($employee->employmentHistory && count($employee->employmentHistory->histories ?? []) > 0)
    <div class="full-width-section">
        <div class="section-title">Employment History</div>
        @foreach($employee->employmentHistory->histories as $history)
            <div class="entry-card">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 40%;"><strong>Company:</strong> {{ $history['company_name'] ?? 'N/A' }}</td>
                        <td style="width: 30%;"><strong>Designation:</strong> {{ $history['designation'] ?? 'N/A' }}</td>
                        <td style="width: 30%;"><strong>Dates:</strong> {{ $history['joining_date'] ?? 'N/A' }} to {{ $history['end_date'] ?? 'Present' }}</td>
                    </tr>
                    @if(!empty($history['job_description']))
                    <tr><td colspan="3" style="padding-top: 4px;"><strong>Description:</strong> {{ $history['job_description'] }}</td></tr>
                    @endif
                    @if(!empty($history['achievements']))
                    <tr><td colspan="3" style="padding-top: 4px;"><strong>Achievements:</strong> {{ $history['achievements'] }}</td></tr>
                    @endif
                </table>
            </div>
        @endforeach
    </div>
    @endif

    <!-- Nominee Information (Full Row) -->
    <div class="section-title">Nominee Information</div>
    <div class="address-box">
        @if($employee->nomineeInfo)
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 33%;"><strong>Name:</strong> {{ $employee->nomineeInfo->nominee_name }}</td>
                    <td style="width: 33%;"><strong>Relation:</strong> {{ $employee->nomineeInfo->relation }}</td>
                    <td style="width: 34%;"><strong>Mobile:</strong> {{ $employee->nomineeInfo->nominee_mobile }}</td>
                </tr>
                <tr>
                    <td style="padding-top: 4px;"><strong>Gender:</strong> {{ $employee->nomineeInfo->gender }}</td>
                    <td style="padding-top: 4px;"><strong>DOB:</strong> {{ \Carbon\Carbon::parse($employee->nomineeInfo->date_of_birth)->format('d M, Y') }}</td>
                    <td style="padding-top: 4px;"><strong>ID:</strong> {{ $employee->nomineeInfo->nid ?? $employee->nomineeInfo->birth_reg_no ?? 'N/A' }}</td>
                </tr>
            </table>
        @else
            N/A
        @endif
    </div>

    <!-- Accounts Information (Full Row) -->
    <div class="section-title">Bank Account Details</div>
    <div class="address-box">
        @if($employee->bankAccount)
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 25%;"><strong>Bank:</strong> {{ $employee->bankAccount->getBank?->name }}</td>
                    <td style="width: 25%;"><strong>Branch:</strong> {{ $employee->bankAccount->getBranch?->name }}</td>
                    <td style="width: 25%;"><strong>A/C Name:</strong> {{ $employee->bankAccount->account_holder_name }}</td>
                    <td style="width: 25%;"><strong>A/C No:</strong> {{ $employee->bankAccount->account_number }}</td>
                </tr>
            </table>
        @else
            N/A
        @endif
    </div>

    <!-- Reference Information (Full Row) -->
    <div class="section-title">Reference / Emergency Contact</div>
    <div class="address-box">
        @if($employee->reference_address)
            @php $ref = (object) $employee->reference_address; @endphp
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="width: 33%;"><strong>Name:</strong> {{ $ref->reference_name ?? 'N/A' }}</td>
                    <td style="width: 33%;"><strong>Phone/Mob:</strong> {{ $ref->phone ?? $ref->mobile ?? 'N/A' }}</td>
                    <td style="width: 34%;"><strong>Email:</strong> {{ $ref->email ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="padding-top: 5px;">
                        <strong>Address:</strong> 
                        {{ $ref->line_1 ?? $ref->address_line ?? 'N/A' }}, 
                        {{ $ref->village ?? 'N/A' }}, {{ $ref->post_office ?? 'N/A' }}, 
                        {{ $ref->thana ?? 'N/A' }}, {{ $ref->district ?? 'N/A' }}, 
                        {{ $ref->country ?? 'N/A' }}
                    </td>
                </tr>
            </table>
        @else
            N/A
        @endif
    </div>

    <!-- Salary & Policies (Shared Row for space) -->
    <table class="row-table" style="margin-top: 10px; page-break-inside: avoid;">
        <tr>
            <td style="width: 50%;">
                <div class="section-title" style="margin-top: 0;">Salary Breakdown</div>
                @if($employee->salaryBreakdown)
                <div class="address-box">
                    <strong>Gross:</strong> {{ $employee->salaryBreakdown->gross_salary }}<br>
                    <strong>Basic:</strong> {{ $employee->salaryBreakdown->basic_salary }}<br>
                    <strong>House Rent:</strong> {{ $employee->salaryBreakdown->house_rent }}<br>
                    <strong>Medical:</strong> {{ $employee->salaryBreakdown->medical_allowance }}
                </div>
                @else
                <div class="address-box">N/A</div>
                @endif
            </td>
            <td style="width: 50%;">
                <div class="section-title" style="margin-top: 0;">Policies & Plans</div>
                <div class="address-box">
                    @php $elig = $employee->employeeEligibility; @endphp
                    @if($elig)
                        @if($elig->shift_plan_status === 'active') <span class="badge">Shift</span> @endif
                        @if($elig->leave_plan_status === 'active') <span class="badge">Leave</span> @endif
                        @if($elig->ot_plan_status === 'active') <span class="badge">OT</span> @endif
                        @if($elig->roster_plans_status === 'active') <span class="badge">Roster</span> @endif
                        @if($elig->bonus_plan_status === 'active') <span class="badge">Bonus</span> @endif
                        @if($elig->meal_plan_status === 'active') <span class="badge">Meal</span> @endif
                    @endif
                    <hr style="margin: 5px 0; border: 0; border-top: 1px solid #eee;">
                    @foreach($employee->shift as $s) <strong>Shift:</strong> {{ $s->name }}<br> @endforeach
                    @foreach($employee->roster as $r) @if($r->status === 'active') <strong>Roster:</strong> {{ $r->name }}<br> @endif @endforeach
                </div>
            </td>
        </tr>
    </table>

    <!-- Leave (Shared Row) -->
    @if($employee->leaveBalances->count() > 0 || $employee->leaveApplications->count() > 0)
    <table class="row-table" style="margin-top: 10px; page-break-inside: avoid;">
        <tr>
            <td>
                <div class="section-title" style="margin-top: 0;">Leave Balance</div>
                <table class="info-grid">
                    @foreach($employee->leaveBalances as $l)
                    <tr><td class="label" style="width: 70%;">{{ $l->leave_type }}</td><td class="value" style="width: 30%; text-align: right;">{{ $l->leave_count }} / {{ $l->total_leave }}</td></tr>
                    @endforeach
                </table>
            </td>
            <td>
                <div class="section-title" style="margin-top: 0;">Leave History</div>
                <table class="info-grid">
                    @foreach($employee->leaveApplications->take(5) as $l)
                    <tr><td class="label" style="width: 80%;">{{ $l->getPlan?->name }} ({{ $l->status }})</td><td class="value" style="width: 20%; text-align: right;">{{ $l->leave_count }}d</td></tr>
                    @endforeach
                </table>
            </td>
        </tr>
    </table>
    @endif

    <div class="footer">
        Generated on {{ date('d M, Y H:i:s') }} | {{ $companyInfo->name }} HRMS
    </div>
</body>
</html>
