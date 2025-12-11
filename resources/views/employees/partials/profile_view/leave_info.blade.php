<style>
    .leave-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 20px;
        transition: all 0.3s ease;
        height: 100%;
    }

    .leave-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .leave-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .leave-type-badge {
        font-size: 0.75rem;
        padding: 4px 10px;
        border-radius: 12px;
    }

    .leave-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-top: 15px;
    }

    .stat-item {
        text-align: center;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 6px;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #6c757d;
    }

    .history-table {
        background: white;
        border-radius: 8px;
    }

    .history-table th {
        background: #f8f9fa;
        font-weight: 600;
        padding: 12px;
    }

    .history-table td {
        padding: 12px;
        vertical-align: middle;
    }

    .skeleton-loader {
        padding: 20px;
    }

    .skeleton-line {
        height: 14px;
        background: linear-gradient(90deg, #e6e6e6 0%, #f2f2f2 50%, #e6e6e6 100%);
        border-radius: 8px;
        margin-bottom: 12px;
        animation: skeleton-loading 1.2s infinite ease-in-out;
    }

    @keyframes skeleton-loading {
        0% {
            background-position: -100px 0;
        }

        100% {
            background-position: 200px 0;
        }
    }
</style>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body pt-0">
                {{-- Tab Navigation --}}
                <ul class="nav nav-underline border-bottom pt-2 mb-3" id="leave-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active p-2" id="details-tab" data-bs-toggle="tab" href="#leave-details"
                            role="tab" aria-controls="leave-details" aria-selected="true">
                            <span class="d-none d-sm-block">Leave Details</span>
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link p-2" id="history-tab" data-bs-toggle="tab" href="#leave-history"
                            role="tab" aria-controls="leave-history" aria-selected="false">
                            <span class="d-none d-sm-block">Leave History</span>
                        </a>
                    </li>
                </ul>

                {{-- Tab Content --}}
                <div class="tab-content" id="leave-tab-content">
                    {{-- Leave Details Tab --}}
                    <div class="tab-pane fade show active" id="leave-details" role="tabpanel"
                        aria-labelledby="details-tab">
                        <div class="row g-4">
                            @php
                                // Dummy data for leave details as objects
                                $leaveDetails = [
                                    (object) [
                                        'name' => 'Annual Leave',
                                        'type' => 'Paid',
                                        'status' => 'Active',
                                        'limit' => 20,
                                        'taken' => 8,
                                        'remaining' => 12,
                                        'badge_color' => 'success',
                                    ],
                                    (object) [
                                        'name' => 'Sick Leave',
                                        'type' => 'Paid',
                                        'status' => 'Active',
                                        'limit' => 15,
                                        'taken' => 5,
                                        'remaining' => 10,
                                        'badge_color' => 'info',
                                    ],
                                    (object) [
                                        'name' => 'Casual Leave',
                                        'type' => 'Paid',
                                        'status' => 'Active',
                                        'limit' => 10,
                                        'taken' => 7,
                                        'remaining' => 3,
                                        'badge_color' => 'primary',
                                    ],
                                    (object) [
                                        'name' => 'Maternity Leave',
                                        'type' => 'Paid',
                                        'status' => 'Inactive',
                                        'limit' => 90,
                                        'taken' => 0,
                                        'remaining' => 90,
                                        'badge_color' => 'warning',
                                    ],
                                    (object) [
                                        'name' => 'Unpaid Leave',
                                        'type' => 'Unpaid',
                                        'status' => 'Active',
                                        'limit' => 30,
                                        'taken' => 2,
                                        'remaining' => 28,
                                        'badge_color' => 'secondary',
                                    ],
                                    (object) [
                                        'name' => 'Study Leave',
                                        'type' => 'Paid',
                                        'status' => 'Active',
                                        'limit' => 5,
                                        'taken' => 0,
                                        'remaining' => 5,
                                        'badge_color' => 'dark',
                                    ],
                                ];
                            @endphp

                            @forelse($leaveDetails as $leave)
                                <div class="col-md-6 col-lg-4">
                                    <div class="leave-card">
                                        <div class="leave-card-header">
                                            <h5 class="mb-0">{{ $leave->name }}</h5>
                                            <span class="badge bg-{{ $leave->badge_color }} leave-type-badge">
                                                {{ $leave->type }}
                                            </span>
                                        </div>

                                        <div class="mb-2">
                                            <span
                                                class="badge bg-{{ $leave->status == 'Active' ? 'success' : 'secondary' }}">
                                                {{ $leave->status }}
                                            </span>
                                        </div>

                                        <div class="leave-stats">
                                            <div class="stat-item">
                                                <span class="stat-value text-primary">{{ $leave->limit }}</span>
                                                <span class="stat-label">Limit</span>
                                            </div>
                                            <div class="stat-item">
                                                <span class="stat-value text-danger">{{ $leave->taken }}</span>
                                                <span class="stat-label">Taken</span>
                                            </div>
                                            <div class="stat-item">
                                                <span class="stat-value text-success">{{ $leave->remaining }}</span>
                                                <span class="stat-label">Remaining</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <i data-feather="info" class="me-2"></i>
                                        No leave details found.
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Leave History Tab --}}
                    <div class="tab-pane fade" id="leave-history" role="tabpanel" aria-labelledby="history-tab">
                        @php
                            // Dummy data for leave history as objects
                            $leaveHistory = [
                                (object) [
                                    'leave_name' => 'Annual Leave',
                                    'type' => 'Paid',
                                    'leave_days' => 3,
                                    'start_date' => '2024-11-15',
                                    'end_date' => '2024-11-17',
                                    'status' => 'Approved',
                                ],
                                (object) [
                                    'leave_name' => 'Sick Leave',
                                    'type' => 'Paid',
                                    'leave_days' => 2,
                                    'start_date' => '2024-10-20',
                                    'end_date' => '2024-10-21',
                                    'status' => 'Approved',
                                ],
                                (object) [
                                    'leave_name' => 'Casual Leave',
                                    'type' => 'Paid',
                                    'leave_days' => 1,
                                    'start_date' => '2024-10-05',
                                    'end_date' => '2024-10-05',
                                    'status' => 'Approved',
                                ],
                                (object) [
                                    'leave_name' => 'Annual Leave',
                                    'type' => 'Paid',
                                    'leave_days' => 5,
                                    'start_date' => '2024-09-10',
                                    'end_date' => '2024-09-14',
                                    'status' => 'Approved',
                                ],
                                (object) [
                                    'leave_name' => 'Casual Leave',
                                    'type' => 'Paid',
                                    'leave_days' => 2,
                                    'start_date' => '2024-08-22',
                                    'end_date' => '2024-08-23',
                                    'status' => 'Approved',
                                ],
                                (object) [
                                    'leave_name' => 'Sick Leave',
                                    'type' => 'Paid',
                                    'leave_days' => 3,
                                    'start_date' => '2024-07-15',
                                    'end_date' => '2024-07-17',
                                    'status' => 'Approved',
                                ],
                                (object) [
                                    'leave_name' => 'Unpaid Leave',
                                    'type' => 'Unpaid',
                                    'leave_days' => 2,
                                    'start_date' => '2024-06-05',
                                    'end_date' => '2024-06-06',
                                    'status' => 'Approved',
                                ],
                                (object) [
                                    'leave_name' => 'Casual Leave',
                                    'type' => 'Paid',
                                    'leave_days' => 4,
                                    'start_date' => '2024-05-20',
                                    'end_date' => '2024-05-23',
                                    'status' => 'Approved',
                                ],
                            ];
                        @endphp

                        <div class="table-responsive">
                            <table class="table table-hover history-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Leave Name</th>
                                        <th>Type</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Leave Days</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($leaveHistory as $index => $history)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $history->leave_name }}</strong>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $history->type == 'Paid' ? 'success' : 'secondary' }}">
                                                    {{ $history->type }}
                                                </span>
                                            </td>
                                            <td>{{ date('M d, Y', strtotime($history->start_date)) }}</td>
                                            <td>{{ date('M d, Y', strtotime($history->end_date)) }}</td>
                                            <td>
                                                <span class="badge bg-primary rounded-pill">
                                                    {{ $history->leave_days }}
                                                    {{ $history->leave_days > 1 ? 'days' : 'day' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">
                                                    {{ $history->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="alert alert-info mb-0">
                                                    <i data-feather="info" class="me-2"></i>
                                                    No leave history found.
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize Feather Icons when tab content changes
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });

    // Re-initialize Feather Icons when switching tabs
    const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');
    tabButtons.forEach(button => {
        button.addEventListener('shown.bs.tab', function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    });
</script>
