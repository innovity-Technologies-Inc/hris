<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Requisition Sheet - Print</title>
    <style>
        @page {
            size: A4;
            margin: 15mm 10mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
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
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .report-header p {
            font-size: 9pt;
            color: #666;
            margin-bottom: 3px;
        }

        .report-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding: 8px;
            background: #f5f5f5;
            border: 1px solid #ccc;
        }

        .report-info div {
            font-size: 9pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        thead {
            background-color: #e0e0e0;
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
            padding: 2px 6px;
            border: 1px solid #666;
            font-size: 7pt;
            background: #fff;
        }

        .badge-success {
            background: #e8e8e8;
            border-color: #333;
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
        <h1>Vehicle Requisitions List</h1>
        <p>Track and monitor company trip requests</p>
        <p><strong>Report Date:</strong> {{ date('l, F j, Y') }}</p>
    </div>

    <div class="report-info">
        <div>
            <strong>Total Requisitions:</strong> {{ count($records) }}
        </div>
        <div>
            <strong>Printed:</strong> {{ date('d M, Y h:i A') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 3%;">#</th>
                <th style="width: 25%;">Employee</th>
                <th style="width: 12%;">Trip Type</th>
                <th style="width: 15%;">Vehicle Type Required</th>
                <th style="width: 10%;">Passengers</th>
                <th style="width: 20%;">Schedule</th>
                <th style="width: 15%;">Destination</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($records as $index => $record)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left">
                        <strong>{{ $record->getEmployee->full_name ?? 'N/A' }}</strong><br>
                        <span style="font-size: 7pt; color: #666;">ID: {{ $record->getEmployee->system_id ?? 'N/A' }}</span>
                    </td>
                    <td>{{ $record->trip_type }} ({{ $record->trip_mode }})</td>
                    <td>{{ $record->vehicle_type_required }}</td>
                    <td>{{ $record->no_of_passengers }}</td>
                    <td class="text-left" style="font-size: 7pt;">
                        <strong>From:</strong> {{ $record->start_date_time ? $record->start_date_time->format('d M Y, h:i A') : '-' }}<br>
                        <strong>To:</strong> {{ $record->end_date_time ? $record->end_date_time->format('d M Y, h:i A') : '-' }}
                    </td>
                    <td class="text-left">
                        <strong>From:</strong> {{ $record->pickup_location }}<br>
                        <strong>To:</strong> {{ $record->destination }}
                    </td>
                    <td>
                        <span class="badge fw-bold">
                            {{ $record->approval_status }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="report-footer">
        <div>
            <strong>Generated by HRMS</strong>
        </div>
        <div>
            Page 1 of 1
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>

</body>

</html>
