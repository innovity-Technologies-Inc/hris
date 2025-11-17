@extends('structure.master')
@section('content')
<div class="container-fluid mt-4">
    {{-- Page Header --}}
    <div class="mb-4">
        <h4 class="fw-semibold">
            <i class="mdi mdi-calendar-clock text-primary me-2"></i>Create Roster Plan
        </h4>
        <p class="text-muted small mb-0">Define a roster plan with shifts and repetition settings</p>
    </div>

    <form method="POST" action="{{isset($plan) ? route('plans.roster_plans.update', $plan->id ) : route('plans.roster_plans.store')}}" id="rosterPlanForm">
        @csrf
        @if(isset($plan))
            @method('put')
        @endif

        {{-- Basic Roster Information --}}
        <div class="card border mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0 fw-semibold">
                    <i class="mdi mdi-information-outline text-primary me-2"></i>Basic Roster Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- Plan Name --}}
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label fw-semibold">
                            Plan Name <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            id="name"
                            name="name"
                            placeholder="E.g., Weekly Day-Night Rotation"
                            value="{{ isset($plan) ? $plan->name : old('name') }}"
                            required>
                        @error('name')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Short Name --}}
                    <div class="col-md-6 mb-3">
                        <label for="short_name" class="form-label fw-semibold">
                            Short Name
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            id="short_name"
                            name="short_name"
                            placeholder="E.g., WDN"
                            value="{{ isset($plan) ? $plan->short_name : old('short_name') }}"
                            maxlength="10">
                        <small class="text-muted">Optional abbreviated name (max 10 characters)</small>
                        @error('short_name')
                            <span class="text-danger small d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    {{-- Repetition Days --}}
                    <div class="col-md-6 mb-3">
                        <label for="swapping" class="form-label fw-semibold">
                            Repetition (Days) <span class="text-danger">*</span>
                        </label>
                        <input
                            type="number"
                            class="form-control"
                            id="swapping"
                            name="swapping"
                            placeholder="E.g., 7"
                            value="{{ isset($plan) ? $plan->swapping : old('swapping') }}"
                            min="1"
                            required>
                        <small class="text-muted">Number of days before the roster pattern repeats</small>
                        @error('swapping')
                            <span class="text-danger small d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label fw-semibold">
                            Status <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="">Select Status</option>
                            <option value="active" {{ isset($plan) && $plan->status == 'active' ? 'selected' : '' }} {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ isset($plan) && $plan->status == 'inactive' ? 'selected' : '' }} {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    {{-- Description --}}
                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label fw-semibold">
                            Description
                        </label>
                        <textarea
                            class="form-control"
                            id="description"
                            name="description"
                            rows="3"
                            placeholder="Optional notes about this roster plan">{{ isset($plan) ? $plan->description :  old('description') }}</textarea>
                        @error('description')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Shift Assignment --}}
        <div class="card border mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0 fw-semibold">
                    <i class="mdi mdi-clock-outline text-success me-2"></i>Shift Assignment
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- Shift 1 Selection --}}
                    <div class="col-md-6 mb-3">
                        <label for="first_shift_id" class="form-label fw-semibold">
                            Shift 1 <span class="text-danger">*</span>
                        </label>
                        <select class="form-select shift-select" id="first_shift_id" name="first_shift_id" data-target="shift_1_details" required>
                            <option value="">Select Shift 1</option>
                            @foreach($shifts as $item)
                                <option value="{{$item->id}}" {{isset($plan) && $plan->first_shift_id == $item->id ? 'selected' : ''}}>{{$item->name}}</option>
                            @endforeach
                        </select>
                        @error('first_shift_id')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror

                        {{-- Dynamic Shift 1 Details Container --}}
                        <div id="shift_1_details" class="shift-details mt-3 d-none">
                            <div class="border rounded p-3 bg-light">
                                <h6 class="fw-semibold mb-3 text-primary">
                                    <i class="mdi mdi-information me-1"></i>Shift Details
                                </h6>
                                <div class="shift1Details">
                                    {{-- JavaScript will populate this --}}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Shift 2 Selection --}}
                    <div class="col-md-6 mb-3">
                        <label for="second_shift_id" class="form-label fw-semibold">
                            Shift 2
                        </label>
                        <select class="form-select shift-select" id="second_shift_id" name="second_shift_id" data-target="shift_2_details">
                            <option value="">Select Shift 2</option>
                            @foreach($shifts as $item)
                                <option value="{{$item->id}}" {{isset($plan) && $plan->second_shift_id == $item->id ? 'selected' : ''}}>{{$item->name}}</option>
                            @endforeach
                        </select>
                        @error('second_shift_id')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror

                        {{-- Dynamic Shift 2 Details Container --}}
                        <div id="shift_2_details" class="shift-details mt-3 d-none">
                            <div class="border rounded p-3 bg-light">
                                <h6 class="fw-semibold mb-3 text-primary">
                                    <i class="mdi mdi-information me-1"></i>Shift Details
                                </h6>
                                <div class="shift-info">
                                    {{-- JavaScript will populate this --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Actions --}}
        <div class="d-flex justify-content-end gap-2 mb-4">
            <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                <i class="mdi mdi-arrow-left me-1"></i>Cancel
            </button>
            <button type="submit" class="btn btn-warning">
                <i class="mdi mdi-content-save me-1"></i>Save Roster Plan
            </button>
        </div>
    </form>
