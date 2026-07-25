@extends('structure.master')

@section('content')
    @php
        $isEdit = isset($policy);
        $currency = \App\HelperClass::getCurrency() ?? '৳';
    @endphp

    <div class="py-4 w-100">
        <!-- Header Block -->
        <div class="d-flex align-items-center mb-4 p-3 bg-white rounded-4 shadow-sm border">
            <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3 d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                <i data-feather="percent" class="text-primary fs-3" style="width: 24px; height: 24px;"></i>
            </div>
            <div>
                <h3 class="fs-4 fw-bold text-dark mb-1">Tax & Exemption Policy</h3>
                <p class="text-muted mb-0 small">Configure zero-tax income thresholds, exemption policies, and taxable slab percentages.</p>
            </div>
        </div>

        <form action="{{ route('tax-policy.update', $policy->id) }}" method="POST" id="taxPolicyForm">
            @csrf
            @method('PUT')

            <div class="row g-4">
                {{-- LEFT COLUMN: Tax Policy & Exemption Rules --}}
                <div class="col-6">
                    <div class="card shadow border-0 rounded-4 h-100 overflow-hidden">
                        <div class="card-header bg-white py-3 border-bottom border-light">
                            <h5 class="fw-bold mb-0 text-dark d-flex align-items-center">
                                <i data-feather="sliders" class="me-2 text-primary" style="width: 18px; height: 18px;"></i>
                                General Policy Settings
                            </h5>
                        </div>
                        <div class="card-body p-4">

                            {{-- Zero Tax Return Limits --}}
                            <div class="row mb-4 g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark">Zero Tax Limit (Male) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0 fw-bold">{{ $currency }}</span>
                                        <input type="number" step="0.01" class="form-control form-control-md border-start-0" 
                                               name="zero_tax_male" id="zero_tax_male" 
                                               value="{{ $policy->zero_tax_male }}" required>
                                    </div>
                                    <div class="form-text small text-muted">Annual income threshold.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark">Zero Tax Limit (Female) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0 fw-bold">{{ $currency }}</span>
                                        <input type="number" step="0.01" class="form-control form-control-md border-start-0" 
                                               name="zero_tax_female" id="zero_tax_female" 
                                               value="{{ $policy->zero_tax_female }}" required>
                                    </div>
                                    <div class="form-text small text-muted">Annual income threshold.</div>
                                </div>
                            </div>

                            {{-- Minimum Tax Amount --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-dark">Minimum Tax Amount <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0 fw-bold">{{ $currency }}</span>
                                    <input type="number" step="0.01" class="form-control form-control-md border-start-0" 
                                           name="min_tax_amount" id="min_tax_amount" 
                                           value="{{ $policy->min_tax_amount }}" required>
                                </div>
                                <div class="form-text small text-muted">Minimum tax liability if taxable income exceeds limit.</div>
                            </div>

                            {{-- Negotiable Tax Settings --}}
                            <div class="row mb-4 g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark">Min Negotiable Tax Limit <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0 fw-bold">{{ $currency }}</span>
                                        <input type="number" step="0.01" class="form-control form-control-md border-start-0" 
                                               name="min_negotiable_tax_limit" id="min_negotiable_tax_limit" 
                                               value="{{ $policy->min_negotiable_tax_limit }}" required>
                                    </div>
                                    <div class="form-text small text-muted">Minimum threshold to apply reduction.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark">Tax Payable Percentage (%) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0 fw-bold">%</span>
                                        <input type="number" step="0.01" class="form-control form-control-md border-start-0" 
                                               name="tax_payable_percentage" id="tax_payable_percentage" 
                                               value="{{ $policy->tax_payable_percentage }}" required>
                                    </div>
                                    <div class="form-text small text-muted">Percentage of tax to actually pay.</div>
                                </div>
                            </div>

                            {{-- Tax Month Setting --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-dark">Total Tax Months <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light text-muted border-end-0 fw-bold"><i data-feather="calendar" style="width: 16px; height: 16px;"></i></span>
                                    <input type="number" class="form-control form-control-md border-start-0" 
                                           name="total_tax_month" id="total_tax_month" 
                                           value="{{ $policy->total_tax_month ?? 12 }}" required>
                                </div>
                                <div class="form-text small text-muted">Specify the total tax months (Note: the tax month will be including the bonus).</div>
                            </div>

                            {{-- Applicable Pay Groups Checkbox List --}}
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-dark mb-2">Applicable Pay Groups <span class="text-danger">*</span></label>
                                <div class="border rounded-4 p-3" style="background-color: #fafbfc; max-height: 200px; overflow-y: auto;">
                                    @foreach($payGroups as $payGroup)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" name="applicable_pay_groups[]" 
                                                   id="paygroup_{{ $payGroup->id }}" value="{{ $payGroup->id }}"
                                                   @if(!empty($policy->applicable_pay_groups) && in_array($payGroup->id, $policy->applicable_pay_groups)) checked @endif>
                                            <label class="form-check-label text-dark fw-semibold small" for="paygroup_{{ $payGroup->id }}">
                                                {{ $payGroup->title }} ({{ $payGroup->payroll_frequency }})
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="form-text small text-muted">Select the pay groups for which tax calculation is applicable. Ineligible pay groups will have 0.00 tax deduction.</div>
                            </div>

                            <hr class="my-4" style="border-style: dashed; opacity: 0.15;">

                            {{-- Exemption Policy Header --}}
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-success bg-opacity-10 rounded-circle p-2 me-2 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    <i data-feather="gift" class="text-success" style="width: 16px; height: 16px;"></i>
                                </div>
                                <h6 class="fw-bold mb-0 text-dark">Exemption Policy Details</h6>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold text-dark">Exemption Rule Type <span class="text-danger">*</span></label>
                                <select class="form-select form-select-md rounded-3" name="exemption_type" id="exemption_type" required>
                                    <option value="fixed" {{ ($policy->exemption_type === 'fixed') ? 'selected' : '' }}>Fixed Amount / Ratio</option>
                                    <option value="exempt_allowance" {{ ($policy->exemption_type === 'exempt_allowance') ? 'selected' : '' }}>Exempt Allowances</option>
                                </select>
                            </div>

                            {{-- Exemption Mode: Fixed Inputs --}}
                            <div class="row mb-3 g-3" id="fixedExemptionSection">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark">Salary Ratio limit</label>
                                    <input type="text" class="form-control form-control-md rounded-3" 
                                           name="salary_ratio" id="salary_ratio" 
                                           value="{{ $policy->salary_ratio }}" placeholder="e.g. 1/3, 2/3">
                                    <div class="form-text small text-muted">Use standard fractional input formats like 1/3.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark">Fixed Exempt Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light text-muted border-end-0 fw-bold">{{ $currency }}</span>
                                        <input type="number" step="0.01" class="form-control form-control-md border-start-0" 
                                               name="fixed_amount" id="fixed_amount" 
                                               value="{{ $policy->fixed_amount }}">
                                    </div>
                                </div>
                            </div>

                            {{-- Exemption Mode: Exempt Allowances Dynamic Selector --}}
                            <div class="mb-3" id="exemptAllowancesSection" style="display: none;">
                                <label class="form-label fw-semibold text-dark">Add Exempt Allowance</label>
                                <div class="input-group mb-3 shadow-sm rounded-3 overflow-hidden">
                                    <select class="form-select border-end-0 select2" id="allowanceDropdown">
                                        <option value="">Select an allowance...</option>
                                        @foreach($allowanceMapping as $dbField => $displayName)
                                            <option value="{{ $dbField }}">{{ $displayName }}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn px-3 fw-bold text-white" type="button" id="addAllowanceBtn" style="background-color: var(--primary-color, #974063); border-color: var(--primary-color, #974063);">
                                        <i data-feather="plus" class="me-1" style="width: 16px; height: 16px;"></i> Add
                                    </button>
                                </div>
                                
                                <div id="allowanceListContainer" class="d-flex flex-wrap gap-2 border p-3 rounded-4" style="min-height: 80px; background-color: #fafbfc;">
                                    {{-- Rendered badges --}}
                                    @if($policy->exemption_type === 'exempt_allowance' && is_array($policy->exempt_allowances))
                                        @foreach($policy->exempt_allowances as $allowance)
                                            @if(isset($allowanceMapping[$allowance]))
                                                <span class="badge bg-primary text-white d-flex align-items-center gap-2 px-3 py-2 rounded-pill shadow-sm allowance-badge" data-value="{{ $allowance }}">
                                                    {{ $allowanceMapping[$allowance] }}
                                                    <span class="remove-allowance-badge text-white font-bold" data-value="{{ $allowance }}" style="cursor: pointer; font-size: 16px; line-height: 1;">&times;</span>
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
                <div class="col-6">
                    <div class="card shadow border-0 rounded-4 h-100 overflow-hidden">
                        <div class="card-header bg-white py-3 border-bottom border-light d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0 text-dark d-flex align-items-center">
                                <i data-feather="layers" class="me-2 text-primary" style="width: 18px; height: 18px;"></i>
                                Tax Slabs Configuration
                            </h5>
                            <button type="button" class="btn btn-sm rounded-pill shadow-sm px-3 text-white" id="addSlabBtn" style="background-color: var(--primary-color, #974063); border-color: var(--primary-color, #974063);">
                                        <i data-feather="plus" class="me-1" style="width: 14px; height: 14px;"></i> Add Slab
                            </button>
                        </div>
                        <div class="card-body p-4">
                            <div class="table-responsive">
                                <table class="table align-middle" id="slabsTable">
                                    <thead>
                                        <tr class="text-muted small uppercase">
                                            <th>Taxable Amount ({{ $currency }})</th>
                                            <th style="width: 120px;">Tax (%) <span class="text-danger">*</span></th>
                                            <th>Calculated Tax ({{ $currency }})</th>
                                            <th style="width: 50px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="slabsContainer">
                                        @if($policy->slabs->isNotEmpty())
                                            @foreach($policy->slabs as $index => $slab)
                                                <tr class="slab-row">
                                                    <td>
                                                        <input type="number" step="0.01" name="slabs[{{ $index }}][taxable_amount]" 
                                                               class="form-control form-control-md slab-taxable-amount rounded-3" value="{{ $slab->taxable_amount }}" placeholder="Unlimited">
                                                    </td>
                                                    <td>
                                                        <input type="number" step="0.01" name="slabs[{{ $index }}][tax_percentage]" 
                                                               class="form-control form-control-md slab-tax-percentage rounded-3" value="{{ $slab->tax_percentage }}" required>
                                                    </td>
                                                    <td>
                                                        <input type="number" step="0.01" name="slabs[{{ $index }}][tax_amount]" 
                                                               class="form-control form-control-md slab-tax-amount text-muted bg-light border-0 rounded-3" value="{{ $slab->tax_amount }}" readonly required>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-link text-danger p-0 delete-slab-row shadow-none">
                                                            <i data-feather="trash-2" style="width: 18px; height: 18px;"></i>
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

            {{-- Submit Action --}}
            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-lg rounded-pill px-5 fw-bold shadow text-white" id="submitBtn" style="background-color: var(--primary-color, #974063); border-color: var(--primary-color, #974063);">
                    <i data-feather="check-circle" class="me-1"></i> Save Configuration
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            // Select2 initialization
            $('.select2').select2({
                theme: 'bootstrap-5',
                allowClear: true,
                width: '100%'
            });

            // Exemption Policy interactive toggle
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

                // Check if already in the list
                if ($(`#allowanceListContainer .allowance-badge[data-value="${val}"]`).length > 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Allowance Added',
                        text: 'This allowance is already in the list of exempt items.'
                    });
                    return;
                }

                // Append Badge
                const html = `
                    <span class="badge bg-primary text-white d-flex align-items-center gap-2 px-3 py-2 rounded-pill shadow-sm allowance-badge animate__animated animate__fadeIn" data-value="${val}">
                        ${text}
                        <span class="remove-allowance-badge text-white" data-value="${val}" style="cursor: pointer; font-size: 16px; line-height: 1;">&times;</span>
                        <input type="hidden" name="exempt_allowances[]" value="${val}">
                    </span>
                `;
                $('#allowanceListContainer').append(html);
                dropdown.val('').trigger('change');
            });

            // Remove Badge
            $(document).on('click', '.remove-allowance-badge', function() {
                $(this).closest('.allowance-badge').remove();
            });

            // Slabs dynamically generated rows indices
            let slabIndex = {{ $policy->slabs->count() }};

            // Max Tax calculation per slab row
            function calculateSlabTax($row) {
                const taxableValRaw = $row.find('.slab-taxable-amount').val();
                const pct = parseFloat($row.find('.slab-tax-percentage').val() || 0);
                
                if (taxableValRaw === '' || taxableValRaw === null || taxableValRaw === undefined) {
                    // Open-ended slab
                    $row.find('.slab-tax-amount').val('0.00');
                } else {
                    const taxableVal = parseFloat(taxableValRaw);
                    const tax = (taxableVal * pct) / 100;
                    $row.find('.slab-tax-amount').val(tax.toFixed(2));
                }
            }

            $(document).on('input change', '.slab-taxable-amount, .slab-tax-percentage', function() {
                calculateSlabTax($(this).closest('.slab-row'));
            });

            // Add new Slab row
            $('#addSlabBtn').on('click', function(e) {
                e.preventDefault();
                const html = `
                    <tr class="slab-row animate__animated animate__fadeIn">
                        <td>
                            <input type="number" step="0.01" name="slabs[${slabIndex}][taxable_amount]" 
                                   class="form-control form-control-md slab-taxable-amount rounded-3" value="" placeholder="Unlimited">
                        </td>
                        <td>
                            <input type="number" step="0.01" name="slabs[${slabIndex}][tax_percentage]" 
                                   class="form-control form-control-md slab-tax-percentage rounded-3" value="0.00" required>
                        </td>
                        <td>
                            <input type="number" step="0.01" name="slabs[${slabIndex}][tax_amount]" 
                                   class="form-control form-control-md slab-tax-amount text-muted bg-light border-0 rounded-3" value="0.00" readonly required>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-link text-danger p-0 delete-slab-row shadow-none">
                                <i data-feather="trash-2" style="width: 18px; height: 18px;"></i>
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

            // Axios configuration save
            $('#taxPolicyForm').on('submit', function(e) {
                e.preventDefault();
                const form = this;
                const submitBtn = $('#submitBtn');

                // Client-side validations
                if ($('#exemption_type').val() === 'exempt_allowance' && $('#allowanceListContainer .allowance-badge').length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Select Allowances',
                        text: 'Please select and add at least one allowance for exempt rules.'
                    });
                    return;
                }

                if ($('#slabsContainer .slab-row').length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Configure Slabs',
                        text: 'Please define at least one tax slab bracket.'
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
                                title: 'Saved!',
                                text: res.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = res.data.redirect_url;
                            });
                        }
                    })
                    .catch(error => {
                        submitBtn.prop('disabled', false).html('<i data-feather="check-circle" class="me-1"></i> Save Configuration');
                        if (typeof feather !== 'undefined') feather.replace();

                        if (error.response && error.response.status === 422) {
                            const errors = error.response.data.errors;
                            if (errors) {
                                Object.keys(errors).forEach(key => {
                                    let input = form.querySelector(`[name="${key}"]`);
                                    
                                    // Slabs array based elements validation keys mapping
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
                            const msg = error.response?.data?.message || 'Failed to save configuration settings.';
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
