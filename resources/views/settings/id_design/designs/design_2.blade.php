@php
    // Use dummy data if no employee data is provided
    $dummyEmployee = (object) [
        'employee_id' => 'EMP-2026-001',
        'name' => 'John Michael Doe',
        'designation' => 'Senior Software Engineer',
        'department' => 'Information Technology',
        'join_date' => '15 Jan 2024',
        'blood_group' => 'O+',
        'emergency_contact' => '+880-1712-345678',
        'emergency_contact_name' => 'Jane Doe (Spouse)',
        'email' => 'john.doe@gen-itech.com',
        'photo' => null,
    ];

    $emp = $employee ?? $dummyEmployee;

    // Prepare QR code text for employee
    $qrText = 'Employee: ' . ($emp->name ?? 'Employee Name') . "\n";
    $qrText .= 'ID: ' . ($emp->employee_id ?? 'N/A') . "\n";
    $qrText .= 'Designation: ' . ($emp->designation ?? 'N/A') . "\n";
    $qrText .= 'Department: ' . ($emp->department ?? 'N/A') . "\n";
    $qrText .= 'Join Date: ' . ($emp->join_date ?? 'N/A') . "\n";
    $qrText .= 'Blood Group: ' . ($emp->blood_group ?? 'N/A') . "\n";
    $qrText .= 'Emergency: ' . ($emp->emergency_contact ?? 'N/A') . "\n";
    $qrText .= 'Valid Until: ' . date('d M Y', strtotime('+2 years'));

    // Get company logo path from settings for QR code
    $qrCodeService = app(\App\Services\QrCodeService::class);
    $companyLogoPath = $qrCodeService->getSystemLogoPath();

    // Generate QR code with company logo
    $qrCodeBase64 = '';
    try {
        $qrCodeBase64 = $qrCodeService->generateQRBase64($qrText, $companyLogoPath, 300, 5);
    } catch (\Exception $e) {
        try {
            $qrCodeBase64 = $qrCodeService->generateQRBase64($qrText, '', 300, 5);
        } catch (\Exception $e2) {
            $qrCodeBase64 = '';
        }
    }

    // Dummy setting data
    $dummySetting = (object) [
        'company_name' => 'GEN-ITECH Solutions Ltd.',
        'website' => 'www.gen-itech.com',
        'contact_phone' => '+880-123-456-7890',
        'logo_light' => null,
    ];

    $set = $setting ?? $dummySetting;
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

        .qr-section {
            text-align: center;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.8mm;
        }

        .qr-label {
            font-size: 4.5pt;
            color: #666;
            font-weight: bold;
        }

        #qrcode {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.6mm;
            background: white;
            border: 0.5mm solid #0052cc;
            border-radius: 2mm;
            width: 23mm;
            height: 23mm;
            overflow: hidden;
            flex-shrink: 0;
        }

        #qrcode img {
            display: block !important;
            width: 100% !important;
            height: 100% !important;
            object-fit: contain !important;
            image-rendering: crisp-edges !important;
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

            #qrcode,
            #qrcode img {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                image-rendering: crisp-edges !important;
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
                <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect fill='%230052cc' width='100' height='100' rx='10'/%3E%3Ctext x='50' y='60' font-size='35' fill='white' text-anchor='middle' font-family='Arial' font-weight='bold'%3EGT%3C/text%3E%3C/svg%3E"
                    alt="Company Logo" class="logo">
                <div class="header-text">
                    <div class="card-title">Employee ID Card</div>
                    <div class="company-name">{{ $set->company_name }}</div>
                </div>
            </div>

            <div class="card-body">
                <div class="employee-photo-container">
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 120'%3E%3Crect fill='%23e2e8f0' width='100' height='120'/%3E%3Cpath d='M50 45c8 0 14-6 14-14s-6-14-14-14-14 6-14 14 6 14 14 14zm0 5c-10 0-30 5-30 15v8h60v-8c0-10-20-15-30-15z' fill='%23a0aec0' transform='translate(0 10)'/%3E%3C/svg%3E"
                        alt="Employee Photo" class="employee-photo" id="employeePhoto">
                </div>

                <div class="employee-details">
                    <div class="employee-name" id="employeeName">{{ $emp->name }}</div>
                    <div class="employee-id" id="employeeId">ID: {{ $emp->employee_id }}</div>
                    <div class="designation-badge" id="employeeDesignation">{{ $emp->designation }}</div>

                    <div class="info-grid">
                        <div class="info-row">
                            <span class="info-label">Department:</span>
                            <span class="info-value" id="employeeDept">{{ $emp->department }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Join Date:</span>
                            <span class="info-value" id="employeeJoinDate">{{ $emp->join_date }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Blood Group:</span>
                            <span class="info-value" id="employeeBloodGroup">{{ $emp->blood_group }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Email:</span>
                            <span class="info-value" id="employeeEmail"
                                style="font-size: 3.5pt;">{{ $emp->email ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Phone:</span>
                            <span class="info-value" id="employeePhone">{{ $set->contact_phone }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <p>{{ $set->company_name }}</p>
            </div>
        </div>

        <!-- BACK CARD (PORTRAIT/VERTICAL) -->
        <div class="card-face card-back">
            <div class="card-back-content">
                <div class="back-header">
                    <h2>Employee ID</h2>
                </div>

                <div class="qr-section">
                    <div class="qr-label">Scan to Verify</div>
                    <div id="qrcode">
                        @if ($qrCodeBase64)
                            <img src="{{ $qrCodeBase64 }}" alt="Employee QR Code">
                        @else
                            <div style="font-size: 8px; text-align: center; color: #999;">QR Code<br>Unavailable</div>
                        @endif
                    </div>
                </div>

                <div class="emergency-section">
                    <h3>Emergency Contact</h3>
                    <div class="emergency-info">
                        <div class="emergency-row">
                            <span class="emergency-label">Name:</span>
                            <span class="emergency-value" id="emergencyName">{{ $emp->emergency_contact_name }}</span>
                        </div>
                        <div class="emergency-row">
                            <span class="emergency-label">Phone:</span>
                            <span class="emergency-value" id="emergencyPhone">{{ $emp->emergency_contact }}</span>
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
                    <p><strong>{{ $set->company_name }}</strong></p>
                    <p class="contact-info">{{ $set->website }} | {{ $set->contact_phone }}</p>
                    <p>Issued: {{ $issueDate }}</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const employeeData = {
            employee: {
                id: "{{ $emp->employee_id }}",
                name: "{{ $emp->name }}",
                designation: "{{ $emp->designation }}",
                department: "{{ $emp->department }}",
                join_date: "{{ $emp->join_date }}",
                blood_group: "{{ $emp->blood_group }}",
                photo: "{{ $emp->photo ?? '' }}",
                emergency_contact_name: "{{ $emp->emergency_contact_name }}",
                emergency_contact_phone: "{{ $emp->emergency_contact }}"
            },
            validity: {
                issue_date: "{{ $issueDate }}",
                expiry_date: "{{ $expiryDate }}"
            },
            company: {
                name: "{{ $set->company_name }}",
                website: "{{ $set->website }}",
                contact: "{{ $set->contact_phone }}"
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
