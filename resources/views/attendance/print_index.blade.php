<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Attendance Sheet - Print</title>
    <style>
        /* A4 Paper Setup */
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

        /* Report Header */
        .report-header {
            border-bottom: 3px solid #333;
            margin-bottom: 15px;
            padding-bottom: 10px;
        }

        .report-header h1 {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 5px;
            color: #000;
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

        .report-info strong {
            font-weight: bold;
        }

        /* Table Styling */
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
            color: #000;
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

        .fw-bold {
            font-weight: bold;
        }

        /* Badge Styling */
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

        .badge-danger {
            background: #d0d0d0;
            border-color: #333;
        }

        .badge-warning {
            background: #f0f0f0;
            border-color: #666;
        }

        .badge-info,
        .badge-primary {
            background: #e8e8e8;
            border-color: #666;
        }

        /* Footer */
        .report-footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
            font-size: 8pt;
            color: #666;
            display: flex;
            justify-content: space-between;
        }

        .small-text {
            font-size: 7pt;
            color: #666;
        }

        /* Auto print on load */
        @media print {
            body {
                margin: 0;
            }
        }
    </style>
</head>

<body>

    {{-- Report Header --}}
    <div class="report-header">
        <h1>Daily Attendance Sheet</h1>
        <p>Track and monitor employee attendance records</p>
        <p><strong>Report Date:</strong> {{ date('l, F j, Y') }}</p>
    </div>

    {{-- Report Info --}}
    <div class="report-info">
        <div>
            <strong>Total Records:</strong> {{ count($attendanceRecords) }}
        </div>
        <div>
            <strong>Printed:</strong> {{ date('d M, Y h:i A') }}
        </div>
    </div>

    {{-- Attendance Table --}}
    <table>
        <thead>
            <tr>
                <th style="width: 3%;">#</th>
                <th style="width: 20%;">Employee Name</th>
                <th style="width: 10%;">Shift Type</th>
                <th style="width: 12%;">Clock In</th>
                <th style="width: 10%;">In Status</th>
                <th style="width: 12%;">Clock Out</th>
                <th style="width: 10%;">Out Status</th>
                <th style="width: 10%;">Work Type</th>
                <th style="width: 13%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendanceRecords as $index => $record)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-left">
                        <strong>{{ $record->getEmployee->full_name }}</strong><br>
                        <span class="small-text">ID: {{ $record->getEmployee->applicant_id }}</span>
                    </td>
                    <td>
                        <span class="badge badge-info">{{ $record->shift_type }}</span>
                    </td>
                    <td>
                        @if ($record->in_time)
                            <div class="small-text">{{ \Carbon\Carbon::parse($record->in_time)->format('d M, Y') }}
                            </div>
                            <strong>{{ \Carbon\Carbon::parse($record->in_time)->format('h:i A') }}</strong>
                        @else
                            —
                        @endif
                    </td>
                    <td>
                        <span
                            class="badge
                            @if ($record->in_status == 'On-Time') badge-success
                            @elseif($record->in_status == 'Excessive-Late') badge-danger
                            @elseif($record->in_status == 'Late') badge-warning @endif">
                            {{ $record->in_status ?? 'N/A' }}
                        </span>
                    </td>
                    <td>
                        @if ($record->out_time)
                            <div class="small-text">{{ \Carbon\Carbon::parse($record->out_time)->format('d M, Y') }}
                            </div>
                            <strong>{{ \Carbon\Carbon::parse($record->out_time)->format('h:i A') }}</strong>
                        @else
                            —
                        @endif
                    </td>
                    <td>
                        <span
                            class="badge
                            @if ($record->out_status == 'On-Time') badge-success
                            @elseif($record->out_status == 'Early-Exit') badge-danger @endif">
                            {{ $record->out_status ?? 'N/A' }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-primary">{{ $record->work_type }}</span>
                    </td>
                    <td>
                        <span
                            class="badge
                            @if ($record->attendance_status == 'Present') badge-success
                            @elseif($record->attendance_status == 'Absent') badge-danger @endif fw-bold">
                            {{ $record->attendance_status }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Report Footer --}}
    <div class="report-footer">
        <div>
            <strong>Generated by HRMS</strong>
        </div>
        <div>
            Page 1 of 1
        </div>
    </div>

    <script>
        // Auto print on page load
        window.onload = function() {
            window.print();
        };
    </script>

</body>

</html>
