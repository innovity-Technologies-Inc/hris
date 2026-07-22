<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Travel Movement - Print</title>
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

        .filter-tags {
            margin-bottom: 8px;
            font-size: 7.5pt;
            color: #555;
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

        .badge-pending  { background: #fff3cd; border-color: #d4a017; }
        .badge-approved { background: #d4edda; border-color: #28a745; }
        .badge-rejected { background: #f8d7da; border-color: #dc3545; }
        .badge-paid     { background: #d4edda; border-color: #28a745; }
        .badge-unpaid   { background: #e2e3e5; border-color: #6c757d; }

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
        <h1>Employee Travel Movement Records</h1>
        <p>Detailed list of all employee travel and movement requests</p>
        <p><strong>Report Date:</strong> {{ date('l, F j, Y') }}</p>
    </div>

    <div class="report-info">
        <div><strong>Total Records:</strong> {{ count($records) }}</div>
        @if(request('from') || request('to'))
            <div>
                <strong>Date Range:</strong>
                {{ request('from') ? \Carbon\Carbon::parse(request('from'))->format('d M Y') : 'Start' }}
                —
                {{ request('to') ? \Carbon\Carbon::parse(request('to'))->format('d M Y') : 'End' }}
            </div>
        @endif
        @if(request('status'))
            <div><strong>Status:</strong> {{ ucfirst(request('status')) }}</div>
        @endif
        @if(request('payment_status'))
            <div><strong>Payment:</strong> {{ ucfirst(request('payment_status')) }}</div>
        @endif
        <div><strong>Printed:</strong> {{ date('d M, Y h:i A') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:3%;">#</th>
                <th style="width:16%;">Employee</th>
                <th style="width:10%;">From</th>
                <th style="width:10%;">To</th>
                <th style="width:5%;">Days</th>
                <th style="width:18%;">Source Address</th>
                <th style="width:18%;">Destination</th>
                <th style="width:6%;">Dist (km)</th>
                <th style="width:7%;">Allowance</th>
                <th style="width:7%;">Status</th>
                <th style="width:7%;">Payment</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($records as $index => $record)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left">
                        <strong>{{ $record->getEmployee?->full_name ?? 'N/A' }}</strong><br>
                        <span style="font-size:6.5pt; color:#666;">{{ $record->getEmployee?->system_id ?? '' }}</span>
                    </td>
                    <td>{{ $record->from_date ? \Carbon\Carbon::parse($record->from_date)->format('d M Y') : '—' }}</td>
                    <td>{{ $record->to_date  ? \Carbon\Carbon::parse($record->to_date)->format('d M Y')  : '—' }}</td>
                    <td>{{ $record->total_days ?? '—' }}</td>
                    <td class="text-left" style="font-size:6.5pt;">{{ $record->source_address ?? '—' }}</td>
                    <td class="text-left" style="font-size:6.5pt;">{{ $record->destination_address ?? '—' }}</td>
                    <td>{{ $record->distance ?? '0' }}</td>
                    <td>{{ number_format($record->total_allowance ?? 0, 2) }}</td>
                    <td>
                        <span class="badge badge-{{ $record->status }}">
                            {{ ucfirst($record->status ?? '—') }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-{{ $record->payment_status ?? 'unpaid' }}">
                            {{ ucfirst($record->payment_status ?? 'Unpaid') }}
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
