<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tax Deduction History - Print</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 8pt;
            line-height: 1.3;
            color: #000;
            background: #fff;
        }

        .report-header {
            border-bottom: 3px solid #333;
            margin-bottom: 15px;
            padding-bottom: 10px;
        }

        .report-header h1 {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .report-header p {
            font-size: 8pt;
            color: #666;
            margin-bottom: 2px;
        }

        .report-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            padding: 6px 8px;
            background: #f5f5f5;
            border: 1px solid #ccc;
            font-size: 8pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        thead {
            background-color: #ddd;
            border-bottom: 2px solid #333;
        }

        th {
            padding: 6px 4px;
            font-weight: bold;
            text-align: center;
            border: 1px solid #999;
            font-size: 8pt;
        }

        td {
            padding: 5px 4px;
            border: 1px solid #ccc;
            font-size: 8pt;
            vertical-align: middle;
            text-align: center;
        }

        tbody tr {
            page-break-inside: avoid;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .fw-bold {
            font-weight: bold;
        }

        .report-footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
            font-size: 8pt;
            color: #666;
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>

<body>

    <div class="report-header">
        <h1>Tax Deduction History</h1>
        <p>List of employee tax deductions processed via payroll cycles</p>
        <p><strong>Report Date:</strong> {{ date('l, F j, Y') }}</p>
    </div>

    <div class="report-info">
        <div><strong>Total Records:</strong> {{ count($records) }}</div>
        @if(request('keyword'))
            <div><strong>Keyword Filter:</strong> "{{ request('keyword') }}"</div>
        @endif
        <div><strong>Printed:</strong> {{ date('d M, Y h:i A') }}</div>
    </div>

    @php
        $currency = \App\HelperClass::getCurrency() ?? '৳';
    @endphp

    <table>
        <thead>
            <tr>
                <th style="width: 4%;">#</th>
                <th style="width: 20%;" class="text-left">Employee Name</th>
                <th style="width: 10%;">Employee ID</th>
                <th style="width: 9%;">Salary Month</th>
                <th style="width: 10%;">Deduction Date</th>
                <th style="width: 8%;">Frequency</th>
                <th style="width: 9%;">Worked</th>
                <th style="width: 10%;" class="text-right">Annual Tax</th>
                <th style="width: 10%;" class="text-right">Monthly Rate</th>
                <th style="width: 10%;" class="text-right">Deducted Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($records as $index => $record)
                @php
                    $hoursOrDays = '—';
                    if ($record->frequency === 'hourly') {
                        $hoursOrDays = number_format($record->hours_worked, 2) . ' hrs';
                    } elseif ($record->frequency === 'daily') {
                        $hoursOrDays = number_format($record->days_worked, 1) . ' days';
                    }
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left fw-bold">{{ $record->employee?->full_name ?? 'N/A' }}</td>
                    <td>{{ $record->employee?->applicant_id ?? $record->employee?->system_id ?? 'N/A' }}</td>
                    <td>{{ $record->salary_month ? \Carbon\Carbon::parse($record->salary_month . '-01')->format('M Y') : '—' }}</td>
                    <td>{{ $record->deduction_date ? $record->deduction_date->format('d M Y') : 'N/A' }}</td>
                    <td>{{ ucfirst($record->frequency) }}</td>
                    <td>{{ $hoursOrDays }}</td>
                    <td class="text-right">{{ $currency }}{{ number_format($record->annual_tax_payable, 2) }}</td>
                    <td class="text-right">{{ $currency }}{{ number_format($record->monthly_tax_rate, 2) }}</td>
                    <td class="text-right fw-bold text-success">{{ $currency }}{{ number_format($record->amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="report-footer">
        <div><strong>Generated by HRMS</strong></div>
        <div>Printed on: {{ date('d-M-Y') }}</div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>

</body>

</html>
