@extends('structure.master')

@section('content')
    <div class="row">
        <div class="col-12 col-lg-10 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-user-cog fa-lg me-2"></i>
                        <h4 class="mb-0 text-white font-weight-bold">
                            @if (isset($organizationStructure))
                                Edit Key Person
                            @else
                                Add Key Person
                            @endif
                        </h4>
                    </div>
                    <a href="{{ route('organization-structure.index') }}" class="btn btn-light btn-sm fw-semibold shadow-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>

                <div class="card-body p-4">
                    <form id="keyPeopleForm"
                        action="{{ isset($organizationStructure) ? route('organization-structure.update', $organizationStructure->id) : route('organization-structure.store') }}"
                        method="post" enctype="multipart/form-data">
                        @csrf
                        @if (isset($organizationStructure))
                            @method('PUT')
                        @endif

                        <!-- Section: Mode Selector -->
                        <div class="mb-4">
                            <label class="form-label fw-bold"><i class="fas fa-users text-primary me-1"></i> Person Source <span class="text-danger">*</span></label>
                            <div class="btn-group w-100" role="group" aria-label="Creation Mode Toggle">
                                <input type="radio" class="btn-check" name="creation_mode" id="mode_employee" value="employee" 
                                    {{ isset($organizationStructure) && $organizationStructure->employee_id ? 'checked' : (!isset($organizationStructure) ? 'checked' : '') }}>
                                <label class="btn btn-outline-primary py-2 fw-semibold" for="mode_employee">
                                    <i class="fas fa-id-card me-2"></i>Attach Existing Employee
                                </label>
                                <input type="radio" class="btn-check" name="creation_mode" id="mode_custom" value="custom"
                                    {{ isset($organizationStructure) && !$organizationStructure->employee_id ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary py-2 fw-semibold" for="mode_custom">
                                    <i class="fas fa-user-plus me-2"></i>Create Custom/External Person
                                </label>
                            </div>
                        </div>

                        <!-- Section: Target Level -->
                        <div class="mb-4">
                            <h5 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-sitemap me-2"></i>Target Hierarchy Level
                            </h5>
                            <div class="row">
                                <!-- Type / Level Select -->
                                <div class="col-md-6 mb-3">
                                    <label for="type" class="form-label fw-semibold">
                                        Type / Level <span class="text-danger">*</span>
                                    </label>
                                    <select name="type" id="type" class="form-select select2_list" required>
                                        <option value="">-- Select Level --</option>
                                        <option value="group"
                                            {{ isset($organizationStructure) && $organizationStructure->type_form == 'group' ? 'selected' : '' }}>
                                            Group</option>
                                        <option value="company"
                                            {{ isset($organizationStructure) && $organizationStructure->type_form == 'company' ? 'selected' : '' }}>
                                            Company</option>
                                        <option value="location"
                                            {{ isset($organizationStructure) && $organizationStructure->type_form == 'location' ? 'selected' : '' }}>
                                            Branch</option>
                                        <option value="division"
                                            {{ isset($organizationStructure) && $organizationStructure->type_form == 'division' ? 'selected' : '' }}>
                                            Division</option>
                                        <option value="department"
                                            {{ isset($organizationStructure) && $organizationStructure->type_form == 'department' ? 'selected' : '' }}>
                                            Department</option>
                                        <option value="section"
                                            {{ isset($organizationStructure) && $organizationStructure->type_form == 'section' ? 'selected' : '' }}>
                                            Section</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Group -->
                                <div class="col-md-4 mb-3 d-none" id="div_group_id">
                                    <label for="group_id" class="form-label fw-semibold">Group <span class="text-danger">*</span></label>
                                    <select name="group_id" id="group_id" class="form-select select2_list">
                                        <option value="">Select Group</option>
                                        @foreach ($groups as $group)
                                            <option value="{{ $group->id }}"
                                                {{ (isset($organizationStructure) && $organizationStructure->group_id == $group->id) || request('group_id') == $group->id ? 'selected' : '' }}>
                                                {{ $group->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Company -->
                                <div class="col-md-4 mb-3 d-none" id="div_company_id">
                                    <label for="company_id" class="form-label fw-semibold">Company <span class="text-danger">*</span></label>
                                    <select name="company_id" id="company_id" class="form-select select2_list">
                                        <option value="">Select Company</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}"
                                                {{ (isset($organizationStructure) && $organizationStructure->company_id == $company->id) || request('company_id') == $company->id ? 'selected' : '' }}>
                                                {{ $company->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Branch -->
                                <div class="col-md-4 mb-3 d-none" id="div_branch_unit_id">
                                    <label for="branch_unit_id" class="form-label fw-semibold">Branch <span class="text-danger">*</span></label>
                                    <select name="branch_unit_id" id="branch_unit_id" class="form-select select2_list">
                                        <option value="">Select Branch</option>
                                        @foreach ($locations as $loc)
                                            <option value="{{ $loc->id }}"
                                                {{ (isset($organizationStructure) && $organizationStructure->branch_unit_id == $loc->id) || request('branch_unit_id') == $loc->id ? 'selected' : '' }}>
                                                {{ $loc->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Division -->
                                <div class="col-md-4 mb-3 d-none" id="div_division_id">
                                    <label for="division_id" class="form-label fw-semibold">Division <span class="text-danger">*</span></label>
                                    <select name="division_id" id="division_id" class="form-select select2_list">
                                        <option value="">Select Division</option>
                                        @foreach ($divisions as $div)
                                            <option value="{{ $div->id }}"
                                                {{ (isset($organizationStructure) && $organizationStructure->division_id == $div->id) || request('division_id') == $div->id ? 'selected' : '' }}>
                                                {{ $div->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Department -->
                                <div class="col-md-4 mb-3 d-none" id="div_department_id">
                                    <label for="department_id" class="form-label fw-semibold">Department <span class="text-danger">*</span></label>
                                    <select name="department_id" id="department_id" class="form-select select2_list">
                                        <option value="">Select Department</option>
                                        @foreach ($departments as $dept)
                                            <option value="{{ $dept->id }}"
                                                {{ (isset($organizationStructure) && $organizationStructure->department_id == $dept->id) || request('department_id') == $dept->id ? 'selected' : '' }}>
                                                {{ $dept->department_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Section -->
                                <div class="col-md-4 mb-3 d-none" id="div_section_id">
                                    <label for="section_id" class="form-label fw-semibold">Section <span class="text-danger">*</span></label>
                                    <select name="section_id" id="section_id" class="form-select select2_list">
                                        <option value="">Select Section</option>
                                        @foreach ($sections as $sec)
                                            <option value="{{ $sec->id }}"
                                                {{ (isset($organizationStructure) && $organizationStructure->section_id == $sec->id) || request('section_id') == $sec->id ? 'selected' : '' }}>
                                                {{ $sec->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Section: Person Information -->
                        <div class="mb-4">
                            <h5 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-user-circle me-2"></i>Person Details
                            </h5>

                            <!-- Mode: Attach Employee -->
                            <div id="employee_section">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label for="employee_id" class="form-label fw-semibold">Select Employee <span class="text-danger">*</span></label>
                                        <select name="employee_id" id="employee_id" class="form-select select2_list">
                                            <option value="">-- Select Employee --</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}"
                                                    {{ isset($organizationStructure) && $organizationStructure->employee_id == $employee->id ? 'selected' : '' }}>
                                                    {{ $employee->full_name }} ({{ $employee->system_id }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Mode: Custom Person -->
                            <div id="custom_person_section" class="d-none">
                                <div class="row">
                                    @if (isset($organizationStructure) && $organizationStructure->photo_path)
                                        <div class="col-12 mb-3 text-center">
                                            <div class="mb-2">
                                                <img src="{{ \App\HelperClass::get_file_url($organizationStructure->photo_path) }}"
                                                    class="rounded-circle border border-primary border-2 shadow"
                                                    style="width: 90px; height: 90px; object-fit: cover;"
                                                    alt="Profile Image">
                                            </div>
                                            <small class="text-muted">Current Profile Photo</small>
                                        </div>
                                    @endif

                                    <!-- Name -->
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter full name"
                                            value="{{ old('name', isset($organizationStructure) ? $organizationStructure->name : '') }}">
                                    </div>

                                    <!-- Photo -->
                                    <div class="col-md-6 mb-3">
                                        <label for="photo_path" class="form-label fw-semibold">Profile Photo</label>
                                        <input type="file" name="photo_path" id="photo_path" class="form-control" accept="image/*">
                                        <small class="text-muted d-block mt-1">Format: JPG, PNG, GIF. Max: 2MB.</small>
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label fw-semibold">Email Address</label>
                                        <input type="email" name="email" id="email" class="form-control" placeholder="name@company.com"
                                            value="{{ old('email', isset($organizationStructure) ? $organizationStructure->email : '') }}">
                                    </div>

                                    <!-- Contact No -->
                                    <div class="col-md-6 mb-3">
                                        <label for="contact_no" class="form-label fw-semibold">Contact Number</label>
                                        <input type="tel" name="contact_no" id="contact_no" class="form-control" placeholder="+880 1XXX-XXXXXX"
                                            value="{{ old('contact_no', isset($organizationStructure) ? $organizationStructure->contact_no : '') }}">
                                    </div>

                                    <!-- Address -->
                                    <div class="col-md-12 mb-3">
                                        <label for="address" class="form-label fw-semibold">Address</label>
                                        <textarea name="address" id="address" class="form-control" rows="2" placeholder="Full contact address...">{{ old('address', isset($organizationStructure) ? $organizationStructure->address : '') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Shared Fields (Position & Status) -->
                            <div class="row">
                                <!-- Position -->
                                <div class="col-md-6 mb-3 position-relative">
                                    <label for="position" class="form-label fw-semibold">Position / Role <span class="text-danger">*</span></label>
                                    <input type="text" name="position" id="position" class="form-control" placeholder="e.g. Site Manager, Director" autocomplete="off"
                                        value="{{ old('position', isset($organizationStructure) ? $organizationStructure->position : '') }}" required>
                                    <div class="suggestions-list list-group position-absolute w-100" id="suggestionsList"
                                        style="z-index: 1000; display: none; max-height: 200px; overflow-y: auto;">
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                    <select class="form-select" name="status" id="status" required>
                                        <option value="active" {{ isset($organizationStructure) && $organizationStructure->status_form == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ isset($organizationStructure) && $organizationStructure->status_form == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-end gap-2 border-top pt-3 mt-4">
                            <a href="{{ route('organization-structure.index') }}" class="btn btn-secondary px-4">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="fas fa-save me-1"></i>
                                {{ isset($organizationStructure) ? 'Update Key Person' : 'Save Key Person' }}
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
        $(document).ready(function() {
            'use strict';

            // Autocomplete suggestions
            const positionSuggestions = [
                'Managing Director', 'CEO', 'CFO', 'COO', 'CTO',
                'General Manager', 'Manager', 'Senior Manager',
                'Department Head', 'Team Lead', 'Supervisor', 'Site Manager',
                'Branch Manager', 'Location Head', 'Operations Manager',
                'Group Chairman', 'Group CEO', 'Group CFO', 'Group Director'
            ];

            // 1. Initialize Select2
            $('.select2_list').select2({
                placeholder: function() {
                    return $(this).data('placeholder') || $(this).find('option:first').text();
                },
                allowClear: true,
                width: '100%'
            });

            // 2. Creation Mode Switching
            function switchCreationMode() {
                const mode = $('input[name="creation_mode"]:checked').val();
                if (mode === 'employee') {
                    $('#employee_section').removeClass('d-none');
                    $('#employee_id').prop('required', true);
                    
                    $('#custom_person_section').addClass('d-none');
                    $('#name').prop('required', false);
                } else {
                    $('#employee_section').addClass('d-none');
                    $('#employee_id').prop('required', false).val(null).trigger('change');
                    
                    $('#custom_person_section').removeClass('d-none');
                    $('#name').prop('required', true);
                }
            }

            $('input[name="creation_mode"]').on('change', switchCreationMode);
            switchCreationMode(); // initial run

            // 3. Hierarchy levels toggling
            function toggleHierarchyFields() {
                const type = $('#type').val();
                
                // Hide all
                $('#div_group_id, #div_company_id, #div_branch_unit_id, #div_division_id, #div_department_id, #div_section_id').addClass('d-none');
                $('#group_id, #company_id, #branch_unit_id, #division_id, #department_id, #section_id').prop('required', false);

                if (!type) return;

                if (type === 'group') {
                    $('#div_group_id').removeClass('d-none');
                    $('#group_id').prop('required', true);
                } else if (type === 'company') {
                    $('#div_group_id, #div_company_id').removeClass('d-none');
                    $('#group_id, #company_id').prop('required', true);
                } else if (type === 'location') {
                    $('#div_group_id, #div_company_id, #div_branch_unit_id').removeClass('d-none');
                    $('#group_id, #company_id, #branch_unit_id').prop('required', true);
                } else if (type === 'division') {
                    $('#div_group_id, #div_company_id, #div_division_id').removeClass('d-none');
                    $('#group_id, #company_id, #division_id').prop('required', true);
                } else if (type === 'department') {
                    $('#div_group_id, #div_company_id, #div_division_id, #div_department_id').removeClass('d-none');
                    $('#group_id, #company_id, #division_id, #department_id').prop('required', true);
                } else if (type === 'section') {
                    $('#div_group_id, #div_company_id, #div_division_id, #div_department_id, #div_section_id').removeClass('d-none');
                    $('#group_id, #company_id, #division_id, #department_id, #section_id').prop('required', true);
                }
            }

            $('#type').on('change', toggleHierarchyFields);
            toggleHierarchyFields(); // initial run

            // 4. Dynamic Cascading dropdowns (using standard helper endpoints)
            function loading($el, text = 'Loading...') {
                $el.prop('disabled', true).html(`<option value="">${text}</option>`);
            }

            function reset($el, text) {
                $el.prop('disabled', false).html(`<option value="">${text}</option>`);
            }

            function loadDivisions() {
                const companyId = $('#company_id').val();
                if (!companyId) return;
                const locationId = $('#branch_unit_id').val() || 'null';

                loading($('#division_id'));
                reset($('#department_id'), 'Select Department');
                reset($('#section_id'), 'Select Section');

                $.get(`/get-divisions/${companyId}/${locationId}`, function(data) {
                    reset($('#division_id'), 'Select Division');
                    if (data && data.length) {
                        $.each(data, function(_, item) {
                            $('#division_id').append(`<option value="${item.id}">${item.name}</option>`);
                        });
                    }
                    loadDepartments();
                });
            }

            function loadDepartments() {
                const companyId = $('#company_id').val();
                if (!companyId) return;
                const locationId = $('#branch_unit_id').val() || 'null';
                const divisionId = $('#division_id').val() || 'null';

                loading($('#department_id'));
                reset($('#section_id'), 'Select Section');

                $.get(`/get-departments/${companyId}/${locationId}/${divisionId}`, function(data) {
                    reset($('#department_id'), 'Select Department');
                    if (data && data.length) {
                        $.each(data, function(_, item) {
                            $('#department_id').append(`<option value="${item.id}">${item.department_name}</option>`);
                        });
                    }
                    loadSections();
                });
            }

            function loadSections() {
                const companyId = $('#company_id').val();
                if (!companyId) return;
                const locationId = $('#branch_unit_id').val() || 'null';
                const divisionId = $('#division_id').val() || 'null';
                const departmentId = $('#department_id').val() || 'null';

                loading($('#section_id'));

                $.get(`/get-sections/${companyId}/${locationId}/${divisionId}/${departmentId}`, function(data) {
                    reset($('#section_id'), 'Select Section');
                    if (data && data.length) {
                        $.each(data, function(_, item) {
                            $('#section_id').append(`<option value="${item.id}">${item.name}</option>`);
                        });
                    }
                });
            }

            // Company change event
            $('#company_id').on('change', function() {
                const companyId = $(this).val();
                if (!companyId) return;

                reset($('#division_id'), 'Select Division');
                reset($('#department_id'), 'Select Department');
                reset($('#section_id'), 'Select Section');

                // Load branch
                loading($('#branch_unit_id'));
                $.get(`/get-units/${companyId}`, function(data) {
                    reset($('#branch_unit_id'), 'Select Branch');
                    if (data && data.length) {
                        $.each(data, function(_, item) {
                            $('#branch_unit_id').append(`<option value="${item.id}">${item.name}</option>`);
                        });
                    }
                    loadDivisions();
                }).fail(function() {
                    reset($('#branch_unit_id'), 'Select Branch');
                    loadDivisions();
                });
            });

            $('#branch_unit_id').on('change', loadDivisions);
            $('#division_id').on('change', loadDepartments);
            $('#department_id').on('change', loadSections);

            // 5. Position autocomplete
            const positionInput = document.getElementById('position');
            const suggestionsList = document.getElementById('suggestionsList');

            if (positionInput) {
                positionInput.addEventListener('input', (e) => {
                    const value = e.target.value.trim();
                    if (value.length > 0) {
                        const suggestions = positionSuggestions.filter(d =>
                            d.toLowerCase().includes(value.toLowerCase())
                        );

                        if (suggestions.length > 0) {
                            suggestionsList.innerHTML = '';
                            suggestions.forEach(suggestion => {
                                const item = document.createElement('a');
                                item.href = '#';
                                item.className = 'list-group-item list-group-item-action';
                                item.innerHTML = `<i class="fas fa-briefcase text-muted me-2"></i>${suggestion}`;
                                item.addEventListener('click', (e) => {
                                    e.preventDefault();
                                    positionInput.value = suggestion;
                                    suggestionsList.style.display = 'none';
                                });
                                suggestionsList.appendChild(item);
                            });
                            suggestionsList.style.display = 'block';
                        } else {
                            suggestionsList.style.display = 'none';
                        }
                    } else {
                        suggestionsList.style.display = 'none';
                    }
                });

                positionInput.addEventListener('blur', () => {
                    setTimeout(() => { suggestionsList.style.display = 'none'; }, 200);
                });
            }

            // 6. Axios Form Submission
            const form = document.getElementById('keyPeopleForm');
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const submitBtn = form.querySelector('[type="submit"]');
                submitBtn.disabled = true;

                // Clear previous errors
                form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

                const formData = new FormData(form);
                const url = form.getAttribute('action');

                axios({
                    method: 'post',
                    url: url,
                    data: formData
                })
                .then(response => {
                    if (response.data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.data.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = response.data.redirect;
                        });
                    }
                })
                .catch(error => {
                    submitBtn.disabled = false;
                    if (error.response && error.response.status === 422) {
                        const errors = error.response.data.errors;
                        Object.keys(errors).forEach(key => {
                            // Find matching input/select (handle Select2 container too)
                            let input = form.querySelector(`[name="${key}"]`);
                            if (input) {
                                input.classList.add('is-invalid');
                                
                                const feedback = document.createElement('div');
                                feedback.className = 'invalid-feedback d-block';
                                feedback.innerText = errors[key][0];
                                
                                // If select2 is initialized, append feedback after the select2-container
                                if (input.classList.contains('select2_list') || input.id === 'employee_id') {
                                    const select2Container = $(input).next('.select2-container');
                                    if (select2Container.length) {
                                        select2Container.after(feedback);
                                        return;
                                    }
                                }
                                input.after(feedback);
                            }
                        });
                        toastr.error('Please correct the validation errors below.');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.response?.data?.message || 'Something went wrong. Please try again later.'
                        });
                    }
                });
            });
        });
    </script>
@endpush
