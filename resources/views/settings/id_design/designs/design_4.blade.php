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
            border: 1mm solid #1e88e5;
            border-radius: 3mm;
            box-shadow: 0 4px 12px rgba(30, 136, 229, 0.25), 0 2px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            page-break-inside: avoid;
            position: relative;
        }

        /* ========================================
           FRONT CARD - VERTICAL/PORTRAIT LAYOUT
           ======================================== */
        .card-front {
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .card-front::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 15mm;
            background: linear-gradient(135deg, #1e88e5 0%, #1976d2 50%, #1565c0 100%);
            clip-path: polygon(0 0, 100% 0, 100% 60%, 85% 100%, 70% 60%, 55% 100%, 40% 60%, 25% 100%, 10% 60%, 0 100%);
            z-index: 0;
            opacity: 0.08;
        }

        .card-front::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 12mm;
            background: linear-gradient(135deg, #1e88e5 0%, #1976d2 50%, #1565c0 100%);
            clip-path: polygon(0 40%, 15% 0, 30% 40%, 45% 0, 60% 40%, 75% 0, 90% 40%, 100% 0, 100% 100%, 0 100%);
            z-index: 0;
            opacity: 0.08;
        }

        .card-header {
            background: white;
            padding: 3mm 2mm 2mm 2mm;
            text-align: center;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.5mm;
            position: relative;
            z-index: 2;
        }

        .logo {
            width: 15mm;
            height: 15mm;
            object-fit: contain;
            background: transparent;
            flex-shrink: 0;
        }

        .header-text {
            text-align: center;
        }

        .card-title {
            font-size: 8pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            line-height: 1.2;
            color: #1e88e5;
        }

        .company-name {
            font-size: 5.5pt;
            font-weight: 600;
            line-height: 1.2;
            margin-top: 0.5mm;
            color: #333;
        }

        .card-body {
            padding: 2mm 2.5mm 2mm 2.5mm;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 1.5mm;
            overflow: hidden;
            position: relative;
            z-index: 2;
        }

        .employee-photo-container {
            text-align: center;
            flex-shrink: 0;
            margin-bottom: 0.5mm;
        }

        .employee-photo {
            width: 22mm;
            height: 22mm;
            clip-path: polygon(30% 0%, 70% 0%, 100% 50%, 70% 100%, 30% 100%, 0% 50%);
            object-fit: cover;
            background: #e3f2fd;
            border: none;
            box-shadow: 0 0 0 1.5mm #1e88e5, 0 2mm 8mm rgba(30, 136, 229, 0.3);
        }

        .employee-details {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.5mm;
            min-width: 0;
        }

        .employee-name {
            font-size: 7.5pt;
            font-weight: bold;
            color: #1e88e5;
            line-height: 1.3;
            text-align: center;
            word-wrap: break-word;
            text-transform: none;
            letter-spacing: 0.3px;
            margin-bottom: 0.5mm;
        }

        .employee-id {
            font-size: 5.5pt;
            color: #333;
            line-height: 1.3;
            text-align: center;
            font-weight: 600;
            margin-bottom: 1mm;
        }

        .designation-badge {
            background: #e3f2fd;
            color: #1565c0;
            padding: 0.8mm 2mm;
            border-radius: 3mm;
            font-size: 5.5pt;
            font-weight: 600;
            text-align: center;
            text-transform: none;
            letter-spacing: 0.2px;
            flex-shrink: 0;
            margin-bottom: 1.5mm;
        }

        .info-grid {
            display: flex;
            flex-direction: column;
            gap: 1mm;
            flex: 1;
            padding: 0 1.5mm;
        }

        .info-row {
            display: flex;
            flex-direction: row;
            min-width: 0;
            padding: 0.5mm 0;
            border-bottom: 0.5pt solid #e3f2fd;
            align-items: center;
        }

        .info-label {
            font-weight: bold;
            color: #1e88e5;
            font-size: 5pt;
            min-width: 13mm;
            flex-shrink: 0;
        }

        .info-value {
            color: #333;
            font-size: 5pt;
            word-wrap: break-word;
            word-break: break-all;
            flex: 1;
            overflow: visible;
            line-height: 1.4;
        }

        .card-footer {
            background: white;
            color: #1e88e5;
            padding: 1.5mm 2mm 3mm 2mm;
            text-align: center;
            font-size: 5.5pt;
            flex-shrink: 0;
            line-height: 1.3;
            font-weight: 600;
            position: relative;
            z-index: 2;
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
            position: relative;
        }

        .card-back::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 15mm;
            background: linear-gradient(135deg, #1e88e5 0%, #1976d2 50%, #1565c0 100%);
            clip-path: polygon(0 0, 100% 0, 100% 60%, 85% 100%, 70% 60%, 55% 100%, 40% 60%, 25% 100%, 10% 60%, 0 100%);
            z-index: 0;
            opacity: 0.08;
        }

        .card-back::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 12mm;
            background: linear-gradient(135deg, #1e88e5 0%, #1976d2 50%, #1565c0 100%);
            clip-path: polygon(0 40%, 15% 0, 30% 40%, 45% 0, 60% 40%, 75% 0, 90% 40%, 100% 0, 100% 100%, 0 100%);
            z-index: 0;
            opacity: 0.08;
        }

        .card-back-content {
            padding: 3mm 3mm 2mm 3mm;
            display: flex;
            flex-direction: column;
            height: 100%;
            gap: 2mm;
            overflow: hidden;
            position: relative;
            z-index: 2;
        }

        .back-header {
            background: transparent;
            color: #333;
            padding: 0;
            text-align: center;
            flex-shrink: 0;
            margin-bottom: 1mm;
        }

        .back-header h2 {
            font-size: 7pt;
            letter-spacing: 0.3px;
            line-height: 1.3;
            text-transform: none;
            margin: 0;
            font-weight: bold;
            color: #333;
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
            padding: 1mm;
            background: white;
            border: 1mm solid #1e88e5;
            border-radius: 2mm;
            width: 25mm;
            height: 25mm;
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
            background: white;
            padding: 0;
            border-radius: 0;
            border: none;
            flex-shrink: 0;
        }

        .emergency-section h3 {
            font-size: 6pt;
            color: #333;
            margin: 0 0 1mm 0;
            text-transform: none;
            letter-spacing: 0;
            font-weight: bold;
            text-align: center;
        }

        .emergency-info {
            display: grid;
            grid-template-columns: 1fr;
            gap: 0.4mm;
        }

        .emergency-row {
            display: flex;
            flex-direction: row;
            font-size: 5.5pt;
            padding: 0.3mm 0;
            justify-content: center;
        }

        .emergency-label {
            font-weight: bold;
            color: #333;
            min-width: auto;
            flex-shrink: 0;
        }

        .emergency-value {
            color: #333;
            flex: 0;
            word-wrap: break-word;
            margin-left: 1mm;
        }

        .terms-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .terms-section h3 {
            font-size: 6pt;
            color: #333;
            margin: 0 0 1mm 0;
            text-transform: none;
            letter-spacing: 0;
            border-bottom: 1mm solid #333;
            padding-bottom: 0.5mm;
            flex-shrink: 0;
            text-align: center;
            font-weight: bold;
        }

        .terms-section ul {
            list-style: none;
            padding-left: 0;
            margin: 0;
            font-size: 5pt;
            line-height: 1.5;
        }

        .terms-section li {
            padding: 0.3mm 0;
            padding-left: 0;
            position: relative;
            text-align: center;
        }

        .terms-section li:before {
            content: none;
        }

        .address-line {
            font-size: 5pt;
            color: #333;
            text-align: center;
            line-height: 1.4;
            margin: 0.3mm 0;
        }

        .address-line strong {
            font-weight: bold;
            color: #333;
        }

        .back-footer {
            background: white;
            padding: 2mm 1mm 3mm 1mm;
            text-align: center;
            border-radius: 0;
            font-size: 5pt;
            color: #333;
            border: none;
            flex-shrink: 0;
            line-height: 1.4;
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            gap: 1.5mm;
        }

        .back-footer p {
            margin: 0;
        }

        .contact-info {
            color: #333;
            font-weight: normal;
        }

        .signature-line {
            margin-top: 3mm;
            padding-top: 2mm;
            border-top: 0.5mm solid #333;
            font-size: 5.5pt;
            font-weight: bold;
            color: #333;
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
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 120'%3E%3Crect fill='%23e3f2fd' width='100' height='120'/%3E%3Cpath d='M50 45c8 0 14-6 14-14s-6-14-14-14-14 6-14 14 6 14 14 14zm0 5c-10 0-30 5-30 15v8h60v-8c0-10-20-15-30-15z' fill='%231e88e5' transform='translate(0 10)'/%3E%3C/svg%3E"
                        alt="Employee Photo" class="employee-photo" id="employeePhoto">
                </div>

                <div class="employee-details">
                    <div class="employee-name" id="employeeName">{{ $emp->name }}</div>
                    <div class="designation-badge" id="employeeDesignation">{{ $emp->designation }}</div>

                    <div class="info-grid">
                        <div class="info-row">
                            <span class="info-label">ID:</span>
                            <span class="info-value" id="employeeId">{{ $emp->employee_id }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Blood Group:</span>
                            <span class="info-value" id="employeeBloodGroup">{{ $emp->blood_group }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Joining Date:</span>
                            <span class="info-value" id="employeeJoinDate">{{ $emp->join_date }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Mobile No:</span>
                            <span class="info-value" id="employeePhone">{{ $set->contact_phone }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Email:</span>
                            <span class="info-value" id="employeeEmail">{{ $emp->email ?? 'N/A' }}</span>
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
                <div class="card-header">
                    <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect fill='%231e88e5' width='100' height='100' rx='10'/%3E%3Ctext x='50' y='60' font-size='35' fill='white' text-anchor='middle' font-family='Arial' font-weight='bold'%3EGT%3C/text%3E%3C/svg%3E"
                        alt="Company Logo" class="logo">
                </div>

                <div class="back-header">
                    <h2>If found, please return to:</h2>
                </div>

                <div class="terms-section">
                    <h3>Corporate Office:</h3>
                    <div class="address-line">{{ $set->company_name }}</div>
                    <div class="address-line">Address Line 1</div>
                    <div class="address-line">Address Line 2</div>
                    <div class="address-line">City, Country</div>
                    <div class="address-line" style="margin-top: 2mm;"><strong>Email:</strong> {{ $set->website }}
                    </div>
                    <div class="address-line"><strong>Phone:</strong> {{ $set->contact_phone }}</div>
                    <div class="address-line"><strong>Web:</strong> {{ $set->website }}</div>
                </div>

                <div class="qr-section">
                    <div id="qrcode">
                        @if ($qrCodeBase64)
                            <img src="{{ $qrCodeBase64 }}" alt="Employee QR Code">
                        @else
                            <div style="font-size: 8px; text-align: center; color: #999;">QR Code<br>Unavailable</div>
                        @endif
                    </div>
                </div>

                <div class="back-footer">
                    <div class="signature-line">Authorized Signature</div>
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
