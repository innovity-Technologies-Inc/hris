@extends('structure.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-users-cog me-2"></i>
                            <h5 class="mb-0">{{ isset($organizationStructure) ? 'Edit' : 'Add' }} Organization Key Member
                            </h5>
                        </div>
                        <a href="{{ route('organization-structure.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Back to List
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form id="keyMemberForm"
                        action="{{ isset($organizationStructure) ? route('organization-structure.update', $organizationStructure->id) : route('organization-structure.store') }}"
                        method="post" enctype="multipart/form-data">
                        @csrf
                        @if (isset($organizationStructure))
                            @method('PUT')
                        @endif

                        <!-- Organization Hierarchy Section -->
                        <div class="mb-4">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-sitemap me-2"></i>Organization Hierarchy
                            </h6>
                            <div class="row">
                                <!-- Type Selector -->
                                <div class="col-md-6 mb-3">
                                    <label for="typeSelect" class="form-label fw-semibold">
                                        <i class="fas fa-layer-group text-info me-1"></i>
                                        Organization Type <span class="text-danger">*</span>
                                    </label>
                                    <select name="type" id="typeSelect" class="form-select" required>
                                        <option value="">-- Select Type --</option>
                                        <option value="group"
                                            {{ isset($organizationStructure) && $organizationStructure->type_form == 'group' ? 'selected' : '' }}>
                                            <i class="fas fa-users"></i> Group
                                        </option>
                                        <option value="company"
                                            {{ isset($organizationStructure) && $organizationStructure->type_form == 'company' ? 'selected' : '' }}>
                                            Company
                                        </option>
                                        <option value="location"
                                            {{ isset($organizationStructure) && $organizationStructure->type_form == 'location' ? 'selected' : '' }}>
                                            Location
                                        </option>
                                        <option value="division"
                                            {{ isset($organizationStructure) && $organizationStructure->type_form == 'division' ? 'selected' : '' }}>
                                            Division
                                        </option>
                                        <option value="department"
                                            {{ isset($organizationStructure) && $organizationStructure->type_form == 'department' ? 'selected' : '' }}>
                                            Department
                                        </option>
                                        <option value="section"
                                            {{ isset($organizationStructure) && $organizationStructure->type_form == 'section' ? 'selected' : '' }}>
                                            Section
                                        </option>
                                    </select>
                                    @error('type')
                                        <small class="text-danger"><i
                                                class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Group -->
                                <div class="col-md-6 mb-3" id="groupField" style="display: none;">
                                    <label for="groupSelect" class="form-label fw-semibold">
                                        <i class="fas fa-users text-primary me-1"></i>
                                        Group <span class="text-danger">*</span>
                                    </label>
                                    <select name="group_id" id="groupSelect" class="form-select select2_list">
                                        <option value="">-- Select Group --</option>
                                        @foreach ($groups as $group)
                                            <option value="{{ $group->id }}"
                                                {{ isset($organizationStructure) && $organizationStructure->group_id == $group->id ? 'selected' : '' }}>
                                                {{ $group->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('group_id')
                                        <small class="text-danger"><i
                                                class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Company -->
                                <div class="col-md-6 mb-3" id="companyField" style="display: none;">
                                    <label for="companySelect" class="form-label fw-semibold">
                                        <i class="fas fa-building text-success me-1"></i>
                                        Company <span class="text-danger">*</span>
                                    </label>
                                    <select name="company_id" id="companySelect" class="form-select select2_list">
                                        <option value="">-- Select Company --</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}"
                                                {{ isset($organizationStructure) && $organizationStructure->company_id == $company->id ? 'selected' : '' }}>
                                                {{ $company->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('company_id')
                                        <small class="text-danger"><i
                                                class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Location -->
                                <div class="col-md-6 mb-3" id="locationField" style="display: none;">
                                    <label for="locationSelect" class="form-label fw-semibold">
                                        <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                        Location <span class="text-danger">*</span>
                                    </label>
                                    <select name="branch_unit_id" id="locationSelect" class="form-select select2_list">
                                        <option value="">-- Select Location --</option>
                                        @foreach ($locations as $location)
                                            <option value="{{ $location->id }}"
                                                {{ isset($organizationStructure) && $organizationStructure->branch_unit_id == $location->id ? 'selected' : '' }}>
                                                {{ $location->unit_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('branch_unit_id')
                                        <small class="text-danger"><i
                                                class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Division -->
                                <div class="col-md-6 mb-3" id="divisionField" style="display: none;">
                                    <label for="divisionSelect" class="form-label fw-semibold">
                                        <i class="fas fa-project-diagram text-warning me-1"></i>
                                        Division <span class="text-danger">*</span>
                                    </label>
                                    <select name="division_id" id="divisionSelect" class="form-select select2_list">
                                        <option value="">-- Select Division --</option>
                                        @foreach ($divisions as $division)
                                            <option value="{{ $division->id }}"
                                                {{ isset($organizationStructure) && $organizationStructure->division_id == $division->id ? 'selected' : '' }}>
                                                {{ $division->division_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('division_id')
                                        <small class="text-danger"><i
                                                class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Department -->
                                <div class="col-md-6 mb-3" id="departmentField" style="display: none;">
                                    <label for="departmentSelect" class="form-label fw-semibold">
                                        <i class="fas fa-sitemap text-info me-1"></i>
                                        Department <span class="text-danger">*</span>
                                    </label>
                                    <select name="department_id" id="departmentSelect" class="form-select select2_list">
                                        <option value="">-- Select Department --</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}"
                                                {{ isset($organizationStructure) && $organizationStructure->department_id == $department->id ? 'selected' : '' }}>
                                                {{ $department->department_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <small class="text-danger"><i
                                                class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Section -->
                                <div class="col-md-6 mb-3" id="sectionField" style="display: none;">
                                    <label for="sectionSelect" class="form-label fw-semibold">
                                        <i class="fas fa-network-wired text-secondary me-1"></i>
                                        Section <span class="text-danger">*</span>
                                    </label>
                                    <select name="section_id" id="sectionSelect" class="form-select select2_list">
                                        <option value="">-- Select Section --</option>
                                        @foreach ($sections as $section)
                                            <option value="{{ $section->id }}"
                                                {{ isset($organizationStructure) && $organizationStructure->section_id == $section->id ? 'selected' : '' }}>
                                                {{ $section->section_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('section_id')
                                        <small class="text-danger"><i
                                                class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Personal Information Section -->
                        <div class="mb-4">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-user-circle me-2"></i>Personal Information
                            </h6>
                            <div class="row">
                                <!-- Profile Image Preview -->
                                @if (isset($organizationStructure) && $organizationStructure->photo_path)
                                    <div class="col-12 mb-3 text-center">
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/' . $organizationStructure->photo_path) }}"
                                                class="rounded-circle border-primary shadow"
                                                style="width: 100px; height: 100px; object-fit: cover; border: 3px solid;"
                                                alt="Current Profile">
                                        </div>
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>Current Profile Image
                                        </small>
                                    </div>
                                @endif

                                <!-- Name -->
                                <div class="col-md-4 mb-3">
                                    <label for="nameInput" class="form-label fw-semibold">
                                        <i class="fas fa-user text-primary me-1"></i>
                                        Full Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name" id="nameInput" class="form-control"
                                        placeholder="Enter full name"
                                        value="{{ old('name', isset($organizationStructure) ? $organizationStructure->name : '') }}"
                                        required>
                                    @error('name')
                                        <small class="text-danger"><i
                                                class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Designation -->
                                <div class="col-md-4 mb-3 position-relative">
                                    <label for="designationInput" class="form-label fw-semibold">
                                        <i class="fas fa-id-badge text-success me-1"></i>
                                        Designation <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="designation" id="designationInput" class="form-control"
                                        placeholder="Enter or select designation" autocomplete="off"
                                        value="{{ old('designation', isset($organizationStructure) ? $organizationStructure->designation : '') }}"
                                        required>
                                    <div class="suggestions-list list-group position-absolute w-100" id="suggestionsList"
                                        style="z-index: 1000; display: none; max-height: 200px; overflow-y: auto;"></div>
                                    @error('designation')
                                        <small class="text-danger"><i
                                                class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Profile Image Upload -->
                                <div class="col-md-4 mb-3">
                                    <label for="photo_path" class="form-label fw-semibold">
                                        <i class="fas fa-image text-info me-1"></i>
                                        Profile Image
                                    </label>
                                    <input type="file" class="form-control" name="photo_path" id="photo_path"
                                        accept="image/jpeg,image/png,image/jpg,image/gif">
                                    <small class="text-muted d-block mt-1">
                                        <i class="fas fa-info-circle me-1"></i>Max 2MB (JPG, PNG, GIF)
                                    </small>
                                    @error('photo_path')
                                        <small class="text-danger"><i
                                                class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <div class="mb-4">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-address-book me-2"></i>Contact Information
                            </h6>
                            <div class="row">
                                <!-- Email -->
                                <div class="col-md-4 mb-3">
                                    <label for="emailInput" class="form-label fw-semibold">
                                        <i class="fas fa-envelope text-danger me-1"></i>
                                        Email Address <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" name="email" id="emailInput" class="form-control"
                                        placeholder="example@company.com"
                                        value="{{ old('email', isset($organizationStructure) ? $organizationStructure->email : '') }}"
                                        required>
                                    @error('email')
                                        <small class="text-danger"><i
                                                class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div class="col-md-4 mb-3">
                                    <label for="phoneInput" class="form-label fw-semibold">
                                        <i class="fas fa-phone text-success me-1"></i>
                                        Phone Number <span class="text-danger">*</span>
                                    </label>
                                    <input type="tel" name="contact_no" id="phoneInput" class="form-control"
                                        placeholder="+880 1XXX-XXXXXX"
                                        value="{{ old('contact_no', isset($organizationStructure) ? $organizationStructure->contact_no : '') }}"
                                        required>
                                    @error('contact_no')
                                        <small class="text-danger"><i
                                                class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Status -->
                                <div class="col-md-4 mb-3">
                                    <label for="status" class="form-label fw-semibold">
                                        <i class="fas fa-toggle-on text-warning me-1"></i>
                                        Status <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" name="status" id="status">
                                        <option value="active"
                                            {{ isset($organizationStructure) && $organizationStructure->status_form == 'active' ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="inactive"
                                            {{ isset($organizationStructure) && $organizationStructure->status_form == 'inactive' ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                    </select>
                                </div>

                                <!-- Address -->
                                <div class="col-md-12 mb-3">
                                    <label for="notesInput" class="form-label fw-semibold">
                                        <i class="fas fa-map-marked-alt text-info me-1"></i>
                                        Address
                                    </label>
                                    <textarea name="address" id="notesInput" class="form-control" rows="3"
                                        placeholder="Enter complete address (optional)">{{ old('address', isset($organizationStructure) ? $organizationStructure->address : '') }}</textarea>
                                    @error('address')
                                        <small class="text-danger"><i
                                                class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end gap-2 border-top pt-3 mt-4">
                            <a href="{{ route('organization-structure.index') }}" class="btn btn-secondary px-4">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="fas fa-save me-1"></i>
                                {{ isset($organizationStructure) ? 'Update Member' : 'Add Member' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            'use strict';

            const designationsByType = {
                group: ['Group Chairman', 'Group CEO', 'Group CFO', 'Group Director', 'Group VP'],
                company: ['Managing Director', 'CEO', 'CFO', 'Company Secretary', 'General Manager'],
                location: ['Site Manager', 'Branch Manager', 'Location Head', 'Operations Manager'],
                division: ['Division Head', 'VP - Division', 'Division Manager', 'Senior Manager'],
                department: ['Department Head', 'Manager', 'Assistant Manager', 'Team Lead'],
                section: ['Section Head', 'Supervisor', 'Team Lead', 'Senior Executive']
            };

            // Form elements
            const typeSelect = document.getElementById('typeSelect');
            const groupSelect = document.getElementById('groupSelect');
            const companySelect = document.getElementById('companySelect');
            const locationSelect = document.getElementById('locationSelect');
            const divisionSelect = document.getElementById('divisionSelect');
            const departmentSelect = document.getElementById('departmentSelect');
            const sectionSelect = document.getElementById('sectionSelect');
            const designationInput = document.getElementById('designationInput');
            const suggestionsList = document.getElementById('suggestionsList');

            // Field containers
            const groupField = document.getElementById('groupField');
            const companyField = document.getElementById('companyField');
            const locationField = document.getElementById('locationField');
            const divisionField = document.getElementById('divisionField');
            const departmentField = document.getElementById('departmentField');
            const sectionField = document.getElementById('sectionField');

            // Type hierarchy configuration
            const typeHierarchy = {
                group: ['group'],
                company: ['group', 'company'],
                location: ['group', 'company', 'location'],
                division: ['group', 'company', 'division'],
                department: ['group', 'company', 'division', 'department'],
                section: ['group', 'company', 'division', 'department', 'section']
            };

            // Initialize form on page load
            @if (isset($organizationStructure))
                const initialType = '{{ $organizationStructure->type_form }}';
                if (initialType) {
                    showFieldsForType(typeHierarchy[initialType]);
                }
            @endif

            // Handle type change
            typeSelect.addEventListener('change', (e) => {
                const selectedType = e.target.value;
                hideAllFields();

                if (selectedType && typeHierarchy[selectedType]) {
                    showFieldsForType(typeHierarchy[selectedType]);
                    updateDesignationSuggestions(selectedType);
                }
            });

            // Group change - load companies
            groupSelect.addEventListener('change', (e) => {
                const groupId = e.target.value;
                if (groupId) {
                    fetch(`/get-companies/${groupId}`)
                        .then(response => response.json())
                        .then(data => {
                            populateSelect(companySelect, data, 'name');
                        })
                        .catch(error => console.error('Error fetching companies:', error));
                } else {
                    resetSelect(companySelect);
                    resetSelect(locationSelect);
                    resetSelect(divisionSelect);
                    resetSelect(departmentSelect);
                    resetSelect(sectionSelect);
                }
            });

            // Company change - load locations and divisions
            companySelect.addEventListener('change', (e) => {
                const companyId = e.target.value;
                if (companyId) {
                    // Fetch locations
                    fetch(`/get-locations/${companyId}`)
                        .then(response => response.json())
                        .then(data => {
                            populateSelect(locationSelect, data, 'unit_name');
                        })
                        .catch(error => console.error('Error fetching locations:', error));

                    // Fetch divisions
                    fetch(`/get-org-divisions/${companyId}`)
                        .then(response => response.json())
                        .then(data => {
                            populateSelect(divisionSelect, data, 'division_name');
                        })
                        .catch(error => console.error('Error fetching divisions:', error));
                } else {
                    resetSelect(locationSelect);
                    resetSelect(divisionSelect);
                    resetSelect(departmentSelect);
                    resetSelect(sectionSelect);
                }
            });

            // Division change - load departments
            divisionSelect.addEventListener('change', (e) => {
                const divisionId = e.target.value;
                if (divisionId) {
                    fetch(`/get-org-departments/${divisionId}`)
                        .then(response => response.json())
                        .then(data => {
                            populateSelect(departmentSelect, data, 'department_name');
                        })
                        .catch(error => console.error('Error fetching departments:', error));
                } else {
                    resetSelect(departmentSelect);
                    resetSelect(sectionSelect);
                }
            });

            // Department change - load sections
            departmentSelect.addEventListener('change', (e) => {
                const departmentId = e.target.value;
                if (departmentId) {
                    fetch(`/get-org-sections/${departmentId}`)
                        .then(response => response.json())
                        .then(data => {
                            populateSelect(sectionSelect, data, 'section_name');
                        })
                        .catch(error => console.error('Error fetching sections:', error));
                } else {
                    resetSelect(sectionSelect);
                }
            });

            // Designation autocomplete
            designationInput.addEventListener('input', (e) => {
                const value = e.target.value.trim();
                const selectedType = typeSelect.value;

                if (value.length > 0 && selectedType && designationsByType[selectedType]) {
                    const suggestions = designationsByType[selectedType].filter(d =>
                        d.toLowerCase().includes(value.toLowerCase())
                    );

                    if (suggestions.length > 0) {
                        showSuggestions(suggestions);
                    } else {
                        hideSuggestions();
                    }
                } else {
                    hideSuggestions();
                }
            });

            designationInput.addEventListener('blur', () => {
                setTimeout(() => hideSuggestions(), 200);
            });

            // Helper functions
            function populateSelect(selectElement, data, nameField) {
                // Keep first option (placeholder)
                selectElement.innerHTML = selectElement.options[0].outerHTML;

                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item[nameField];
                    selectElement.appendChild(option);
                });
            }

            function resetSelect(selectElement) {
                selectElement.innerHTML = selectElement.options[0].outerHTML;
                selectElement.value = '';
            }

            function hideAllFields() {
                [groupField, companyField, locationField, divisionField, departmentField, sectionField].forEach(
                    field => {
                        field.style.display = 'none';
                    });
            }

            function showFieldsForType(fields) {
                const fieldMap = {
                    group: groupField,
                    company: companyField,
                    location: locationField,
                    division: divisionField,
                    department: departmentField,
                    section: sectionField
                };

                fields.forEach(fieldName => {
                    if (fieldMap[fieldName]) {
                        fieldMap[fieldName].style.display = 'block';
                    }
                });
            }

            function updateDesignationSuggestions(type) {
                designationInput.value = '';
            }

            function showSuggestions(suggestions) {
                suggestionsList.innerHTML = '';

                suggestions.forEach(suggestion => {
                    const item = document.createElement('a');
                    item.href = '#';
                    item.className = 'list-group-item list-group-item-action';
                    item.innerHTML = `<i class="fas fa-briefcase text-muted me-2"></i>${suggestion}`;
                    item.addEventListener('click', (e) => {
                        e.preventDefault();
                        designationInput.value = suggestion;
                        hideSuggestions();
                    });
                    suggestionsList.appendChild(item);
                });

                suggestionsList.style.display = 'block';
            }

            function hideSuggestions() {
                suggestionsList.style.display = 'none';
            }

        })();
    </script>
@endsection
