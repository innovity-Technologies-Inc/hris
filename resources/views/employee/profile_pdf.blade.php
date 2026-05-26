<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Profile - {{ $employee->full_name }}</title>
    <style>
        @page {
            margin: 15px;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 8px;
            color: #333;
            line-height: 1.3;
            margin: 0;
            padding: 10px;
            background-color: #fff;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #974063;
            margin-bottom: 10px;
            padding-bottom: 5px;
        }
        .company-name {
            font-size: 14px;
            font-weight: bold;
            color: #974063;
            margin: 0;
        }
        .company-details {
            font-size: 7px;
            color: #666;
            margin: 0;
        }
        .profile-photo {
            width: 60px;
            height: 60px;
            border: 1px solid #ddd;
            border-radius: 4px;
            object-fit: cover;
        }
        .section-title {
            font-size: 9px;
            font-weight: bold;
            color: #fff;
            background-color: #974063;
            padding: 3px 6px;
            margin: 8px 0 4px 0;
            border-radius: 2px;
            text-transform: uppercase;
            width: 100%;
        }
        .info-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        .info-grid td {
            padding: 3px 2px;
            border-bottom: 1px solid #f2f2f2;
            vertical-align: top;
        }
        .label {
            font-weight: bold;
            color: #974063;
            width: 20%;
            font-size: 7px;
            text-transform: uppercase;
        }
        .value {
            color: #222;
            width: 30%;
            font-weight: 500;
        }
        .address-box {
            background-color: #fcfcfc;
            padding: 6px;
            border: 1px solid #eee;
            border-radius: 2px;
            width: 100%;
        }
        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 6px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 4px;
        }
        .badge {
            display: inline-block;
            padding: 1px 3px;
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            border-radius: 2px;
            margin-right: 2px;
            font-size: 6px;
        }
        .badge-success { background-color: #d4edda; color: #155724; border-color: #c3e6cb; }
        .badge-danger { background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; }
        .entry-card {
            border: 1px solid #eee;
            background-color: #f9f9f9;
            padding: 5px;
            margin-bottom: 4px;
            border-radius: 2px;
            width: 100%;
        }
        .sub-header {
            font-weight: bold;
            color: #444;
            border-bottom: 1px solid #eee;
            padding-bottom: 2px;
            margin-top: 5px;
            font-size: 8px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="width: 75%;">
                <h1 class="company-name">{{ $companyInfo->name }}</h1>
                <p class="company-details">{{ $companyInfo->address }}</p>
                <p class="company-details">Phone: {{ $companyInfo->phone }} | Email: {{ $companyInfo->email }}</p>
                <h2 style="margin-top: 5px; color: #444; font-size: 10px;">EMPLOYEE COMPREHENSIVE PROFILE</h2>
            </td>
            <td style="width: 25%; text-align: right;">
                @if($employee->photo_path)
                    <img src="{{ public_path('storage/' . $employee->photo_path) }}" class="profile-photo" alt="Photo">
                @else
                    <div style="width: 60px; height: 60px; background-color: #eee; border-radius: 4px; display: inline-block; line-height: 60px; text-align: center; color: #999;">NO PHOTO</div>
                @endif
            </td>
        </tr>
    </table>

    <p style="margin: 0; font-size: 8px; font-weight: bold;">
        Applicant ID: {{ $employee->applicant_id ?? 'N/A' }} | 
        System ID: {{ $employee->system_id ?? 'N/A' }} | 
        Punch Card: {{ $employee->punch_card_no ?? 'N/A' }}
    </p>

    <!-- 1. Personal Information -->
    <div class="section-title">Personal Information</div>
    <table class="info-grid">
        <tr>
            <td class="label">First Name</td><td class="value">{{ $employee->first_name }}</td>
            <td class="label">Middle Name</td><td class="value">{{ $employee->middle_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Last Name</td><td class="value">{{ $employee->last_name }}</td>
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
            <td class="label">Height</td><td class="value">{{ ($employee->height_feet ?? 0) . "' " . ($employee->height_inches ?? 0) . '"' }}</td>
            <td class="label">Children</td><td class="value">{{ $employee->children_count ?? 0 }}</td>
        </tr>
        <tr>
            <td class="label">Father's Name</td><td class="value">{{ $employee->father_name }}</td>
            <td class="label">Mother's Name</td><td class="value">{{ $employee->mother_name }}</td>
        </tr>
        <tr>
            <td class="label">Spouse Name</td><td class="value">{{ $employee->spouse_name ?? 'N/A' }}</td>
            <td class="label">Status</td><td class="value">{{ $employee->status }}</td>
        </tr>
    </table>

    <!-- 2. Birth & Identification Documents -->
    <div class="section-title">Birth & Identification Documents</div>
    <table class="info-grid">
        <tr>
            <td class="label">Date of Birth</td><td class="value">{{ $employee->date_of_birth }}</td>
            <td class="label">Birth Country</td><td class="value">{{ $employee->birth_country ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Birth Reg No</td><td class="value">{{ $employee->birth_reg_no ?? 'N/A' }}</td>
            <td class="label">TIN Number</td><td class="value">{{ $employee->tin ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">BGMEA ID</td><td class="value">{{ $employee->bgmea_id ?? 'N/A' }}</td>
            <td class="label">NID / Residency ID</td><td class="value">{{ $employee->residency_id_number ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Passport Number</td><td class="value">{{ $employee->passport_no ?? 'N/A' }}</td>
            <td class="label">Passport Expiry</td><td class="value">{{ $employee->passport_expiry ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Visa Expiry</td><td class="value">{{ $employee->visa_expiry ?? 'N/A' }}</td>
            <td class="label">Work Permit Exp</td><td class="value">{{ $employee->work_expiry ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">License Number</td><td class="value">{{ $employee->license_no ?? 'N/A' }}</td>
            <td class="label">License Expiry</td><td class="value">{{ $employee->license_expiry ?? 'N/A' }}</td>
        </tr>
    </table>

    <!-- 3. Contact Information -->
    <div class="section-title">Contact Information</div>
    <table class="info-grid">
        <tr>
            <td class="label">Personal Mobile</td><td class="value">{{ $employee->personal_mobile }}</td>
            <td class="label">Home Phone</td><td class="value">{{ $employee->home_phone ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Personal Email</td><td class="value">{{ $employee->personal_email ?? 'N/A' }}</td>
            <td class="label">Work Mobile</td><td class="value">{{ $employee->work_mobile ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Work Phone</td><td class="value">{{ $employee->work_phone ?? 'N/A' }}</td>
            <td class="label">Work Email</td><td class="value">{{ $employee->work_email ?? 'N/A' }}</td>
        </tr>
    </table>

    <!-- 4. Present Address -->
    <div class="section-title">Present Address</div>
    <div class="address-box">
        @php $present = (object) $employee->present_address; @endphp
        <table class="info-grid">
            <tr><td class="label">Address Line</td><td class="value" colspan="3">{{ $present->line_1 ?? $present->address_line ?? 'N/A' }}</td></tr>
            <tr>
                <td class="label">Village</td><td class="value">{{ $present->village ?? 'N/A' }}</td>
                <td class="label">Post Office</td><td class="value">{{ $present->post_office ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Thana / Upazila</td><td class="value">{{ $present->thana ?? 'N/A' }}</td>
                <td class="label">District</td><td class="value">{{ $present->district ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Division / State</td><td class="value">{{ $present->division ?? $present->state ?? 'N/A' }}</td>
                <td class="label">Zip / Country</td><td class="value">{{ $present->zip_code ?? '' }} {{ $present->country ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <!-- 5. Permanent Address -->
    <div class="section-title">Permanent Address</div>
    <div class="address-box">
        @php $perm = (object) ($employee->permanent_address ?? $employee->present_address); @endphp
        <table class="info-grid">
            <tr><td class="label">Address Line</td><td class="value" colspan="3">{{ $perm->line_1 ?? $perm->address_line ?? 'N/A' }}</td></tr>
            <tr>
                <td class="label">Village</td><td class="value">{{ $perm->village ?? 'N/A' }}</td>
                <td class="label">Post Office</td><td class="value">{{ $perm->post_office ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Thana / Upazila</td><td class="value">{{ $perm->thana ?? 'N/A' }}</td>
                <td class="label">District</td><td class="value">{{ $perm->district ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Division / State</td><td class="value">{{ $perm->division ?? $perm->state ?? 'N/A' }}</td>
                <td class="label">Zip / Country</td><td class="value">{{ $perm->zip_code ?? '' }} {{ $perm->country ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <!-- 6. Reference Information -->
    <div class="section-title">Reference / Emergency Contact</div>
    <div class="address-box">
        @if($employee->reference_address)
            @php $ref = (object) $employee->reference_address; @endphp
            <table class="info-grid">
                <tr>
                    <td class="label">Name</td><td class="value">{{ $ref->reference_name ?? 'N/A' }}</td>
                    <td class="label">Ref ID</td><td class="value">{{ $ref->emp_id ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Designation</td><td class="value">{{ $ref->reference_designation ?? 'N/A' }}</td>
                    <td class="label">Contact</td><td class="value">{{ $ref->mobile ?? $ref->phone ?? 'N/A' }}</td>
                </tr>
                <tr><td class="label">Email</td><td class="value" colspan="3">{{ $ref->email ?? 'N/A' }}</td></tr>
                <tr><td class="label">Address</td><td class="value" colspan="3">{{ $ref->line_1 ?? $ref->address_line ?? '' }}, {{ $ref->village ?? '' }}, {{ $ref->thana ?? '' }}, {{ $ref->district ?? '' }}, {{ $ref->state ?? '' }}, {{ $ref->country ?? '' }}</td></tr>
            </table>
        @else
            N/A
        @endif
    </div>

    <!-- 7. Office Information -->
    @if($officeInfo)
    <div class="section-title">Office Information</div>
    
    <div class="sub-header">PAYROLL & IDENTIFICATION</div>
    <table class="info-grid">
        <tr>
            <td class="label">Employee Type</td><td class="value">{{ $officeInfo->emp_type ?? 'N/A' }}</td>
            <td class="label">HR File No</td><td class="value">{{ $officeInfo->hr_file_no ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Pay Grade</td><td class="value">{{ $officeInfo->getGrade?->name ?? 'N/A' }}</td>
            <td class="label">Act / Tofsil</td><td class="value">{{ $officeInfo->getTofsil?->name ?? 'N/A' }}</td>
        </tr>
        <tr><td class="label">File Note</td><td class="value" colspan="3">{{ $officeInfo->file_note ?? 'N/A' }}</td></tr>
    </table>
    
    <div class="sub-header">JOINING DETAILS</div>
    <table class="info-grid">
        <tr>
            <td class="label">Joining Company</td><td class="value">{{ $officeInfo->getJoiningCompany?->name ?? 'N/A' }}</td>
            <td class="label">Joining B.U.</td><td class="value">{{ $officeInfo->getJoiningBusinessUnit?->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Joining Division</td><td class="value">{{ $officeInfo->getJoiningDivision?->name ?? 'N/A' }}</td>
            <td class="label">Joining Dept.</td><td class="value">{{ $officeInfo->getJoiningDepartment?->department_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Joining Section</td><td class="value">{{ $officeInfo->getJoiningSection?->name ?? 'N/A' }}</td>
            <td class="label">Joining Desig.</td><td class="value">{{ $officeInfo->getJoiningDesignation?->company_designation ?? 'N/A' }}</td>
        </tr>
        <tr><td class="label">Date of Join</td><td class="value">{{ $officeInfo->date_of_join ?? 'N/A' }}</td><td></td><td></td></tr>
    </table>

    <div class="sub-header">CURRENT POSITION</div>
    <table class="info-grid">
        <tr>
            <td class="label">Company</td><td class="value">{{ $officeInfo->getCurrentCompany?->name ?? 'N/A' }}</td>
            <td class="label">Business Unit</td><td class="value">{{ $officeInfo->getCurrentBusinessUnit?->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Division</td><td class="value">{{ $officeInfo->getCurrentDivision?->name ?? 'N/A' }}</td>
            <td class="label">Department</td><td class="value">{{ $officeInfo->getCurrentDepartment?->department_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Section</td><td class="value">{{ $officeInfo->getCurrentSection?->name ?? 'N/A' }}</td>
            <td class="label">Designation</td><td class="value">{{ $officeInfo->getCurrentDesignation?->company_designation ?? 'N/A' }}</td>
        </tr>
    </table>

    <div class="sub-header">ORIENTATION & PROGRESSION</div>
    <table class="info-grid">
        <tr>
            <td class="label">Orientation</td><td class="value">{{ ucfirst($officeInfo->orientation_required ?? 'no') }}</td>
            <td class="label">Period</td><td class="value">{{ $officeInfo->orientation_from ?? 'N/A' }} to {{ $officeInfo->orientation_to ?? 'N/A' }} ({{ $officeInfo->orientation_days ?? 0 }} days)</td>
        </tr>
        <tr><td class="label">Orient. Type</td><td class="value" colspan="3">{{ $officeInfo->orientation_type ?? 'N/A' }}</td></tr>
        <tr>
            <td class="label">Confirmation</td><td class="value">{{ $officeInfo->confirmation_date ?? 'N/A' }}</td>
            <td class="label">Probation</td><td class="value">{{ ($officeInfo->probation_duration ?? 0) . ' Days' }}</td>
        </tr>
        <tr>
            <td class="label">Next Promotion</td><td class="value">{{ $officeInfo->next_promotion_date ?? 'N/A' }}</td>
            <td class="label">Promo Cycle</td><td class="value">{{ $officeInfo->promotion_cycle ?? 'N/A' }}</td>
        </tr>
        <tr><td class="label">Increment Cycle</td><td class="value" colspan="3">{{ $officeInfo->increment_cycle ?? 'N/A' }}</td></tr>
    </table>

    <div class="sub-header">BENEFITS & SCHEDULE</div>
    <table class="info-grid">
        <tr>
            <td class="label">Salary Type</td><td class="value">{{ $officeInfo->salary_type ?? 'N/A' }}</td>
            <td class="label">Weekends</td><td class="value">{{ implode(', ', (array)($officeInfo->weekends ?? [])) }}</td>
        </tr>
        <tr><td class="label">Alternate Off</td><td class="value" colspan="3">{{ implode(', ', (array)($officeInfo->alternate_off_day ?? [])) }}</td></tr>
    </table>
    <div style="margin-top: 3px;">
        <span class="badge {{ $officeInfo->ot_allowed == 'yes' ? 'badge-success' : 'badge-danger' }}">OT: {{ strtoupper($officeInfo->ot_allowed ?? 'no') }}</span>
        <span class="badge {{ $officeInfo->pf_eligible == 'yes' ? 'badge-success' : 'badge-danger' }}">PF: {{ strtoupper($officeInfo->pf_eligible ?? 'no') }}</span>
        <span class="badge {{ $officeInfo->transport_eligible == 'yes' ? 'badge-success' : 'badge-danger' }}">TRANS: {{ strtoupper($officeInfo->transport_eligible ?? 'no') }}</span>
        <span class="badge {{ $officeInfo->gratuity_eligible == 'yes' ? 'badge-success' : 'badge-danger' }}">GRAT: {{ strtoupper($officeInfo->gratuity_eligible ?? 'no') }}</span>
        <span class="badge {{ $officeInfo->can_apply_loan == 'yes' ? 'badge-success' : 'badge-danger' }}">LOAN: {{ strtoupper($officeInfo->can_apply_loan ?? 'no') }}</span>
        <span class="badge {{ $officeInfo->can_apply_advance == 'yes' ? 'badge-success' : 'badge-danger' }}">ADVANCE: {{ strtoupper($officeInfo->can_apply_advance ?? 'no') }}</span>
        @if($officeInfo->pf_eligible == 'yes')
            <span style="font-size: 7px; margin-left: 5px;">(PF Effective: {{ $officeInfo->pf_effective_date ?? 'N/A' }})</span>
        @endif
    </div>
    @endif

    <!-- 8. Education Information -->
    @if($employee->educationInfo && count($employee->educationInfo->educations ?? []) > 0)
    <div class="section-title">Education Information</div>
    @foreach($employee->educationInfo->educations as $edu)
        <div class="entry-card">
            <table class="info-grid">
                <tr><td class="label">Title</td><td class="value">{{ $edu['education_title'] ?? 'N/A' }}</td><td class="label">Institute</td><td class="value">{{ $edu['institute'] ?? 'N/A' }}</td></tr>
                <tr><td class="label">Major</td><td class="value">{{ $edu['group_major'] ?? 'N/A' }}</td><td class="label">Year</td><td class="value">{{ $edu['passing_year'] ?? 'N/A' }}</td></tr>
                <tr><td class="label">Result</td><td class="value" colspan="3">{{ $edu['result_grade'] ?? 'N/A' }} {{ isset($edu['gpa_cgpa']) ? '('.$edu['gpa_cgpa'].')' : '' }}</td></tr>
            </table>
        </div>
    @endforeach
    @endif

    <!-- 9. Training Information -->
    @if($employee->educationInfo && count($employee->educationInfo->trainings ?? []) > 0)
    <div class="section-title">Training Information</div>
    @foreach($employee->educationInfo->trainings as $trn)
        <div class="entry-card">
            <table class="info-grid">
                <tr><td class="label">Title</td><td class="value">{{ $trn['training_title'] ?? 'N/A' }}</td><td class="label">Course</td><td class="value">{{ $trn['course_name'] ?? 'N/A' }}</td></tr>
                <tr><td class="label">Institute</td><td class="value">{{ $trn['institute'] ?? 'N/A' }}</td><td class="label">Duration</td><td class="value">{{ $trn['duration'] ?? 'N/A' }}</td></tr>
                <tr><td class="label">Period</td><td class="value" colspan="3">{{ $trn['from_date'] ?? '' }} to {{ $trn['to_date'] ?? '' }}</td></tr>
            </table>
        </div>
    @endforeach
    @endif

    <!-- 10. Employment History -->
    @if($employee->employmentHistory && count($employee->employmentHistory->histories ?? []) > 0)
    <div class="section-title">Employment History</div>
    @foreach($employee->employmentHistory->histories as $history)
        <div class="entry-card">
            <table class="info-grid">
                <tr><td class="label">Company</td><td class="value">{{ $history['company_name'] ?? $history['company'] ?? 'N/A' }}</td><td class="label">Designation</td><td class="value">{{ $history['designation'] ?? 'N/A' }}</td></tr>
                <tr><td class="label">Period</td><td class="value" colspan="3">{{ $history['joining_date'] ?? $history['from_date'] ?? 'N/A' }} to {{ $history['end_date'] ?? $history['to_date'] ?? 'Present' }}</td></tr>
                @if(!empty($history['job_description']))
                <tr><td class="label">Job Desc</td><td class="value" colspan="3" style="font-size: 7px; color: #555;">{{ $history['job_description'] }}</td></tr>
                @endif
            </table>
        </div>
    @endforeach
    @endif

    <!-- 11. Nominee Information -->
    @if($employee->nomineeInfo)
    <div class="section-title">Nominee Information</div>
    <div class="address-box">
        @php $n = $employee->nomineeInfo; @endphp
        <table class="info-grid">
            <tr><td class="label">Name</td><td class="value">{{ $n->nominee_name }}</td><td class="label">Relation</td><td class="value">{{ $n->relation }}</td></tr>
            <tr><td class="label">Mobile</td><td class="value">{{ $n->nominee_mobile }}</td><td class="label">ID/NID</td><td class="value">{{ $n->nid ?? $n->birth_reg_no ?? 'N/A' }}</td></tr>
            <tr><td class="label">Gender</td><td class="value">{{ $n->gender }}</td><td class="label">DOB</td><td class="value">{{ $n->date_of_birth }}</td></tr>
            <tr><td class="label">Address</td><td class="value" colspan="3">{{ $n->present_address_line ?? 'N/A' }}, {{ $n->village ?? '' }}, {{ $n->post_office ?? '' }}, {{ $n->thana ?? '' }}, {{ $n->district ?? '' }}, {{ $n->state ?? '' }}, {{ $n->country ?? '' }}</td></tr>
        </table>
    </div>
    @endif

    <!-- 12. Bank Account Details -->
    @if($employee->bankAccount)
    <div class="section-title">Bank Account Details</div>
    <div class="address-box">
        @php $ba = $employee->bankAccount; @endphp
        <table class="info-grid">
            <tr><td class="label">Bank</td><td class="value">{{ $ba->getBank?->name }}</td><td class="label">Branch</td><td class="value">{{ $ba->getBranch?->name }}</td></tr>
            <tr><td class="label">Holder</td><td class="value">{{ $ba->account_holder_name }}</td><td class="label">Account</td><td class="value">{{ $ba->account_number }}</td></tr>
            <tr><td class="label">Status</td><td class="value" colspan="3">{{ $ba->status }}</td></tr>
        </table>
    </div>
    @endif

    <!-- 13. Salary Breakdown -->
    @if($employee->salaryBreakdown)
    <div class="section-title">Salary Breakdown</div>
    <table class="info-grid">
        @php $s = $employee->salaryBreakdown; $cur = $s->currency ?? ''; @endphp
        <tr><td class="label">Basic Salary</td><td class="value">{{ $s->basic_salary }} {{ $cur }}</td><td class="label">House Allow</td><td class="value">{{ $s->house_allowance ?? 0 }} {{ $cur }}</td></tr>
        <tr><td class="label">Transport</td><td class="value">{{ $s->transport_allowance ?? 0 }} {{ $cur }}</td><td class="label">Gross Salary</td><td class="value" style="font-weight: bold;">{{ $s->gross_salary }} {{ $cur }}</td></tr>
    </table>
    @endif

    <!-- 14. Policies & Assigned Plans -->
    <div style="page-break-inside: avoid;">
        <div class="section-title">Active Policies & Assigned Plans</div>
        <div class="address-box">
            @php $elig = $employee->employeeEligibility; @endphp
            @if($elig)
                <p><strong>Active:</strong> 
                @if($elig->shift_plan_status === 'active') <span class="badge">Shift</span> @endif
                @if($elig->leave_plan_status === 'active') <span class="badge">Leave</span> @endif
                @if($elig->ot_plan_status === 'active') <span class="badge">OT</span> @endif
                @if($elig->roster_plans_status === 'active') <span class="badge">Roster</span> @endif
                @if($elig->bonus_plan_status === 'active') <span class="badge">Bonus</span> @endif
                @if($elig->meal_plan_status === 'active') <span class="badge">Meal</span> @endif
                </p>
            @endif
            @if($employee->shift->count() > 0)
                <p><strong>Shifts:</strong> @foreach($employee->shift as $s) <span class="badge">{{ $s->name }}</span> @endforeach</p>
            @endif
        </div>
    </div>

    <!-- 15. Leave Summary -->
    <div style="page-break-inside: avoid;">
        <div class="section-title">Leave Summary</div>
        <table class="info-grid">
            <tr>
                <td style="width: 45%; vertical-align: top;">
                    <div class="sub-header">LEAVE BALANCES</div>
                    @foreach($employee->leaveBalances as $l)
                    <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #f2f2f2; padding: 2px 0;">
                        <span style="font-weight: bold;">{{ $l->leave_type }}:</span>
                        <span>{{ $l->leave_count }} / {{ $l->total_leave }}</span>
                    </div>
                    @endforeach
                </td>
                <td style="width: 10%;"></td>
                <td style="width: 45%; vertical-align: top;">
                    <div class="sub-header">RECENT HISTORY</div>
                    @foreach($employee->leaveApplications->take(5) as $l)
                    <div style="border-bottom: 1px solid #f2f2f2; padding: 2px 0;">
                        <span style="font-weight: bold;">{{ $l->getPlan?->name ?? 'Leave' }}</span> - {{ strtoupper($l->status) }}<br>
                        <span style="color: #666; font-size: 7px;">{{ $l->from }} to {{ $l->to }} ({{ $l->leave_count }}d)</span>
                    </div>
                    @endforeach
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Generated on {{ date('d M, Y H:i:s') }} | {{ $companyInfo->name }} HRMS | Page 1
    </div>
</body>
</html>
