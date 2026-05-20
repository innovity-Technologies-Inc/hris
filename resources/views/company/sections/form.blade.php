@extends('structure.master')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">
                        {{ isset($section) ? 'Edit' : 'Add' }} Section
                    </h5>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form
                        action="{{ isset($section) ? route('sections.update', $section->id) : route('sections.store') }}"
                        method="POST">
                        @csrf
                        @isset($section)
                            @method('PUT')
                        @endisset

                        <div class="row">

                            {{-- Section Name --}}
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Section Name *</label>
                                <input type="text" name="name" class="form-control"
                                       value="{{ old('name', $section->name ?? '') }}" required>
                            </div>

                            {{-- Short Name --}}
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Short Name *</label>
                                <input type="text" name="short_name" class="form-control"
                                       value="{{ old('short_name', $section->short_name ?? '') }}" required>
                            </div>

                            {{-- Company --}}
                            <div class="col-md-4 mb-2">
                                <label class="form-label">Company *</label>
                                <select id="company_id" name="company_id" class="form-select select2_list" required>
                                    <option value="">Select Company</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}"
                                            {{ old('company_id', $section->company_id ?? '') == $company->id ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Location --}}
                            @if(\App\HelperClass::getGeneralSetting()->branch_status == '1')
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">Branch</label>
                                    <select id="location_id" name="location_id" class="form-select select2_list">
                                        <option value="">Select Branch</option>
                                        @foreach($locations as $location)
                                            <option value="{{ $location->id }}"
                                                {{ old('location_id', $section->location_id ?? '') == $location->id ? 'selected' : '' }}>
                                                {{ $location->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            {{-- Division --}}
                            @if(\App\HelperClass::getGeneralSetting()->division_status == '1')
                                <div class="col-md-4 mb-2">
                                    <label class="form-label">Division</label>
                                    <select id="division_id" name="division_id" class="form-select select2_list">
                                        <option value="">Select Division</option>
                                        @foreach($divisions as $division)
                                            <option value="{{ $division->id }}"
                                                {{ old('division_id', $section->division_id ?? '') == $division->id ? 'selected' : '' }}>
                                                {{ $division->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            {{-- Department --}}
                            @if(\App\HelperClass::getGeneralSetting()->department_status == '1')
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">Department</label>
                                    <select id="department_id" name="department_id" class="form-select select2_list">
                                        <option value="">Select Department</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}"
                                                {{ old('department_id', $section->department_id ?? '') == $department->id ? 'selected' : '' }}>
                                                {{ $department->department_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            {{-- Status --}}
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <option value="active" {{ ($section->status ?? '') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ ($section->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                        </div>

                        <div class="mt-3">
                            <button class="btn btn-primary">Submit</button>
                        </div>

                    </form>
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

            // Function to load divisions (reusable)
            function loadDivisions() {
                const companyId = $('#company_id').val();
                if (!companyId) return;

                const locationId = $('#location_id').val() || 'null';

                loading($('#division_id'));
                reset($('#department_id'), 'Select Department');

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

            // Company → Branch + Divisions
            $('#company_id').on('change', function () {
                const companyId = $(this).val();
                if (!companyId) return;

                @if(\App\HelperClass::getGeneralSetting()->branch_status == '1')
                loading($('#location_id'));
                reset($('#division_id'), 'Select Division');
                @endif
                reset($('#department_id'), 'Select Department');

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
                    // After branches loaded, load divisions (with location_id empty)
                    loadDivisions();
                });
                @else
                // If no branch feature, load divisions directly on company change
                loadDivisions();
                @endif
            });

            // Branch → Divisions
            $('#location_id').on('change', function () {
                loadDivisions();  // Re-load divisions filtered by selected branch
            });

            // Company + Branch + Division → Department
            $('#company_id, #location_id, #division_id').on('change', function () {
                const companyId = $('#company_id').val();
                if (!companyId) return;

                const locationId = $('#location_id').val() || 'null';
                const divisionId = $('#division_id').val() || 'null';

                loading($('#department_id'));

                $.get(`/get-departments/${companyId}/${locationId}/${divisionId}`, function (data) {
                    reset($('#department_id'), 'Select Department');
                    if (!data.length) {
                        $('#department_id').html('<option value="">No department found</option>');
                        return;
                    }
                    $.each(data, function (_, item) {
                        $('#department_id').append(`<option value="${item.id}">${item.department_name}</option>`);
                    });
                });
            });
        });
    </script>
@endpush

