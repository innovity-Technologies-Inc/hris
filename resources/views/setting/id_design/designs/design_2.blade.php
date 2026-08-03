@php
    // Get actual employee data or use dummy for preview
    if (!isset($employee)) {
        // Dummy data for preview only
        $employee = (object) [
            'id' => null,
            'system_id' => 'EMP-PREVIEW',
            'full_name' => 'John Michael Doe',
            'personal_mobile' => '+880-1712-345678',
            'work_mobile' => null,
            'work_email' => 'john.doe@company.com',
            'personal_email' => 'john.doe@gmail.com',
            'blood_group' => 'O+',
            'photo_path' => null,
        ];
    }

    // Get employee's office information for current company and designation
$currentCompany = null;
$currentDesignation = null;
$currentDepartment = null;
$companyLogoPath = null;
$companyName = null;
$joinDate = 'N/A';

if ($employee && isset($employee->id)) {
    $officeInfo = \App\Models\Employee\EmployeeOfficeInfo::with([
        'getCurrentCompany',
        'getCurrentDesignation',
        'getCurrentDepartment',
    ])
        ->where('employee_id', $employee->id)
        ->first();

    if ($officeInfo) {
        $currentCompany = $officeInfo->getCurrentCompany;
        $currentDesignation = $officeInfo->getCurrentDesignation;
        $currentDepartment = $officeInfo->getCurrentDepartment;
        $joinDate = $officeInfo->date_of_join ? date('d M Y', strtotime($officeInfo->date_of_join)) : 'N/A';

        // Get company-specific data
        if ($currentCompany) {
            $companyName = $currentCompany->name;
        }
    }
}

// System settings
$generalSettings = \App\Models\Setting\GeneralSetting::first();
$companyLogoPath = $generalSettings?->logo_path;

// Company information with fallbacks
$companyInfo = (object) [
    'name' => $companyName ?? ($generalSettings?->company_name ?? 'Company Name'),
    'logo' => $generalSettings?->logo ?? null,
    'website' => $generalSettings?->website ?? 'www.company.com',
    'telephone' => $currentCompany?->telephone ?? ($generalSettings?->contact_phone ?? '+000-000-000'),
    'fax' => $currentCompany?->fax ?? '',
    'email' => $currentCompany?->email ?? ($generalSettings?->email ?? 'info@company.com'),
    'address' => $currentCompany?->address ?? ($generalSettings?->address ?? 'Company Address'),
    'city' => $generalSettings?->city ?? '',
    'state' => $generalSettings?->state ?? '',
    'zip_code' => $generalSettings?->zip_code ?? '',
    'country' => $generalSettings?->country ?? '',
];

