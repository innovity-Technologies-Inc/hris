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
            font-size: 8px;
            color: #333;
            line-height: 1.3;
            margin: 0;
            padding: 20px;
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
        }
        .info-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        .info-grid td {
            padding: 2px 0;
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
        }
        .address-box {
            background-color: #fcfcfc;
            padding: 6px;
            border: 1px solid #eee;
            border-radius: 2px;
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
        }
        .row-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }
        .row-table td {
            vertical-align: top;
            padding: 0 5px;
        }
        hr {
            border: 0;
            border-top: 1px solid #eee;
            margin: 4px 0;
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
        <table class="row-table">
            <tr>
                <td colspan="4"><strong>Address Line:</strong> {{ $present->line_1 ?? $present->address_line ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Village:</strong> {{ $present->village ?? 'N/A' }}</td>
                <td><strong>PO:</strong> {{ $present->post_office ?? 'N/A' }}</td>
                <td><strong>Thana:</strong> {{ $present->thana ?? 'N/A' }}</td>
                <td><strong>District:</strong> {{ $present->district ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Division:</strong> {{ $present->division ?? 'N/A' }}</td>
                <td><strong>State:</strong> {{ $present->state ?? 'N/A' }}</td>
                <td><strong>Zip:</strong> {{ $present->zip_code ?? 'N/A' }}</td>
                <td><strong>Country:</strong> {{ $present->country ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <!-- 5. Permanent Address -->
    <div class="section-title">Permanent Address</div>
    <div class="address-box">
        @php 
            $perm = (object) ($employee->permanent_address ?? $employee->present_address); 
            if (empty($employee->permanent_address)) $perm = (object) $employee->present_address;
        @endphp
        <table class="row-table">
            <tr>
                <td colspan="4"><strong>Address Line:</strong> {{ $perm->line_1 ?? $perm->address_line ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Village:</strong> {{ $perm->village ?? 'N/A' }}</td>
                <td><strong>PO:</strong> {{ $perm->post_office ?? 'N/A' }}</td>
                <td><strong>Thana:</strong> {{ $perm->thana ?? 'N/A' }}</td>
                <td><strong>District:</strong> {{ $perm->district ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Division:</strong> {{ $perm->division ?? 'N/A' }}</td>
                <td><strong>State:</strong> {{ $perm->state ?? 'N/A' }}</td>
                <td><strong>Zip:</strong> {{ $perm->zip_code ?? 'N/A' }}</td>
                <td><strong>Country:</strong> {{ $perm->country ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <!-- 6. Reference Information -->
    <div class="section-title">Reference / Emergency Contact</div>
    <div class="address-box">
        @if($employee->reference_address)
            @php $ref = (object) $employee->reference_address; @endphp
            <table class="row-table">
                <tr>
                    <td><strong>Name:</strong> {{ $ref->reference_name ?? 'N/A' }}</td>
                    <td><strong>ID:</strong> {{ $ref->emp_id ?? 'N/A' }}</td>
                    <td><strong>Designation:</strong> {{ $ref->reference_designation ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Mobile:</strong> {{ $ref->mobile ?? 'N/A' }}</td>
                    <td><strong>Phone:</strong> {{ $ref->phone ?? 'N/A' }}</td>
                    <td><strong>Email:</strong> {{ $ref->email ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="padding-top: 3px;">
                        <strong>Address:</strong> {{ $ref->line_1 ?? $ref->address_line ?? '' }}, {{ $ref->village ?? '' }}, {{ $ref->thana ?? '' }}, {{ $ref->district ?? '' }}, {{ $ref->state ?? '' }}, {{ $ref->country ?? '' }}
                    </td>
                </tr>
            </table>
        @else
            N/A
        @endif
    </div>

    <!-- 7. Office Information -->
    @if($officeInfo)
    <div class="section-title">Office Information</div>
    <table class="info-grid">
        <tr>
            <td class="label">Employee Type</td><td class="value">{{ $officeInfo->emp_type ?? 'N/A' }}</td>
            <td class="label">HR File No</td><td class="value">{{ $officeInfo->hr_file_no ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Pay Grade</td><td class="value">{{ $officeInfo->getGrade?->name ?? 'N/A' }}</td>
            <td class="label">Act / Tofsil</td><td class="value">{{ $officeInfo->getTofsil?->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">File Note</td><td colspan="3" class="value">{{ $officeInfo->file_note ?? 'N/A' }}</td>
        </tr>
    </table>
    
    <div style="margin: 5px 0; font-weight: bold; border-bottom: 1px solid #eee;">JOINING DETAILS</div>
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
        <tr>
            <td class="label">Date of Join</td><td class="value">{{ $officeInfo->date_of_join ?? 'N/A' }}</td>
            <td></td><td></td>
        </tr>
    </table>

    <div style="margin: 5px 0; font-weight: bold; border-bottom: 1px solid #eee;">CURRENT POSITION</div>
    <table class="info-grid">
        <tr>
            <td class="label">Current Company</td><td class="value">{{ $officeInfo->getCurrentCompany?->name ?? 'N/A' }}</td>
            <td class="label">Current B.U.</td><td class="value">{{ $officeInfo->getCurrentBusinessUnit?->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Current Division</td><td class="value">{{ $officeInfo->getCurrentDivision?->name ?? 'N/A' }}</td>
            <td class="label">Current Dept.</td><td class="value">{{ $officeInfo->getCurrentDepartment?->department_name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Current Section</td><td class="value">{{ $officeInfo->getCurrentSection?->name ?? 'N/A' }}</td>
            <td class="label">Current Desig.</td><td class="value">{{ $officeInfo->getCurrentDesignation?->company_designation ?? 'N/A' }}</td>
        </tr>
    </table>

    <div style="margin: 5px 0; font-weight: bold; border-bottom: 1px solid #eee;">ORIENTATION & CYCLES</div>
    <table class="info-grid">
        <tr>
            <td class="label">Orient. Required</td><td class="value">{{ ucfirst($officeInfo->orientation_required ?? 'no') }}</td>
            <td class="label">Orient. Period</td><td class="value">{{ $officeInfo->orientation_from ?? 'N/A' }} to {{ $officeInfo->orientation_to ?? 'N/A' }} ({{ $officeInfo->orientation_days ?? 0 }} days)</td>
        </tr>
        <tr>
            <td class="label">Confirmation Date</td><td class="value">{{ $officeInfo->confirmation_date ?? 'N/A' }}</td>
            <td class="label">Probation</td><td class="value">{{ ($officeInfo->probation_duration ?? 0) . ' Days' }}</td>
        </tr>
        <tr>
            <td class="label">Promotion Cycle</td><td class="value">{{ $officeInfo->promotion_cycle ?? 'N/A' }}</td>
            <td class="label">Increment Cycle</td><td class="value">{{ $officeInfo->increment_cycle ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Next Promotion</td><td class="value">{{ $officeInfo->next_promotion_date ?? 'N/A' }}</td>
            <td></td><td></td>
        </tr>
    </table>

    <div style="margin: 5px 0; font-weight: bold; border-bottom: 1px solid #eee;">BENEFITS & SCHEDULE</div>
    <table class="info-grid">
        <tr>
            <td class="label">Salary Type</td><td class="value">{{ $officeInfo->salary_type ?? 'N/A' }}</td>
            <td class="label">Weekends</td><td class="value">{{ implode(', ', (array)($officeInfo->weekends ?? [])) }}</td>
        </tr>
        <tr>
            <td class="label">Alternate Off</td><td class="value">{{ implode(', ', (array)($officeInfo->alternate_off_day ?? [])) }}</td>
            <td></td><td></td>
        </tr>
    </table>
    <div style="margin-top: 3px;">
        <span class="badge {{ $officeInfo->ot_allowed == 'yes' ? 'badge-success' : 'badge-danger' }}">OT: {{ strtoupper($officeInfo->ot_allowed ?? 'no') }}</span>
        <span class="badge {{ $officeInfo->pf_eligible == 'yes' ? 'badge-success' : 'badge-danger' }}">PF: {{ strtoupper($officeInfo->pf_eligible ?? 'no') }}</span>
        <span class="badge {{ $officeInfo->transport_eligible == 'yes' ? 'badge-success' : 'badge-danger' }}">Transport: {{ strtoupper($officeInfo->transport_eligible ?? 'no') }}</span>
        <span class="badge {{ $officeInfo->gratuity_eligible == 'yes' ? 'badge-success' : 'badge-danger' }}">Gratuity: {{ strtoupper($officeInfo->gratuity_eligible ?? 'no') }}</span>
        <span class="badge {{ $officeInfo->can_apply_loan == 'yes' ? 'badge-success' : 'badge-danger' }}">Loan: {{ strtoupper($officeInfo->can_apply_loan ?? 'no') }}</span>
        <span class="badge {{ $officeInfo->can_apply_advance == 'yes' ? 'badge-success' : 'badge-danger' }}">Advance: {{ strtoupper($officeInfo->can_apply_advance ?? 'no') }}</span>
    </div>
    @endif

    <!-- 8. Education Information -->
    @if($employee->educationInfo && count($employee->educationInfo->educations ?? []) > 0)
    <div class="section-title">Education Information</div>
    @foreach($employee->educationInfo->educations as $edu)
        <div class="entry-card">
            <table class="row-table">
                <tr>
                    <td style="width: 35%;"><strong>Title:</strong> {{ $edu['education_title'] ?? 'N/A' }}</td>
                    <td style="width: 35%;"><strong>Institute:</strong> {{ $edu['institute'] ?? 'N/A' }}</td>
                    <td style="width: 30%;"><strong>Board:</strong> {{ $edu['board_university'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Major:</strong> {{ $edu['group_major'] ?? 'N/A' }}</td>
                    <td><strong>Year:</strong> {{ $edu['passing_year'] ?? 'N/A' }}</td>
                    <td><strong>Result:</strong> {{ $edu['result_grade'] ?? 'N/A' }} {{ isset($edu['gpa_cgpa']) ? '('.$edu['gpa_cgpa'].')' : '' }}</td>
                </tr>
            </table>
        </div>
    @endforeach
    @endif

    <!-- 9. Training Information -->
    @if($employee->educationInfo && count($employee->educationInfo->trainings ?? []) > 0)
    <div class="section-title">Training Information</div>
    @foreach($employee->educationInfo->trainings as $trn)
        <div class="entry-card">
            <table class="row-table">
                <tr>
                    <td style="width: 50%;"><strong>Title:</strong> {{ $trn['training_title'] ?? 'N/A' }}</td>
                    <td style="width: 50%;"><strong>Course:</strong> {{ $trn['course_name'] ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td><strong>Institute:</strong> {{ $trn['institute'] ?? 'N/A' }} ({{ $trn['location'] ?? 'N/A' }}, {{ $trn['country'] ?? 'N/A' }})</td>
                    <td><strong>Duration:</strong> {{ $trn['duration'] ?? 'N/A' }} | <strong>Dates:</strong> {{ $trn['from_date'] ?? '' }} to {{ $trn['to_date'] ?? '' }}</td>
                </tr>
            </table>
        </div>
    @endforeach
    @endif

    <!-- 10. Employment History -->
    @if($employee->employmentHistory && count($employee->employmentHistory->histories ?? []) > 0)
    <div class="section-title">Employment History</div>
    @foreach($employee->employmentHistory->histories as $history)
        <div class="entry-card">
            <table class="row-table">
                <tr>
                    <td style="width: 40%;"><strong>Company:</strong> {{ $history['company_name'] ?? $history['company'] ?? 'N/A' }}</td>
                    <td style="width: 30%;"><strong>Designation:</strong> {{ $history['designation'] ?? 'N/A' }}</td>
                    <td style="width: 30%;"><strong>Period:</strong> {{ $history['joining_date'] ?? $history['from_date'] ?? 'N/A' }} to {{ $history['end_date'] ?? $history['to_date'] ?? 'Present' }}</td>
                </tr>
                @if(!empty($history['job_description']))
                <tr><td colspan="3" style="padding-top: 3px;"><strong>Description:</strong> <span style="font-size: 7px; color: #555;">{{ $history['job_description'] }}</span></td></tr>
                @endif
                @if(!empty($history['achievements']))
                <tr><td colspan="3" style="padding-top: 2px;"><strong>Achievements:</strong> <span style="font-size: 7px; color: #555;">{{ $history['achievements'] }}</span></td></tr>
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
        <table class="row-table">
            <tr>
                <td><strong>Name:</strong> {{ $n->nominee_name }}</td>
                <td><strong>Relation:</strong> {{ $n->relation }}</td>
                <td><strong>Mobile:</strong> {{ $n->nominee_mobile }}</td>
                <td><strong>Phone:</strong> {{ $n->phone ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Gender:</strong> {{ $n->gender }}</td>
                <td><strong>DOB:</strong> {{ $n->date_of_birth }}</td>
                <td><strong>ID/NID:</strong> {{ $n->nid ?? 'N/A' }}</td>
                <td><strong>Birth Reg:</strong> {{ $n->birth_reg_no ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td><strong>Religion:</strong> {{ $n->religion ?? 'N/A' }}</td>
                <td><strong>Nationality:</strong> {{ $n->nationality ?? 'N/A' }}</td>
                <td><strong>Marital:</strong> {{ $n->marital_status ?? 'N/A' }}</td>
                <td><strong>Blood Group:</strong> {{ $n->blood_group ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td colspan="4" style="padding-top: 3px;">
                    <strong>Address:</strong> {{ $n->present_address_line ?? 'N/A' }}, {{ $n->village ?? '' }}, {{ $n->post_office ?? '' }}, {{ $n->thana ?? '' }}, {{ $n->district ?? '' }}, {{ $n->state ?? '' }}, {{ $n->zip_code ?? '' }}, {{ $n->country ?? '' }}
                </td>
            </tr>
        </table>
    </div>
    @endif

    <!-- 12. Bank Account Details -->
    @if($employee->bankAccount)
    <div class="section-title">Bank Account Details</div>
    <div class="address-box">
        @php $ba = $employee->bankAccount; @endphp
        <table class="row-table">
            <tr>
                <td><strong>Bank:</strong> {{ $ba->getBank?->name }}</td>
                <td><strong>Branch:</strong> {{ $ba->getBranch?->name }}</td>
                <td><strong>Holder:</strong> {{ $ba->account_holder_name }}</td>
                <td><strong>Account:</strong> {{ $ba->account_number }}</td>
            </tr>
            <tr>
                <td><strong>Status:</strong> {{ $ba->status }}</td>
                <td colspan="3"><strong>Remarks:</strong> {{ $ba->remarks ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>
    @endif

    <!-- 13. Salary Breakdown -->
    @if($employee->salaryBreakdown)
    <div class="section-title">Salary Breakdown</div>
    <table class="info-grid">
        @php $s = $employee->salaryBreakdown; $cur = $s->currency ?? ''; @endphp
        <tr>
            <td class="label">Basic Salary</td><td class="value">{{ $s->basic_salary }} {{ $cur }}</td>
            <td class="label">House Allowance</td><td class="value">{{ $s->house_allowance ?? 0 }} {{ $cur }}</td>
        </tr>
        <tr>
            <td class="label">Transport Allow.</td><td class="value">{{ $s->transport_allowance ?? 0 }} {{ $cur }}</td>
            <td class="label">Food Allowance</td><td class="value">{{ $s->food_allowance ?? 0 }} {{ $cur }}</td>
        </tr>
        <tr>
            <td class="label">Medical Allow.</td><td class="value">{{ $s->medical_allowance ?? 0 }} {{ $cur }}</td>
            <td class="label">Other Earnings</td><td class="value">{{ $s->other_earnings ?? 0 }} {{ $cur }}</td>
        </tr>
        <tr>
            <td class="label" style="font-size: 8px; color: #000;">Gross Salary</td><td class="value" style="font-size: 9px; font-weight: bold;">{{ $s->gross_salary }} {{ $cur }}</td>
            <td></td><td></td>
        </tr>
    </table>
    @endif

    <!-- 14. Policies & Assigned Plans -->
    <div style="page-break-inside: avoid;">
        <table class="row-table" style="margin-top: 10px;">
            <tr>
                <td style="width: 50%;">
                    <div class="section-title" style="margin-top: 0;">Active Policies</div>
                    <div class="address-box">
                        @php $elig = $employee->employeeEligibility; @endphp
                        @if($elig)
                            @if($elig->shift_plan_status === 'active') <span class="badge">Shift Plan</span> @endif
                            @if($elig->leave_plan_status === 'active') <span class="badge">Leave Plan</span> @endif
                            @if($elig->ot_plan_status === 'active') <span class="badge">OT Allowed</span> @endif
                            @if($elig->roster_plans_status === 'active') <span class="badge">Roster Plan</span> @endif
                            @if($elig->bonus_plan_status === 'active') <span class="badge">Bonus Eligible</span> @endif
                            @if($elig->meal_plan_status === 'active') <span class="badge">Meal Plan</span> @endif
                        @else
                            No active policies
                        @endif
                    </div>
                </td>
                <td style="width: 50%;">
                    <div class="section-title" style="margin-top: 0;">Assigned Plans</div>
                    <div class="address-box">
                        @forelse($employee->shift as $s) <div class="badge">{{ $s->name }} (Shift)</div> @empty @endforelse
                        @forelse($employee->roster as $r) @if($r->status === 'active') <div class="badge">{{ $r->name }} (Roster)</div> @endif @empty @endforelse
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- 15. Leave Summary -->
    @if($employee->leaveBalances->count() > 0 || $employee->leaveApplications->count() > 0)
    <div style="page-break-inside: avoid;">
        <table class="row-table" style="margin-top: 10px;">
            <tr>
                <td style="width: 45%;">
                    <div class="section-title" style="margin-top: 0;">Leave Balance</div>
                    <table class="info-grid">
                        @foreach($employee->leaveBalances as $l)
                        <tr><td class="label" style="width: 70%;">{{ $l->leave_type }}</td><td class="value" style="width: 30%; text-align: right;">{{ $l->leave_count }} / {{ $l->total_leave }}</td></tr>
                        @endforeach
                    </table>
                </td>
                <td style="width: 55%;">
                    <div class="section-title" style="margin-top: 0;">Recent Leave History</div>
                    <table class="info-grid">
                        @foreach($employee->leaveApplications->take(10) as $l)
                        <tr><td class="label" style="width: 75%;">{{ $l->getPlan?->name }} ({{ $l->status }})</td><td class="value" style="width: 25%; text-align: right;">{{ $l->leave_count }}d</td></tr>
                        @endforeach
                    </table>
                </td>
            </tr>
        </table>
    </div>
    @endif

    <div class="footer">
        Generated on {{ date('d M, Y H:i:s') }} | {{ $companyInfo->name }} HRMS | Page 1
    </div>
</body>
</html>
