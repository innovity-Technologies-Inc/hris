@extends('structure.master')

@section('content')
    @php
        $isEdit = isset($policy);
    @endphp

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="card-title mb-0 text-white">
                        <i data-feather="{{ $isEdit ? 'edit' : 'plus' }}" class="me-2"></i>
                        {{ $isEdit ? 'Edit' : 'Create' }} Tax Policy
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ $isEdit ? route('tax-policy.update', $policy->id) : route('tax-policy.store') }}"
                          method="POST" id="taxPolicyForm">
                        @csrf
                        @if($isEdit) @method('PUT') @endif

                        <div class="row g-4">
                            {{-- LEFT COLUMN: Tax Policy Details --}}
                            <div class="col-lg-6">
                                <div class="card border border-light shadow-none h-100" style="border-radius: 12px;">
                                    <div class="card-header bg-light">
                                        <h6 class="fw-bold mb-0 text-dark">Tax Policy Details</h6>
                                    </div>
                                    <div class="card-body">
                                        {{-- Company & Branch (Organizational Scoping) --}}
                                        <div class="row mb-3 g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Company</label>
                                                <select class="select2" name="company_id" id="company_id">
                                                    <option value="">Global (All Companies)</option>
                                                    @foreach($companies as $company)
                                                        <option value="{{ $company->id }}" {{ ($isEdit && $policy->company_id == $company->id) ? 'selected' : '' }}>
                                                            {{ $company->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Branch</label>
                                                <select class="select2" name="branch_id" id="branch_id">
                                                    <option value="">All Branches</option>
                                                </select>
                                            </div>
                                        </div>

                                        {{-- Zero Tax Return Limits --}}
                                        <div class="row mb-3 g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Zero Tax Return Income (Male) <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text">৳</span>
                                                    <input type="number" step="0.01" class="form-control" name="zero_tax_male" id="zero_tax_male" 
                                                           value="{{ $isEdit ? $policy->zero_tax_male : '0.00' }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Zero Tax Return Income (Female) <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <span class="input-group-text">৳</span>
                                                    <input type="number" step="0.01" class="form-control" name="zero_tax_female" id="zero_tax_female" 
                                                           value="{{ $isEdit ? $policy->zero_tax_female : '0.00' }}" required>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Minimum Tax Amount --}}
                                        <div class="mb-4">
                                            <label class="form-label fw-semibold">Minimum Tax Amount <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">৳</span>
                                                <input type="number" step="0.01" class="form-control" name="min_tax_amount" id="min_tax_amount" 
                                                       value="{{ $isEdit ? $policy->min_tax_amount : '0.00' }}" required>
                                            </div>
                                        </div>

                                        <hr class="my-4">

                                        {{-- Exemption Policy Section --}}
                                        <h6 class="fw-bold mb-3 text-primary">Exemption Policy</h6>
                                        
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Exemption Type <span class="text-danger">*</span></label>
                                            <select class="form-select" name="exemption_type" id="exemption_type" required>
                                                <option value="fixed" {{ ($isEdit && $policy->exemption_type === 'fixed') ? 'selected' : '' }}>Fixed</option>
                                                <option value="exempt_allowance" {{ ($isEdit && $policy->exemption_type === 'exempt_allowance') ? 'selected' : '' }}>Exempt Allowances</option>
                                            </select>
                                        </div>

                                        {{-- Exemption Type: Fixed Inputs --}}
                                        <div class="row mb-3 g-3" id="fixedExemptionSection">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Salary Ratio</label>
                                                <input type="text" class="form-control" name="salary_ratio" id="salary_ratio" 
                                                       value="{{ $isEdit ? $policy->salary_ratio : '' }}" placeholder="e.g. 1/3, 2/3">
                                                <div class="form-text text-muted" style="font-size: 11px;">Note: Enter as a fraction like 1/3, 2/3.</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Fixed Amount</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">৳</span>
                                                    <input type="number" step="0.01" class="form-control" name="fixed_amount" id="fixed_amount" 
                                                           value="{{ $isEdit ? $policy->fixed_amount : '' }}">
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Exemption Type: Exempt Allowances Inputs --}}
                                        <div class="mb-3" id="exemptAllowancesSection" style="display: none;">
                                            <label class="form-label fw-semibold">Select Allowance to Add</label>
                                            <div class="input-group mb-3">
                                                <select class="form-select" id="allowanceDropdown">
                                                    <option value="">Select Allowance</option>
                                                    @foreach($allowanceMapping as $dbField => $displayName)
                                                        <option value="{{ $dbField }}">{{ $displayName }}</option>
                                                    @endforeach
                                                </select>
                                                <button class="btn btn-primary btn-sm" type="button" id="addAllowanceBtn">
                                                    <i data-feather="plus" style="width: 14px; height: 14px;"></i> Add
                                                </button>
                                            </div>
                                            
                                            <div id="allowanceListContainer" class="d-flex flex-wrap gap-2 border p-3 rounded" style="min-height: 60px; background: rgba(0,0,0,0.01);">
                                                {{-- Existing allowances badges go here --}}
                                                @if($isEdit && $policy->exemption_type === 'exempt_allowance' && is_array($policy->exempt_allowances))
                                                    @foreach($policy->exempt_allowances as $allowance)
                                                        @if(isset($allowanceMapping[$allowance]))
                                                            <span class="badge bg-primary text-white d-flex align-items-center gap-1 p-2 allowance-badge" data-value="{{ $allowance }}">
                                                                {{ $allowanceMapping[$allowance] }}
                                                                <span class="remove-allowance-badge text-white ms-1" data-value="{{ $allowance }}" style="cursor: pointer; font-size: 14px; font-weight: bold;">&times;</span>
                                                                <input type="hidden" name="exempt_allowances[]" value="{{ $allowance }}">
                                                            </span>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- RIGHT COLUMN: Tax Slabs Management --}}
                            <div class="col-lg-6">
                                <div class="card border border-light shadow-none h-100" style="border-radius: 12px;">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                        <h6 class="fw-bold mb-0 text-dark">Tax Slabs Configuration</h6>
                                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill" id="addSlabBtn">
                                            <i data-feather="plus" class="me-1" style="width: 14px;"></i> Add Slab
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table align-middle" id="slabsTable">
                                                <thead>
                                                    <tr>
                                                        <th>Taxable Amount (৳) <span class="text-danger">*</span></th>
                                                        <th style="width: 100px;">Tax (%) <span class="text-danger">*</span></th>
                                                        <th>Calculated Tax (৳)</th>
                                                        <th style="width: 50px;"></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="slabsContainer">
                                                    {{-- Existing Slabs go here --}}
                                                    @if($isEdit && $policy->slabs->isNotEmpty())
                                                        @foreach($policy->slabs as $index => $slab)
                                                            <tr class="slab-row">
                                                                <td>
                                                                    <input type="number" step="0.01" name="slabs[{{ $index }}][taxable_amount]" 
                                                                           class="form-control form-control-sm slab-taxable-amount" value="{{ $slab->taxable_amount }}" required>
                                                                </td>
                                                                <td>
                                                                    <input type="number" step="0.01" name="slabs[{{ $index }}][tax_percentage]" 
                                                                           class="form-control form-control-sm slab-tax-percentage" value="{{ $slab->tax_percentage }}" required>
                                                                </td>
                                                                <td>
                                                                    <input type="number" step="0.01" name="slabs[{{ $index }}][tax_amount]" 
                                                                           class="form-control form-control-sm slab-tax-amount text-muted bg-light" value="{{ $slab->tax_amount }}" readonly required>
                                                                </td>
                                                                <td class="text-center">
                                                                    <button type="button" class="btn btn-link text-danger p-0 delete-slab-row">
                                                                        <i data-feather="trash-2" style="width: 16px;"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Footer Buttons --}}
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill fw-bold shadow-lg" id="submitBtn">
                                <i data-feather="check-circle" class="me-1"></i> Save Tax Policy
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
        $(function() {
            // Select2 Init
            $('.select2').select2({
                theme: 'bootstrap-5',
                allowClear: true,
                width: '100%'
            });

            // AJAX loader helper for organization units
            function ajaxLoad(url, $select, placeholder, selectedValue = null){
                if (!$select.length) return Promise.resolve();
                return $.get(url).then(function(data){
                    $select.html(`<option value="">${placeholder}</option>`);
                    data.forEach(item=>{
                        $select.append(
                            `<option value="${item.id}">${item.name ?? item.department_name ?? item.full_name}</option>`
                        );
                    });
                    if(selectedValue){
                        $select.val(selectedValue).trigger('change');
                    }
                }).catch(function(){
                    $select.html('<option value="">Error loading data</option>');
                });
            }

            function loadBranches(companyId, selected=null){
                if(!companyId) {
                    $('#branch_id').html('<option value="">All Branches</option>').trigger('change');
                    return Promise.resolve();
                }
                return ajaxLoad(`/get-units/${companyId}`, $('#branch_id'), 'All Branches', selected);
            }

            $('#company_id').on('change', function() {
                let company = $(this).val();
                loadBranches(company);
            });

            @if($isEdit)
                const editData = {
                    company: "{{ $policy->company_id ?? '' }}",
                    branch: "{{ $policy->branch_id ?? '' }}"
                };
                if(editData.company) {
                    loadBranches(editData.company, editData.branch);
                }
            @endif

            // Toggle Exemption Type
            const exemptionType = $('#exemption_type');
            const fixedSection = $('#fixedExemptionSection');
            const allowancesSection = $('#exemptAllowancesSection');

            function toggleExemptionType() {
                if (exemptionType.val() === 'fixed') {
                    fixedSection.show();
                    allowancesSection.hide();
                    $('#salary_ratio, #fixed_amount').attr('required', true);
                } else {
                    fixedSection.hide();
                    allowancesSection.show();
                    $('#salary_ratio, #fixed_amount').removeAttr('required').val('');
                }
            }

            exemptionType.on('change', toggleExemptionType);
            toggleExemptionType();

            // Add Allowance
            $('#addAllowanceBtn').on('click', function(e) {
                e.preventDefault();
                const dropdown = $('#allowanceDropdown');
                const val = dropdown.val();
                const text = dropdown.find('option:selected').text();

                if (!val) return;

                // Check if already added
                if ($(`#allowanceListContainer .allowance-badge[data-value="${val}"]`).length > 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Already Added',
                        text: 'This allowance is already in the exempt list.'
                    });
                    return;
                }

                // Add Badge
                const html = `
                    <span class="badge bg-primary text-white d-flex align-items-center gap-1 p-2 allowance-badge animate__animated animate__fadeIn" data-value="${val}">
                        ${text}
                        <span class="remove-allowance-badge text-white ms-1" data-value="${val}" style="cursor: pointer; font-size: 14px; font-weight: bold;">&times;</span>
                        <input type="hidden" name="exempt_allowances[]" value="${val}">
                    </span>
                `;
                $('#allowanceListContainer').append(html);
                dropdown.val('').trigger('change');
            });

            // Remove Allowance Badge
            $(document).on('click', '.remove-allowance-badge', function() {
                $(this).closest('.allowance-badge').remove();
            });

            // Slab row index generator
            let slabIndex = {{ $isEdit ? $policy->slabs->count() : 0 }};

            // Calculate slab tax amount
            function calculateSlabTax($row) {
                const amount = parseFloat($row.find('.slab-taxable-amount').val() || 0);
                const pct = parseFloat($row.find('.slab-tax-percentage').val() || 0);
                const tax = (amount * pct) / 100;
                $row.find('.slab-tax-amount').val(tax.toFixed(2));
            }

            // Dynamic calculation listeners
            $(document).on('input change', '.slab-taxable-amount, .slab-tax-percentage', function() {
                const row = $(this).closest('.slab-row');
                calculateSlabTax(row);
            });

            // Add Slab row
            $('#addSlabBtn').on('click', function(e) {
                e.preventDefault();
                const html = `
                    <tr class="slab-row animate__animated animate__fadeIn">
                        <td>
                            <input type="number" step="0.01" name="slabs[${slabIndex}][taxable_amount]" 
                                   class="form-control form-control-sm slab-taxable-amount" value="0.00" required>
                        </td>
                        <td>
                            <input type="number" step="0.01" name="slabs[${slabIndex}][tax_percentage]" 
                                   class="form-control form-control-sm slab-tax-percentage" value="0.00" required>
                        </td>
                        <td>
                            <input type="number" step="0.01" name="slabs[${slabIndex}][tax_amount]" 
                                   class="form-control form-control-sm slab-tax-amount text-muted bg-light" value="0.00" readonly required>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-link text-danger p-0 delete-slab-row">
                                <i data-feather="trash-2" style="width: 16px;"></i>
                            </button>
                        </td>
                    </tr>
                `;
                $('#slabsContainer').append(html);
                if (typeof feather !== 'undefined') feather.replace();
                slabIndex++;
            });

            // Delete Slab row
            $(document).on('click', '.delete-slab-row', function() {
                $(this).closest('.slab-row').remove();
            });

            // Axios Form Submission
            $('#taxPolicyForm').on('submit', function(e) {
                e.preventDefault();
                const form = this;
                const submitBtn = $('#submitBtn');

                // Validation checks
                if ($('#exemption_type').val() === 'exempt_allowance' && $('#allowanceListContainer .allowance-badge').length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Select Allowances',
                        text: 'Exempt Allowances type requires at least one allowance added to the list.'
                    });
                    return;
                }

                if ($('#slabsContainer .slab-row').length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Add Slabs',
                        text: 'Please configure at least one tax slab.'
                    });
                    return;
                }

                $('.invalid-feedback').remove();
                $('.is-invalid').removeClass('is-invalid');
                submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Saving...');

                const formData = new FormData(form);

                axios.post(form.action, formData)
                    .then(response => {
                        const res = response.data;
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: res.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = res.data.redirect_url;
                            });
                        }
                    })
                    .catch(error => {
                        submitBtn.prop('disabled', false).html('<i data-feather="check-circle" class="me-1"></i> Save Tax Policy');
                        if (typeof feather !== 'undefined') feather.replace();

                        if (error.response && error.response.status === 422) {
                            const errors = error.response.data.errors;
                            if (errors) {
                                Object.keys(errors).forEach(key => {
                                    // Handle array-based validation keys (like slabs.0.taxable_amount)
                                    const cleanKey = key.replace(/\./g, '_');
                                    let input = form.querySelector(`[name="${key}"]`);
                                    
                                    // If not found directly, try select2 or other formats
                                    if (!input && key.includes('.')) {
                                        const parts = key.split('.');
                                        const arrayName = parts[0];
                                        const index = parts[1];
                                        const field = parts[2];
                                        input = form.querySelector(`[name="${arrayName}[${index}][${field}]"]`);
                                    }

                                    if (input) {
                                        $(input).addClass('is-invalid');
                                        const errorDiv = document.createElement('div');
                                        errorDiv.className = 'invalid-feedback';
                                        errorDiv.innerText = errors[key][0];
                                        
                                        if ($(input).hasClass('select2') || $(input).hasClass('select2_list')) {
                                            $(input).next('.select2-container').after(errorDiv);
                                        } else {
                                            input.after(errorDiv);
                                        }
                                    }
                                });
                            }
                        } else {
                            const msg = error.response?.data?.message || 'Something went wrong!';
                            Swal.fire({
                                icon: 'error',
                                title: 'Operation Failed',
                                text: msg
                            });
                        }
                    });
            });
        });
    </script>
@endpush
