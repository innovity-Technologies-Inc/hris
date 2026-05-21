@extends('structure.master')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Main Card -->
        <div class="card shadow-lg border-0 rounded-4 my-4">
            <!-- Form Body -->
            <div class="card-body p-4 p-md-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fs-4 fw-bold text-dark mb-0">{{ isset($movement) ? 'Edit' : 'Create' }} Travel Movement Application</h2>
                    <a href="{{ route('movement.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                        <i class="bi bi-list me-1"></i> View Logs
                    </a>
                </div>

                @php
                    $isEmployee = auth()->user()->user_type === 'Employee';
                    $loggedInEmployeeId = auth()->user()->employee_id;
                    $loggedInEmployeeName = auth()->user()->employee?->full_name ?? auth()->user()->name;
                @endphp

                <form id="employeeTravel MovementForm"
                      method="POST"
                      action="{{ isset($movement) ? route('movement.update', $movement->id) : route('movement.store') }}">

                    @csrf
                    @isset($movement)
                        @method('PUT')
                    @endisset

                    <!-- Employee Information Section -->
                    <div class="mb-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3 d-inline-flex align-items-center justify-content-center">
                                <i class="bi bi-person-badge text-primary fs-4"></i>
                            </div>
                            <h3 class="fs-5 fw-bold text-dark mb-0">Employee Information</h3>
                        </div>

                        <div class="row g-4">
                            <!-- Employee Selection -->
                            <div class="col-md-6">
                                <div class="card border shadow-sm h-100">
                                    <div class="card-body p-4">
                                        <label for="employee_id" class="form-label fw-semibold text-dark mb-3 d-flex align-items-center">
                                            <i class="bi bi-person text-primary me-2 fs-5"></i>
                                            <span>Select Employee</span>
                                            <span class="badge bg-danger ms-2">Required</span>
                                        </label>
                                        <select name="employee_id" id="employee_id" class="form-select form-select-lg select2_list" required @if($isEmployee) disabled @endif>
                                            <option value="">Select Employee</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}"
                                                    {{ (old('employee_id', $movement->employee_id ?? '') == $employee->id || ($isEmployee && $loggedInEmployeeId == $employee->id)) ? 'selected' : '' }}>
                                                    {{ $employee->full_name }} ({{ $employee->applicant_id }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @if($isEmployee)
                                            <input type="hidden" name="employee_id" value="{{ $loggedInEmployeeId }}">
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Status (Hidden for Employees) -->
                            <div class="col-md-6">
                                <div class="card border shadow-sm h-100">
                                    <div class="card-body p-4">
                                        <label for="status" class="form-label fw-semibold text-dark mb-3 d-flex align-items-center">
                                            <i class="bi bi-flag text-info me-2 fs-5"></i>
                                            <span>Status</span>
                                            <span class="badge bg-danger ms-2">Required</span>
                                        </label>
                                        @if(!$isEmployee)
                                            <select name="status" class="form-select form-select-lg" required>
                                                @foreach($statusOptions as $status)
                                                    <option value="{{ $status['value'] }}"
                                                        {{ old('status', $movement->status ?? 'pending') == $status['value'] ? 'selected' : '' }}>
                                                        {{ $status['label'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input type="text" class="form-control form-control-lg bg-light" value="Pending" readonly>
                                            <input type="hidden" name="status" value="pending">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Travel Movement Logistics Section -->
                    <div class="mb-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3 d-inline-flex align-items-center justify-content-center">
                                <i class="bi bi-geo-alt text-success fs-4"></i>
                            </div>
                            <h3 class="fs-5 fw-bold text-dark mb-0">Travel Movement Logistics</h3>
                        </div>

                        <div class="card border shadow-sm p-4">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">From Date & Time <span class="text-danger">*</span></label>
                                    <input type="datetime-local" id="from_date" name="from_date"
                                           value="{{ old('from_date', isset($movement) ? $movement->from_date : '') }}"
                                           class="form-control form-control-lg" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">To Date & Time <span class="text-danger">*</span></label>
                                    <input type="datetime-local" id="to_date" name="to_date"
                                           value="{{ old('to_date', isset($movement) ? $movement->to_date : '') }}"
                                           class="form-control form-control-lg" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Source Address <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-map"></i></span>
                                        <input type="text" id="source_address" name="source_address"
                                               value="{{ old('source_address', $movement->source_address ?? '') }}"
                                               class="form-control form-control-lg border-start-0" placeholder="Search starting point..." required>
                                    </div>
                                    <input type="hidden" id="source_lat" name="source_lat" value="{{ old('source_lat', $movement->source_lat ?? '') }}">
                                    <input type="hidden" id="source_lng" name="source_lng" value="{{ old('source_lng', $movement->source_lng ?? '') }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Destination Address <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-pin-map-fill"></i></span>
                                        <input type="text" id="destination_address" name="destination_address"
                                               value="{{ old('destination_address', $movement->destination_address ?? '') }}"
                                               class="form-control form-control-lg border-start-0" placeholder="Search destination..." required>
                                    </div>
                                    <input type="hidden" id="dest_lat" name="dest_lat" value="{{ old('dest_lat', $movement->dest_lat ?? '') }}">
                                    <input type="hidden" id="dest_lng" name="dest_lng" value="{{ old('dest_lng', $movement->dest_lng ?? '') }}">
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Reason <span class="text-danger">*</span></label>
                                    <textarea name="reason" class="form-control form-control-lg" rows="2" placeholder="Briefly explain the purpose of this movement..." required>{{ old('reason', $movement->reason ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Allowances & Summary Section -->
                    <div class="mb-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3 d-inline-flex align-items-center justify-content-center">
                                <i class="bi bi-cash-stack text-warning fs-4"></i>
                            </div>
                            <h3 class="fs-5 fw-bold text-dark mb-0">Allowances & Summary</h3>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="card border shadow-sm h-100">
                                    <div class="card-body p-4">
                                        <label class="form-label fw-semibold">TA Plan</label>
                                        <select id="ta_plan_id" name="ta_plan_id" class="form-select" required>
                                            <option value="">Select TA Plan</option>
                                            @foreach($taPlans as $plan)
                                                <option value="{{ $plan->id }}" data-rate="{{ $plan->remuneration }}"
                                                    {{ old('ta_plan_id', $movement->ta_plan_id ?? '') == $plan->id ? 'selected' : '' }}>
                                                    {{ $plan->name }} (৳{{ $plan->remuneration }}/KM)
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card border shadow-sm h-100">
                                    <div class="card-body p-4">
                                        <label class="form-label fw-semibold">DA Plan</label>
                                        <select id="da_plan_id" name="da_plan_id" class="form-select" required>
                                            <option value="">Select DA Plan</option>
                                            @foreach($daPlans as $plan)
                                                <option value="{{ $plan->id }}" data-rate="{{ $plan->remuneration }}"
                                                    {{ old('da_plan_id', $movement->da_plan_id ?? '') == $plan->id ? 'selected' : '' }}>
                                                    {{ $plan->name }} (৳{{ $plan->remuneration }}/Day)
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card border shadow-sm h-100">
                                    <div class="card-body p-4 text-center bg-dark text-white rounded-3">
                                        <small class="text-white-50 d-block mb-1">Calculated Distance</small>
                                        <div class="h3 mb-0 fw-bold"><span id="display_distance">{{ old('distance', $movement->distance ?? '0.00') }}</span> <small class="fs-6">KM</small></div>
                                        <input type="hidden" id="covered_distance" name="distance" value="{{ old('distance', $movement->distance ?? '') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="card border-0 bg-light rounded-4">
                                    <div class="card-body p-4">
                                        <div class="row g-3 text-center">
                                            <div class="col-md-3">
                                                <span class="d-block text-muted small mb-1">Total Days</span>
                                                <h4 class="mb-0 fw-bold" id="total_days">{{ old('total_days', $movement->total_days ?? 0) }}</h4>
                                            </div>
                                            <div class="col-md-3">
                                                <span class="d-block text-muted small mb-1">Total TA</span>
                                                <h4 class="mb-0 fw-bold text-primary" id="total_ta">৳{{ number_format(old('total_ta', $movement->total_ta ?? 0),2) }}</h4>
                                            </div>
                                            <div class="col-md-3">
                                                <span class="d-block text-muted small mb-1">Total DA</span>
                                                <h4 class="mb-0 fw-bold text-primary" id="total_da">৳{{ number_format(old('total_da', $movement->total_da ?? 0),2) }}</h4>
                                            </div>
                                            <div class="col-md-3">
                                                <span class="d-block text-muted small mb-1 text-uppercase fw-bold">Grand Total</span>
                                                <h4 class="mb-0 fw-bold text-success" id="total_allowance">৳{{ number_format(old('total_allowance', $movement->total_allowance ?? 0),2) }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Hidden DB Fields --}}
                        <input type="hidden" name="total_days" id="total_days_input" value="{{ old('total_days', $movement->total_days ?? 0) }}">
                        <input type="hidden" name="total_ta" id="total_ta_input" value="{{ old('total_ta', $movement->total_ta ?? 0) }}">
                        <input type="hidden" name="total_da" id="total_da_input" value="{{ old('total_da', $movement->total_da ?? 0) }}">
                        <input type="hidden" name="total_allowance" id="total_allowance_input" value="{{ old('total_allowance', $movement->total_allowance ?? 0) }}">
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end gap-3 mt-4">
                        <a href="{{ route('movement.index') }}" class="btn btn-lg btn-outline-secondary px-4 px-md-5 rounded-3">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-lg btn-dark px-4 px-md-5 rounded-3 shadow">
                            <i class="bi bi-send-fill me-2"></i>{{ isset($movement) ? 'Update Travel Movement' : 'Submit Travel Movement' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer Info -->
        <div class="text-center mt-4 text-muted small">
            <i class="bi bi-info-circle me-1"></i>
            Travel Movements are tracked and verified against configured TA/DA plans
        </div>
    </div>
</div>
@endsection


@push('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&libraries=places&callback=initAutocomplete" async defer></script>
    <script>
        /* ======================================================
           GLOBAL VARIABLES
        ====================================================== */
        let sourcePlace = null;
        let destinationPlace = null;
        let geocoder = null;

        function buildPlaceFromLatLng(lat, lng) {
            return { geometry: { location: new google.maps.LatLng(lat, lng) } };
        }

        function initAutocomplete() {
            geocoder = new google.maps.Geocoder();
            const sourceInput = document.getElementById('source_address');
            const destInput   = document.getElementById('destination_address');

            if (!google.maps.places) return;

            const sourceAutocomplete = new google.maps.places.Autocomplete(sourceInput, {
                componentRestrictions: { country: 'bd' },
                fields: ['geometry', 'formatted_address']
            });

            const destAutocomplete = new google.maps.places.Autocomplete(destInput, {
                componentRestrictions: { country: 'bd' },
                fields: ['geometry', 'formatted_address']
            });

            sourceAutocomplete.addListener('place_changed', () => {
                const place = sourceAutocomplete.getPlace();
                if (!place.geometry) return;
                document.getElementById('source_lat').value = place.geometry.location.lat();
                document.getElementById('source_lng').value = place.geometry.location.lng();
                forceRecalculateFromInputs();
            });

            destAutocomplete.addListener('place_changed', () => {
                const place = destAutocomplete.getPlace();
                if (!place.geometry) return;
                document.getElementById('dest_lat').value = place.geometry.location.lat();
                document.getElementById('dest_lng').value = place.geometry.location.lng();
                forceRecalculateFromInputs();
            });
        }

        function forceRecalculateFromInputs() {
            const sLat = parseFloat(document.getElementById('source_lat').value);
            const sLng = parseFloat(document.getElementById('source_lng').value);
            const dLat = parseFloat(document.getElementById('dest_lat').value);
            const dLng = parseFloat(document.getElementById('dest_lng').value);

            if (isNaN(sLat) || isNaN(sLng) || isNaN(dLat) || isNaN(dLng)) return;

            sourcePlace = buildPlaceFromLatLng(sLat, sLng);
            destinationPlace = buildPlaceFromLatLng(dLat, dLng);
            calculateDistance();
        }

        function calculateDistance() {
            if (!sourcePlace || !destinationPlace) return;
            const service = new google.maps.DistanceMatrixService();
            service.getDistanceMatrix({
                origins: [sourcePlace.geometry.location],
                destinations: [destinationPlace.geometry.location],
                travelMode: 'DRIVING',
                unitSystem: google.maps.UnitSystem.METRIC
            }, (res, status) => {
                let dist = 0;
                if (status === 'OK' && res.rows[0].elements[0].status === 'OK') {
                    dist = (res.rows[0].elements[0].distance.value / 1000);
                } else {
                    dist = calculateStraightDistanceLogic();
                }
                
                document.getElementById('covered_distance').value = dist.toFixed(2);
                document.getElementById('display_distance').textContent = dist.toFixed(2);
                calculateAllowance();
            });
        }

        function calculateStraightDistanceLogic() {
            const R = 6371;
            const lat1 = sourcePlace.geometry.location.lat();
            const lon1 = sourcePlace.geometry.location.lng();
            const lat2 = destinationPlace.geometry.location.lat();
            const lon2 = destinationPlace.geometry.location.lng();
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat/2)**2 + Math.cos(lat1*Math.PI/180)*Math.cos(lat2*Math.PI/180)*Math.sin(dLon/2)**2;
            return (R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)) * 1.3);
        }

        function calculateAllowance() {
            const distance = parseFloat(document.getElementById('covered_distance').value) || 0;
            const taPlan = document.getElementById('ta_plan_id');
            const daPlan = document.getElementById('da_plan_id');
            const taRate = taPlan.selectedOptions[0]?.dataset.rate || 0;
            const daRate = daPlan.selectedOptions[0]?.dataset.rate || 0;

            const from = new Date(document.getElementById('from_date').value);
            const to = new Date(document.getElementById('to_date').value);

            const days = (!isNaN(from) && !isNaN(to) && to >= from)
                ? Math.max(1, Math.ceil((to - from) / 86400000))
                : 0;

            const totalTa = distance * taRate;
            const totalDa = days * daRate;
            const total = totalTa + totalDa;

            document.getElementById('total_days').textContent = days;
            document.getElementById('total_ta').textContent = `৳${totalTa.toFixed(2)}`;
            document.getElementById('total_da').textContent = `৳${totalDa.toFixed(2)}`;
            document.getElementById('total_allowance').textContent = `৳${total.toFixed(2)}`;

            document.getElementById('total_days_input').value = days;
            document.getElementById('total_ta_input').value = totalTa.toFixed(2);
            document.getElementById('total_da_input').value = totalDa.toFixed(2);
            document.getElementById('total_allowance_input').value = total.toFixed(2);
        }

        document.addEventListener('DOMContentLoaded', () => {
            initAutocomplete();
            ['from_date','to_date','ta_plan_id','da_plan_id'].forEach(id => 
                document.getElementById(id)?.addEventListener('change', calculateAllowance)
            );
            if (document.getElementById('source_lat').value && document.getElementById('dest_lat').value) {
                forceRecalculateFromInputs();
            }
        });
    </script>
@endpush
