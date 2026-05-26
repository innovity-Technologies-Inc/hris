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
        .address-box { background-color: #fcfcfc; padding: 6px; border: 1px solid #eee; border-radius: 2px; width: 100%; }
        .footer { margin-top: 15px; text-align: center; font-size: 6px; color: #999; border-top: 1px solid #eee; padding-top: 4px; }
        .badge { display: inline-block; padding: 1px 3px; background-color: #f0f0f0; border: 1px solid #ddd; border-radius: 2px; margin-right: 2px; font-size: 6px; }
        .badge-success { background-color: #d4edda; color: #155724; border-color: #c3e6cb; }
        .badge-danger { background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; }
        .entry-card { border: 1px solid #eee; background-color: #f9f9f9; padding: 5px; margin-bottom: 4px; border-radius: 2px; width: 100%; }
        .sub-header { font-weight: bold; color: #444; border-bottom: 1px solid #eee; padding-bottom: 2px; margin-top: 5px; font-size: 8px; text-transform: uppercase; }
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

    <div class="section-title">Birth & Identification Documents</div>
    <table class="info-grid">
        <tr><td class="label">Date of Birth</td><td class="value">{{ $employee->date_of_birth }}</td><td class="label">Birth Country</td><td class="value">{{ $employee->birth_country ?? 'N/A' }}</td></tr>
        <tr><td class="label">Birth Reg No</td><td class="value">{{ $employee->birth_reg_no ?? 'N/A' }}</td><td class="label">TIN Number</td><td class="value">{{ $employee->tin ?? 'N/A' }}</td></tr>
        <tr><td class="label">BGMEA ID</td><td class="value">{{ $employee->bgmea_id ?? 'N/A' }}</td><td class="label">NID / Residency ID</td><td class="value">{{ $employee->residency_id_number ?? 'N/A' }}</td></tr>
        <tr><td class="label">Passport No</td><td class="value">{{ $employee->passport_no ?? 'N/A' }}</td><td class="label">Passport Exp</td><td class="value">{{ $employee->passport_expiry ?? 'N/A' }}</td></tr>
        <tr><td class="label">Visa Expiry</td><td class="value">{{ $employee->visa_expiry ?? 'N/A' }}</td><td class="label">Work Permit Exp</td><td class="value">{{ $employee->work_expiry ?? 'N/A' }}</td></tr>
        <tr><td class="label">License No</td><td class="value">{{ $employee->license_no ?? 'N/A' }}</td><td class="label">License Exp</td><td class="value">{{ $employee->license_expiry ?? 'N/A' }}</td></tr>
    </table>

    <div class="section-title">Contact Information</div>
    <table class="info-grid">
        <tr><td class="label">Personal Mobile</td><td class="value">{{ $employee->personal_mobile }}</td><td class="label">Home Phone</td><td class="value">{{ $employee->home_phone ?? 'N/A' }}</td></tr>
        <tr><td class="label">Personal Email</td><td class="value">{{ $employee->personal_email ?? 'N/A' }}</td><td class="label">Work Mobile</td><td class="value">{{ $employee->work_mobile ?? 'N/A' }}</td></tr>
        <tr><td class="label">Work Phone</td><td class="value">{{ $employee->work_phone ?? 'N/A' }}</td><td class="label">Work Email</td><td class="value">{{ $employee->work_email ?? 'N/A' }}</td></tr>
    </table>

    <div class="section-title">Present Address</div>
    <div class="address-box">
        @php $present = (object) $employee->present_address; @endphp
        <table class="info-grid">
            <tr><td class="label">Line 1</td><td class="value" colspan="3">{{ $present->line_1 ?? $present->address_line ?? 'N/A' }}</td></tr>
            <tr><td class="label">Village</td><td class="value">{{ $present->village ?? 'N/A' }}</td><td class="label">PO</td><td class="value">{{ $present->post_office ?? 'N/A' }}</td></tr>
            <tr><td class="label">Thana</td><td class="value">{{ $present->thana ?? 'N/A' }}</td><td class="label">District</td><td class="value">{{ $present->district ?? 'N/A' }}</td></tr>
            <tr><td class="label">Division</td><td class="value">{{ $present->division ?? 'N/A' }}</td><td class="label">Country</td><td class="value">{{ $present->country ?? 'N/A' }}</td></tr>
        </table>
    </div>

    <div class="section-title">Permanent Address</div>
    <div class="address-box">
        @php $perm = (object) ($employee->permanent_address ?? $employee->present_address); @endphp
        <table class="info-grid">
            <tr><td class="label">Line 1</td><td class="value" colspan="3">{{ $perm->line_1 ?? $perm->address_line ?? 'N/A' }}</td></tr>
            <tr><td class="label">Village</td><td class="value">{{ $perm->village ?? 'N/A' }}</td><td class="label">PO</td><td class="value">{{ $perm->post_office ?? 'N/A' }}</td></tr>
            <tr><td class="label">Thana</td><td class="value">{{ $perm->thana ?? 'N/A' }}</td><td class="label">District</td><td class="value">{{ $perm->district ?? 'N/A' }}</td></tr>
            <tr><td class="label">Division</td><td class="value">{{ $perm->division ?? 'N/A' }}</td><td class="label">Country</td><td class="value">{{ $perm->country ?? 'N/A' }}</td></tr>
        </table>
    </div>

    <div class="section-title">Reference / Emergency Contact</div>
    <div class="address-box">
        @if($employee->reference_address)
            @php $ref = (object) $employee->reference_address; @endphp
            <table class="info-grid">
                <tr><td class="label">Name</td><td class="value">{{ $ref->reference_name ?? 'N/A' }}</td><td class="label">Ref ID</td><td class="value">{{ $ref->emp_id ?? 'N/A' }}</td></tr>
                <tr><td class="label">Designation</td><td class="value">{{ $ref->reference_designation ?? 'N/A' }}</td><td class="label">Contact</td><td class="value">{{ $ref->mobile ?? $ref->phone ?? 'N/A' }}</td></tr>
                <tr><td class="label">Email</td><td class="value" colspan="3">{{ $ref->email ?? 'N/A' }}</td></tr>
            </table>
        @else
            N/A
        @endif
    </div>

    @if($officeInfo)
    <div class="section-title">Office Information</div>
    <div class="sub-header">Payroll & Identification</div>
    <table class="info-grid">
        <tr><td class="label">Employee Type</td><td class="value">{{ $officeInfo->emp_type ?? 'N/A' }}</td><td class="label">HR File No</td><td class="value">{{ $officeInfo->hr_file_no ?? 'N/A' }}</td></tr>
        <tr><td class="label">Pay Grade</td><td class="value">{{ $officeInfo->getGrade?->name ?? 'N/A' }}</td><td class="label">Act / Tofsil</td><td class="value">{{ $officeInfo->getTofsil?->name ?? 'N/A' }}</td></tr>
        <tr><td class="label">File Note</td><td class="value" colspan="3">{{ $officeInfo->file_note ?? 'N/A' }}</td></tr>
    </table>
    <div class="sub-header">Joining Details</div>
    <table class="info-grid">
        <tr><td class="label">Company</td><td class="value">{{ $officeInfo->getJoiningCompany?->name ?? 'N/A' }}</td><td class="label">Business Unit</td><td class="value">{{ $officeInfo->getJoiningBusinessUnit?->name ?? 'N/A' }}</td></tr>
        <tr><td class="label">Division</td><td class="value">{{ $officeInfo->getJoiningDivision?->name ?? 'N/A' }}</td><td class="label">Department</td><td class="value">{{ $officeInfo->getJoiningDepartment?->department_name ?? 'N/A' }}</td></tr>
        <tr><td class="label">Section</td><td class="value">{{ $officeInfo->getJoiningSection?->name ?? 'N/A' }}</td><td class="label">Designation</td><td class="value">{{ $officeInfo->getJoiningDesignation?->company_designation ?? 'N/A' }}</td></tr>
        <tr><td class="label">Date of Join</td><td class="value">{{ $officeInfo->date_of_join ?? 'N/A' }}</td><td></td><td></td></tr>
    </table>
    <div class="sub-header">Orientation & Progression</div>
    <table class="info-grid">
        <tr><td class="label">Orientation</td><td class="value">{{ ucfirst($officeInfo->orientation_required ?? 'no') }}</td><td class="label">Period</td><td class="value">{{ $officeInfo->orientation_from ?? 'N/A' }} to {{ $officeInfo->orientation_to ?? 'N/A' }}</td></tr>
        <tr><td class="label">Confirmation</td><td class="value">{{ $officeInfo->confirmation_date ?? 'N/A' }}</td><td class="label">Probation</td><td class="value">{{ ($officeInfo->probation_duration ?? 0) . ' Days' }}</td></tr>
        <tr><td class="label">Next Promotion</td><td class="value">{{ $officeInfo->next_promotion_date ?? 'N/A' }}</td><td class="label">Promo Cycle</td><td class="value">{{ $officeInfo->promotion_cycle ?? 'N/A' }}</td></tr>
    </table>
    <div style="margin-top: 3px;">
        <span class="badge {{ $officeInfo->ot_allowed == 'yes' ? 'badge-success' : 'badge-danger' }}">OT: {{ strtoupper($officeInfo->ot_allowed ?? 'no') }}</span>
        <span class="badge {{ $officeInfo->pf_eligible == 'yes' ? 'badge-success' : 'badge-danger' }}">PF: {{ strtoupper($officeInfo->pf_eligible ?? 'no') }}</span>
        <span class="badge {{ $officeInfo->transport_eligible == 'yes' ? 'badge-success' : 'badge-danger' }}">TRANS: {{ strtoupper($officeInfo->transport_eligible ?? 'no') }}</span>
        <span class="badge {{ $officeInfo->gratuity_eligible == 'yes' ? 'badge-success' : 'badge-danger' }}">GRAT: {{ strtoupper($officeInfo->gratuity_eligible ?? 'no') }}</span>
    </div>
    @endif

    @if($employee->educationInfo && count($employee->educationInfo->educations ?? []) > 0)
    <div class="section-title">Education Records</div>
    @foreach($employee->educationInfo->educations as $edu)
        <div class="entry-card">
            <table class="info-grid">
                <tr><td class="label">Title</td><td class="value">{{ $edu['education_title'] ?? 'N/A' }}</td><td class="label">Institute</td><td class="value">{{ $edu['institute'] ?? 'N/A' }}</td></tr>
                <tr><td class="label">Year</td><td class="value">{{ $edu['passing_year'] ?? 'N/A' }}</td><td class="label">Result</td><td class="value">{{ $edu['result_grade'] ?? 'N/A' }}</td></tr>
            </table>
        </div>
    @endforeach
    @endif

    @if($employee->employmentHistory && count($employee->employmentHistory->histories ?? []) > 0)
    <div class="section-title">Employment History</div>
    @foreach($employee->employmentHistory->histories as $history)
        <div class="entry-card">
            <table class="info-grid">
                <tr><td class="label">Company</td><td class="value">{{ $history['company_name'] ?? $history['company'] ?? 'N/A' }}</td><td class="label">Designation</td><td class="value">{{ $history['designation'] ?? 'N/A' }}</td></tr>
                <tr><td class="label">Period</td><td class="value" colspan="3">{{ $history['joining_date'] ?? $history['from_date'] ?? 'N/A' }} to {{ $history['end_date'] ?? $history['to_date'] ?? 'Present' }}</td></tr>
            </table>
        </div>
    @endforeach
    @endif

    @if($employee->nomineeInfo)
    <div class="section-title">Nominee Information</div>
    <div class="address-box">
        @php $n = $employee->nomineeInfo; @endphp
        <table class="info-grid">
            <tr><td class="label">Name</td><td class="value">{{ $n->nominee_name }}</td><td class="label">Relation</td><td class="value">{{ $n->relation }}</td></tr>
            <tr><td class="label">Mobile</td><td class="value">{{ $n->nominee_mobile }}</td><td class="label">ID/NID</td><td class="value">{{ $n->nid ?? 'N/A' }}</td></tr>
        </table>
    </div>
    @endif

    @if($employee->bankAccount)
    <div class="section-title">Bank Details</div>
    <table class="info-grid">
        @php $ba = $employee->bankAccount; @endphp
        <tr><td class="label">Bank</td><td class="value">{{ $ba->getBank?->name }}</td><td class="label">Branch</td><td class="value">{{ $ba->getBranch?->name }}</td></tr>
        <tr><td class="label">Holder</td><td class="value">{{ $ba->account_holder_name }}</td><td class="label">Account</td><td class="value">{{ $ba->account_number }}</td></tr>
    </table>
    @endif

    @if($employee->salaryBreakdown)
    <div class="section-title">Salary Breakdown</div>
    <table class="info-grid">
        @php $s = $employee->salaryBreakdown; $cur = $employee->currency ?? ''; @endphp
        <tr><td class="label">Basic Salary</td><td class="value">{{ number_format($s->basic_salary ?? 0, 2) }} {{ $cur }}</td><td class="label">House Allow</td><td class="value">{{ number_format($s->house_allowance ?? 0, 2) }} {{ $cur }}</td></tr>
        <tr><td class="label">Gross Salary</td><td class="value fw-bold" colspan="3">{{ number_format($s->gross_salary ?? 0, 2) }} {{ $cur }}</td></tr>
    </table>
    @endif

    <div class="footer">Generated on {{ date('d M, Y H:i:s') }} | {{ $companyInfo->name }} HRMS | Page 1</div>
</body>
</html>
