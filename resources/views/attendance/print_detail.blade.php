<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Details - {{ $record->getEmployee->full_name }}</title>
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
            line-height: 1.4;
            color: #000;
            background: #fff;
        }

        /* Report Header */
        .report-header {
            border-bottom: 3px solid #333;
            margin-bottom: 15px;
            padding-bottom: 10px;
            text-align: center;
        }

        .report-header h1 {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .report-header p {
            font-size: 9pt;
            color: #666;
        }

        /* Employee Info Section */
        .employee-section {
            border: 1px solid #ccc;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        .section-header {
            background: #e8e8e8;
            border-bottom: 2px solid #666;
            padding: 8px 10px;
            font-weight: bold;
            font-size: 11pt;
        }

        .section-body {
            padding: 10px;
        }

        .employee-photo {
            text-align: center;
            margin-bottom: 10px;
        }

        .employee-photo img {
            width: 100px;
            height: 100px;
            border: 2px solid #333;
            border-radius: 50%;
            object-fit: cover;
        }

        .employee-avatar {
            display: inline-flex;
            width: 100px;
            height: 100px;
            border: 2px solid #333;
            border-radius: 50%;
            background: #e0e0e0;
            align-items: center;
            justify-content: center;
            font-size: 32pt;
            font-weight: bold;
            color: #333;
        }

        .employee-id {
            display: inline-block;
            margin-top: 5px;
            padding: 3px 8px;
            border: 1px solid #666;
            background: #f0f0f0;
            font-size: 8pt;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 10px;
        }

        .info-item {
            margin-bottom: 8px;
        }

        .info-label {
            font-size: 8pt;
            color: #666;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .info-value {
            font-size: 10pt;
            font-weight: bold;
            color: #000;
        }

        /* Two Column Layout */
        .two-column {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 15px;
        }

        .column-box {
            border: 1px solid #ccc;
            page-break-inside: avoid;
        }

        .box-header {
            background: #e8e8e8;
            border-bottom: 2px solid #666;
            padding: 6px 8px;
            font-weight: bold;
            font-size: 10pt;
        }

        .box-header.success {
            background: #d5d5d5;
        }

        .box-header.danger {
            background: #c8c8c8;
        }

        .box-body {
            padding: 10px;
        }

        .time-display {
            margin-bottom: 10px;
        }

        .time-label {
            font-size: 7pt;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .time-value {
            font-size: 12pt;
            font-weight: bold;
            color: #000;
        }

        .status-row {
            padding-top: 8px;
            border-top: 1px solid #ccc;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .status-label {
            font-size: 9pt;
            color: #666;
        }

        /* Additional Info Grid */
        .additional-info {
            border: 1px solid #ccc;
            margin-bottom: 15px;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            padding: 10px;
        }

        .detail-item {
            border: 1px solid #ddd;
            padding: 8px;
            background: #f9f9f9;
        }

        /* Badge */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border: 1px solid #666;
            font-size: 8pt;
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

        /* Footer */
        .report-footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
            font-size: 8pt;
            color: #666;
            text-align: center;
        }

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
        <h1>Attendance Details Report</h1>
        <p>Employee Attendance Information</p>
        <p>Printed on: {{ date('d M, Y h:i A') }}</p>
    </div>

    {{-- Employee Information --}}
    <div class="employee-section">
        <div class="section-header">Employee Information</div>
        <div class="section-body">
            <div class="employee-photo">
                @if ($record->getEmployee && $record->getEmployee->photo_path)
                    <img src="{{ asset('storage/' . $record->getEmployee->photo_path) }}"
                        alt="{{ $record->getEmployee->full_name }}">
                @else
                    <div class="employee-avatar">
                        {{ strtoupper(substr($record->getEmployee->full_name, 0, 2)) }}
                    </div>
                @endif
                <br>
                <span class="employee-id">ID: {{ $record->getEmployee->applicant_id }}</span>
            </div>

            <div style="text-align: center; margin-bottom: 10px;">
                <h2 style="font-size: 14pt; margin-bottom: 5px;">{{ $record->getEmployee->full_name }}</h2>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value" style="font-size: 9pt;">
                        {{ $record->getEmployee->work_email ?? ($record->getEmployee->personal_email ?? 'N/A') }}
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">Phone</div>
                    <div class="info-value" style="font-size: 9pt;">
                        {{ $record->getEmployee->work_mobile ?? ($record->getEmployee->personal_mobile ?? 'N/A') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Clock In/Out Information --}}
    <div class="two-column">
        <div class="column-box">
            <div class="box-header success">Clock In Information</div>
            <div class="box-body">
                @if ($record->in_time)
                    <div class="time-display">
                        <div class="time-label">Date</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($record->in_time)->format('d M, Y') }}</div>
                    </div>
                    <div class="time-display">
                        <div class="time-label">Time</div>
                        <div class="time-value">{{ \Carbon\Carbon::parse($record->in_time)->format('h:i A') }}</div>
                    </div>
                @else
                    <div class="time-value">Not Clocked In</div>
                @endif
                <div class="status-row">
                    <span class="status-label">Status</span>
                    <span
                        class="badge
                        @if ($record->in_status == 'On-Time') badge-success
                        @elseif($record->in_status == 'Excessive-Late') badge-danger
                        @elseif($record->in_status == 'Late') badge-warning @endif">
                        {{ $record->in_status ?? 'N/A' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="column-box">
            <div class="box-header danger">Clock Out Information</div>
            <div class="box-body">
                @if ($record->out_time)
                    <div class="time-display">
                        <div class="time-label">Date</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($record->out_time)->format('d M, Y') }}</div>
                    </div>
                    <div class="time-display">
                        <div class="time-label">Time</div>
                        <div class="time-value">{{ \Carbon\Carbon::parse($record->out_time)->format('h:i A') }}</div>
                    </div>
                @else
                    <div class="time-value">Not Clocked Out</div>
                @endif
                <div class="status-row">
                    <span class="status-label">Status</span>
                    <span
                        class="badge
                        @if ($record->out_status == 'On-Time') badge-success
                        @elseif($record->out_status == 'Early-Exit') badge-danger @endif">
                        {{ $record->out_status ?? 'N/A' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Additional Information --}}
    <div class="additional-info">
        <div class="section-header">Additional Information</div>
        <div class="detail-grid">
            <div class="detail-item">
                <div class="info-label">Shift Type</div>
                <div class="info-value">{{ $record->shift_type }}</div>
            </div>
            <div class="detail-item">
                <div class="info-label">Work Type</div>
                <div class="info-value">{{ $record->work_type }}</div>
            </div>
            <div class="detail-item">
                <div class="info-label">Working Time</div>
                <div class="info-value">{{ $record->working_time ?? '0:00' }}</div>
            </div>
            <div class="detail-item">
                <div class="info-label">Overtime</div>
                <div class="info-value">{{ $record->overtime ?? '0:00' }}</div>
            </div>
            <div class="detail-item">
                <div class="info-label">Late Count</div>
                <div class="info-value">{{ $record->late_count ?? '0' }}</div>
            </div>
            <div class="detail-item">
                <div class="info-label">Early Out Count</div>
                <div class="info-value">{{ $record->early_out_count ?? '0' }}</div>
            </div>
            <div class="detail-item">
                <div class="info-label">Workstation</div>
                <div class="info-value">{{ $record->workstation ?? 'N/A' }}</div>
            </div>
            <div class="detail-item">
                <div class="info-label">Status</div>
                <div class="info-value">
                    <span
                        class="badge
                        @if ($record->attendance_status == 'Present') badge-success
                        @elseif($record->attendance_status == 'Absent') badge-danger @endif">
                        {{ $record->attendance_status }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Shift Details --}}
    @if ($record->getShift)
        <div class="employee-section">
            <div class="section-header">Shift Details</div>
            <div class="section-body">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Shift Name</div>
                        <div class="info-value">{{ $record->getShift->name ?? 'N/A' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Clock In Time</div>
                        <div class="info-value">
                            {{ $record->getShift->clock_in_time ? \Carbon\Carbon::parse($record->getShift->clock_in_time)->format('h:i A') : 'N/A' }}
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Clock Out Time</div>
                        <div class="info-value">
                            {{ $record->getShift->clock_out_time ? \Carbon\Carbon::parse($record->getShift->clock_out_time)->format('h:i A') : 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Footer --}}
    <div class="report-footer">
        <strong>Generated by HRMS</strong> - Human Resource Management System
    </div>

    <script>
        // Auto print on page load
        window.onload = function() {
            window.print();
        };
    </script>

</body>

</html>

