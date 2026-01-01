@extends('structure.master')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">

                <form id="employeeMovementForm"
                      method="POST"
                      action="{{ isset($movement) ? route('movement.update', $movement->id) : route('movement.store') }}">

                    @csrf
                    @isset($movement)
                        @method('PUT')
                    @endisset

                    <div class="row g-4">

                        {{-- Employee --}}
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Employee <span class="text-danger">*</span></label>
                            <select name="employee_id" class="form-select select2_list" required>
                                <option value="">Select Employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}"
                                        {{ old('employee_id', $movement->employee_id ?? '') == $employee->id ? 'selected' : '' }}>
                                        {{ $employee->full_name }} ({{ $employee->applicant_id }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Dates --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">From Date & Time</label>
                            <input type="datetime-local" id="from_date" name="from_date"
                                   value="{{ old('from_date', isset($movement) ? $movement->from_date : '') }}"
                                   class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">To Date & Time</label>
                            <input type="datetime-local" id="to_date" name="to_date"
                                   value="{{ old('to_date', isset($movement) ? $movement->to_date : '') }}"
                                   class="form-control" required>
                        </div>

                        {{-- Source --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Source Address</label>
                            <input type="text" id="source_address" name="source_address"
                                   value="{{ old('source_address', $movement->source_address ?? '') }}"
                                   class="form-control" required>

                            <input type="hidden" id="source_lat" name="source_lat"
                                   value="{{ old('source_lat', $movement->source_lat ?? '') }}">
                            <input type="hidden" id="source_lng" name="source_lng"
                                   value="{{ old('source_lng', $movement->source_lng ?? '') }}">
                        </div>

                        {{-- Destination --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Destination Address</label>
                            <input type="text" id="destination_address" name="destination_address"
                                   value="{{ old('destination_address', $movement->destination_address ?? '') }}"
                                   class="form-control" required>

                            <input type="hidden" id="dest_lat" name="dest_lat"
                                   value="{{ old('dest_lat', $movement->dest_lat ?? '') }}">
                            <input type="hidden" id="dest_lng" name="dest_lng"
                                   value="{{ old('dest_lng', $movement->dest_lng ?? '') }}">
                        </div>

                        {{-- Distance --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Distance (KM)</label>
                            <input type="number" id="covered_distance" name="distance"
                                   value="{{ old('distance', $movement->distance ?? '') }}"
                                   class="form-control" step="0.01" readonly required>
                        </div>

                        {{-- TA --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">TA Plan</label>
                            <select id="ta_plan_id" name="ta_plan_id" class="form-select" required>
                                <option value="">Select TA Plan</option>
                                @foreach($taPlans as $plan)
                                    <option value="{{ $plan->id }}"
                                            data-rate="{{ $plan->remuneration }}"
                                        {{ old('ta_plan_id', $movement->ta_plan_id ?? '') == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name }} (৳{{ $plan->remuneration }}/KM)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- DA --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">DA Plan</label>
                            <select id="da_plan_id" name="da_plan_id" class="form-select" required>
                                <option value="">Select DA Plan</option>
                                @foreach($daPlans as $plan)
                                    <option value="{{ $plan->id }}"
                                            data-rate="{{ $plan->remuneration }}"
                                        {{ old('da_plan_id', $movement->da_plan_id ?? '') == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name }} (৳{{ $plan->remuneration }}/Day)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Summary --}}
                        <div class="col-md-12">
                            <div class="border rounded bg-light p-3">
                                <div class="row text-center">
                                    <div class="col-md-3">
                                        <small>Total Days</small>
                                        <h5 id="total_days">{{ old('total_days', $movement->total_days ?? 0) }}</h5>
                                    </div>
                                    <div class="col-md-3">
                                        <small>Total TA</small>
                                        <h5 id="total_ta">৳{{ number_format(old('total_ta', $movement->total_ta ?? 0),2) }}</h5>
                                    </div>
                                    <div class="col-md-3">
                                        <small>Total DA</small>
                                        <h5 id="total_da">৳{{ number_format(old('total_da', $movement->total_da ?? 0),2) }}</h5>
                                    </div>
                                    <div class="col-md-3">
                                        <small>Total Allowance</small>
                                        <h5 id="total_allowance">৳{{ number_format(old('total_allowance', $movement->total_allowance ?? 0),2) }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Hidden DB Fields --}}
                        <input type="hidden" name="total_days" id="total_days_input"
                               value="{{ old('total_days', $movement->total_days ?? 0) }}">
                        <input type="hidden" name="total_ta" id="total_ta_input"
                               value="{{ old('total_ta', $movement->total_ta ?? 0) }}">
                        <input type="hidden" name="total_da" id="total_da_input"
                               value="{{ old('total_da', $movement->total_da ?? 0) }}">
                        <input type="hidden" name="total_allowance" id="total_allowance_input"
                               value="{{ old('total_allowance', $movement->total_allowance ?? 0) }}">

                        {{-- Reason --}}
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Reason</label>
                            <textarea name="reason" class="form-control" rows="3" required>{{ old('reason', $movement->reason ?? '') }}</textarea>
                        </div>

                        {{-- Status --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select" required>
                                @foreach($statusOptions as $status)
                                    <option value="{{ $status['value'] }}"
                                        {{ old('status', $movement->status ?? '') == $status['value'] ? 'selected' : '' }}>
                                        {{ $status['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary">
                            {{ isset($movement) ? 'Update Movement' : 'Submit Movement' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&libraries=places&callback=initAutocomplete"
        async defer></script>

    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&libraries=places"></script>
    <script>
        /* ======================================================
           GLOBAL VARIABLES
        ====================================================== */
        let sourcePlace = null;
        let destinationPlace = null;
        let geocoder = null;

        /* ======================================================
           HELPER: BUILD PLACE FROM LAT/LNG
        ====================================================== */
        function buildPlaceFromLatLng(lat, lng) {
            return {
                geometry: { location: new google.maps.LatLng(lat, lng) }
            };
        }

        /* ======================================================
           INIT AUTOCOMPLETE (CLASSIC – STABLE)
        ====================================================== */
        function initAutocomplete() {
            geocoder = new google.maps.Geocoder();

            const sourceInput = document.getElementById('source_address');
            const destInput   = document.getElementById('destination_address');

            if (!google.maps.places) {
                initManualGeocoding();
                return;
            }

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

                source_lat.value = place.geometry.location.lat();
                source_lng.value = place.geometry.location.lng();
                forceRecalculateFromInputs();
            });

            destAutocomplete.addListener('place_changed', () => {
                const place = destAutocomplete.getPlace();
                if (!place.geometry) return;

                dest_lat.value = place.geometry.location.lat();
                dest_lng.value = place.geometry.location.lng();
                forceRecalculateFromInputs();
            });
        }

        /* ======================================================
           MANUAL GEOCODING FALLBACK
        ====================================================== */
        function initManualGeocoding() {
            function debounce(fn, delay = 600) {
                let t;
                return (...args) => {
                    clearTimeout(t);
                    t = setTimeout(() => fn.apply(this, args), delay);
                };
            }

            async function geocodeAddress(address) {
                return new Promise((resolve, reject) => {
                    geocoder.geocode({ address: address + ', Bangladesh' }, (res, status) => {
                        if (status === 'OK' && res[0]) resolve(res[0]);
                        else reject(status);
                    });
                });
            }

            ['source_address', 'destination_address'].forEach(id => {
                const input = document.getElementById(id);
                input.addEventListener('input', debounce(async function () {
                    if (!this.value) return;
                    try {
                        const place = await geocodeAddress(this.value);

                        if (id === 'source_address') {
                            source_lat.value = place.geometry.location.lat();
                            source_lng.value = place.geometry.location.lng();
                        } else {
                            dest_lat.value = place.geometry.location.lat();
                            dest_lng.value = place.geometry.location.lng();
                        }

                        forceRecalculateFromInputs();
                    } catch {}
                }));
            });
        }

        /* ======================================================
           FORCE REBUILD PLACES FROM LAT/LNG
        ====================================================== */
        function forceRecalculateFromInputs() {
            const sLat = parseFloat(source_lat.value);
            const sLng = parseFloat(source_lng.value);
            const dLat = parseFloat(dest_lat.value);
            const dLng = parseFloat(dest_lng.value);

            if (isNaN(sLat) || isNaN(sLng) || isNaN(dLat) || isNaN(dLng)) return;

            sourcePlace = buildPlaceFromLatLng(sLat, sLng);
            destinationPlace = buildPlaceFromLatLng(dLat, dLng);

            calculateDistance();
        }

        /* ======================================================
           DISTANCE MATRIX (CLASSIC)
        ====================================================== */
        function calculateDistance() {
            if (!sourcePlace || !destinationPlace) return;

            const service = new google.maps.DistanceMatrixService();
            service.getDistanceMatrix({
                origins: [sourcePlace.geometry.location],
                destinations: [destinationPlace.geometry.location],
                travelMode: 'DRIVING',
                unitSystem: google.maps.UnitSystem.METRIC
            }, (res, status) => {
                if (status !== 'OK' || res.rows[0].elements[0].status !== 'OK') {
                    return calculateStraightDistance();
                }

                covered_distance.value = (res.rows[0].elements[0].distance.value / 1000).toFixed(2);
                calculateAllowance();
            });
        }

        /* ======================================================
           STRAIGHT-LINE DISTANCE FALLBACK
        ====================================================== */
        function calculateStraightDistance() {
            const R = 6371;
            const lat1 = sourcePlace.geometry.location.lat();
            const lon1 = sourcePlace.geometry.location.lng();
            const lat2 = destinationPlace.geometry.location.lat();
            const lon2 = destinationPlace.geometry.location.lng();

            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat/2)**2 + Math.cos(lat1*Math.PI/180)*Math.cos(lat2*Math.PI/180)*Math.sin(dLon/2)**2;

            covered_distance.value = (R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)) * 1.3).toFixed(2);
            calculateAllowance();
        }

        /* ======================================================
           ALLOWANCE CALCULATION + DB SYNC
        ====================================================== */
        function calculateAllowance() {
            const distance = parseFloat(covered_distance.value) || 0;
            const taRate = ta_plan_id.selectedOptions[0]?.dataset.rate || 0;
            const daRate = da_plan_id.selectedOptions[0]?.dataset.rate || 0;

            const from = new Date(from_date.value);
            const to = new Date(to_date.value);

            const days = (!isNaN(from) && !isNaN(to) && to >= from)
                ? Math.max(1, Math.ceil((to - from) / 86400000))
                : 0;

            const totalTa = distance * taRate;
            const totalDa = days * daRate;
            const total = totalTa + totalDa;

            // Update UI
            total_days.textContent = days;
            total_ta.textContent = `৳${totalTa.toFixed(2)}`;
            total_da.textContent = `৳${totalDa.toFixed(2)}`;
            total_allowance.textContent = `৳${total.toFixed(2)}`;

            // Update hidden fields for DB
            total_days_input.value = days;
            total_ta_input.value = totalTa.toFixed(2);
            total_da_input.value = totalDa.toFixed(2);
            total_allowance_input.value = total.toFixed(2);
        }

        /* ======================================================
           DOM READY
        ====================================================== */
        document.addEventListener('DOMContentLoaded', () => {
            initAutocomplete();
            initManualGeocoding();

            // Listen to TA / DA / Date changes
            ['from_date','to_date','ta_plan_id','da_plan_id']
                .forEach(id => document.getElementById(id)
                    ?.addEventListener('change', calculateAllowance));

            // In edit mode: force distance calculation on page load
            if (source_lat.value && dest_lat.value) {
                forceRecalculateFromInputs();
            }
        });
    </script>
@endpush
