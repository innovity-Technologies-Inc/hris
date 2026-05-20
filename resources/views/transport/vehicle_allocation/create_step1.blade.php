@extends('structure.master')
@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-car-side me-2"></i>
                            <h5 class="mb-0">New Vehicle Allocation - Step 1</h5>
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
                            <div class="progress-bar bg-primary" style="width: 0%;"></div>
                        </div>
                        <div class="step-item text-center position-relative" style="z-index: 1;">
                            <div class="step-circle bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 40px; height: 40px;">
                                <i class="fas fa-list"></i>
                            </div>
                            <small class="d-block mt-1 fw-semibold text-primary">Select Type</small>
                        </div>
                        <div class="step-item text-center position-relative" style="z-index: 1;">
                            <div class="step-circle bg-secondary bg-opacity-25 text-muted rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 40px; height: 40px;">
                                <i class="fas fa-car"></i>
                            </div>
                            <small class="d-block mt-1 text-muted">Select Vehicle</small>
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
                    <h6 class="text-primary mb-4">
                        <i class="fas fa-info-circle me-2"></i>Select Allocation Type
                    </h6>

                    <form action="{{ route('transport.vehicle_allocations.step2') }}" method="POST">
                        @csrf

                        <!-- Allocation Type Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Allocation Type <span class="text-danger">*</span></label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check card p-3 h-100">
                                        <input class="form-check-input" type="radio" name="allocation_type"
                                            id="type_employee_transport" value="employee_transport"
                                            {{ old('allocation_type', request('type') == 'employee_transport' ? 'employee_transport' : '') == 'employee_transport' ? 'checked' : '' }}
                                            onchange="toggleReferenceSelection()">
                                        <label class="form-check-label w-100" for="type_employee_transport">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                                    <i class="fas fa-bus text-primary fa-lg"></i>
                                                </div>
                                                <div>
                                                    <strong>Employee Transport</strong>
                                                    <small class="text-muted d-block">For pending transport services</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check card p-3 h-100">
                                        <input class="form-check-input" type="radio" name="allocation_type"
                                            id="type_trip_based" value="trip_based"
                                            {{ old('allocation_type') == 'trip_based' ? 'checked' : '' }}
                                            onchange="toggleReferenceSelection()">
                                        <label class="form-check-label w-100" for="type_trip_based">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                                    <i class="fas fa-route text-primary fa-lg"></i>
                                                </div>
                                                <div>
                                                    <strong>Trip Based</strong>
                                                    <small class="text-muted d-block">For requisitions/trips</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @error('allocation_type')
                                <small class="text-danger"><i
                                        class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Reference Selection for Employee Transport -->
                        <div class="mb-4" id="employee_transport_reference" style="display: none;">
                            <label class="form-label fw-semibold">Select Transport Service <span
                                    class="text-danger">*</span></label>
                            <select name="reference_id" id="reference_id" class="form-select select2_list">
                                <option value="">-- Select Pending Transport Service --</option>
                                @foreach ($pendingTransports ?? [] as $transport)
                                    <option value="{{ $transport->id }}"
                                        {{ old('reference_id', request('id')) == $transport->id ? 'selected' : '' }}>
                                        {{ $transport->service_name }} - {{ $transport->company->name ?? '' }}
                                        ({{ $transport->start_date->format('d/m') }} -
                                        {{ $transport->end_date->format('d/m/Y') }})
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" name="reference_type" value="App\Models\Transport\EmployeeTransport">
                            @error('reference_id')
                                <small class="text-danger"><i
                                        class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Reference Selection for Trip -->
                        <div class="mb-4" id="trip_reference" style="display: none;">
                            <label class="form-label fw-semibold">Select Requisition <span
                                    class="text-danger">*</span></label>
                            <select name="requisition_id" id="requisition_id" class="form-select select2_list">
                                <option value="">-- Select Pending Requisition --</option>
                                @foreach ($pendingRequisitions ?? [] as $requisition)
                                    <option value="{{ $requisition->id }}"
                                        {{ old('requisition_id') == $requisition->id ? 'selected' : '' }}>
                                        {{ $requisition->purpose ?? 'Requisition #' . $requisition->id }}
                                        ({{ $requisition->journey_date ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('requisition_id')
                                <small class="text-danger"><i
                                        class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Manual Allocation Details (for Temporary/Permanent) -->
                        <div class="mb-4" id="manual_allocation" style="display: none;">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-semibold">Allocation Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control"
                                        placeholder="e.g., Executive Vehicle Allocation" value="{{ old('name') }}">
                                    @error('name')
                                        <small class="text-danger"><i
                                                class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Start Date <span
                                            class="text-danger">*</span></label>
                                    <input type="date" name="start_date" class="form-control"
                                        value="{{ old('start_date') }}">
                                    @error('start_date')
                                        <small class="text-danger"><i
                                                class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">End Date</label>
                                    <input type="date" name="end_date" class="form-control"
                                        value="{{ old('end_date') }}">
                                    <small class="text-muted">Leave empty for permanent allocation</small>
                                    @error('end_date')
                                        <small class="text-danger"><i
                                                class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-semibold">Purpose/Remarks</label>
                                    <textarea name="remarks" class="form-control" rows="3"
                                        placeholder="Describe the purpose of this allocation...">{{ old('remarks') }}</textarea>
                                    @error('remarks')
                                        <small class="text-danger"><i
                                                class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end gap-2 border-top pt-4 mt-4">
                            <a href="{{ route('transport.vehicle_allocations.dashboard') }}"
                                class="btn btn-secondary px-4">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-5">
                                Next Step <i class="fas fa-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            toggleReferenceSelection();
        });

        function toggleReferenceSelection() {
            const typeEmpTransport = document.getElementById('type_employee_transport');
            const typeTripBased = document.getElementById('type_trip_based');

            const empTransportRef = document.getElementById('employee_transport_reference');
            const tripRef = document.getElementById('trip_reference');

            // Hide all
            empTransportRef.style.display = 'none';
            if (tripRef) tripRef.style.display = 'none';

            // Show based on selection
            if (typeEmpTransport && typeEmpTransport.checked) {
                empTransportRef.style.display = 'block';
            } else if (typeTripBased && typeTripBased.checked) {
                if (tripRef) tripRef.style.display = 'block';
            }
        }
    </script>
@endpush

