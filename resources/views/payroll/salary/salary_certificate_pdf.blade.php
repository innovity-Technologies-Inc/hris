<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Certificate - {{ $employee->full_name }}</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 13px;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 30px 50px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }
        .company-logo {
            max-height: 60px;
            width: auto;
            margin-bottom: 8px;
        }
        .company-name {
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            color: #1a5a96;
        }
        .company-details {
            font-size: 11px;
            color: #666;
            margin-top: 3px;
        }
        .certificate-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            text-decoration: underline;
            margin: 25px 0;
            text-transform: uppercase;
        }
        .content {
            text-align: justify;
            margin-bottom: 15px;
        }
        .content p {
            margin-bottom: 10px;
        }
        .salary-table {
            width: 85%;
            margin: 10px auto;
            border-collapse: collapse;
        }
        .salary-table td {
            padding: 5px 12px;
            border: 1px solid #ccc;
        }
        .salary-table td.label {
            font-weight: bold;
            background-color: #f5f5f5;
            width: 60%;
        }
        .salary-table td.value {
            text-align: right;
            width: 40%;
        }
        .footer {
            margin-top: 40px;
        }
        .signature-box {
            width: 250px;
            text-align: center;
        }
        .signature-line {
            border-top: 1.5px solid #333;
            margin-top: 40px;
            padding-top: 8px;
            font-weight: bold;
        }
        .date-section {
            margin-bottom: 15px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        @if($officeInfo?->getCurrentCompany?->logo)
            <img src="{{ public_path('storage/' . $officeInfo->getCurrentCompany->logo) }}" class="company-logo" alt="Logo">
        @endif
        <h1 class="company-name">{{ $officeInfo?->getCurrentCompany?->name ?? 'N/A' }}</h1>
        <div class="company-details">
            {{ $officeInfo?->getCurrentCompany?->address ?? '' }}<br>
            Phone: {{ $officeInfo?->getCurrentCompany?->telephone ?? '' }} | Email: {{ $officeInfo?->getCurrentCompany?->email ?? '' }}
        </div>
    </div>

    <div class="date-section">
        Date: {{ date('d F, Y') }}
    </div>

    <div class="certificate-title">TO WHOM IT MAY CONCERN</div>

    <div class="content">
        <p>
            This is to certify that <strong>{{ $employee->full_name }}</strong>, son/daughter of <strong>{{ $employee->father_name ?? 'N/A' }}</strong>, 
            is a permanent employee of <strong>{{ $officeInfo?->getCurrentCompany?->name ?? 'N/A' }}</strong>. 
            He/She joined the company on <strong>{{ $officeInfo?->date_of_join ? \Carbon\Carbon::parse($officeInfo->date_of_join)->format('d F, Y') : 'N/A' }}</strong> 
            and is currently serving as <strong>{{ $officeInfo?->getCurrentDesignation?->company_designation ?? 'N/A' }}</strong> 
            in the <strong>{{ $officeInfo?->getCurrentDepartment?->department_name ?? 'N/A' }}</strong> department.
        </p>
        <p>
            As per our records, his/her monthly salary breakdown is as follows:
        </p>
    </div>

    <table class="salary-table">
        <tr>
            <td class="label">Basic Salary</td>
            <td class="value">৳ {{ number_format($data['basic_salary'], 2) }}</td>
        </tr>
        <tr>
            <td class="label">House Rent Allowance</td>
            <td class="value">৳ {{ number_format($data['house_allowance'], 2) }}</td>
        </tr>
        <tr>
            <td class="label">Medical Allowance</td>
            <td class="value">৳ {{ number_format($data['medical_allowance'], 2) }}</td>
        </tr>
        @if($data['transport_allowance'] > 0)
        <tr>
            <td class="label">Transport Allowance</td>
            <td class="value">৳ {{ number_format($data['transport_allowance'], 2) }}</td>
        </tr>
        @endif
        @if($data['food_allowance'] > 0)
        <tr>
            <td class="label">Food Allowance</td>
            <td class="value">৳ {{ number_format($data['food_allowance'], 2) }}</td>
        </tr>
        @endif
        @if($data['other_earnings'] > 0)
        <tr>
            <td class="label">Other Earnings / Allowances</td>
            <td class="value">৳ {{ number_format($data['other_earnings'], 2) }}</td>
        </tr>
        @endif
        @if($data['overtime'] > 0)
        <tr>
            <td class="label">Overtime (Monthly)</td>
            <td class="value">৳ {{ number_format($data['overtime'], 2) }}</td>
        </tr>
        @endif
        <tr style="font-weight: bold; background-color: #eee;">
            <td class="label">Total Monthly Gross Remuneration</td>
            <td class="value">৳ {{ number_format($data['total_remuneration'], 2) }}</td>
        </tr>
    </table>

    <div class="content" style="margin-top: 15px;">
        <p>
            In words: <strong>Taka {{ App\HelperClass::numberToWords($data['total_remuneration']) }} Only.</strong>
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
                <span style="font-weight: normal; font-size: 11px;">{{ $officeInfo?->getCurrentCompany?->name ?? 'N/A' }}</span>
            </div>
        </div>
    </div>
</body>
</html>

