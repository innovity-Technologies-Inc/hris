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
                            @forelse($leaveDetails as $leave)
                                <div class="col-md-6 col-lg-4">
                                    <div class="leave-card">
                                        <div class="leave-card-header">
                                            <h5 class="mb-0">{{ $leave->getPlan->name ?? 'N/A' }}</h5>
                                            <span class="badge bg-success">
                                                {{ $leave->getPlan->leave_type ?? 'N/A' }}
                                            </span>
                                        </div>

                                        <div class="mb-2">
                                            <span
                                                class="badge bg-success">
                                                Active
                                            </span>
                                        </div>

                                        <div class="leave-stats">
                                            <div class="stat-item">
                                                <span class="stat-value text-primary">{{ $leave->getPlan->leave_limit ?? 'N/A' }}</span>
                                                <span class="stat-label">Limit</span>
                                            </div>
                                            @php
                                                if(!empty($leave->leaveCount->leave_taken)) {
                                                    $taken = $leave->leaveCount->leave_taken;
                                                }else{
                                                    $taken = 0;
                                                }
                                            @endphp
                                            <div class="stat-item">
                                                <span class="stat-value text-danger">{{ $taken }}</span>
                                                <span class="stat-label">Taken</span>
                                            </div>
                                            <div class="stat-item">
                                                <span class="stat-value text-success">{{ ($leave->getPlan->leave_limit ?? 0) - $taken }}</span>
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
                                @php $sl = 1; @endphp
                                    @forelse($leaveHistory as $item)
                                        <tr>
                                            <td>{{ $sl++ }}</td>
                                            <td>
                                                <strong>{{ $item->getPlan->name ?? 'N/A' }}</strong>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-success">
                                                    {{ $item->getPlan->leave_type ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>{{ !empty($item->from) ? \Carbon\Carbon::parse($item->from)->format('jS F, Y') : 'N/A' }}</td>
                                            <td>{{ !empty($item->to) ? \Carbon\Carbon::parse($item->to)->format('jS F, Y') : 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-primary rounded-pill">
                                                    {{ $item->leave_count ?? 0 }} days
                                                </span>
                                            </td>
                                            <td>
                                                @if(($item->status ?? 'pending') == 'pending')
                                                    <span class="badge bg-warning text-dark">
                                                        {{ ucwords($item->status ?? 'pending') }}
                                                    </span>
                                                @elseif(($item->status ?? 'pending') == 'approved')
                                                    <span class="badge bg-success">
                                                        {{ ucwords($item->status ?? 'pending') }}
                                                    </span>
                                                @elseif(($item->status ?? 'pending') == 'rejected')
                                                    <span class="badge bg-danger">
                                                        {{ ucwords($item->status ?? 'pending') }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-info">
                                                        {{ ucwords($item->status ?? 'pending') }}
                                                    </span>
                                                @endif
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

