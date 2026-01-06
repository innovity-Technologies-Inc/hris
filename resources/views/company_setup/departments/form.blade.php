@extends('structure.master')
@section('content')
    {{--    Form --}}

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">{{ isset($department) ? 'Edit' : 'Add' }} Department</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form
                                action="{{ isset($department) ? route('departments.update', $department->id) : route('departments.store') }}"
                                method="post">
                                @csrf
                                @if (isset($department))
                                    @method('PUT')
                                @endif

                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label for="department_name" class="form-label">Department Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="department_name" class="form-control"
                                            name="department_name" placeholder="Enter Department Name"
                                            value="{{ isset($department) ? $department->department_name : old('department_name') }}"
                                            required maxlength="255">
                                        @error('department_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="short_name" class="form-label">Short Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="short_name" class="form-control" name="short_name"
                                            placeholder="Enter Short Name"
                                            value="{{ isset($department) ? $department->short_name : old('short_name') }}"
                                            required maxlength="50">
                                        @error('short_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 mb-2">
                                        <label for="company_id" class="form-label">Company <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select select2_list" name="company_id" id="company_id" required>
                                            <option value="">Choose Company</option>
                                            @foreach ($companies as $item)
                                                <option value="{{ $item->id }}"
                                                    @if (isset($department) && $department->company_id == $item->id) selected @endif>
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('company_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    @if(\App\HelperClass::getGeneralSetting()->branch_status == '1')
                                    <div class="col-md-3 mb-2">
                                        <label for="location_id" class="form-label">Branch <span
                                                class="text-danger">*</span></label>
                                        <select id="location_id" class="form-select select2_list" name="location_id">
                                            <option value="">Select Branch</option>
                                            @foreach ($locations as $location)
                                                <option value="{{ $location->id }}"
                                                    {{ isset($department) && $department->location_id == $location->id ? 'selected' : '' }}>
                                                    {{ $location->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('location_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    @endif
                                    @if(\App\HelperClass::getGeneralSetting()->division_status == '1')
                                    <div class="col-md-3 mb-2">
                                        <label for="division_id" class="form-label">Division <span
                                                class="text-danger">*</span></label>
                                        <select id="division_id" class="form-select select2_list" name="division_id"
                                            required>
                                            <option value="">Select Division</option>
                                            @foreach ($divisions as $division)
                                                <option value="{{ $division->id }}"
                                                    @if (isset($department) && $department->division_id == $division->id) selected @endif>
                                                    {{ $division->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('division_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-select" name="status">
                                            <option value="active" @if (isset($department) && $department->status == 'active') selected @endif>Active
                                            </option>
                                            <option value="inactive" @if (isset($department) && $department->status == 'inactive') selected @endif>
                                                Inactive</option>
                                        </select>
                                    </div>
                                </div>




                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {

            function loading($el, text = 'Loading...') {
                $el.prop('disabled', true).html(`<option value="">${text}</option>`);
            }

            function reset($el, text) {
                $el.prop('disabled', false).html(`<option value="">${text}</option>`);
            }

            // -------------------------
            // Load Divisions (reusable)
            // -------------------------
            function loadDivisions() {
                const companyId = $('#company_id').val();
                if (!companyId) return;

                const locationId = $('#location_id').val() || 'null';

                loading($('#division_id'));

                $.get(`/get-divisions/${companyId}/${locationId}`, function (data) {
                    reset($('#division_id'), 'Select Division');

                    if (!data.length) {
                        $('#division_id').html('<option value="">No division found</option>');
                        return;
                    }

                    $.each(data, function (_, item) {
                        $('#division_id').append(`<option value="${item.id}">${item.name}</option>`);
                    });
                });
            }

            // -------------------------
            // Company Change
            // -------------------------
            $('#company_id').on('change', function () {
                const companyId = $(this).val();
                if (!companyId) return;

                @if(\App\HelperClass::getGeneralSetting()->branch_status == '1')
                loading($('#location_id'));
                reset($('#division_id'), 'Select Division');
                @else
                reset($('#division_id'), 'Select Division');
                @endif

                @if(\App\HelperClass::getGeneralSetting()->branch_status == '1')
                $.get(`/get-units/${companyId}`, function (data) {
                    reset($('#location_id'), 'Select Branch');

                    if (!data.length) {
                        $('#location_id').html('<option value="">No branch found</option>');
                    } else {
                        $.each(data, function (_, item) {
                            $('#location_id').append(`<option value="${item.id}">${item.name}</option>`);
                        });
                    }

                    // After branches are loaded → load divisions (no location filter yet)
                    loadDivisions();
                });
                @else
                // Branch feature disabled → load divisions directly
                loadDivisions();
                @endif
            });

            // -------------------------
            // Branch Change → Reload Divisions
            // -------------------------
            $('#location_id').on('change', function () {
                loadDivisions();
            });

            // Optional: Auto-trigger for edit mode (pre-selected values)
            @if(isset($section) && $section->company_id)
            $('#company_id').trigger('change');

            @if(\App\HelperClass::getGeneralSetting()->branch_status == '1' && ($section->location_id ?? false))
            setTimeout(function() {
                $('#location_id').val('{{ $section->location_id }}').trigger('change');
            }, 500);
            @endif
            @endif

        });
    </script>
@endpush
