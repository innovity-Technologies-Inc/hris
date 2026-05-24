@extends('structure.master')

@section('content')
<div class="row g-4">
    <!-- Profile Summary Card -->
    <div class="col-md-12">
        <div class="card glass-card border-0 shadow-lg overflow-hidden">
            <div class="card-body p-0">
                <div class="row g-0">
                    <div class="col-md-auto bg-primary bg-opacity-10 p-4 text-center d-flex flex-column align-items-center justify-content-center border-end">
                        <div class="avatar-xl mb-3">
                            <img src="{{ $employee->profile_photo ? asset('storage/' . $employee->profile_photo) : asset('assets/images/users/user-1.jpg') }}" 
                                 class="rounded-circle img-thumbnail shadow-sm" alt="profile-image" style="width: 100px; height: 100px; object-fit: cover;">
                        </div>
                        <h4 class="mb-1 fw-bold">{{ $employee->full_name }}</h4>
                        <p class="text-muted mb-0 small">{{ $employee->officeInfo->getCurrentDesignation->company_designation ?? 'N/A' }}</p>
                        <span class="badge bg-primary rounded-pill px-3 py-1 mt-2">{{ $employee->applicant_id }}</span>
                    </div>
                    <div class="col p-4">
                        <div class="row g-4 text-center text-md-start">
                            <div class="col-md-4">
                                <div class="p-3 rounded-4 bg-light bg-opacity-50 border h-100">
                                    <label class="text-muted small text-uppercase fw-bold d-block mb-1">Serving Tenure</label>
                                    <h3 class="mb-0 text-primary fw-bold">{{ $stats['tenure'] }}</h3>
                                    <small class="text-muted">Joined on {{ \Carbon\Carbon::parse($employee->officeInfo->date_of_join ?? $employee->created_at)->format('d M Y') }}</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 rounded-4 bg-light bg-opacity-50 border h-100">
                                    <label class="text-muted small text-uppercase fw-bold d-block mb-1">Total Earnings</label>
                                    <h3 class="mb-0 text-success fw-bold">{{ number_format($stats['total_earnings'], 2) }}</h3>
                                    <small class="text-muted">Across {{ $stats['payrolls_count'] }} payroll cycles</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 rounded-4 bg-light bg-opacity-50 border h-100">
                                    <label class="text-muted small text-uppercase fw-bold d-block mb-1">Total Bonuses</label>
                                    <h3 class="mb-0 text-orange fw-bold">{{ number_format($stats['total_bonus'], 2) }}</h3>
                                    <small class="text-muted">Rewards & Performance bonus</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Timeline & Details Section -->
    <div class="col-lg-8">
        <div class="card glass-card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Career Journey Timeline</h5>
                <i data-feather="trending-up" class="text-primary"></i>
            </div>
            <div class="card-body">
                <div class="timeline-container px-3">
                    @forelse($timeline as $event)
                        <div class="timeline-item d-flex gap-4 pb-4 position-relative">
                            <div class="timeline-left d-flex flex-column align-items-center">
                                <div class="timeline-icon bg-{{ $event['color'] }} rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                                     style="width: 40px; height: 40px; z-index: 2;">
                                    <i data-feather="{{ $event['icon'] }}" class="text-white" style="width: 18px;"></i>
                                </div>
                                @if(!$loop->last)
                                    <div class="timeline-line bg-light position-absolute h-100" style="width: 2px; top: 40px; z-index: 1;"></div>
                                @endif
                            </div>
                            <div class="timeline-right pb-3">
                                <div class="card border-0 bg-light bg-opacity-25 shadow-none mb-0">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <h6 class="fw-bold mb-0 text-dark">{{ $event['title'] }}</h6>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($event['date'])->format('d M Y') }}</small>
                                        </div>
                                        <p class="mb-0 text-muted small">{{ $event['description'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i data-feather="inbox" class="mb-2" style="width: 40px; height: 40px;"></i>
                            <p>No journey records found yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Info Sidebar -->
    <div class="col-lg-4">
        <div class="card glass-card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="card-title mb-0">Current Placement</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small d-block mb-1">Department</label>
                    <div class="p-2 bg-light rounded border-start border-4 border-primary fw-medium">
                        {{ $employee->officeInfo->getCurrentDepartment->department_name ?? 'N/A' }}
                    </div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small d-block mb-1">Office Location</label>
                    <div class="p-2 bg-light rounded border-start border-4 border-info fw-medium">
                        {{ $employee->officeInfo->getCurrentBusinessUnit->name ?? 'N/A' }}
                    </div>
                </div>
                <div>
                    <label class="text-muted small d-block mb-1">Reporting Status</label>
                    <div class="p-2 bg-light rounded border-start border-4 border-success fw-medium">
                        {{ ucfirst($employee->status) }} Profile
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>

<style>
    .timeline-container {
        max-height: 600px;
        overflow-y: auto;
    }
    .timeline-item:last-child {
        padding-bottom: 0 !important;
    }
    .bg-purple { background-color: #6f42c1 !important; }
    .bg-orange { background-color: #fd7e14 !important; }
    .text-orange { color: #fd7e14 !important; }
</style>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        if (window.feather) {
            window.feather.replace();
        }
    });
</script>
@endpush
