@extends('structure.master')
@section('content')
    <div class="row">
        <!-- Statistics Cards Row -->
        <div class="col-12 mb-4">
            <div class="row g-3">
                <!-- Total Vehicles -->
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Total Vehicles</h6>
                                    <h2 class="mb-0">{{ $stats['total_vehicles'] ?? 0 }}</h2>
                                </div>
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                    <i class="fas fa-car fa-2x text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Available Vehicles -->
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Available</h6>
                                    <h2 class="mb-0 text-success">{{ $stats['available_vehicles'] ?? 0 }}</h2>
                                </div>
                                <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                    <i class="fas fa-check-circle fa-2x text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Allocated Vehicles -->
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Allocated</h6>
                                    <h2 class="mb-0 text-info">{{ $stats['allocated_vehicles'] ?? 0 }}</h2>
                                </div>
                                <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                    <i class="fas fa-car-side fa-2x text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Requests -->
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Pending Requests</h6>
                                    <h2 class="mb-0 text-warning">{{ $stats['pending_requests'] ?? 0 }}</h2>
                                </div>
                                <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                    <i class="fas fa-clock fa-2x text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-12 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body py-3">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <h6 class="mb-0 text-primary">
                            <i class="fas fa-bolt me-2"></i>Quick Actions
                        </h6>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('transport.vehicle_allocations.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus me-1"></i>New Allocation
                            </a>
                            <a href="{{ route('transport.vehicle_allocations.history') }}"
                                class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-history me-1"></i>View History
                            </a>
                            <a href="{{ route('transport.vehicles.index') }}" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-car me-1"></i>Vehicle List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Row -->
        <div class="col-lg-8">
            <!-- Pending Transport Requests -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-warning bg-opacity-10 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 text-warning">
                            <i class="fas fa-hourglass-half me-2"></i>Pending Transport Requests
                        </h6>
                        <span class="badge bg-warning">{{ $pendingTransports->count() + $pendingRequisitions->count() }}
                            Pending</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if ($pendingTransports->count() > 0 || $pendingRequisitions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Request Type</th>
                                        <th>Service/Purpose</th>
                                        <th>Requested By</th>
                                        <th>Type</th>
                                        <th>Duration</th>
                                        <th>Passengers</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pendingTransports as $transport)
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary">Employee Transport</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('transport.employee_transports.show', $transport->id) }}"
                                                    class="text-decoration-none fw-semibold">
                                                    {{ $transport->service_name }}
                                                </a>
                                            </td>
                                            <td>{{ $transport->company->name ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ $transport->transport_type }}</span>
                                            </td>
                                            <td>{{ $transport->start_date->format('d/m') }} -
                                                {{ $transport->end_date->format('d/m/Y') }}</td>
                                            <td>
                                                <i class="fas fa-users text-muted me-1"></i>
                                                {{ $transport->estimated_passengers ?? '-' }}
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('transport.vehicle_allocations.create', ['type' => 'employee_transport', 'id' => $transport->id]) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-car-side me-1"></i>Allocate
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach

                                    @foreach ($pendingRequisitions as $requisition)
                                        <tr>
                                            <td>
                                                <span class="badge bg-secondary">Vehicle Requisition</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('transport.vehicle_requisitions.show', $requisition->id) }}"
                                                    class="text-decoration-none fw-semibold">
                                                    {{ $requisition->purpose_of_travel }}
                                                </a>
                                            </td>
                                            <td>
                                                {{ $requisition->getEmployee->full_name ?? 'N/A' }}
                                                @if ($requisition->getEmployee)
                                                    <small
                                                        class="text-muted d-block">{{ $requisition->getEmployee->system_id }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $requisition->trip_type }}</span>
                                            </td>
                                            <td>
                                                {{ $requisition->start_date_time ? $requisition->start_date_time->format('d/m/Y H:i') : '-' }}
                                            </td>
                                            <td>
                                                <i class="fas fa-users text-muted me-1"></i>
                                                {{ $requisition->no_of_passengers ?? '-' }}
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('transport.vehicle_allocations.create', ['type' => 'vehicle_requisition', 'id' => $requisition->id]) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-car-side me-1"></i>Allocate
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <p class="text-muted mb-0">No pending transport requests</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Active Allocations -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success bg-opacity-10 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 text-success">
                            <i class="fas fa-car-side me-2"></i>Active Allocations
                        </h6>
                        <span class="badge bg-success">{{ $activeAllocations->count() }} Active</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if ($activeAllocations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Vehicle</th>
                                        <th>Allocation Name</th>
                                        <th>Type</th>
                                        <th>Period</th>
                                        <th>Remaining</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($activeAllocations as $allocation)
                                        @php
                                            $endDate = \Carbon\Carbon::parse($allocation->end_date);
                                            $endTimestamp = $endDate->timestamp;
                                            $totalSeconds = now()->diffInSeconds($endDate, false);
                                            $daysLeft = floor(abs($totalSeconds) / 86400);
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    @if ($allocation->getVehicle->vehicle_image)
                                                        <img src="{{ asset('storage/' . $allocation->getVehicle->vehicle_image) }}"
                                                            alt="Vehicle" class="rounded"
                                                            style="width: 50px; height: 50px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                            style="width: 50px; height: 50px;">
                                                            <i class="fas fa-car text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-semibold">
                                                            {{ $allocation->getVehicle->vehicle_category ?? 'N/A' }}</div>
                                                        <small
                                                            class="text-muted">{{ $allocation->getVehicle->license_number ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $allocation->name }}</td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $allocation->allocation_type }}</span>
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($allocation->start_date)->format('d/m') }} -
                                                {{ $endDate->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                @if ($totalSeconds > 0)
                                                    <span
                                                        class="badge bg-{{ $daysLeft > 7 ? 'success' : ($daysLeft > 3 ? 'warning' : 'danger') }} countdown-timer"
                                                        data-end-time="{{ $endTimestamp }}">
                                                        <i class="fas fa-clock me-1"></i>
                                                        <span class="timer-display">Calculating...</span>
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger">
                                                        <i class="fas fa-times-circle me-1"></i>Expired
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('transport.vehicle_allocations.show', $allocation->id) }}"
                                                        class="btn btn-outline-info" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger"
                                                        onclick="releaseVehicle({{ $allocation->id }})" title="Release">
                                                        <i class="fas fa-unlock"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-car fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">No active allocations</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Side Panel -->
        <div class="col-lg-4">
            <!-- Available Vehicles -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary bg-opacity-10 border-0">
                    <h6 class="mb-0 text-primary">
                        <i class="fas fa-car me-2"></i>Available Vehicles
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if ($availableVehicles->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach ($availableVehicles->take(5) as $vehicle)
                                <div class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="d-flex align-items-start gap-2 flex-grow-1">
                                        @if ($vehicle->vehicle_image)
                                            <img src="{{ asset('storage/' . $vehicle->vehicle_image) }}" alt="Vehicle"
                                                class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px;">
                                                <i class="fas fa-car text-muted"></i>
                                            </div>
                                        @endif
                                        <div class="flex-grow-1">
                                            <div>
                                                <strong>{{ $vehicle->model_number ?? 'N/A' }}</strong>
                                            </div>
                                            <div class="mt-2 small">
                                                <span class="badge bg-info">{{ $vehicle->body_type ?? 'N/A' }}</span>
                                                <span class="badge bg-secondary">
                                                    <i
                                                        class="fas fa-users me-1"></i>{{ $vehicle->seating_capacity ?? '-' }}
                                                    Seats
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if ($availableVehicles->count() > 5)
                            <div class="card-footer bg-transparent text-center">
                                <a href="{{ route('transport.vehicles.index', ['status' => 'Available']) }}"
                                    class="text-primary text-decoration-none">
                                    View all {{ $availableVehicles->count() }} vehicles
                                    <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-car-crash fa-2x text-muted mb-2"></i>
                            <p class="text-muted small mb-0">No vehicles available</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Upcoming Allocations Ending -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-danger bg-opacity-10 border-0">
                    <h6 class="mb-0 text-danger">
                        <i class="fas fa-calendar-times me-2"></i>Ending Soon
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if (isset($endingSoon) && $endingSoon->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach ($endingSoon as $allocation)
                                @php
                                    $endDate = \Carbon\Carbon::parse($allocation->end_date);
                                    $endTimestamp = $endDate->timestamp;
                                @endphp
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong>{{ $allocation->getVehicle->license_number ?? 'N/A' }}</strong>
                                            <small class="text-muted d-block">{{ $allocation->name }}</small>
                                        </div>
                                        <span class="badge bg-danger ending-soon-timer"
                                            data-end-time="{{ $endTimestamp }}">
                                            <i class="fas fa-clock me-1"></i>
                                            <span class="timer-display">Calculating...</span>
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-check fa-2x text-success mb-2"></i>
                            <p class="text-muted small mb-0">No allocations ending soon</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Release Vehicle Modal -->
    <div class="modal fade" id="releaseModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Release Vehicle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="releaseForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <p>Are you sure you want to release this vehicle allocation?</p>
                        <div class="mb-3">
                            <label for="release_remarks" class="form-label">Remarks (Optional)</label>
                            <textarea name="release_remarks" id="release_remarks" class="form-control" rows="3"
                                placeholder="Enter any remarks for releasing this vehicle..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-unlock me-1"></i>Release Vehicle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function releaseVehicle(allocationId) {
            const form = document.getElementById('releaseForm');
            form.action = `{{ route('transport.vehicle_allocations.release', ':id') }}`.replace(':id', allocationId);
            const modal = new bootstrap.Modal(document.getElementById('releaseModal'));
            modal.show();
        }

        // Update Ending Soon Timers
        function updateEndingSoonTimers() {
            const timers = document.querySelectorAll('.ending-soon-timer');

            timers.forEach(timer => {
                const endTime = parseInt(timer.getAttribute('data-end-time'));
                const now = Math.floor(Date.now() / 1000);
                const remainingSeconds = endTime - now;

                if (remainingSeconds <= 0) {
                    timer.innerHTML = '<i class="fas fa-times-circle me-1"></i>Expired';
                    timer.classList.remove('bg-danger', 'bg-warning');
                    timer.classList.add('bg-danger');
                    return;
                }

                // Calculate time units
                const hours = Math.floor(remainingSeconds / 3600);
                const minutes = Math.floor((remainingSeconds % 3600) / 60);
                const seconds = remainingSeconds % 60;

                // Build display string
                const displayText = `${hours}h ${minutes}m ${seconds}s`;

                // Update display
                const displayElement = timer.querySelector('.timer-display');
                if (displayElement) {
                    displayElement.textContent = displayText;
                }
            });
        }

        // Live Countdown Timer
        function updateCountdownTimers() {
            const timers = document.querySelectorAll('.countdown-timer');

            timers.forEach(timer => {
                const endTime = parseInt(timer.getAttribute('data-end-time'));
                const now = Math.floor(Date.now() / 1000);
                const remainingSeconds = endTime - now;

                if (remainingSeconds <= 0) {
                    timer.classList.remove('bg-success', 'bg-warning', 'bg-danger');
                    timer.classList.add('bg-danger');
                    timer.innerHTML = '<i class="fas fa-times-circle me-1"></i>Expired';
                    return;
                }

                // Calculate time units
                const days = Math.floor(remainingSeconds / 86400);
                const hours = Math.floor((remainingSeconds % 86400) / 3600);
                const minutes = Math.floor((remainingSeconds % 3600) / 60);
                const seconds = remainingSeconds % 60;

                // Build display string
                let displayText = '';
                if (days > 0) displayText += `${days}d `;
                if (hours > 0 || days > 0) displayText += `${hours}h `;
                if (minutes > 0 || hours > 0 || days > 0) displayText += `${minutes}m `;
                displayText += `${seconds}s`;

                // Update badge color based on days remaining
                timer.classList.remove('bg-success', 'bg-warning', 'bg-danger');
                if (days > 7) {
                    timer.classList.add('bg-success');
                } else if (days > 3) {
                    timer.classList.add('bg-warning');
                } else {
                    timer.classList.add('bg-danger');
                }

                // Update display
                const displayElement = timer.querySelector('.timer-display');
                if (displayElement) {
                    displayElement.textContent = displayText.trim();
                }
            });
        }

        // Initialize and update every second
        document.addEventListener('DOMContentLoaded', function() {
            updateCountdownTimers();
            updateEndingSoonTimers();
            setInterval(updateCountdownTimers, 1000);
            setInterval(updateEndingSoonTimers, 1000);
        });
    </script>
@endpush
