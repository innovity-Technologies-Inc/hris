<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Career Movements (Transfers) - Print</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 12mm 10mm;
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
            margin-bottom: 12px;
            padding-bottom: 8px;
        }

        .report-header h1 {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .report-header p {
            font-size: 8pt;
            color: #666;
            margin-bottom: 2px;
        }

        .report-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 6px 8px;
            background: #f5f5f5;
            border: 1px solid #ccc;
            font-size: 8pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        thead {
            background-color: #ddd;
            border-bottom: 2px solid #333;
        }

        th {
            padding: 5px 3px;
            font-weight: bold;
            text-align: center;
            border: 1px solid #999;
            font-size: 7.5pt;
        }

        td {
            padding: 4px 3px;
            border: 1px solid #ccc;
            font-size: 7.5pt;
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
            font-size: 6.5pt;
        }

        .badge-pending   { background: #fff3cd; border-color: #d4a017; }
        .badge-approved  { background: #d1ecf1; border-color: #bee5eb; }
        .badge-completed { background: #d4edda; border-color: #28a745; }
        .badge-rejected  { background: #f8d7da; border-color: #dc3545; }

        .report-footer {
            margin-top: 15px;
            padding-top: 8px;
            border-top: 1px solid #ccc;
            font-size: 7.5pt;
            color: #666;
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>

<body>

    <div class="report-header">
        <h1>Career Movement Logs</h1>
        <p>Detailed list of all career movements, transfers, promotions, and demotions</p>
        <p><strong>Report Date:</strong> {{ date('l, F j, Y') }}</p>
    </div>

    <div class="report-info">
        <div><strong>Total Records:</strong> {{ count($records) }}</div>
        <div><strong>Printed:</strong> {{ date('d M, Y h:i A') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:3%;">#</th>
                <th style="width:18%;">Employee</th>
                <th style="width:20%;">Current Placement</th>
                <th style="width:20%;">Requested Placement</th>
                <th style="width:12%;">Movement Type</th>
                <th style="width:10%;">Effective From</th>
                <th style="width:9%;">Status</th>
                <th style="width:8%;">Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($records as $index => $record)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left">
                        <strong>{{ $record->employee?->full_name ?? 'N/A' }}</strong><br>
                        <span style="font-size:6.5pt; color:#666;">ID: {{ $record->employee?->applicant_id ?? '' }}</span>
                    </td>
                    <td class="text-left">
                        <strong>{{ $record->currentCompany?->name ?? 'N/A' }}</strong><br>
                        <span style="font-size:6.5pt; color:#666;">{{ $record->currentBusinessUnit?->name ?? '' }}</span>
                    </td>
                    <td class="text-left">
                        <strong>{{ $record->requestedCompany?->name ?? 'N/A' }}</strong><br>
                        <span style="font-size:6.5pt; color:#666;">{{ $record->requestedBusinessUnit?->name ?? '' }}</span>
                    </td>
                    <td>
                        {{ $record->movementType?->name ?? 'N/A' }}
                    </td>
                    <td>{{ $record->effective_from ? \Carbon\Carbon::parse($record->effective_from)->format('d M Y') : '—' }}</td>
                    <td>
                        <span class="badge badge-{{ $record->status }}">
                            {{ ucfirst($record->status ?? '—') }}
                        </span>
                    </td>
                    <td>{{ $record->created_at ? \Carbon\Carbon::parse($record->created_at)->format('d M Y') : '—' }}</td>
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
