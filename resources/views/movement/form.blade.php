@extends('structure.master')

@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('movement.index') }}" class="btn btn-outline-secondary btn-sm">
                <i style="height: 12px; width: 12px" data-feather="arrow-left"></i> Back to Logs
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $isEmployee = auth()->user()->user_type === \App\Enums\UserType::Employee;
        $loggedInEmployeeId = auth()->user()->employee_id;
        $loggedInEmployeeName = auth()->user()->employee?->full_name ?? auth()->user()->name;
    @endphp

    <form id="employeeTravelMovementForm"
          method="POST"
          action="{{ isset($movement) ? route('movement.update', $movement->id) : route('movement.store') }}"
          enctype="multipart/form-data">

        @csrf
        @isset($movement)
            @method('PUT')
        @endisset

        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Employee Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Employee Selection -->
                            <div class="col-md-6 mb-3">
                                <label for="employee_id" class="form-label fw-semibold">Employee <span class="text-danger">*</span></label>
                                <select name="employee_id" id="employee_id" class="form-select select2_list" required @if($isEmployee) disabled @endif>
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

                            <!-- Status -->
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                @if(!$isEmployee)
                                    <select name="status" class="form-select" required>
                                        @foreach($statusOptions as $status)
                                            <option value="{{ $status['value'] }}"
                                                {{ old('status', $movement->status ?? 'pending') == $status['value'] ? 'selected' : '' }}>
                                                {{ $status['label'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <input type="text" class="form-control bg-light" value="Pending" readonly>
                                    <input type="hidden" name="status" value="pending">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-header bg-light-subtle">
                        <h5 class="card-title mb-0">Travel Movement Logistics</h5>
                    </div>
                    <div class="card-body">
                        <!-- Part 1: Date & Time -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">From Date & Time <span class="text-danger">*</span></label>
                                <input type="datetime-local" id="from_date" name="from_date"
                                       value="{{ old('from_date', isset($movement) ? \Carbon\Carbon::parse($movement->from_date)->format('Y-m-d\TH:i') : '') }}"
                                       class="form-control" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">To Date & Time <span class="text-danger">*</span></label>
                                <input type="datetime-local" id="to_date" name="to_date"
                                       value="{{ old('to_date', isset($movement) ? \Carbon\Carbon::parse($movement->to_date)->format('Y-m-d\TH:i') : '') }}"
                                       class="form-control" required>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Part 2: Routes Details -->
                        <h6 class="fw-semibold text-primary mb-3"><i class="bi bi-pin-map-fill me-2"></i>Routes/Destinations</h6>
                        <div id="route-legs-container">
                            @php
                                $details = isset($movement) && $movement->details->isNotEmpty() ? $movement->details : [];
                            @endphp

                            @if(count($details) > 0)
                                @foreach($details as $index => $detail)
                                    @include('movement.partials.route_leg_card', ['index' => $index, 'detail' => $detail, 'showRemove' => count($details) > 1])
                                @endforeach
                            @else
                                @include('movement.partials.route_leg_card', ['index' => 0, 'detail' => null, 'showRemove' => false])
                            @endif
                        </div>

                        <!-- Add Button -->
                        <div class="d-flex justify-content-start mb-3">
                            <button type="button" id="add-leg-btn" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-plus-circle me-1"></i> Add Route
                            </button>
                        </div>

                        <!-- Overall calculated values summary -->
                        <div class="row mt-3">
                            <div class="col-12 mb-3">
                                <div class="border rounded shadow-sm p-3 bg-light">
                                    <div class="row text-center">
                                        <div class="col-md-6 border-end">
                                            <span class="d-block text-muted small mb-1 fw-semibold text-uppercase">Total Days</span>
                                            <h4 class="mb-0 fw-bold text-dark" id="display_days">{{ old('total_days', $movement->total_days ?? 0) }}</h4>
                                        </div>
                                        <div class="col-md-6">
                                            <span class="d-block text-muted small mb-1 fw-semibold text-uppercase">Overall Distance</span>
                                            <h4 class="mb-0 fw-bold text-primary"><span id="display_distance">{{ old('distance', $movement->distance ?? '0.00') }}</span> <small class="text-muted">KM</small></h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- Always keep hidden inputs for backend logic --}}
        <input type="hidden" id="covered_distance" name="distance" value="{{ old('distance', $movement->distance ?? '0.00') }}">
        <input type="hidden" name="total_days" id="total_days_input" value="{{ old('total_days', $movement->total_days ?? 0) }}">

        <div class="row mt-3 mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('movement.index') }}" class="btn btn-secondary">
                        <i style="height: 12px; width: 12px" data-feather="x"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i style="height: 12px; width: 12px" data-feather="save"></i>
                        {{ isset($movement) ? 'Update' : 'Submit' }} Application
                    </button>
                </div>
            </div>
        </div>
    </form>

    <!-- Template for adding dynamic route legs -->
    <div id="route-leg-template" class="d-none">
        @include('movement.partials.route_leg_card', ['index' => '__INDEX__', 'detail' => null, 'showRemove' => true])
    </div>
@endsection

@push('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&libraries=places&callback=initAllAutocompletes" async defer></script>
    <script>
        let autocompleteInstances = [];

        function initAllAutocompletes() {
            document.querySelectorAll('.route-card').forEach(card => {
                initLegAutocomplete(card);
            });
            calculateOverallDistance();
            calculateTotalDays();
        }

        function initLegAutocomplete(card) {
            const index = card.getAttribute('data-index');
            const sourceInput = card.querySelector('.source-address');
            const destInput = card.querySelector('.destination-address');

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
                card.querySelector('.source-lat').value = place.geometry.location.lat();
                card.querySelector('.source-lng').value = place.geometry.location.lng();
                calculateLegDistance(card);
            });

            destAutocomplete.addListener('place_changed', () => {
                const place = destAutocomplete.getPlace();
                if (!place.geometry) return;
                card.querySelector('.dest-lat').value = place.geometry.location.lat();
                card.querySelector('.dest-lng').value = place.geometry.location.lng();
                calculateLegDistance(card);
            });
        }

        function calculateLegDistance(card) {
            const sLat = parseFloat(card.querySelector('.source-lat').value);
            const sLng = parseFloat(card.querySelector('.source-lng').value);
            const dLat = parseFloat(card.querySelector('.dest-lat').value);
            const dLng = parseFloat(card.querySelector('.dest-lng').value);

            if (isNaN(sLat) || isNaN(sLng) || isNaN(dLat) || isNaN(dLng)) return;

            const origin = new google.maps.LatLng(sLat, sLng);
            const destination = new google.maps.LatLng(dLat, dLng);

            const service = new google.maps.DistanceMatrixService();
            service.getDistanceMatrix({
                origins: [origin],
                destinations: [destination],
                travelMode: 'DRIVING',
                unitSystem: google.maps.UnitSystem.METRIC
            }, (res, status) => {
                let dist = 0;
                if (status === 'OK' && res.rows[0].elements[0].status === 'OK') {
                    dist = (res.rows[0].elements[0].distance.value / 1000);
                } else {
                    dist = calculateStraightDistanceLogic(sLat, sLng, dLat, dLng);
                }
                
                card.querySelector('.leg-distance').value = dist.toFixed(2);
                calculateOverallDistance();
            });
        }

        function calculateStraightDistanceLogic(lat1, lon1, lat2, lon2) {
            const R = 6371; // Earth radius in km
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat/2)**2 + Math.cos(lat1*Math.PI/180)*Math.cos(lat2*Math.PI/180)*Math.sin(dLon/2)**2;
            return (R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)) * 1.3);
        }

        function calculateOverallDistance() {
            let totalDist = 0;
            document.querySelectorAll('.leg-distance').forEach(elem => {
                totalDist += parseFloat(elem.value) || 0;
            });
            
            document.getElementById('covered_distance').value = totalDist.toFixed(2);
            document.getElementById('display_distance').textContent = totalDist.toFixed(2);
        }

        function calculateTotalDays() {
            const fromDateInput = document.getElementById('from_date');
            const toDateInput = document.getElementById('to_date');
            
            if (!fromDateInput || !toDateInput) return;

            const from = new Date(fromDateInput.value);
            const to = new Date(toDateInput.value);

            const days = (!isNaN(from) && !isNaN(to) && to >= from)
                ? Math.max(1, Math.ceil((to - from) / 86400000))
                : 0;

            document.getElementById('display_days').textContent = days;
            document.getElementById('total_days_input').value = days;
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('from_date')?.addEventListener('change', calculateTotalDays);
            document.getElementById('to_date')?.addEventListener('change', calculateTotalDays);

            // Add Leg Card Action
            document.getElementById('add-leg-btn').addEventListener('click', () => {
                const container = document.getElementById('route-legs-container');
                const template = document.getElementById('route-leg-template').innerHTML;
                
                // Fetch destination details of previous/last card for auto-population
                const cards = container.querySelectorAll('.route-card');
                let prevDestAddr = '';
                let prevDestLat = '';
                let prevDestLng = '';
                if (cards.length > 0) {
                    const lastCard = cards[cards.length - 1];
                    prevDestAddr = lastCard.querySelector('.destination-address').value;
                    prevDestLat = lastCard.querySelector('.dest-lat').value;
                    prevDestLng = lastCard.querySelector('.dest-lng').value;
                }

                // Get next index
                const nextIndex = container.querySelectorAll('.route-card').length;
                const html = template.replace(/__INDEX__/g, nextIndex);
                
                // Append card
                container.insertAdjacentHTML('beforeend', html);
                
                // Initialize autocomplete for the new card
                const newCard = container.querySelector(`.route-card[data-index="${nextIndex}"]`);
                
                // Auto-populate new card's source with previous card's destination
                if (prevDestAddr) {
                    newCard.querySelector('.source-address').value = prevDestAddr;
                    newCard.querySelector('.source-lat').value = prevDestLat;
                    newCard.querySelector('.source-lng').value = prevDestLng;
                }

                initLegAutocomplete(newCard);
                
                // Show remove buttons since we have more than one card
                container.querySelectorAll('.remove-leg-btn').forEach(btn => btn.classList.remove('d-none'));
                
                // Update route numbers
                updateLegNumbers();
            });

            // Remove Leg Card Action (Delegated)
            document.getElementById('route-legs-container').addEventListener('click', (e) => {
                const removeBtn = e.target.closest('.remove-leg-btn');
                if (!removeBtn) return;

                const card = removeBtn.closest('.route-card');
                card.remove();

                const container = document.getElementById('route-legs-container');
                const cards = container.querySelectorAll('.route-card');

                // Hide remove buttons if only 1 card left
                if (cards.length <= 1) {
                    container.querySelectorAll('.remove-leg-btn').forEach(btn => btn.classList.add('d-none'));
                }

                // Update input names indices
                cards.forEach((c, idx) => {
                    c.setAttribute('data-index', idx);
                    c.querySelectorAll('[name^="items["]').forEach(input => {
                        const name = input.getAttribute('name');
                        const updatedName = name.replace(/items\[\d+\]/, `items[${idx}]`);
                        input.setAttribute('name', updatedName);
                    });
                });

                updateLegNumbers();
                calculateOverallDistance();
            });
            
            function updateLegNumbers() {
                document.querySelectorAll('#route-legs-container .leg-number').forEach((span, idx) => {
                    span.textContent = idx + 1;
                });
            }

            // Axios Form Submission Intercept
            const form = document.getElementById('employeeTravelMovementForm');
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                // Clear previous validation error highlights
                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

                const formData = new FormData(form);

                // Disable submit button
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalBtnHtml = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Submitting...';

                axios({
                    method: 'post',
                    url: form.getAttribute('action'),
                    data: formData,
                    headers: { 'Content-Type': 'multipart/form-data' }
                })
                .then(response => {
                    if (response.data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.data.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = "{{ route('movement.index') }}";
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.data.message || 'Something went wrong.'
                        });
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnHtml;
                    }
                })
                .catch(error => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnHtml;

                    if (error.response && error.response.status === 422) {
                        const errors = error.response.data.errors;
                        
                        // Highlight errors dynamically next to fields
                        Object.keys(errors).forEach(key => {
                            // Map dots in nested key names to array notation (e.g. items.0.reason -> items[0][reason])
                            let inputName = key;
                            if (key.includes('.')) {
                                const parts = key.split('.');
                                inputName = parts[0] + '[' + parts[1] + ']';
                                for (let i = 2; i < parts.length; i++) {
                                    inputName += '[' + parts[i] + ']';
                                }
                            }

                            const input = form.querySelector(`[name="${inputName}"]`);
                            if (input) {
                                input.classList.add('is-invalid');
                                const feedback = document.createElement('div');
                                feedback.className = 'invalid-feedback';
                                feedback.textContent = errors[key][0];
                                
                                // Insert feedback after input or input-group parent
                                const inputGroup = input.closest('.input-group');
                                if (inputGroup) {
                                    inputGroup.after(feedback);
                                } else {
                                    input.after(feedback);
                                }
                            }
                        });

                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error',
                            text: 'Please correct the highlighted errors before submitting.'
                        });
                    } else {
                        const errMsg = error.response && error.response.data && error.response.data.message
                            ? error.response.data.message
                            : 'An unexpected error occurred. Please try again.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errMsg
                        });
                    }
                });
            });
        });
    </script>
@endpush
