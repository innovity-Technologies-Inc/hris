<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ ucfirst($type) }} Report</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #0056b3;
            padding-bottom: 12px;
            margin-bottom: 25px;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            color: #0056b3;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 5px 0 0 0;
            font-size: 14px;
            font-weight: normal;
            color: #555;
        }
        .header-meta {
            margin-top: 8px;
            font-size: 10px;
            color: #777;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
            text-transform: uppercase;
            font-size: 9px;
        }
        tr:nth-child(even) {
            background-color: #fdfdfd;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-approved {
            background-color: #d4edda;
            color: #155724;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $headerName }}</h1>
        <h2>{{ ucfirst($type) }} Summary Report</h2>
        <div class="header-meta">
            Generated on: {{ now()->format('Y-m-d H:i:s') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>System ID</th>
                <th>Employee ID</th>
                <th>Employee Name</th>
                <th>Company</th>
                <th>Branch</th>
                <th>Department</th>
                <th>{{ $type === 'resignation' ? 'Resignation Date' : 'Termination Date' }}</th>
                <th>Reason</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($offboardings as $row)
                @php
                    $officeInfo = $row->employee?->officeInfo;
                @endphp
                <tr>
                    <td>{{ $row->employee?->system_id ?? 'N/A' }}</td>
                    <td>{{ $row->employee?->applicant_id ?? 'N/A' }}</td>
                    <td>{{ $row->employee?->full_name ?? 'N/A' }}</td>
                    <td>{{ $officeInfo?->getCurrentCompany?->name ?? 'N/A' }}</td>
                    <td>{{ $officeInfo?->getCurrentBusinessUnit?->name ?? 'N/A' }}</td>
                    <td>{{ $officeInfo?->getCurrentDepartment?->department_name ?? 'N/A' }}</td>
                    <td>{{ $row->resignation_date ?? 'N/A' }}</td>
                    <td>{{ $row->reason ?? 'N/A' }}</td>
                    <td>
                        <span class="status-badge status-{{ $row->status }}">
                            {{ $row->status }}
                        </span>
                    </td>
                </tr>
            @endforeach
            @if($offboardings->isEmpty())
                <tr>
                    <td colspan="9" style="text-align: center;">No records found.</td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
