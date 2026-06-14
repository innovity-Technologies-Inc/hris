@extends('structure.master')

@section('content')
    @php
        $isEdit = isset($advanceData);
    @endphp

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i data-feather="{{ $isEdit ? 'edit' : 'plus' }}" class="me-2 text-primary"></i>
                        {{ $isEdit ? 'Edit' : 'Process' }} Advance Salary
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ $isEdit ? route('advance-salary.update', $advanceData->id) : route('advance-salary.store') }}"
                          method="POST" id="advanceSalaryForm">
                        @csrf
                        @if($isEdit) @method('PUT') @endif

                        <div class="row g-4">
                            {{-- Selection Filters --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-muted">Company <span class="text-danger">*</span></label>
                                <select class="form-select select2" name="company_id" id="company_id" required>
                                    <option value="">Select Company</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ (isset($advanceData) && $advanceData->company_id == $company->id) ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-muted">Branch</label>
                                <select class="form-select select2" name="branch_id" id="branch_id">
                                    <option value="">Select Branch</option>
                                    {{-- Loaded via AJAX --}}
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-muted">Department</label>
                                <select class="form-select select2" name="department_id" id="department_id">
                                    <option value="">Select Department</option>
                                    {{-- Loaded via AJAX --}}
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-muted">Pay Group <span class="text-danger">*</span></label>
                                <select class="form-select select2" name="pay_group_id" id="pay_group_id" required>
                                    <option value="">Select Pay Group</option>
                                    @foreach($payGroups as $group)
                                        <option value="{{ $group->id }}" 
                                                data-frequency="{{ strtolower($group->payroll_frequency) }}"
                                                {{ (isset($advanceData) && $advanceData->pay_group_id == $group->id) ? 'selected' : '' }}>
                                            {{ $group->name }} ({{ $group->payroll_frequency }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4" id="salary_month_container">
                                <label class="form-label fw-semibold small text-muted">Advance Month <span class="text-danger">*</span></label>
                                <input type="month" class="form-control" name="salary_month" id="salary_month" 
                                       value="{{ isset($advanceData) ? $advanceData->salary_month : date('Y-m') }}">
                            </div>

                            <div class="col-md-4 custom-date-container" style="display: none;">
                                <label class="form-label fw-semibold small text-muted">Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="start_date" id="start_date" 
                                       value="{{ isset($advanceData) ? \Carbon\Carbon::parse($advanceData->start_date)->format('Y-m-d') : '' }}">
                            </div>

                            <div class="col-md-4 custom-date-container" style="display: none;">
                                <label class="form-label fw-semibold small text-muted">End Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="end_date" id="end_date" 
                                       value="{{ isset($advanceData) ? \Carbon\Carbon::parse($advanceData->end_date)->format('Y-m-d') : '' }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold small text-muted">Deduction Month <span class="text-danger">*</span></label>
                                <input type="month" class="form-control" name="deduction_month" id="deduction_month" 
                                       value="{{ isset($firstItem) ? $firstItem->deduction_month : date('Y-m', strtotime('+1 month')) }}" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-muted">Amount Type <span class="text-danger">*</span></label>
                                <select class="form-select" name="amount_type" id="amount_type" required>
                                    <option value="fixed" {{ (isset($firstItem) && $firstItem->amount_type == 'fixed') ? 'selected' : '' }}>Fixed Amount</option>
                                    <option value="percentage" {{ (isset($firstItem) && $firstItem->amount_type == 'percentage') ? 'selected' : '' }}>Percentage</option>
                                </select>
                            </div>

                            <div class="col-md-3" id="percentage_base_container" style="display: none;">
                                <label class="form-label fw-semibold small text-muted">Percentage Base</label>
                                <select class="form-select" name="percentage_base" id="percentage_base">
                                    <option value="gross_salary">Gross Salary</option>
                                    <option value="basic_salary">Basic Salary</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-muted">Amount Value <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" name="amount_value" 
                                       value="{{ isset($firstItem) ? $firstItem->amount_value : '' }}" placeholder="Enter amount or percent" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-semibold small text-muted">Specific Employee (Optional)</label>
                                <select class="form-select select2" name="employee_id" id="employee_id">
                                    <option value="">All Eligible Employees</option>
                                    {{-- Loaded via AJAX --}}
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold small text-muted">Reason</label>
                                <textarea class="form-control" name="reason" rows="2">{{ isset($firstItem) ? $firstItem->reason : '' }}</textarea>
                            </div>
                        </div>

                        <div class="mt-4 pt-3 border-top text-end">
                            <a href="{{ route('advance-salary.index') }}" class="btn btn-light px-4 me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary px-5">
                                <i data-feather="play" class="me-2" style="width: 16px;"></i>
                                {{ $isEdit ? 'Update' : 'Process' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({ width: '100%' });

            const amountType = $('#amount_type');
            const percContainer = $('#percentage_base_container');

            function togglePercentage() {
                if (amountType.val() === 'percentage') {
                    percContainer.show();
                } else {
                    percContainer.hide();
                }
            }
            amountType.on('change', togglePercentage);
            togglePercentage();

            const payGroupId = $('#pay_group_id');
            const salaryMonthContainer = $('#salary_month_container');
            const customDateContainers = $('.custom-date-container');

            function toggleFrequencyFields() {
                const selected = payGroupId.find(':selected');
                const frequency = selected.data('frequency');

                if (frequency === 'monthly') {
                    salaryMonthContainer.show();
                    $('#salary_month').attr('required', true);
                    customDateContainers.hide();
                    $('#start_date, #end_date').attr('required', false);
                } else if (frequency) {
                    salaryMonthContainer.hide();
                    $('#salary_month').attr('required', false);
                    customDateContainers.show();
                    $('#start_date, #end_date').attr('required', true);
                }
            }

            payGroupId.on('change', toggleFrequencyFields);
            toggleFrequencyFields();

            // Organizational Selects (AJAX)
            $('#company_id').on('change', function() {
                const companyId = $(this).val();
                if (!companyId) return;

                axios.get(`/get-units/${companyId}`).then(res => {
                    let options = '<option value="">Select Branch</option>';
                    res.data.forEach(item => {
                        options += `<option value="${item.id}">${item.name}</option>`;
                    });
                    $('#branch_id').html(options).trigger('change');
                });

                loadEmployees();
            });

            $('#branch_id').on('change', function() {
                const companyId = $('#company_id').val();
                const branchId = $(this).val();
                if (!companyId) return;

                axios.get(`/get-departments/${companyId}/${branchId || ''}`).then(res => {
                    let options = '<option value="">Select Department</option>';
                    res.data.forEach(item => {
                        options += `<option value="${item.id}">${item.name}</option>`;
                    });
                    $('#department_id').html(options).trigger('change');
                });
                loadEmployees();
            });

            $('#department_id').on('change', loadEmployees);

            function loadEmployees() {
                const companyId = $('#company_id').val();
                const branchId = $('#branch_id').val() || '';
                const deptId = $('#department_id').val() || '';

                if (!companyId) return;

                axios.get(`/get-employees/${companyId}/${branchId}/${deptId}`).then(res => {
                    let options = '<option value="">All Eligible Employees</option>';
                    res.data.forEach(item => {
                        options += `<option value="${item.id}">${item.full_name} (${item.employee_id})</option>`;
                    });
                    $('#employee_id').html(options);
                });
            }
        });
    </script>
    @endpush
@endsection
