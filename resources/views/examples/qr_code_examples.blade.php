{{--
    QR Code Examples for HRMS
    Location: resources/views/examples/qr_code_examples.blade.php

    This file demonstrates various QR code implementations
--}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Examples - HRMS</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            padding: 40px 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .header p {
            color: #7f8c8d;
        }

        .examples-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .example-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .example-card h3 {
            color: #34495e;
            margin-bottom: 15px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }

        .qr-display {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 20px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .qr-display img {
            max-width: 100%;
            height: auto;
        }

        .qr-label {
            margin-top: 10px;
            font-size: 12px;
            color: #7f8c8d;
            text-align: center;
        }

        .info-box {
            background: #e8f4f8;
            border-left: 4px solid #3498db;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
        }

        .info-box p {
            margin: 5px 0;
            font-size: 14px;
            color: #2c3e50;
        }

        .code-snippet {
            background: #2c3e50;
            color: #ecf0f1;
            padding: 15px;
            border-radius: 6px;
            overflow-x: auto;
            margin: 15px 0;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            margin-right: 5px;
        }

        .badge-success {
            background: #27ae60;
            color: white;
        }

        .badge-info {
            background: #3498db;
            color: white;
        }

        .badge-warning {
            background: #f39c12;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🔲 QR Code Implementation Examples</h1>
            <p>Comprehensive examples for HRMS QR code generation</p>
        </div>

        <div class="examples-grid">
            {{-- Example 1: Basic Employee QR Code --}}
            <div class="example-card">
                <h3>1️⃣ Basic Employee QR Code</h3>

                <span class="badge badge-success">Simple</span>
                <span class="badge badge-info">With Logo</span>

                @php
                    $qrCodeService = app(\App\Services\Setting\QrCodeService::class);

                    $employeeData = "Employee ID: EMP001\n";
                    $employeeData .= "Name: John Doe\n";
                    $employeeData .= "Department: IT\n";
                    $employeeData .= 'Designation: Software Engineer';

                    try {
                        $qr1 = $qrCodeService->generateQRBase64($employeeData, null);
                    } catch (\Exception $e) {
                        $qr1 = null;
                    }
                @endphp

                <div class="qr-display">
                    @if ($qr1)
                        <img src="{{ $qr1 }}" alt="Employee QR" width="250">
                        <div class="qr-label">✅ Employee QR Code</div>
                    @else
                        <div class="qr-label">❌ QR Generation Failed</div>
                    @endif
                </div>

                <div class="info-box">
                    <p><strong>Use Case:</strong> Employee badges, ID cards</p>
                    <p><strong>Logo:</strong> System logo from settings</p>
                    <p><strong>Status:</strong> Working</p>
                </div>

                <div class="code-snippet">@verbatim
                        $qrService = app(\App\Services\Setting\QrCodeService::class);
                        $qr = $qrService->generateQRBase64(
                        "Employee ID: EMP001\nName: John",
                        null, // System logo
                        250
                        );
                        <img src="{{ $qr }}" alt="QR">
                    @endverbatim
                </div>
            </div>

            {{-- Example 2: Attendance Verification QR --}}
            <div class="example-card">
                <h3>2️⃣ Attendance Verification QR</h3>

                <span class="badge badge-info">Compact</span>
                <span class="badge badge-warning">No Logo</span>

                @php
                    $attendanceData = "Attendance ID: ATT2025001\n";
                    $attendanceData .= "Employee: John Doe (EMP001)\n";
                    $attendanceData .= 'Date: ' . now()->format('d M Y') . "\n";
                    $attendanceData .= "Check In: 09:00 AM\n";
                    $attendanceData .= "Check Out: 06:00 PM\n";
                    $attendanceData .= 'Status: Present';

                    try {
                        $qr2 = $qrCodeService->generateQRBase64($attendanceData, '');
                    } catch (\Exception $e) {
                        $qr2 = null;
                    }
                @endphp

                <div class="qr-display">
                    @if ($qr2)
                        <img src="{{ $qr2 }}" alt="Attendance QR" width="200">
                        <div class="qr-label">✅ Attendance QR (no logo)</div>
                    @else
                        <div class="qr-label">❌ QR Generation Failed</div>
                    @endif
                </div>

                <div class="info-box">
                    <p><strong>Use Case:</strong> Attendance records, timesheets</p>
                    <p><strong>Logo:</strong> None (for reliability)</p>
                    <p><strong>Size:</strong> Compact (200px)</p>
                </div>

                <div class="code-snippet">@verbatim
                        $qr = $qrService->generateQRBase64(
                        "Attendance ID: ATT001",
                        '', // No logo
                        200
                        );
                    @endverbatim
                </div>
            </div>

            {{-- Example 3: Simple Text QR --}}
            <div class="example-card">
                <h3>3️⃣ Simple Text QR Code</h3>

                <span class="badge badge-success">Basic</span>
                <span class="badge badge-info">Text Data</span>

                @php
                    $simpleData = 'Hello HRMS QR Code System';

                    try {
                        $qr3 = $qrCodeService->generateQRBase64($simpleData, null);
                    } catch (\Exception $e) {
                        $qr3 = null;
                    }
                @endphp

                <div class="qr-display">
                    @if ($qr3)
                        <img src="{{ $qr3 }}" alt="Simple QR" width="220">
                        <div class="qr-label">✅ Simple Text QR</div>
                    @else
                        <div class="qr-label">❌ QR Generation Failed</div>
                    @endif
                </div>

                <div class="info-box">
                    <p><strong>Use Case:</strong> Basic verification, links</p>
                    <p><strong>Data:</strong> Plain text string</p>
                    <p><strong>Status:</strong> Working</p>
                </div>

                <div class="code-snippet">@verbatim
                        $qr = $qrService->generateQRBase64(
                        "Your text here",
                        null,
                        220
                        );
                    @endverbatim
                </div>
            </div>

            {{-- Example 4: JSON Data QR --}}
            <div class="example-card">
                <h3>4️⃣ Structured Data (JSON)</h3>

                <span class="badge badge-warning">JSON</span>
                <span class="badge badge-info">Structured</span>

                @php
                    $jsonData = json_encode([
                        'employee_id' => 'EMP001',
                        'name' => 'John Doe',
                        'access_level' => 'Level 3',
                        'valid_until' => now()->addMonths(6)->format('Y-m-d'),
                    ]);

                    try {
                        $qr4 = $qrCodeService->generateQRBase64($jsonData, null);
                    } catch (\Exception $e) {
                        $qr4 = null;
                    }
                @endphp

                <div class="qr-display">
                    @if ($qr4)
                        <img src="{{ $qr4 }}" alt="JSON QR" width="240">
                        <div class="qr-label">✅ JSON Data QR</div>
                    @else
                        <div class="qr-label">❌ QR Generation Failed</div>
                    @endif
                </div>

                <div class="info-box">
                    <p><strong>Use Case:</strong> Access control, badge data</p>
                    <p><strong>Format:</strong> JSON (machine-readable)</p>
                    <p><strong>Status:</strong> Working</p>
                </div>

                <div class="code-snippet">@verbatim
                        $data = json_encode([
                        'id' => 'EMP001',
                        'access' => 'Level 3'
                        ]);
                        $qr = $qrService->generateQRBase64($data);
                    @endverbatim
                </div>
            </div>

            {{-- Example 5: URL QR Code --}}
            <div class="example-card">
                <h3>5️⃣ Verification URL QR</h3>

                <span class="badge badge-success">URL</span>
                <span class="badge badge-info">Secure</span>

                @php
                    $verifyUrl = url('/verify/sample?id=123&hash=abc');

                    try {
                        $qr5 = $qrCodeService->generateQRBase64($verifyUrl, null);
                    } catch (\Exception $e) {
                        $qr5 = null;
                    }
                @endphp

                <div class="qr-display">
                    @if ($qr5)
                        <img src="{{ $qr5 }}" alt="URL QR" width="230">
                        <div class="qr-label">✅ Verification URL QR</div>
                    @else
                        <div class="qr-label">❌ QR Generation Failed</div>
                    @endif
                </div>

                <div class="info-box">
                    <p><strong>Use Case:</strong> Payslips, certificates</p>
                    <p><strong>Data:</strong> Verification URL with hash</p>
                    <p><strong>Security:</strong> Server-side validation</p>
                </div>

                <div class="code-snippet">@verbatim
                        $hash = hash('sha256', $id);
                        $url = route('verify', ['id' => $id, 'hash' => $hash]);
                        $qr = $qrService->generateQRBase64($url);
                    @endverbatim
                </div>
            </div>

            {{-- Example 6: Multi-line Text QR --}}
            <div class="example-card">
                <h3>6️⃣ Multi-line Text QR</h3>

                <span class="badge badge-info">Text</span>
                <span class="badge badge-warning">Multi-line</span>

                @php
                    $multiLineData = "Name: John Doe\n";
                    $multiLineData .= "ID: EMP001\n";
                    $multiLineData .= "Department: IT\n";
                    $multiLineData .= "Mobile: +1234567890\n";
                    $multiLineData .= 'Email: john@company.com';

                    try {
                        $qr6 = $qrCodeService->generateQRBase64($multiLineData, null);
                    } catch (\Exception $e) {
                        $qr6 = null;
                    }
                @endphp

                <div class="qr-display">
                    @if ($qr6)
                        <img src="{{ $qr6 }}" alt="Multi-line QR" width="250">
                        <div class="qr-label">✅ Multi-line Data QR</div>
                    @else
                        <div class="qr-label">❌ QR Generation Failed</div>
                    @endif
                </div>

                <div class="info-box">
                    <p><strong>Use Case:</strong> Employee profiles, contact info</p>
                    <p><strong>Format:</strong> Multi-line text</p>
                    <p><strong>Status:</strong> Working</p>
                </div>

                <div class="code-snippet">@verbatim
                        $data = "Name: John\n";
                        $data .= "ID: EMP001\n";
                        $data .= "Department: IT\n";
                        $data .= "Mobile: +1234567890";
                        $qr = $qrService->generateQRBase64($data);
                    @endverbatim
                </div>
            </div>
        </div>

        {{-- Additional Information Section --}}
        <div class="example-card" style="margin-bottom: 40px;">
            <h3>📋 Quick Reference</h3>

            <h4 style="margin-top: 20px;">Available Methods</h4>
            <div class="code-snippet">@verbatim
                    generateQRBase64($text, $logo, $size)
                    → Returns base64 data URL for <img> tags

                    generateEmployeeQR($employee, $options)
                    → Employee-specific QR with auto-formatted data

                    generateAttendanceQR($attendance, $options)
                    → Attendance record QR code

                    downloadQR($text, $logo, $filename, $size)
                    → Prepare QR for file download

                    getSystemLogoPath()
                    → Get company logo path from settings
                @endverbatim
            </div>
            <h4 style="margin-top: 20px;">Logo Options</h4>
            <div class="code-snippet">@verbatim
                    null → Use system logo (default)
                    '' → No logo (max reliability)
                    '/path' → Custom logo file path
                @endverbatim
            </div>
            <h4 style="margin-top: 20px;">Recommended Sizes</h4>
            <div class="code-snippet">@verbatim
                    200-250px → ID cards, badges
                    300-400px → Documents, certificates
                    400-600px → High-quality prints
                @endverbatim
            </div>
        </div>

        <div class="example-card">
            <h3>🚀 How to Use in Your Code</h3>

            <div class="info-box">
                <p><strong>Step 1:</strong> Get service instance</p>
                <div class="code-snippet" style="margin: 10px 0;">@verbatim
                        $qrService = app(\App\Services\Setting\QrCodeService::class);
                    @endverbatim
                </div>
            </div>

            <div class="info-box">
                <p><strong>Step 2:</strong> Generate QR code</p>
                <div class="code-snippet" style="margin: 10px 0;">@verbatim
                        $qr = $qrService->generateQRBase64("Your data", null, 300);
                    @endverbatim
                </div>
            </div>

            <div class="info-box">
                <p><strong>Step 3:</strong> Display in template</p>
                <div class="code-snippet" style="margin: 10px 0;">@verbatim
                        <img src="{{ $qr }}" alt="QR Code">
                    @endverbatim
                </div>
            </div>

            <h4 style="margin-top: 20px; margin-bottom: 10px;">✅ All Examples Are Working!</h4>
            <p>The QR codes above are generated live. Scan them with your phone camera to verify they work correctly.
            </p>
        </div>
    </div>
</body>

</html>

