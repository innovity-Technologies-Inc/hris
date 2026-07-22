<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Demotion Logs - Print</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm 10mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
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

        .badge {
            display: inline-block;
            padding: 1px 5px;
            border: 1px solid #666;
            font-size: 7pt;
        }

        .badge-pending  { background: #fff3cd; border-color: #d4a017; }
        .badge-approved { background: #d1ecf1; border-color: #bee5eb; }
        .badge-rejected { background: #f8d7da; border-color: #dc3545; }

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
        <h1>Employee Demotion Sheet</h1>
        <p>List of all employee designations demotions, salary updates, and adjustments</p>
        <p><strong>Report Date:</strong> {{ date('l, F j, Y') }}</p>
    </div>

    <div class="report-info">
        <div><strong>Total Demotions:</strong> {{ count($records) }}</div>
        @if(request('keyword'))
            <div><strong>Keyword Filter:</strong> "{{ request('keyword') }}"</div>
        @endif
        <div><strong>Printed:</strong> {{ date('d M, Y h:i A') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:5%;">#</th>
                <th style="width:20%;">Employee</th>
                <th style="width:15%;">Prev Designation</th>
                <th style="width:15%;">New Designation</th>
                <th style="width:10%;">Decrease Amount</th>
                <th style="width:10%;">Prev Gross</th>
                <th style="width:10%;">New Gross</th>
                <th style="width:8%;">Effective From</th>
                <th style="width:7%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($records as $index => $record)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left">
                        <strong>{{ $record->getEmployee?->full_name ?? 'N/A' }}</strong><br>
                        <span style="font-size:7pt; color:#666;">ID: {{ $record->getEmployee?->applicant_id ?? '' }}</span>
                    </td>
                    <td>{{ $record->getPreviousDesignation?->company_designation ?? '—' }}</td>
                    <td>{{ $record->getNewDesignation?->company_designation ?? '—' }}</td>
                    <td>৳{{ number_format($record->salary_decrease_amount ?? 0, 2) }}</td>
                    <td>৳{{ number_format($record->previous_gross_salary ?? 0, 2) }}</td>
                    <td>৳{{ number_format($record->new_gross_salary ?? 0, 2) }}</td>
                    <td>{{ $record->effective_from ? \Carbon\Carbon::parse($record->effective_from)->format('d M Y') : '—' }}</td>
                    <td>
                        <span class="badge badge-{{ strtolower($record->status ?? 'pending') }}">
                            {{ ucfirst($record->status ?? 'Pending') }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="report-footer">
        <div><strong>Generated by HRMS</strong></div>
        <div>Page 1 of 1</div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>

</body>

</html>
