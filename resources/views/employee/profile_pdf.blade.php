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
            font-size: 11px;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 40px;
            background-color: #fff;
        }
        .header {
            display: flex;
            align-items: flex-start;
            border-bottom: 2px solid #974063;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-info {
            flex: 1;
        }
        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #974063;
            margin: 0 0 5px 0;
        }
        .company-details {
            font-size: 10px;
            color: #666;
            margin: 0;
        }
        .profile-photo {
            width: 100px;
            height: 100px;
            border: 3px solid #f0f0f0;
            border-radius: 8px;
            object-fit: cover;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #fff;
            background-color: #974063;
            padding: 5px 10px;
            margin: 20px 0 10px 0;
            border-radius: 4px;
            text-transform: uppercase;
        }
        .info-grid {
            width: 100%;
            border-collapse: collapse;
        }
        .info-grid td {
            padding: 6px 0;
            border-bottom: 1px solid #f9f9f9;
            vertical-align: top;
        }
        .label {
            font-weight: bold;
            color: #555;
            width: 30%;
        }
        .value {
            color: #222;
            width: 70%;
        }
        .address-box {
            background-color: #fcfcfc;
            padding: 10px;
            border: 1px solid #eee;
            border-radius: 4px;
            margin-top: 5px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }
        .col-6 {
            width: 50%;
            padding: 0 10px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <div class="header" style="display: table; width: 100%;">
        <div style="display: table-cell; vertical-align: top; width: 70%;">
            <h1 class="company-name">{{ $companyInfo->name }}</h1>
            <p class="company-details">{{ $companyInfo->address }}</p>
            <p class="company-details">Phone: {{ $companyInfo->phone }} | Email: {{ $companyInfo->email }}</p>
            <h2 style="margin-top: 15px; color: #444; font-size: 16px;">EMPLOYEE DETAILED PROFILE</h2>
        </div>
        <div style="display: table-cell; vertical-align: top; text-align: right; width: 30%;">
            @if($employee->photo_path)
                <img src="{{ public_path('storage/' . $employee->photo_path) }}" class="profile-photo" alt="Photo">
            @else
                <div style="width: 100px; height: 100px; background-color: #eee; border-radius: 8px; display: inline-block; line-height: 100px; text-align: center; color: #999;">NO PHOTO</div>
            @endif
        </div>
    </div>

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
            <td class="label">Mother's Name</td>
            <td class="value">{{ $employee->mother_name }}</td>
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
        <tr>
            <td class="label">Height</td>
            <td class="value">{{ $employee->height_feet ? $employee->height_feet . " ft " . $employee->height_inches . " in" : 'N/A' }}</td>
            <td class="label">Children</td>
            <td class="value">{{ $employee->children_count }}</td>
        </tr>
    </table>

    <!-- Contact Information -->
    <div class="section-title">Contact Information</div>
    <table class="info-grid">
        <tr>
            <td class="label">Personal Mobile</td>
            <td class="value">{{ $employee->personal_mobile }}</td>
            <td class="label">Personal Email</td>
            <td class="value">{{ $employee->personal_email ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Work Mobile</td>
            <td class="value">{{ $employee->work_mobile ?? 'N/A' }}</td>
            <td class="label">Work Email</td>
            <td class="value">{{ $employee->work_email ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Home Phone</td>
            <td class="value" colspan="3">{{ $employee->home_phone ?? 'N/A' }}</td>
        </tr>
    </table>

    <!-- Office Information -->
    @if($officeInfo)
    <div class="section-title">Office Information</div>
    <table class="info-grid">
        <tr>
            <td class="label">Designation</td>
            <td class="value">{{ $officeInfo->getCurrentDesignation?->name ?? 'N/A' }}</td>
            <td class="label">Department</td>
            <td class="value">{{ $officeInfo->getCurrentDepartment?->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Division</td>
            <td class="value">{{ $officeInfo->getCurrentDivision?->name ?? 'N/A' }}</td>
            <td class="label">Section</td>
            <td class="value">{{ $officeInfo->getCurrentSection?->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Date of Joining</td>
            <td class="value">{{ $officeInfo->date_of_join ? \Carbon\Carbon::parse($officeInfo->date_of_join)->format('d M, Y') : 'N/A' }}</td>
            <td class="label">Employment Type</td>
            <td class="value">{{ $officeInfo->emp_type ?? 'N/A' }}</td>
        </tr>
    </table>
    @endif

    <!-- Document Information -->
    <div class="section-title">Document Information</div>
    <table class="info-grid">
        <tr>
            <td class="label">TIN</td>
            <td class="value">{{ $employee->tin ?? 'N/A' }}</td>
            <td class="label">Passport No</td>
            <td class="value">{{ $employee->passport_no ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Passport Expiry</td>
            <td class="value">{{ $employee->passport_expiry ? \Carbon\Carbon::parse($employee->passport_expiry)->format('d M, Y') : 'N/A' }}</td>
            <td class="label">NID / Residency ID</td>
            <td class="value">{{ $employee->residency_id_number ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">License No</td>
            <td class="value">{{ $employee->license_no ?? 'N/A' }}</td>
            <td class="label">License Expiry</td>
            <td class="value">{{ $employee->license_expiry ? \Carbon\Carbon::parse($employee->license_expiry)->format('d M, Y') : 'N/A' }}</td>
        </tr>
    </table>

    <!-- Address Information -->
    <div style="display: table; width: 100%; margin-top: 20px;">
        <div style="display: table-cell; width: 50%; padding-right: 10px;">
            <div class="section-title">Present Address</div>
            <div class="address-box">
                @php $present = (object) $employee->present_address; @endphp
                {{ $present->address_line ?? '' }}<br>
                {{ $present->village ?? '' }}, {{ $present->post_office ?? '' }}<br>
                {{ $present->thana ?? '' }}, {{ $present->district ?? '' }}<br>
                {{ $present->state ?? '' }} - {{ $present->zip_code ?? '' }}<br>
                {{ $present->country ?? '' }}
            </div>
        </div>
        <div style="display: table-cell; width: 50%; padding-left: 10px;">
            <div class="section-title">Permanent Address</div>
            <div class="address-box">
                @if($employee->permanent_address)
                    @php $permanent = (object) $employee->permanent_address; @endphp
                    {{ $permanent->address_line ?? '' }}<br>
                    {{ $permanent->village ?? '' }}, {{ $permanent->post_office ?? '' }}<br>
                    {{ $permanent->thana ?? '' }}, {{ $permanent->district ?? '' }}<br>
                    {{ $permanent->state ?? '' }} - {{ $permanent->zip_code ?? '' }}<br>
                    {{ $permanent->country ?? '' }}
                @else
                    Same as Present Address
                @endif
            </div>
        </div>
    </div>

    <div class="footer">
        Generated on {{ date('d M, Y H:i:s') }} | {{ $companyInfo->name }} HRMS
    </div>
</body>
</html>
