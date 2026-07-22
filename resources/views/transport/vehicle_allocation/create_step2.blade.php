@extends('structure.master')
@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-car me-2"></i>
                            <h5 class="mb-0">New Vehicle Allocation - Step 2</h5>
                        </div>
                        <a href="{{ route('transport.vehicle_allocations.dashboard') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-times me-1"></i>Cancel
                        </a>
                    </div>
                </div>

                <!-- Progress Steps -->
                <div class="card-body border-bottom bg-light">
                    <div class="d-flex justify-content-between align-items-center position-relative">
                        <div class="progress position-absolute" style="height: 2px; width: 100%; z-index: 0;">
                            <div class="progress-bar bg-primary" style="width: 50%;"></div>
                        </div>
                        <div class="step-item text-center position-relative" style="z-index: 1;">
                            <div class="step-circle bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 40px; height: 40px;">
                                <i class="fas fa-check"></i>
                            </div>
                            <small class="d-block mt-1 text-primary">Select Type</small>
                        </div>
                        <div class="step-item text-center position-relative" style="z-index: 1;">
                            <div class="step-circle bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 40px; height: 40px;">
                                <i class="fas fa-car"></i>
                            </div>
                            <small class="d-block mt-1 fw-semibold text-primary">Select Vehicle</small>
                        </div>
                        <div class="step-item text-center position-relative" style="z-index: 1;">
                            <div class="step-circle bg-secondary bg-opacity-25 text-muted rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 40px; height: 40px;">
                                <i class="fas fa-check"></i>
                            </div>
                            <small class="d-block mt-1 text-muted">Confirm</small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Allocation Summary -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-primary bg-opacity-10">
                            <h6 class="mb-0 text-primary">
                                <i class="fas fa-clipboard-list me-2"></i>Selected Application Details
                            </h6>
                        </div>
                        <div class="card-body">
                            @if (isset($reference))
                                @php
                                    $allocType =
                                        session('allocation_data.allocation_type') ??
                                        ($allocationData['allocation_type'] ?? 'N/A');
                                    $displayType =
                                        $allocType == 'trip_based' ? 'Trip Requisition' : 'Employee Transport';
                                @endphp
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <div class="d-flex flex-column">
                                            <small class="text-muted mb-1">Type</small>
                                            <span class="badge bg-primary w-fit">{{ $displayType }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="d-flex flex-column">
                                            <small
                                                class="text-muted mb-1">{{ $allocType == 'trip_based' ? 'Purpose' : 'Service' }}</small>
                                            <strong class="text-truncate"
                                                title="{{ $reference->purpose_of_travel ?? ($reference->service_name ?? 'N/A') }}">
                                                {{ $reference->purpose_of_travel ?? ($reference->service_name ?? 'N/A') }}
                                            </strong>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex flex-column">
                                            <small class="text-muted mb-1">Passengers</small>
                                            <span class="badge bg-primary w-fit">
                                                <i
                                                    class="fas fa-users me-1"></i>{{ $reference->no_of_passengers ?? ($reference->estimated_passengers ?? 0) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                @php
                                    $allocType =
                                        session('allocation_data.allocation_type') ??
                                        ($allocationData['allocation_type'] ?? 'N/A');
                                    $displayType =
                                        $allocType == 'trip_based' ? 'Trip Requisition' : 'Employee Transport';
                                @endphp
                                <div class="d-flex align-items-center">
                                    <small class="text-muted me-2">Type:</small>
                                    <span class="badge bg-primary">{{ $displayType }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <h6 class="text-primary mb-4">
                        <i class="fas fa-car me-2"></i>Select Vehicle(s) for Allocation
                    </h6>

                    <form action="{{ route('transport.vehicle_allocations.step3') }}" method="POST">
                        @csrf

                        <!-- Pass through allocation data -->
                        <input type="hidden" name="allocation_type"
                            value="{{ session('allocation_data.allocation_type') ?? ($allocationData['allocation_type'] ?? '') }}">
                        <input type="hidden" name="reference_type"
                            value="{{ session('allocation_data.reference_type') ?? ($allocationData['reference_type'] ?? '') }}">
                        <input type="hidden" name="reference_id"
                            value="{{ session('allocation_data.reference_id') ?? ($allocationData['reference_id'] ?? '') }}">
                        <input type="hidden" name="name"
                            value="{{ session('allocation_data.name') ?? ($allocationData['name'] ?? '') }}">
                        <input type="hidden" name="start_date"
                            value="{{ session('allocation_data.start_date') ?? ($allocationData['start_date'] ?? '') }}">
                        <input type="hidden" name="end_date"
                            value="{{ session('allocation_data.end_date') ?? ($allocationData['end_date'] ?? '') }}">
                        <input type="hidden" name="remarks"
                            value="{{ session('allocation_data.remarks') ?? ($allocationData['remarks'] ?? '') }}">

                        <!-- Filter Controls -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Filter by Type</label>
                                <select id="vehicleTypeFilter" class="form-select" onchange="filterVehicles()">
                                    <option value="">All Types</option>
                                    @foreach ($vehicleTypes ?? [] as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Minimum Capacity</label>
                                <input type="number" id="minCapacityFilter" class="form-control"
                                    placeholder="Min passengers" min="1" onchange="filterVehicles()">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Search</label>
                                <input type="text" id="searchFilter" class="form-control"
                                    placeholder="Search by Reg No, Brand..." onkeyup="filterVehicles()">
                            </div>
                        </div>

                        <!-- Vehicle Selection -->
                        <div class="row g-2" id="vehicleList">
                            @forelse($availableVehicles ?? [] as $vehicle)
                                <div class="col-md-3 col-sm-4 vehicle-card"
                                    data-type="{{ $vehicle->vehicle_category ?? '' }}"
                                    data-capacity="{{ $vehicle->seating_capacity ?? 0 }}"
                                    data-search="{{ strtolower(($vehicle->license_number ?? '') . ' ' . ($vehicle->model_number ?? '') . ' ' . ($vehicle->vehicle_category ?? '')) }}">
                                    <div class="card h-100 border vehicle-selection-card {{ in_array($vehicle->id, old('vehicle_ids', [])) ? 'border-primary border-2' : '' }}"
                                        style="cursor: pointer; transition: all 0.2s ease;">

                                        <!-- Vehicle Image -->
                                        <div class="position-relative"
                                            style="height: 100px; overflow: hidden; background: #f8f9fa;">
                                            @if ($vehicle->vehicle_image)
                                                <img src="{{ \App\HelperClass::get_file_url($vehicle->vehicle_image) }}"
                                                    alt="{{ $vehicle->license_number }}" class="w-100 h-100"
                                                    style="object-fit: cover;">
                                            @else
                                                <div
                                                    class="w-100 h-100 d-flex align-items-center justify-content-center bg-light">
                                                    <i class="fas fa-car text-muted"></i>
                                                </div>
                                            @endif

                                            <!-- Checkbox Overlay -->
                                            <div class="position-absolute top-0 end-0 p-1">
                                                <input class="form-check-input vehicle-checkbox" type="checkbox"
                                                    name="vehicle_ids[]" value="{{ $vehicle->id }}"
                                                    id="vehicle_{{ $vehicle->id }}"
                                                    {{ in_array($vehicle->id, old('vehicle_ids', [])) ? 'checked' : '' }}
                                                    onchange="updateSelection(this)"
                                                    style="width: 18px; height: 18px; cursor: pointer;">
                                            </div>
                                        </div>

                                        <!-- Vehicle Details -->
                                        <div class="card-body p-2">
                                            <label for="vehicle_{{ $vehicle->id }}"
                                                style="cursor: pointer; margin-bottom: 0;">
                                                <h6 class="mb-1 fw-bold text-primary" style="font-size: 13px;">
                                                    {{ $vehicle->license_number }}
                                                </h6>
                                                <small class="text-muted d-block" style="font-size: 11px;">
                                                    {{ $vehicle->model_number ?? 'N/A' }}
                                                </small>

                                                <!-- Quick Info -->
                                                <div class="d-flex gap-1 mt-1">
                                                    <span class="badge bg-primary bg-opacity-10 text-primary"
                                                        style="font-size: 10px;">
                                                        {{ $vehicle->vehicle_category ?? 'N/A' }}
                                                    </span>
                                                    <span class="badge bg-primary bg-opacity-10 text-primary"
                                                        style="font-size: 10px;">
                                                        {{ $vehicle->seating_capacity ?? '-' }} seats
                                                    </span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-warning text-center">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        No available vehicles found. All vehicles might be currently allocated.
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        @error('vehicle_ids')
                            <div class="mt-3">
                                <small class="text-danger"><i
                                        class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                            </div>
                        @enderror

                        <!-- Selection Summary -->
                        <div class="alert alert-secondary mt-4" id="selectionSummary">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>
                                    <i class="fas fa-check-square me-2"></i>
                                    <strong id="selectedCount">0</strong> vehicle(s) selected
                                </span>
                                <span>
                                    Total Capacity: <strong id="totalCapacity">0</strong> passengers
                                </span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-between gap-2 border-top pt-4 mt-4">
                            <a href="{{ route('transport.vehicle_allocations.create') }}" class="btn btn-secondary px-4">
                                <i class="fas fa-arrow-left me-1"></i>Previous Step
                            </a>
                            <button type="submit" class="btn btn-primary px-5" id="nextBtn" disabled>
                                Next Step <i class="fas fa-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .vehicle-selection-card {
            cursor: pointer !important;
            transition: all 0.2s ease;
        }

        .vehicle-selection-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1) !important;
        }

        .vehicle-card label {
            cursor: pointer;
        }

        .vehicle-card:has(input:checked) .vehicle-selection-card {
            border-color: #0d6efd !important;
            border-width: 2px !important;
            background-color: #f0f7ff;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            updateSelectionSummary();
        });

        function filterVehicles() {
            const typeFilter = document.getElementById('vehicleTypeFilter').value.toLowerCase();
            const minCapacity = parseInt(document.getElementById('minCapacityFilter').value) || 0;
            const search = document.getElementById('searchFilter').value.toLowerCase();

            document.querySelectorAll('.vehicle-card').forEach(function(card) {
                const type = card.dataset.type.toLowerCase();
                const capacity = parseInt(card.dataset.capacity) || 0;
                const searchText = card.dataset.search;

                let show = true;

                if (typeFilter && type !== typeFilter) show = false;
                if (minCapacity && capacity < minCapacity) show = false;
                if (search && !searchText.includes(search)) show = false;

                card.style.display = show ? 'block' : 'none';
            });
        }

        function updateSelection(checkbox) {
            const card = checkbox.closest('.card');
            if (checkbox.checked) {
                card.classList.add('border-primary', 'border-2');
            } else {
                card.classList.remove('border-primary', 'border-2');
            }
            updateSelectionSummary();
        }

        function updateSelectionSummary() {
            const checkboxes = document.querySelectorAll('.vehicle-checkbox:checked');
            let count = checkboxes.length;
            let totalCapacity = 0;

            checkboxes.forEach(function(cb) {
                const card = cb.closest('.vehicle-card');
                totalCapacity += parseInt(card.dataset.capacity) || 0;
            });

            document.getElementById('selectedCount').textContent = count;
            document.getElementById('totalCapacity').textContent = totalCapacity;
            document.getElementById('nextBtn').disabled = count === 0;
        }
    </script>
@endpush

