<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
        <h5 class="mb-0 text-primary fw-bold">
            <i class="mdi mdi-history me-2"></i> Employee Lifecycle History
        </h5>
    </div>
    <div class="card-body p-4">
        <div class="timeline">
            @forelse($employee->lifecycles()->orderBy('status_date', 'desc')->orderBy('created_at', 'desc')->get() as $history)
                <div class="timeline-item">
                    <div class="timeline-marker bg-primary"></div>
                    <div class="timeline-content">
                        <h6 class="text-dark fw-bold mb-1">{{ ucwords(str_replace('_', ' ', $history->type)) }}</h6>
                        <p class="text-muted small mb-2">
                            <i class="mdi mdi-calendar-clock me-1"></i>
                            {{ $history->status_date ? date('F d, Y', strtotime($history->status_date)) : $history->created_at->format('F d, Y') }}
                        </p>
                        @if($history->description)
                            <p class="mb-0 text-secondary">{{ $history->description }}</p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-4 text-muted">
                    <i class="mdi mdi-inbox-outline fs-1 text-secondary mb-2"></i>
                    <p>No lifecycle history found for this employee.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline::before {
    content: '';
    position: absolute;
    left: 10px;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: #e9ecef;
}
.timeline-item {
    position: relative;
    margin-bottom: 20px;
}
.timeline-marker {
    position: absolute;
    left: -26px;
    top: 0;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #0d6efd;
}
.timeline-content {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}
</style>
