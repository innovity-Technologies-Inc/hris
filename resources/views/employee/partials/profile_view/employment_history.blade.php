<!-- Employment History Section -->
<div class="row">
    <div class="col-12">
        @if(auth()->user()->user_type === \App\Enums\UserType::Employee && ((isset($historyData) && $historyData->status === 'incomplete') || empty($historyData)))
            <!-- Incomplete Profile Warning for Employees -->
            <div class="card border-0 shadow-none mb-3" style="background-color: rgba(151, 64, 99, 0.05); border: 1px solid rgba(151, 64, 99, 0.2) !important;">
                <div class="card-body text-center py-5">
                    <div class="avatar-lg rounded-circle mx-auto mb-4 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background-color: rgba(151, 64, 99, 0.1);">
                        <i class="fas fa-history" style="font-size: 40px; color: #974063;"></i>
                    </div>
                    <h4 class="fw-bold text-dark">Incomplete Employment History</h4>
                    <p class="text-muted mb-4 mx-auto" style="max-width: 500px;">
                        Please provide your previous employment history to proceed with the profile verification.
                    </p>
                    @can('employee-management.create')
                    <a href="{{ isset($historyData) ? route('employee.employment_history.edit', $employee->id) : route('employee.employment_history.create', $employee->id) }}"
                        class="btn btn-lg px-5 shadow-sm text-white" style="background-color: #974063;">
                        <i class="fas fa-plus me-1"></i> Complete History Now
                    </a>
                    @endcan
                </div>
            </div>
        @elseif(isset($historyData) && !empty($histories))
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Employment History</h5>
                    @can('employee-management.edit')
                        <a href="{{ route('employee.employment_history.edit', $employee->id) }}" class="btn btn-sm btn-outline-primary">
                            <i class="mdi mdi-pencil me-1"></i> Edit History
                        </a>
                    @endcan
                </div>
                <div class="card-body">
                    <div class="timeline-v">
                        @foreach($histories as $history)
                            <div class="timeline-item mb-4 border-start ps-4 position-relative">
                                <span class="position-absolute top-0 start-0 translate-middle bg-primary border border-light rounded-circle" style="width: 12px; height: 12px; left: -1px !important; margin-top: 5px;"></span>
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h6 class="fw-bold text-dark mb-0">{{ $history['designation'] ?? 'N/A' }}</h6>
                                    <span class="badge bg-light text-primary border px-2 py-1">
                                        @if(!empty($history['joining_date']))
                                            {{ \Carbon\Carbon::parse($history['joining_date'])->format('M Y') }}
                                        @elseif(!empty($history['from_date']))
                                            {{ \Carbon\Carbon::parse($history['from_date'])->format('M Y') }}
                                        @else
                                            N/A
                                        @endif
                                        - 
                                        @if(isset($history['end_date']) && !empty($history['end_date']))
                                            {{ \Carbon\Carbon::parse($history['end_date'])->format('M Y') }}
                                        @elseif(isset($history['to_date']) && !empty($history['to_date']))
                                            {{ \Carbon\Carbon::parse($history['to_date'])->format('M Y') }}
                                        @else
                                            Present
                                        @endif
                                    </span>
                                </div>
                                <p class="text-primary fw-semibold mb-2">{{ $history['company_name'] ?? $history['company'] ?? 'N/A' }}</p>
                                
                                @if(!empty($history['job_description'] ?? ''))
                                    <div class="mb-2">
                                        <small class="text-muted fw-bold text-uppercase">Description:</small>
                                        <p class="text-muted small mb-0">{{ $history['job_description'] ?? 'N/A' }}</p>
                                    </div>
                                @endif

                                @if(!empty($history['achievements'] ?? ''))
                                    <div>
                                        <small class="text-muted fw-bold text-uppercase">Achievements:</small>
                                        <p class="text-muted small mb-0">{{ $history['achievements'] ?? 'N/A' }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            <!-- No History Data -->
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <i class="mdi mdi-history text-muted fs-48"></i>
                    <h5 class="mt-3">No Employment History Found</h5>
                    <p class="text-muted">No previous employment records have been added yet.</p>
                    @can('employee-management.create')
                        <a href="{{ route('employee.employment_history.create', $employee->id) }}" class="btn btn-primary mt-2">
                            <i class="mdi mdi-plus me-1"></i> Add Employment History
                        </a>
                    @endcan
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.timeline-item {
    border-left: 2px solid #e9ecef !important;
}
.timeline-item:last-child {
    border-left: 2px solid transparent !important;
}
</style>

