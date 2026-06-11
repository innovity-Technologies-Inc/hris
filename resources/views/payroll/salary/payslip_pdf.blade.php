<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip - {{ $employee->full_name }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #444;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .company-logo {
            max-width: 150px;
            margin-bottom: 5px;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
        }
        .company-address {
            font-size: 11px;
            margin: 2px 0;
        }
        .payslip-title {
            font-size: 16px;
            font-weight: bold;
            text-decoration: underline;
            margin-top: 10px;
        }
        .info-section {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 5px;
            vertical-align: top;
        }
        .label {
            font-weight: bold;
            width: 30%;
        }
        .value {
            width: 70%;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .data-table th, .data-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .data-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .totals-section {
            width: 100%;
            margin-top: 10px;
        }
        .total-row {
            font-weight: bold;
            background-color: #eee;
        }
        .footer {
            margin-top: 50px;
            width: 100%;
        }
        .signature-box {
            width: 45%;
            display: inline-block;
            text-align: center;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 40px;
            padding-top: 5px;
        }
        .amount-in-words {
            margin-top: 20px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @if($officeInfo?->getCurrentCompany?->logo)
                <img src="{{ public_path('storage/' . $officeInfo->getCurrentCompany->logo) }}" class="company-logo" alt="Logo">
            @endif
            <h1 class="company-name">{{ $officeInfo?->getCurrentCompany?->name ?? 'N/A' }}</h1>
            <p class="company-address">{{ $officeInfo?->getCurrentCompany?->address ?? '' }}</p>
            <p class="company-address">Phone: {{ $officeInfo?->getCurrentCompany?->telephone ?? '' }} | Email: {{ $officeInfo?->getCurrentCompany?->email ?? '' }}</p>
            <div class="payslip-title">PAYSLIP FOR THE MONTH OF {{ \Carbon\Carbon::parse($payroll->getBatch->salary_month)->format('F, Y') }}</div>
        </div>

        <div class="info-section">
            <table class="info-table">
                <tr>
                    <td style="width: 50%;">
                        <table class="info-table">
                            <tr>
                                <td class="label">Employee ID:</td>
                                <td class="value">{{ $employee->applicant_id }}</td>
                            </tr>
                            <tr>
                                <td class="label">Employee Name:</td>
                                <td class="value">{{ $employee->full_name }}</td>
                            </tr>
                            <tr>
                                <td class="label">Designation:</td>
                                <td class="value">{{ $officeInfo?->getCurrentDesignation?->company_designation ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 50%;">
                        <table class="info-table">
                            <tr>
                                <td class="label">Department:</td>
                                <td class="value">{{ $officeInfo?->getCurrentDepartment?->department_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="label">Batch ID:</td>
                                <td class="value">{{ $payroll->batch_id }}</td>
                            </tr>
                            <tr>
                                <td class="label">Salary Date:</td>
                                <td class="value">{{ \Carbon\Carbon::parse($payroll->created_at)->format('d M, Y') }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 50%;">Earnings</th>
                    <th style="width: 50%;" class="text-right">Amount (BDT)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Gross Salary</td>
                    <td class="text-right">{{ number_format($payroll->salary, 2) }}</td>
                </tr>
                <tr>
                    <td>Overtime ({{ $payroll->overtime_count }} hrs)</td>
                    <td class="text-right">{{ number_format($payroll->overtime_amount, 2) }}</td>
                </tr>
                <tr>
                    <td>Off-day Work ({{ $payroll->offday_work_count }} days)</td>
                    <td class="text-right">{{ number_format($payroll->offday_work_salary, 2) }}</td>
                </tr>
                <tr>
                    <td>Bonus</td>
                    <td class="text-right">{{ number_format($payroll->bonus_amount, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td>Total Earnings (A)</td>
                    <td class="text-right">{{ number_format($payroll->salary + $payroll->overtime_amount + $payroll->offday_work_salary + $payroll->bonus_amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 50%;">Deductions</th>
                    <th style="width: 50%;" class="text-right">Amount (BDT)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Total Deductions (Late, Absent, etc.)</td>
                    <td class="text-right">{{ number_format($payroll->deduction_amount, 2) }}</td>
                </tr>
                <tr>
                    <td>Penalty Deductions</td>
                    <td class="text-right">{{ number_format($payroll->penalty_amount, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td>Total Deductions (B)</td>
                    <td class="text-right">{{ number_format($payroll->deduction_amount + $payroll->penalty_amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <table class="data-table">
            <tr class="total-row" style="font-size: 14px; background-color: #ddd;">
                <td style="width: 50%;">NET PAYABLE (A - B)</td>
                <td style="width: 50%;" class="text-right">৳ {{ number_format($payroll->total_salary, 2) }}</td>
            </tr>
        </table>

        <div class="amount-in-words">
            <strong>Amount in words:</strong> Taka {{ App\HelperClass::numberToWords($payroll->total_salary) }} Only
        </div>

        <div class="footer">
            <table style="width: 100%; border: none;">
                <tr>
                    <td style="width: 30%; text-align: center; vertical-align: bottom;">
                        <div style="border-top: 1px solid #333; padding-top: 5px; margin-top: 40px;">Employee Signature</div>
                    </td>
                    <td style="width: 5%;"></td>
                    <td style="width: 30%; text-align: center; vertical-align: bottom;">
                        <div style="border-top: 1px solid #333; padding-top: 5px; margin-top: 40px;">Accounts Signature</div>
                    </td>
                    <td style="width: 5%;"></td>
                    <td style="width: 30%; text-align: center; vertical-align: bottom;">
                        <div style="border-top: 1px solid #333; padding-top: 5px; margin-top: 40px;">Authorized Signatory</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>

