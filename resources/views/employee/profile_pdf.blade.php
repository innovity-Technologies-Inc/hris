<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Profile - {{ $employee->full_name }}</title>
    <style>
        @page { margin: 15px; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 8px; color: #333; line-height: 1.3; margin: 0; padding: 10px; background-color: #fff; }
        .header-table { width: 100%; border-bottom: 2px solid #974063; margin-bottom: 10px; padding-bottom: 5px; }
        .company-name { font-size: 14px; font-weight: bold; color: #974063; margin: 0; }
        .company-details { font-size: 7px; color: #666; margin: 0; }
        .profile-photo { width: 60px; height: 60px; border: 1px solid #ddd; border-radius: 4px; object-fit: cover; }
        .section-title { font-size: 9px; font-weight: bold; color: #fff; background-color: #974063; padding: 3px 6px; margin: 8px 0 4px 0; border-radius: 2px; text-transform: uppercase; width: 100%; }
        .info-grid { width: 100%; border-collapse: collapse; margin-bottom: 5px; }
        .info-grid td { padding: 3px 2px; border-bottom: 1px solid #f2f2f2; vertical-align: top; }
        .label { font-weight: bold; color: #974063; width: 20%; font-size: 7px; text-transform: uppercase; }
        .value { color: #222; width: 30%; font-weight: 500; }
        .address-box { background-color: #fcfcfc; padding: 6px; border: 1px solid #eee; border-radius: 2px; width: 100%; margin-bottom: 5px; }
        .footer { margin-top: 15px; text-align: center; font-size: 6px; color: #999; border-top: 1px solid #eee; padding-top: 4px; } 
        .badge { display: inline-block; padding: 1px 3px; background-color: #f0f0f0; border: 1px solid #ddd; border-radius: 2px; margin-right: 2px; font-size: 6px; }
        .badge-success { background-color: #d4edda; color: #155724; border-color: #c3e6cb; }
        .badge-danger { background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; }
        .entry-card { border: 1px solid #eee; background-color: #f9f9f9; padding: 5px; margin-bottom: 4px; border-radius: 2px; width: 100%; }
        .sub-header { font-weight: bold; color: #444; border-bottom: 1px solid #eee; padding-bottom: 2px; margin-top: 5px; font-size: 8px; text-transform: uppercase; }
        hr { border: 0; border-top: 1px solid #eee; margin: 4px 0; }
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
        Applicant ID: {{ $employee->applicant_id ?? 'N/A' }} | System ID: {{ $employee->system_id ?? 'N/A' }} | Punch Card: {{ $employee->punch_card_no ?? 'N/A' }}
    </p>

    <!-- 1. Personal Information -->
    <div class="section-title">Personal Information</div>
    <table class="info-grid">
        <tr><td class="label">First Name</td><td class="value">{{ $employee->first_name }}</td><td class="label">Middle Name</td><td class="value">{{ $employee->middle_name ?? 'N/A' }}</td></tr>
        <tr><td class="label">Last Name</td><td class="value">{{ $employee->last_name }}</td><td class="label">Gender</td><td class="value">{{ $employee->gender }}</td></tr>
        <tr><td class="label">Marital Status</td><td class="value">{{ $employee->marital_status ?? 'N/A' }}</td><td class="label">Religion</td><td class="value">{{ $employee->religion }}</td></tr>
        <tr><td class="label">Nationality</td><td class="value">{{ $employee->nationality }}</td><td class="label">Blood Group</td><td class="value">{{ $employee->blood_group ?? 'N/A' }}</td></tr>
        <tr><td class="label">Height</td><td class="value">{{ ($employee->height_feet ?? 0) . "' " . ($employee->height_inches ?? 0) . '"' }}</td><td class="label">Children</td><td class="value">{{ $employee->children_count ?? 0 }}</td></tr>
        <tr><td class="label">Father's Name</td><td class="value">{{ $employee->father_name }}</td><td class="label">Mother's Name</td><td class="value">{{ $employee->mother_name }}</td></tr>
        <tr><td class="label">Spouse Name</td><td class="value">{{ $employee->spouse_name ?? 'N/A' }}</td><td class="label">Status</td><td class="value">{{ $employee->status }}</td></tr>
    </table>

    <!-- 2. Birth & Identification Documents -->
    <div class="section-title">Birth & Identification Documents</div>
    <table class="info-grid">
        <tr><td class="label">Date of Birth</td><td class="value">{{ $employee->date_of_birth }}</td><td class="label">Birth Country</td><td class="value">{{ $employee->birth_country ?? 'N/A' }}</td></tr>
        <tr><td class="label">Birth Reg No</td><td class="value">{{ $employee->birth_reg_no ?? 'N/A' }}</td><td class="label">TIN Number</td><td class="value">{{ $employee->tin ?? 'N/A' }}</td></tr>
        <tr><td class="label">BGMEA ID</td><td class="value">{{ $employee->bgmea_id ?? 'N/A' }}</td><td class="label">NID / Residency ID</td><td class="value">{{ $employee->residency_id_number ?? 'N/A' }}</td></tr>
        <tr><td class="label">Passport No</td><td class="value">{{ $employee->passport_no ?? 'N/A' }}</td><td class="label">Passport Exp</td><td class="value">{{ $employee->passport_expiry ?? 'N/A' }}</td></tr>
        <tr><td class="label">Visa Expiry</td><td class="value">{{ $employee->visa_expiry ?? 'N/A' }}</td><td class="label">Work Permit Exp</td><td class="value">{{ $employee->work_expiry ?? 'N/A' }}</td></tr>
        <tr><td class="label">License No</td><td class="value">{{ $employee->license_no ?? 'N/A' }}</td><td class="label">License Exp</td><td class="value">{{ $employee->license_expiry ?? 'N/A' }}</td></tr>
    </table>

    <!-- 3. Contact Information -->
    <div class="section-title">Contact Information</div>
    <table class="info-grid">
        <tr><td class="label">Personal Mobile</td><td class="value">{{ $employee->personal_mobile }}</td><td class="label">Home Phone</td><td class="value">{{ $employee->home_phone ?? 'N/A' }}</td></tr>
        <tr><td class="label">Personal Email</td><td class="value">{{ $employee->personal_email ?? 'N/A' }}</td><td class="label">Work Mobile</td><td class="value">{{ $employee->work_mobile ?? 'N/A' }}</td></tr>
        <tr><td class="label">Work Phone</td><td class="value">{{ $employee->work_phone ?? 'N/A' }}</td><td class="label">Work Email</td><td class="value">{{ $employee->work_email ?? 'N/A' }}</td></tr>
    </table>

    <!-- 4. Present Address -->
    <div class="section-title">Present Address</div>
    <div class="address-box">
        @php $present = (object) $employee->present_address; @endphp
        <table class="info-grid">
            <tr><td class="label">Address Line</td><td class="value">{{ $present->line_1 ?? $present->address_line ?? 'N/A' }}</td><td class="label">Village</td><td class="value">{{ $present->village ?? 'N/A' }}</td></tr>
            <tr><td class="label">Post Office</td><td class="value">{{ $present->post_office ?? 'N/A' }}</td><td class="label">Thana</td><td class="value">{{ $present->thana ?? 'N/A' }}</td></tr>
            <tr><td class="label">District</td><td class="value">{{ $present->district ?? 'N/A' }}</td><td class="label">Division</td><td class="value">{{ $present->division ?? 'N/A' }}</td></tr>
            <tr><td class="label">State</td><td class="value">{{ $present->state ?? 'N/A' }}</td><td class="label">Zip / Country</td><td class="value">{{ $present->zip_code ?? '' }} {{ $present->country ?? 'N/A' }}</td></tr>
        </table>
    </div>

    <!-- 5. Permanent Address -->
    <div class="section-title">Permanent Address</div>
    <div class="address-box">
        @php $perm = (object) ($employee->permanent_address ?? $employee->present_address); @endphp
        <table class="info-grid">
            <tr><td class="label">Address Line</td><td class="value">{{ $perm->line_1 ?? $perm->address_line ?? 'N/A' }}</td><td class="label">Village</td><td class="value">{{ $perm->village ?? 'N/A' }}</td></tr>
            <tr><td class="label">Post Office</td><td class="value">{{ $perm->post_office ?? 'N/A' }}</td><td class="label">Thana</td><td class="value">{{ $perm->thana ?? 'N/A' }}</td></tr>
            <tr><td class="label">District</td><td class="value">{{ $perm->district ?? 'N/A' }}</td><td class="label">Division</td><td class="value">{{ $perm->division ?? 'N/A' }}</td></tr>
            <tr><td class="label">State</td><td class="value">{{ $perm->state ?? 'N/A' }}</td><td class="label">Zip / Country</td><td class="value">{{ $perm->zip_code ?? '' }} {{ $perm->country ?? 'N/A' }}</td></tr>
        </table>
    </div>

    <!-- 6. Reference Information -->
    <div class="section-title">Reference / Emergency Contact</div>
    <div class="address-box">
        @if($employee->reference_address)
            @php $ref = (object) $employee->reference_address; @endphp
            <table class="info-grid">
                <tr><td class="label">Name</td><td class="value">{{ $ref->reference_name ?? 'N/A' }}</td><td class="label">Ref ID</td><td class="value">{{ $ref->emp_id ?? 'N/A' }}</td></tr>
                <tr><td class="label">Designation</td><td class="value">{{ $ref->reference_designation ?? 'N/A' }}</td><td class="label">Contact</td><td class="value">{{ $ref->mobile ?? $ref->phone ?? 'N/A' }}</td></tr>
                <tr><td class="label">Email</td><td class="value" colspan="3">{{ $ref->email ?? 'N/A' }}</td></tr>
                <tr><td class="label">Address</td><td class="value" colspan="3">{{ $ref->line_1 ?? $ref->address_line ?? '' }}, {{ $ref->village ?? '' }}, {{ $ref->thana ?? '' }}, {{ $ref->district ?? '' }}, {{ $ref->country ?? '' }}</td></tr>
            </table>
        @else
            N/A
        @endif
    </div>

    <!-- 7. Office Information -->
    @if($officeInfo)
    <div class="section-title">Office Information</div>
    <div class="sub-header">Payroll & Identification</div>
    <table class="info-grid">
        <tr><td class="label">Employee Type</td><td class="value">{{ $officeInfo->emp_type ?? 'N/A' }}</td><td class="label">HR File No</td><td class="value">{{ $officeInfo->hr_file_no ?? 'N/A' }}</td></tr>
        <tr><td class="label">Pay Grade</td><td class="value">{{ $officeInfo->getGrade?->grade_name ?? 'N/A' }}</td><td class="label">Status</td><td class="value">{{ strtoupper($employee->status ?? 'N/A') }}</td></tr>
        <tr><td class="label">File Note</td><td class="value" colspan="3">{{ $officeInfo->file_note ?? 'N/A' }}</td></tr>
    </table>
    <div class="sub-header">Joining Details</div>
    <table class="info-grid">
        <tr><td class="label">Joining Company</td><td class="value">{{ $officeInfo->getJoiningCompany?->name ?? 'N/A' }}</td><td class="label">Business Unit</td><td class="value">{{ $officeInfo->getJoiningBusinessUnit?->name ?? 'N/A' }}</td></tr>
        <tr><td class="label">Joining Division</td><td class="value">{{ $officeInfo->getJoiningDivision?->name ?? 'N/A' }}</td><td class="label">Department</td><td class="value">{{ $officeInfo->getJoiningDepartment?->department_name ?? 'N/A' }}</td></tr>
        <tr><td class="label">Joining Section</td><td class="value">{{ $officeInfo->getJoiningSection?->name ?? 'N/A' }}</td><td class="label">Designation</td><td class="value">{{ $officeInfo->getJoiningDesignation?->company_designation ?? 'N/A' }}</td></tr>        
        <tr><td class="label">Date of Join</td><td class="value">{{ $officeInfo->date_of_join ?? 'N/A' }}</td><td></td><td></td></tr>
    </table>
    <div class="sub-header">Current Position</div>
    <table class="info-grid">
        <tr><td class="label">Company</td><td class="value">{{ $officeInfo->getCurrentCompany?->name ?? 'N/A' }}</td><td class="label">Business Unit</td><td class="value">{{ $officeInfo->getCurrentBusinessUnit?->name ?? 'N/A' }}</td></tr>
        <tr><td class="label">Division</td><td class="value">{{ $officeInfo->getCurrentDivision?->name ?? 'N/A' }}</td><td class="label">Department</td><td class="value">{{ $officeInfo->getCurrentDepartment?->department_name ?? 'N/A' }}</td></tr>
        <tr><td class="label">Section</td><td class="value">{{ $officeInfo->getCurrentSection?->name ?? 'N/A' }}</td><td class="label">Designation</td><td class="value">{{ $officeInfo->getCurrentDesignation?->company_designation ?? 'N/A' }}</td></tr>
    </table>
    <div class="sub-header">Orientation & Progression</div>
    <table class="info-grid">
        <tr><td class="label">Orientation</td><td class="value">{{ ucfirst($officeInfo->orientation_required ?? 'no') }}</td><td class="label">Period</td><td class="value">{{ $officeInfo->orientation_from ?? 'N/A' }} to {{ $officeInfo->orientation_to ?? 'N/A' }} ({{ $officeInfo->orientation_days ?? 0 }} days)</td></tr>
        <tr><td class="label">Orient. Type</td><td class="value">{{ $officeInfo->orientation_type ?? 'N/A' }}</td><td class="label">Confirmation</td><td class="value">{{ $officeInfo->confirmation_date ?? 'N/A' }}</td></tr>
        <tr><td class="label">Probation</td><td class="value">{{ ($officeInfo->probation_duration ?? 0) . ' Days' }}</td><td class="label">Next Promotion</td><td class="value">{{ $officeInfo->next_promotion_date ?? 'N/A' }}</td></tr>
        <tr><td class="label">Promo Cycle</td><td class="value">{{ $officeInfo->promotion_cycle ?? 'N/A' }}</td><td class="label">Increment Cycle</td><td class="value">{{ $officeInfo->increment_cycle ?? 'N/A' }}</td></tr>
    </table>
    <div class="sub-header">Benefits & Schedule</div>
    <table class="info-grid">
        <tr><td class="label">PF Effective</td><td class="value">{{ $officeInfo->pf_effective_date ?? 'N/A' }}</td><td class="label">OT Allowed</td><td class="value">{{ strtoupper($officeInfo->ot_allowed ?? 'N/A') }}</td></tr>
        <tr><td class="label">Weekends</td><td class="value">{{ implode(', ', (array)($officeInfo->weekends ?? [])) }}</td><td class="label">Alternate Off</td><td class="value">{{ implode(', ', (array)($officeInfo->alternate_off_day ?? [])) }}</td></tr>
    </table>
    <div style="margin-top: 3px;">
        <span class="badge {{ $officeInfo->ot_allowed == 'yes' ? 'badge-success' : 'badge-danger' }}">OT: {{ strtoupper($officeInfo->ot_allowed ?? 'no') }}</span>
        <span class="badge {{ $officeInfo->pf_eligible == 'yes' ? 'badge-success' : 'badge-danger' }}">PF: {{ strtoupper($officeInfo->pf_eligible ?? 'no') }}</span>
        <span class="badge {{ $officeInfo->transport_eligible == 'yes' ? 'badge-success' : 'badge-danger' }}">TRANS: {{ strtoupper($officeInfo->transport_eligible ?? 'no') }}</span>
        <span class="badge {{ $officeInfo->gratuity_eligible == 'yes' ? 'badge-success' : 'badge-danger' }}">GRAT: {{ strtoupper($officeInfo->gratuity_eligible ?? 'no') }}</span>
        <span class="badge {{ $officeInfo->can_apply_loan == 'yes' ? 'badge-success' : 'badge-danger' }}">LOAN: {{ strtoupper($officeInfo->can_apply_loan ?? 'no') }}</span>
        <span class="badge {{ $officeInfo->can_apply_advance == 'yes' ? 'badge-success' : 'badge-danger' }}">ADVANCE: {{ strtoupper($officeInfo->can_apply_advance ?? 'no') }}</span>
    </div>
    @endif

    <!-- 8. Education Records -->
    @if($employee->educationInfo && count($employee->educationInfo->educations ?? []) > 0)
    <div class="section-title">Education Records</div>
    @foreach($employee->educationInfo->educations as $edu)
        <div class="entry-card">
            <table class="info-grid">
                <tr><td class="label">Title</td><td class="value">{{ $edu['education_title'] ?? 'N/A' }}</td><td class="label">Institute</td><td class="value">{{ $edu['institute'] ?? 'N/A' }}</td></tr>
                <tr><td class="label">Board</td><td class="value">{{ $edu['board_university'] ?? 'N/A' }}</td><td class="label">Major</td><td class="value">{{ $edu['group_major'] ?? 'N/A' }}</td></tr>
                <tr><td class="label">Passing Year</td><td class="value">{{ $edu['passing_year'] ?? 'N/A' }}</td><td class="label">Result</td><td class="value">{{ $edu['result_grade'] ?? 'N/A' }} ({{ $edu['gpa_cgpa'] ?? '0.00' }})</td></tr>
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
                <tr><td class="label">Code</td><td class="value">{{ $trn['training_code'] ?? 'N/A' }}</td><td class="label">Institute</td><td class="value">{{ $trn['institute'] ?? 'N/A' }}</td></tr>
                <tr><td class="label">Location</td><td class="value">{{ $trn['location'] ?? 'N/A' }}</td><td class="label">Country</td><td class="value">{{ $trn['country'] ?? 'N/A' }}</td></tr>
                <tr><td class="label">Duration</td><td class="value">{{ $trn['duration'] ?? 'N/A' }}</td><td class="label">Period</td><td class="value">{{ $trn['from_date'] ?? '' }} - {{ $trn['to_date'] ?? '' }}</td></tr>
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
                <tr><td class="label">Join Date</td><td class="value">{{ $history['joining_date'] ?? $history['from_date'] ?? 'N/A' }}</td><td class="label">End Date</td><td class="value">{{ $history['end_date'] ?? $history['to_date'] ?? 'Present' }}</td></tr>      
                @if(!empty($history['job_description']))
                <tr><td class="label">Description</td><td class="value" colspan="3">{{ $history['job_description'] }}</td></tr>      
                @endif
                @if(!empty($history['achievements']))
                <tr><td class="label">Achievements</td><td class="value" colspan="3">{{ $history['achievements'] }}</td></tr>        
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
            <tr><td class="label">Mobile</td><td class="value">{{ $n->nominee_mobile }}</td><td class="label">Phone</td><td class="value">{{ $n->phone ?? 'N/A' }}</td></tr>
            <tr><td class="label">Gender</td><td class="value">{{ $n->gender }}</td><td class="label">DOB</td><td class="value">{{ $n->date_of_birth }}</td></tr>
            <tr><td class="label">NID</td><td class="value">{{ $n->nid ?? 'N/A' }}</td><td class="label">Birth Reg</td><td class="value">{{ $n->birth_reg_no ?? 'N/A' }}</td></tr>
            <tr><td class="label">Religion</td><td class="value">{{ $n->religion ?? 'N/A' }}</td><td class="label">Nationality</td><td class="value">{{ $n->nationality ?? 'N/A' }}</td></tr>
            <tr><td class="label">Marital</td><td class="value">{{ $n->marital_status ?? 'N/A' }}</td><td class="label">Blood Group</td><td class="value">{{ $n->blood_group ?? 'N/A' }}</td></tr>
            <tr><td class="label">Address</td><td class="value" colspan="3">{{ $n->present_address_line ?? 'N/A' }}, {{ $n->village ?? '' }}, {{ $n->thana ?? '' }}, {{ $n->district ?? '' }}, {{ $n->country ?? '' }}</td></tr>
        </table>
    </div>
    @endif

    <!-- 12. Bank Details -->
    @if($employee->bankAccount)
    <div class="section-title">Bank Details</div>
    <table class="info-grid">
        @php $ba = $employee->bankAccount; @endphp
        <tr><td class="label">Bank</td><td class="value">{{ $ba->getBank?->name }}</td><td class="label">Branch</td><td class="value">{{ $ba->getBranch?->name }}</td></tr>
        <tr><td class="label">Holder</td><td class="value">{{ $ba->account_holder_name }}</td><td class="label">Account</td><td class="value">{{ $ba->account_number }}</td></tr>
        <tr><td class="label">Status</td><td class="value">{{ $ba->status }}</td><td class="label">Remarks</td><td class="value">{{ $ba->remarks ?? 'N/A' }}</td></tr>
    </table>
    @endif

    <!-- 13. Salary Breakdown -->
    @if($employee->salaryBreakdown)
    <div class="section-title">Salary Breakdown</div>
    <table class="info-grid">
        @php
            $s = $employee->salaryBreakdown;
            $cur = $employee->currency ?? '';
            $totB = array_sum([$s->house_allowance ?? 0, $s->transport_allowance ?? 0, $s->food_allowance ?? 0, $s->medical_allowance ?? 0, $s->other_earnings ?? 0]);
        @endphp
        <tr><td class="label">Basic Salary</td><td class="value">{{ number_format($s->basic_salary ?? 0, 2) }} {{ $cur }}</td><td class="label">House Allow</td><td class="value">{{ number_format($s->house_allowance ?? 0, 2) }} {{ $cur }}</td></tr>
        <tr><td class="label">Transport</td><td class="value">{{ number_format($s->transport_allowance ?? 0, 2) }} {{ $cur }}</td><td class="label">Food Allow</td><td class="value">{{ number_format($s->food_allowance ?? 0, 2) }} {{ $cur }}</td></tr>
        <tr><td class="label">Medical</td><td class="value">{{ number_format($s->medical_allowance ?? 0, 2) }} {{ $cur }}</td><td class="label">Other Earnings</td><td class="value">{{ number_format($s->other_earnings ?? 0, 2) }} {{ $cur }}</td></tr>
        <tr><td class="label" style="color: #007bff;">Total Benefits</td><td class="value" style="color: #007bff;">{{ number_format($totB, 2) }} {{ $cur }}</td><td class="label" style="color: #000;">Gross Salary</td><td class="value" style="font-weight: bold; color: #000;">{{ number_format($s->gross_salary ?? 0, 2) }} {{ $cur }}</td></tr>
    </table>
    @endif

    <!-- 14. Policies & Assigned Plans -->
    <div class="section-title">Active Policies & Assigned Plans</div>
    <div class="address-box">
        @php $elig = $employee->employeeEligibility; @endphp
        @if($elig)
            <p style="margin-bottom: 5px;"><strong>Active Policies:</strong>
            @if($elig->shift_plan_status === 'active') <span class="badge">Shift</span> @endif
            @if($elig->leave_plan_status === 'active') <span class="badge">Leave</span> @endif
            @if($elig->ot_plan_status === 'active') <span class="badge">OT</span> @endif
            @if($elig->roster_plans_status === 'active') <span class="badge">Roster</span> @endif
            @if($elig->bonus_plan_status === 'active') <span class="badge">Bonus</span> @endif
            @if($elig->meal_plan_status === 'active') <span class="badge">Meal</span> @endif
            </p>
        @endif
        @if($employee->shift->count() > 0 || $employee->roster->where('status', 'active')->count() > 0)
            <p><strong>Assigned Plans:</strong>
            @foreach($employee->shift as $s) <span class="badge">{{ $s->getPlan?->name }} (Shift)</span> @endforeach
            @foreach($employee->roster->where('status', 'active') as $r) <span class="badge">{{ $r->getPlan?->name }} (Roster)</span> @endforeach
            </p>
        @endif
    </div>

    <!-- 15. Leave Summary -->
    <div class="section-title">Leave Summary</div>
    <table class="info-grid" style="border: none;">
        <tr>
            <td style="width: 48%; border: none;">
                <div class="sub-header">Balances</div>
                <table class="info-grid">
                    @forelse($employee->leaveBalances as $l)
                    <tr><td class="label" style="width: 70%;">{{ $l->leave_type }}</td><td class="value" style="width: 30%; text-align: right;">{{ $l->leave_count }} / {{ $l->total_leave }}</td></tr>
                    @empty
                    <tr><td colspan="2">No balances found</td></tr>
                    @endforelse
                </table>
            </td>
            <td style="width: 4%; border: none;"></td>
            <td style="width: 48%; border: none;">
                <div class="sub-header">Recent History</div>
                <table class="info-grid">
                    @forelse($employee->leaveApplications->take(5) as $l)
                    <tr><td class="label" style="width: 60%;">{{ $l->getPlan?->name ?? 'Leave' }}</td><td class="value" style="width: 40%; text-align: right;">{{ strtoupper($l->status) }} ({{ $l->leave_count }}d)</td></tr>
                    @empty
                    <tr><td colspan="2">No recent history</td></tr>
                    @endforelse
                </table>
            </td>
        </tr>
    </table>

    <div class="footer">Generated on {{ date('d M, Y H:i:s') }} | {{ $companyInfo->name }} HRMS | Page 1</div>
</body>
</html>