$issueDate = date('d M Y');
$expiryDate = date('d M Y', strtotime('+2 years'));
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee ID Card - CR80 Portrait (Vertical)</title>
    <style>
        /* ========================================
           GLOBAL STYLES
           ======================================== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.3;
        }

        /* ========================================
           A4 PAGE CONTAINER - PORTRAIT
           ======================================== */
        .page-container {
            width: 210mm;
            min-height: 297mm;
            background: white;
            margin: 0 auto;
            padding: 20mm;
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            justify-content: center;
            gap: 20mm;
        }

        /* ========================================
           CARD FACES - CR80 PORTRAIT (VERTICAL)
           Width: 53.98mm × Height: 85.60mm
           Like a typical student ID card
           ======================================== */
        .card-face {
            width: 53.98mm;
            height: 85.60mm;
            background: white;
            border: 1px solid #0052cc;
            border-radius: 3mm;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            page-break-inside: avoid;
        }

        /* ========================================
           FRONT CARD - VERTICAL/PORTRAIT LAYOUT
           ======================================== */
        .card-front {
            display: flex;
            flex-direction: column;
        }

        .card-header {
            background: linear-gradient(135deg, #0052cc 0%, #0066ff 100%);
            color: white;
            padding: 2mm 2mm;
            text-align: center;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1mm;
            border-bottom: 2mm solid #ffa500;
        }

        .logo {
            width: 12mm;
            height: 12mm;
            object-fit: contain;
            background: white;
            border-radius: 2mm;
            padding: 1mm;
            flex-shrink: 0;
        }

        .header-text {
            text-align: center;
        }

        .card-title {
            font-size: 7pt;
            font-weight: bold;
            letter-spacing: 0.4px;
            text-transform: uppercase;
            line-height: 1.2;
        }

        .company-name {
            font-size: 4.5pt;
            font-weight: normal;
            line-height: 1.2;
            margin-top: 0.3mm;
        }

        .card-body {
            padding: 2mm 2.5mm;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 1.5mm;
            overflow: hidden;
        }

        .employee-photo-container {
            text-align: center;
            flex-shrink: 0;
            margin-bottom: 0.5mm;
        }

        .employee-photo {
            width: 18mm;
            height: 21mm;
            border-radius: 2mm;
            border: 0.8mm solid #0052cc;
            object-fit: cover;
            background: #e2e8f0;
        }

        .employee-details {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.5mm;
            min-width: 0;
        }

        .employee-name {
            font-size: 5.5pt;
            font-weight: bold;
            color: #0052cc;
            line-height: 1.1;
            text-align: center;
            word-wrap: break-word;
            text-transform: uppercase;
        }

        .employee-id {
            font-size: 3.5pt;
            color: #666;
            line-height: 1;
            text-align: center;
            font-weight: bold;
            margin-bottom: 0.3mm;
        }

        .designation-badge {
            background: #ffa500;
            color: white;
            padding: 0.8mm 1.2mm;
            border-radius: 2.5mm;
            font-size: 4pt;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            flex-shrink: 0;
            margin-bottom: 0.5mm;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.3mm;
            flex: 1;
        }

        .info-row {
            display: flex;
            flex-direction: row;
            min-width: 0;
            padding: 0.2mm 0;
            border-bottom: 0.15mm solid #e2e8f0;
        }

        .info-label {
            font-weight: bold;
            color: #555;
            font-size: 3.5pt;
            width: 12mm;
            flex-shrink: 0;
        }

        .info-value {
            color: #333;
            font-size: 3.5pt;
            word-wrap: break-word;
            flex: 1;
            overflow: visible;
        }

        .card-footer {
            background: #0052cc;
            color: white;
            padding: 1mm 1.5mm;
            text-align: center;
            font-size: 3.8pt;
            flex-shrink: 0;
            line-height: 1.2;
            font-weight: bold;
        }

        .validity {
            color: #ffa500;
            font-weight: bold;
        }

        /* ========================================
           BACK CARD - VERTICAL/PORTRAIT LAYOUT
           ======================================== */
        .card-back {
            display: flex;
            flex-direction: column;
        }

        .card-back-content {
            padding: 2mm 2.5mm;
            display: flex;
            flex-direction: column;
            height: 100%;
            gap: 1.5mm;
            overflow: hidden;
        }

        .back-header {
            background: linear-gradient(135deg, #0052cc 0%, #0066ff 100%);
            color: white;
            padding: 1.5mm 2mm;
            text-align: center;
            border-radius: 2mm;
            flex-shrink: 0;
            border-bottom: 1mm solid #ffa500;
        }

        .back-header h2 {
            font-size: 6.5pt;
            letter-spacing: 0.3px;
            line-height: 1.2;
            text-transform: uppercase;
            margin: 0;
        }

        .emergency-section {
            background: #fff5f5;
            padding: 1.5mm;
            border-radius: 2mm;
            border: 0.4mm solid #e53e3e;
            flex-shrink: 0;
        }

        .emergency-section h3 {
            font-size: 5pt;
            color: #e53e3e;
            margin: 0 0 0.6mm 0;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            font-weight: bold;
        }

        .emergency-info {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.4mm;
        }

        .emergency-row {
            display: flex;
            flex-direction: row;
            font-size: 4pt;
            padding: 0.3mm 0;
        }

        .emergency-label {
            font-weight: bold;
            color: #555;
            width: 11mm;
            flex-shrink: 0;
        }

        .emergency-value {
            color: #333;
            flex: 1;
            word-wrap: break-word;
        }

        .terms-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .terms-section h3 {
            font-size: 5pt;
            color: #0052cc;
            margin: 0 0 0.5mm 0;
            text-transform: uppercase;
            letter-spacing: 0.2px;
            border-bottom: 0.3mm solid #0052cc;
            padding-bottom: 0.4mm;
            flex-shrink: 0;
        }

        .terms-section ul {
            list-style: none;
            padding-left: 0;
            margin: 0;
            font-size: 3.5pt;
            line-height: 1.3;
        }

        .terms-section li {
            padding: 0.25mm 0;
            padding-left: 2.5mm;
            position: relative;
        }

        .terms-section li:before {
            content: "•";
            position: absolute;
            left: 0.5mm;
            color: #0052cc;
            font-weight: bold;
        }

        .back-footer {
            background: #f7fafc;
            padding: 1mm;
            text-align: center;
            border-radius: 2mm;
            font-size: 3.5pt;
            color: #666;
            border: 0.3mm solid #e2e8f0;
            flex-shrink: 0;
            line-height: 1.3;
        }

        .back-footer p {
            margin: 0.3mm 0;
        }

        .contact-info {
            color: #0052cc;
            font-weight: bold;
        }

        .signature-line {
            margin-top: 3mm;
            padding-top: 3mm;
            border-top: 0.8mm solid #333;
            font-size: 6pt;
            font-weight: bold;
            color: #000;
            text-align: center;
        }

        /* ========================================
           PRINT STYLES - A4 PORTRAIT
           ======================================== */
        @media print {
            @page {
                size: A4 portrait;
                margin: 10mm;
            }

            html,
            body {
                width: 210mm;
                height: 297mm;
                margin: 0;
                padding: 0;
            }

            body {
                background: white;
            }

            .page-container {
                width: 210mm;
                min-height: 297mm;
                margin: 0;
                padding: 20mm;
                box-shadow: none;
            }

            .card-face {
                box-shadow: none;
                page-break-inside: avoid;
            }
        }

        /* ========================================
           SCREEN PREVIEW
           ======================================== */
        @media screen {
            .page-container {
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            }

            .card-face {
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }
        }
    </style>
</head>

<body>
    <div class="page-container">
        <!-- FRONT CARD (PORTRAIT/VERTICAL) -->
        <div class="card-face card-front">
            <div class="card-header">
                <img src="{{ url('storage/' . $companyInfo->logo) }}" alt="Company Logo" class="logo"
                    onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22%3E%3Crect fill=%220052cc%22 width=%22100%22 height=%22100%22 rx=%2210%22/%3E%3Ctext x=%2250%22 y=%2260%22 font-size=%2235%22 fill=%22white%22 text-anchor=%22middle%22 font-family=%22Arial%22 font-weight=%22bold%22%3EGT%3C/text%3E%3C/svg%3E'">
                <div class="header-text">
                    <div class="card-title">Employee ID Card</div>
                    <div class="company-name">{{ $companyInfo->name }}</div>
                </div>
            </div>

            <div class="card-body">
                <div class="employee-photo-container">
                    @if ($employee->photo_path && file_exists(public_path('storage/' . $employee->photo_path)))
                        <img src="{{ url('storage/' . $employee->photo_path) }}" alt="Employee Photo"
                            class="employee-photo" id="employeePhoto">
                    @else
                        <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 120'%3E%3Crect fill='%23e2e8f0' width='100' height='120'/%3E%3Cpath d='M50 45c8 0 14-6 14-14s-6-14-14-14-14 6-14 14 6 14 14 14zm0 5c-10 0-30 5-30 15v8h60v-8c0-10-20-15-30-15z' fill='%23a0aec0' transform='translate(0 10)'/%3E%3C/svg%3E"
                            alt="Employee Photo" class="employee-photo" id="employeePhoto">
                    @endif
                </div>

                <div class="employee-details">
                    <div class="employee-name" id="employeeName">{{ $employee->full_name }}</div>
                    <div class="employee-id" id="employeeId">ID: {{ $employee->system_id }}</div>
                    <div class="designation-badge" id="employeeDesignation">
                        {{ $currentDesignation?->company_designation ?? 'N/A' }}</div>

                    <div class="info-grid">
                        <div class="info-row">
                            <span class="info-label">Department:</span>
                            <span class="info-value"
                                id="employeeDept">{{ $currentDepartment?->department_name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Join Date:</span>
                            <span class="info-value" id="employeeJoinDate">{{ $joinDate }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Blood Group:</span>
                            <span class="info-value" id="employeeBloodGroup">{{ $employee->blood_group }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Mobile No:</span>
                            <span class="info-value"
                                id="employeePhone">{{ $employee->personal_mobile ?? ($employee->work_mobile ?? 'N/A') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Email:</span>
                            <span class="info-value" id="employeeEmail"
                                style="font-size: 3.5pt;">{{ $employee->work_email ?? ($employee->personal_email ?? 'N/A') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <p>{{ $companyInfo->name }}</p>
            </div>
        </div>

        <!-- BACK CARD (PORTRAIT/VERTICAL) -->
        <div class="card-face card-back">
            <div class="card-back-content">
                <div class="back-header">
                    <h2>Employee ID</h2>
                </div>

                <div class="emergency-section">
                    <h3>Emergency Contact</h3>
                    <div class="emergency-info">
                        <div class="emergency-row">
                            <span class="emergency-label">Name:</span>
                            <span class="emergency-value"
                                id="emergencyName">{{ $employee->spouse_name ?? ($employee->father_name ?? 'N/A') }}</span>
                        </div>
                        <div class="emergency-row">
                            <span class="emergency-label">Phone:</span>
                            <span class="emergency-value"
                                id="emergencyPhone">{{ $employee->personal_mobile ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <div class="terms-section">
                    <h3>Terms & Conditions</h3>
                    <ul>
                        <li>This card is property of the company</li>
                        <li>Must be worn visibly during work hours</li>
                        <li>Report lost or stolen cards immediately</li>
                        <li>Non-transferable and for authorized use</li>
                        <li>Return card upon termination</li>
                    </ul>
                </div>

                <div class="back-footer">
                    <p><strong>{{ $companyInfo->name }}</strong></p>
                    <p class="contact-info">{{ $companyInfo->website }} | {{ $companyInfo->telephone }}</p>
                    <p>Issued: {{ $issueDate }}</p>
                    <div class="signature-line">Authorized Signature</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const employeeData = {
            employee: {
                id: "{{ $employee->system_id }}",
                name: "{{ $employee->full_name }}",
                designation: "{{ $currentDesignation?->company_designation ?? 'N/A' }}",
                department: "{{ $currentDepartment?->department_name ?? 'N/A' }}",
                join_date: "{{ $joinDate }}",
                blood_group: "{{ $employee->blood_group }}",
                photo: "{{ $employee->photo_path ?? '' }}",
                emergency_contact_name: "{{ $employee->spouse_name ?? ($employee->father_name ?? 'N/A') }}",
                emergency_contact_phone: "{{ $employee->personal_mobile ?? 'N/A' }}"
            },
            validity: {
                issue_date: "{{ $issueDate }}",
                expiry_date: "{{ $expiryDate }}"
            },
            company: {
                name: "{{ $companyInfo->name }}",
                website: "{{ $companyInfo->website }}",
                telephone: "{{ $companyInfo->telephone }}",
                email: "{{ $companyInfo->email }}"
            }
        };

        function updateEmployeeData(newData) {
            if (newData.employee) {
                if (newData.employee.name) document.getElementById('employeeName').textContent = newData.employee.name;
                if (newData.employee.id) document.getElementById('employeeId').textContent = `ID: ${newData.employee.id}`;
                if (newData.employee.designation) document.getElementById('employeeDesignation').textContent = newData
                    .employee.designation;
                if (newData.employee.department) document.getElementById('employeeDept').textContent = newData.employee
                    .department;
                if (newData.employee.join_date) document.getElementById('employeeJoinDate').textContent = newData.employee
                    .join_date;
                if (newData.employee.blood_group) document.getElementById('employeeBloodGroup').textContent = newData
                    .employee.blood_group;
                if (newData.employee.emergency_contact_name) document.getElementById('emergencyName').textContent = newData
                    .employee.emergency_contact_name;
                if (newData.employee.emergency_contact_phone) document.getElementById('emergencyPhone').textContent =
                    newData.employee.emergency_contact_phone;
            }

            if (newData.validity && newData.validity.expiry_date) {
                document.getElementById('expiryDate').textContent = newData.validity.expiry_date;
            }
        }

        window.updateEmployeeData = updateEmployeeData;
        window.employeeData = employeeData;
    </script>
</body>

</html>