</div>

<script src="{{asset('assets/libs/jquery/jquery.min.js')}}"></script>
<script>
    $(function () {

        function loadShiftDetails(shiftId, targetBox) {

            if (!shiftId) {
                $("#" + targetBox).addClass("d-none")
                    .find(".shift1Details, .shift-info").html("");
                return;
            }

            $.get('/get-shift-details/' + shiftId, function (response) {

                if (!response || !response.shift) {
                    console.error("Shift not found");
                    return;
                }

                let shift = response.shift;

                let html = `
                <div class="row">
                    <div class="col-6">
                        <p class="text-muted mb-1 small">Shift Name</p>
                        <p class="fw-semibold mb-0">${shift.name}</p>
                    </div>
                    <div class="col-6">
                        <p class="text-muted mb-1 small">Duration</p>
                        <p class="fw-semibold mb-0">${shift.clock_in_time} - ${shift.clock_out_time}</p>
                    </div>
                </div>
            `;

                $("#" + targetBox)
                    .removeClass("d-none")
                    .find(".shift1Details, .shift-info")
                    .html(html);
            });
        }

        // -----------------------------
        // Handle dropdown changes
        // -----------------------------
        $(".shift-select").on("change", function () {
            let shiftId = $(this).val();
            let targetBox = $(this).data("target");
            loadShiftDetails(shiftId, targetBox);
        });

        // -----------------------------
        // Auto-load values for EDIT & OLD DATA
        // -----------------------------
        @if(isset($plans))
        // EDIT MODE
        let shift1 = "{{ old('first_shift_id', $plans->first_shift_id ?? '') }}";
        let shift2 = "{{ old('second_shift_id', $plans->second_shift_id ?? '') }}";

        if (shift1) {
            loadShiftDetails(shift1, 'shift_1_details');
            $('#first_shift_id').val(shift1);
        }

        if (shift2) {
            loadShiftDetails(shift2, 'shift_2_details');
            $('#second_shift_id').val(shift2);
        }
        @else
        // CREATE MODE WITH OLD VALUES
        let oldShift1 = "{{ old('first_shift_id') }}";
        let oldShift2 = "{{ old('second_shift_id') }}";

        if (oldShift1) {
            loadShiftDetails(oldShift1, 'shift_1_details');
            $('#first_shift_id').val(oldShift1);
        }

        if (oldShift2) {
            loadShiftDetails(oldShift2, 'shift_2_details');
            $('#second_shift_id').val(oldShift2);
        }
        @endif

    });
</script>


@endsection
