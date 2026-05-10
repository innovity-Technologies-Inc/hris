<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Certificate - {{ $employee->full_name }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 14px;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 50px;
        }
        .company-logo {
            max-width: 180px;
            margin-bottom: 10px;
        }
        .company-name {
            font-size: 22px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            color: #1a5a96;
        }
        .company-details {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
        .certificate-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            text-decoration: underline;
            margin: 40px 0;
            text-transform: uppercase;
        }
        .content {
            text-align: justify;
            margin-bottom: 30px;
        }
        .content p {
            margin-bottom: 15px;
        }
        .salary-table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        .salary-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .salary-table td.label {
            font-weight: bold;
            background-color: #f9f9f9;
            width: 60%;
        }
        .salary-table td.value {
            text-align: right;
            width: 40%;
        }
        .footer {
            margin-top: 80px;
        }
        .signature-section {
            width: 100%;
        }
        .signature-box {
            width: 300px;
            text-align: center;
        }
        .signature-line {
            border-top: 2px solid #333;
            margin-top: 50px;
            padding-top: 10px;
            font-weight: bold;
        }
        .date-section {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        @if($officeInfo->getCurrentCompany->logo)
            <img src="{{ public_path('storage/' . $officeInfo->getCurrentCompany->logo) }}" class="company-logo" alt="Logo">
        @endif
        <h1 class="company-name">{{ $officeInfo->getCurrentCompany->name }}</h1>
        <div class="company-details">
            {{ $officeInfo->getCurrentCompany->address }}<br>
            Phone: {{ $officeInfo->getCurrentCompany->telephone }} | Email: {{ $officeInfo->getCurrentCompany->email }}
        </div>
    </div>

    <div class="date-section">
        <strong>Date:</strong> {{ date('d F, Y') }}
    </div>

    <div class="certificate-title">TO WHOM IT MAY CONCERN</div>

    <div class="content">
        <p>
            This is to certify that <strong>{{ $employee->full_name }}</strong>, son/daughter of <strong>{{ $employee->father_name ?? 'N/A' }}</strong>, 
            is a permanent employee of <strong>{{ $officeInfo->getCurrentCompany->name }}</strong>. 
            He/She joined the company on <strong>{{ \Carbon\Carbon::parse($officeInfo->date_of_join)->format('d F, Y') }}</strong> 
            and is currently serving as <strong>{{ $officeInfo->getCurrentDesignation->company_designation ?? 'N/A' }}</strong> 
            in the <strong>{{ $officeInfo->getCurrentDepartment->department_name ?? 'N/A' }}</strong> department.
        </p>
        <p>
            As per our records, his/her current monthly gross salary and other benefits are as follows:
        </p>
    </div>

    <table class="salary-table">
        <tr>
            <td class="label">Gross Salary</td>
            <td class="value">৳ {{ number_format($payroll->salary, 2) }}</td>
        </tr>
        <tr>
            <td class="label">Overtime Allowance (Avg/Monthly)</td>
            <td class="value">৳ {{ number_format($payroll->overtime_amount, 2) }}</td>
        </tr>
        <tr>
            <td class="label">Other Allowances</td>
            <td class="value">৳ {{ number_format($payroll->offday_work_salary + $payroll->bonus_amount, 2) }}</td>
        </tr>
        <tr style="font-weight: bold; background-color: #eee;">
            <td class="label">Total Monthly Remuneration</td>
            <td class="value">৳ {{ number_format($payroll->salary + $payroll->overtime_amount + $payroll->offday_work_salary + $payroll->bonus_amount, 2) }}</td>
        </tr>
    </table>

    <div class="content">
        <p>
            In words: <strong>Taka {{ App\HelperClass::numberToWords($payroll->salary + $payroll->overtime_amount + $payroll->offday_work_salary + $payroll->bonus_amount) }} Only.</strong>
        </p>
        <p>
            During his/her tenure with us, we found him/her to be hardworking, honest, and dedicated to his/her duties. 
            We wish him/her every success in his/her future endeavors.
        </p>
    </div>

    <div class="footer">
        <div class="signature-box">
            <div class="signature-line">
                Authorized Signatory<br>
                <small>{{ $officeInfo->getCurrentCompany->name }}</small>
            </div>
        </div>
    </div>
</body>
</html>
