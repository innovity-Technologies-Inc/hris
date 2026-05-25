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
            font-size: 10px;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 30px;
            background-color: #fff;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #974063;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #974063;
            margin: 0;
        }
        .company-details {
            font-size: 9px;
            color: #666;
            margin: 0;
        }
        .profile-photo {
            width: 80px;
            height: 80px;
            border: 2px solid #f0f0f0;
            border-radius: 6px;
            object-fit: cover;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #fff;
            background-color: #974063;
            padding: 4px 8px;
            margin: 15px 0 8px 0;
            border-radius: 3px;
            text-transform: uppercase;
        }
        .info-grid {
            width: 100%;
            border-collapse: collapse;
        }
        .info-grid td {
            padding: 4px 0;
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
            border-radius: 3px;
            min-height: 60px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 8px;
        }
        .badge {
            display: inline-block;
            padding: 2px 5px;
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            border-radius: 3px;
            margin-right: 3px;
            font-size: 8px;
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
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="width: 70%;">
                <h1 class="company-name">{{ $companyInfo->name }}</h1>
                <p class="company-details">{{ $companyInfo->address }}</p>
                <p class="company-details">Phone: {{ $companyInfo->phone }} | Email: {{ $companyInfo->email }}</p>
                <h2 style="margin-top: 10px; color: #444; font-size: 14px;">EMPLOYEE DETAILED PROFILE</h2>
            </td>
            <td style="width: 30%; text-align: right;">
                @if($employee->photo_path)
                    <img src="{{ public_path('storage/' . $employee->photo_path) }}" class="profile-photo" alt="Photo">
                @else
                    <div style="width: 80px; height: 80px; background-color: #eee; border-radius: 6px; display: inline-block; line-height: 80px; text-align: center; color: #999;">NO PHOTO</div>
                @endif
            </td>
        </tr>
    </table>

    <!-- Personal Information -->
    <div class="section-title">Personal Information</div>
    <table class="info-grid">
        <tr>
            <td class="label">Full Name</td>
            <td class="value">{{ $employee->full_name }}</td>
            <td class="label">Employee ID</td>
            <td class="value">{{ $employee->applicant_id }}</td>
        </tr>
        <tr>
            <td class="label">Father's Name</td>
            <td class="value">{{ $employee->father_name }}</td>
            <td class="label">System ID</td>
            <td class="value">{{ $employee->system_id }}</td>
        </tr>
        <tr>
            <td class="label">Mother's Name</td>
            <td class="value">{{ $employee->mother_name }}</td>
            <td class="label">Punch Card No</td>
            <td class="value">{{ $employee->punch_card_no ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Date of Birth</td>
            <td class="value">{{ \Carbon\Carbon::parse($employee->date_of_birth)->format('d M, Y') }}</td>
            <td class="label">Gender</td>
            <td class="value">{{ $employee->gender }}</td>
        </tr>
        <tr>
            <td class="label">Marital Status</td>
            <td class="value">{{ $employee->marital_status ?? 'N/A' }}</td>
            <td class="label">Religion</td>
            <td class="value">{{ $employee->religion }}</td>
        </tr>
        <tr>
            <td class="label">Nationality</td>
            <td class="value">{{ $employee->nationality }}</td>
            <td class="label">Blood Group</td>
            <td class="value">{{ $employee->blood_group ?? 'N/A' }}</td>
        </tr>
    </table>

    <table class="row-table" style="margin-top: 15px;">
        <tr>
            <td style="width: 33.33%;">
                <div class="section-title" style="margin-top: 0;">Document Identifiers</div>
                <div class="address-box" style="min-height: 100px;">
                    <strong>TIN:</strong> {{ $employee->tin ?? 'N/A' }}<br>
                    <strong>Passport:</strong> {{ $employee->passport_no ?? 'N/A' }}<br>
                    <strong>Expiry:</strong> {{ $employee->passport_expiry ? \Carbon\Carbon::parse($employee->passport_expiry)->format('d M, Y') : 'N/A' }}<br>
                    <strong>NID/ResID:</strong> {{ $employee->residency_id_number ?? 'N/A' }}<br>
                    <strong>License:</strong> {{ $employee->license_no ?? 'N/A' }}
                </div>
            </td>
            <td style="width: 33.33%;">
                <div class="section-title" style="margin-top: 0;">Present Address</div>
                <div class="address-box" style="min-height: 100px;">
                    @php $present = (object) $employee->present_address; @endphp
                    {{ $present->address_line ?? '' }}<br>
                    {{ $present->village ?? '' }}, {{ $present->post_office ?? '' }}<br>
                    {{ $present->thana ?? '' }}, {{ $present->district ?? '' }}<br>
                    {{ $present->state ?? '' }} - {{ $present->zip_code ?? '' }}<br>
                    {{ $present->country ?? '' }}
                </div>
            </td>
            <td style="width: 33.33%;">
                <div class="section-title" style="margin-top: 0;">Permanent Address</div>
                <div class="address-box" style="min-height: 100px;">
                    @if($employee->permanent_address && count($employee->permanent_address) > 0)
                        @php $permanent = (object) $employee->permanent_address; @endphp
                        {{ $permanent->address_line ?? '' }}<br>
                        {{ $permanent->village ?? '' }}, {{ $permanent->post_office ?? '' }}<br>
                        {{ $permanent->thana ?? '' }}, {{ $permanent->district ?? '' }}<br>
                        {{ $permanent->state ?? '' }} - {{ $permanent->zip_code ?? '' }}<br>
                        {{ $permanent->country ?? '' }}
                    @else
                        {{ $present->address_line ?? '' }}<br>
                        {{ $present->village ?? '' }}, {{ $present->post_office ?? '' }}<br>
                        {{ $present->thana ?? '' }}, {{ $present->district ?? '' }}<br>
                        {{ $present->state ?? '' }} - {{ $present->zip_code ?? '' }}<br>
                        {{ $present->country ?? '' }}
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <!-- Office Information -->
    @if($officeInfo)
    <div class="section-title">Office Information</div>
    <table class="info-grid">
        <tr>
            <td class="label">Company</td>
            <td class="value">{{ $officeInfo->getCurrentCompany?->name ?? 'N/A' }}</td>
            <td class="label">Designation</td>
            <td class="value">{{ $officeInfo->getCurrentDesignation?->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Department</td>
            <td class="value">{{ $officeInfo->getCurrentDepartment?->name ?? 'N/A' }}</td>
            <td class="label">Division</td>
            <td class="value">{{ $officeInfo->getCurrentDivision?->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Section</td>
            <td class="value">{{ $officeInfo->getCurrentSection?->name ?? 'N/A' }}</td>
            <td class="label">Date of Join</td>
            <td class="value">{{ $officeInfo->date_of_join ? \Carbon\Carbon::parse($officeInfo->date_of_join)->format('d M, Y') : 'N/A' }}</td>
        </tr>
    </table>
    @endif

    <table class="row-table" style="margin-top: 15px;">
        <tr>
            @if($employee->educationInfo)
            <td>
                <div class="section-title" style="margin-top: 0;">Education</div>
                <div class="address-box">
                    <strong>Degree:</strong> {{ $employee->educationInfo->degree_name }}<br>
                    <strong>Institute:</strong> {{ $employee->educationInfo->institute_name }}<br>
                    <strong>Year:</strong> {{ $employee->educationInfo->passing_year }}
                </div>
            </td>
            @endif
            @if($employee->employmentHistory)
            <td>
                <div class="section-title" style="margin-top: 0;">Experience</div>
                <div class="address-box">
                    <strong>Company:</strong> {{ $employee->employmentHistory->company_name }}<br>
                    <strong>Designation:</strong> {{ $employee->employmentHistory->designation }}<br>
                    <strong>Duration:</strong> {{ $employee->employmentHistory->service_period }}
                </div>
            </td>
            @endif
        </tr>
    </table>

    <table class="row-table" style="margin-top: 15px;">
        <tr>
            @if($employee->nomineeInfo)
            <td>
                <div class="section-title" style="margin-top: 0;">Nominee</div>
                <div class="address-box">
                    <strong>Name:</strong> {{ $employee->nomineeInfo->nominee_name }}<br>
                    <strong>Relation:</strong> {{ $employee->nomineeInfo->relation }}<br>
                    <strong>Mobile:</strong> {{ $employee->nomineeInfo->nominee_mobile }}
                </div>
            </td>
            @endif
            @if($employee->bankAccount)
            <td>
                <div class="section-title" style="margin-top: 0;">Bank Account</div>
                <div class="address-box">
                    <strong>Bank:</strong> {{ $employee->bankAccount->getBank?->name }}<br>
                    <strong>A/C Name:</strong> {{ $employee->bankAccount->account_holder_name }}<br>
                    <strong>A/C No:</strong> {{ $employee->bankAccount->account_number }}
                </div>
            </td>
            @endif
        </tr>
    </table>

    @if($employee->salaryBreakdown)
    <div class="section-title">Salary Breakdown</div>
    <table class="info-grid">
        <tr>
            <td class="label">Gross Salary</td>
            <td class="value">{{ $employee->salaryBreakdown->gross_salary }}</td>
            <td class="label">Basic Salary</td>
            <td class="value">{{ $employee->salaryBreakdown->basic_salary }}</td>
        </tr>
        <tr>
            <td class="label">House Rent</td>
            <td class="value">{{ $employee->salaryBreakdown->house_rent }}</td>
            <td class="label">Medical</td>
            <td class="value">{{ $employee->salaryBreakdown->medical_allowance }}</td>
        </tr>
    </table>
    @endif

    <div style="page-break-inside: avoid;">
        <table class="row-table" style="margin-top: 15px;">
            <tr>
                <td style="width: 50%;">
                    <div class="section-title" style="margin-top: 0;">Policy Tags</div>
                    <div class="address-box">
                        @php $elig = $employee->employeeEligibility; @endphp
                        @if($elig)
                            @if($elig->shift_plan_status === 'active') <span class="badge">Shift Plan</span> @endif
                            @if($elig->leave_plan_status === 'active') <span class="badge">Leave Plan</span> @endif
                            @if($elig->ot_plan_status === 'active') <span class="badge">OT Plan</span> @endif
                            @if($elig->roster_plans_status === 'active') <span class="badge">Roster Plan</span> @endif
                            @if($elig->bonus_plan_status === 'active') <span class="badge">Bonus Plan</span> @endif
                            @if($elig->meal_plan_status === 'active') <span class="badge">Meal Plan</span> @endif
                        @else
                            No active policies
                        @endif
                    </div>
                </td>
                <td style="width: 50%;">
                    <div class="section-title" style="margin-top: 0;">Current Plans</div>
                    <div class="address-box">
                        @foreach($employee->shift as $s) <strong>Shift:</strong> {{ $s->name }}<br> @endforeach
                        @foreach($employee->roster as $r) @if($r->status === 'active') <strong>Roster:</strong> {{ $r->name }}<br> @endif @endforeach
                    </div>
                </td>
            </tr>
        </table>
    </div>

    @if($employee->leaveBalances->count() > 0 || $employee->leaveApplications->count() > 0)
    <div style="page-break-inside: avoid;">
        <table class="row-table" style="margin-top: 15px;">
            <tr>
                <td>
                    <div class="section-title" style="margin-top: 0;">Leave Balance</div>
                    <table class="info-grid">
                        @foreach($employee->leaveBalances as $l)
                        <tr>
                            <td class="label" style="width: 60%;">{{ $l->leave_type }}</td>
                            <td class="value" style="width: 40%; text-align: right;">{{ $l->leave_count }} / {{ $l->total_leave }}</td>
                        </tr>
                        @endforeach
                    </table>
                </td>
                <td>
                    <div class="section-title" style="margin-top: 0;">Recent Leave History</div>
                    <table class="info-grid">
                        @foreach($employee->leaveApplications->take(5) as $l)
                        <tr>
                            <td class="label" style="width: 70%;">{{ $l->getPlan?->name }} ({{ $l->status }})</td>
                            <td class="value" style="width: 30%; text-align: right;">{{ $l->leave_count }} days</td>
                        </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
        </table>
    </div>
    @endif

    <div class="footer">
        Generated on {{ date('d M, Y H:i:s') }} | {{ $companyInfo->name }} HRMS
    </div>
</body>
</html>
