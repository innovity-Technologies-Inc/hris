@extends('structure.master')

@section('content')
    @php
        $isEdit = isset($advanceData);
    @endphp

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="card-title mb-0 text-white">
                        <i data-feather="{{ $isEdit ? 'edit' : 'plus' }}" class="me-2"></i>
                        {{ $isEdit ? 'Edit' : 'Process' }} Advance Salary
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ $isEdit ? route('advance-salary.update', $advanceData->id) : route('advance-salary.store') }}"
                          method="POST" id="advanceSalaryForm">
                        @csrf
                        @if($isEdit) @method('PUT') @endif

                        {{-- ================= ORGANIZATIONAL HIERARCHY ================= --}}
                        <div class="row mb-3 g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Company <span class="text-danger">*</span></label>
                                <select class="select2_list" name="company_id" id="company_id" required>
                                    <option value="">Select Company</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ ($isEdit && $advanceData->company_id == $company->id) ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            @if($generalSettings->branch_status == 1)
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Branch</label>
                                <select class="select2_list" name="branch_id" id="branch_id">
                                    <option value="">Select Branch</option>
                                </select>
                            </div>
                            @endif

                            @if($generalSettings->division_status == 1)
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Division</label>
                                <select class="select2_list" name="division_id" id="division_id">
                                    <option value="">Select Division</option>
                                </select>
                            </div>
                            @endif

                            @if($generalSettings->department_status == 1)
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Department</label>
                                <select class="select2_list" name="department_id" id="department_id">
                                    <option value="">Select Department</option>
                                </select>
                            </div>
                            @endif

                            @if($generalSettings->section_status == 1)
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Section</label>
                                <select class="select2_list" name="section_id" id="section_id">
                                    <option value="">Select Section</option>
                                </select>
                            </div>
                            @endif

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Specific Employee (Optional)</label>
                                <select class="select2_list" name="employee_id" id="employeeSelect">
                                    <option value="">All Eligible Employees</option>
                                    @if($isEdit && isset($firstItem) && $firstItem->employee)
                                        <option value="{{ $firstItem->employee_id }}" selected>
                                            {{ $firstItem->employee->full_name }}
                                        </option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        {{-- ================= PAY GROUP & DATES ================= --}}
                        <div class="row mb-4 g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Pay Group <span class="text-danger">*</span></label>
                                <select class="select2_list" name="pay_group_id" id="pay_group_id" required>
                                    <option value="">Select Pay Group</option>
                                    @foreach($payGroups as $group)
                                        <option value="{{ $group->id }}" 
                                                data-frequency="{{ strtolower($group->payroll_frequency) }}"
                                                {{ ($isEdit && $advanceData->pay_group_id == $group->id) ? 'selected' : '' }}>
                                            {{ $group->name }} ({{ $group->payroll_frequency }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4" id="salary_month_container">
                                <label class="form-label fw-semibold">Advance Month <span class="text-danger">*</span></label>
                                <input type="month" class="form-control" name="salary_month" id="salary_month" 
                                       value="{{ $isEdit ? $advanceData->salary_month : date('Y-m') }}">
                            </div>

                            <div class="col-md-4 custom-date-container d-none">
                                <label class="form-label fw-semibold">Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="start_date" id="start_date" 
                                       value="{{ $isEdit ? \Carbon\Carbon::parse($advanceData->start_date)->format('Y-m-d') : '' }}">
                            </div>

                            <div class="col-md-4 custom-date-container d-none">
                                <label class="form-label fw-semibold">End Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="end_date" id="end_date" 
                                       value="{{ $isEdit ? \Carbon\Carbon::parse($advanceData->end_date)->format('Y-m-d') : '' }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Deduction Month <span class="text-danger">*</span></label>
                                <input type="month" class="form-control" name="deduction_month" id="deduction_month" 
                                       value="{{ isset($firstItem) ? $firstItem->deduction_month : date('Y-m', strtotime('+1 month')) }}" required>
                            </div>
                        </div>

                        {{-- ================= AMOUNT CONFIGURATION ================= --}}
                        <div class="row g-4 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Amount Type <span class="text-danger">*</span></label>
                                <select class="form-select" name="amount_type" id="amount_type" required>
                                    <option value="fixed" {{ (isset($firstItem) && $firstItem->amount_type == 'fixed') ? 'selected' : '' }}>Fixed Amount</option>
                                    <option value="percentage" {{ (isset($firstItem) && $firstItem->amount_type == 'percentage') ? 'selected' : '' }}>Percentage</option>
                                </select>
                            </div>

                            <div class="col-md-4" id="percentage_base_container" style="display: none;">
                                <label class="form-label fw-semibold">Percentage Base</label>
                                <select class="form-select" name="percentage_base" id="percentage_base">
                                    <option value="gross_salary">Gross Salary</option>
                                    <option value="basic_salary">Basic Salary</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Amount Value <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" name="amount_value" 
                                       value="{{ isset($firstItem) ? $firstItem->amount_value : '' }}" placeholder="Enter amount or percent" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Reason</label>
                                <textarea class="form-control" name="reason" rows="2" placeholder="Describe the purpose of this advance">{{ isset($firstItem) ? $firstItem->reason : '' }}</textarea>
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
    <script>
        $(function() {
            $('.select2_list, .select2').select2({
                theme: 'bootstrap-5',
                allowClear: true,
                width: '100%'
            });

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
                if(!companyId) return Promise.resolve();
                return ajaxLoad(`/get-units/${companyId}`, $('#branch_id'),'Select Branch',selected);
            }

            function loadDivisions(companyId, branchId, selected=null){
                return ajaxLoad(`/get-divisions/${companyId}/${branchId ?? 'null'}`, $('#division_id'),'Select Division',selected);
            }

            function loadDepartments(companyId, branchId, divisionId, selected=null){
                return ajaxLoad(`/get-departments/${companyId}/${branchId ?? 'null'}/${divisionId ?? 'null'}`, $('#department_id'),'Select Department',selected);
            }

            function loadSections(companyId, branchId, divisionId, departmentId, selected=null){
                return ajaxLoad(`/get-sections/${companyId}/${branchId ?? 'null'}/${divisionId ?? 'null'}/${departmentId ?? 'null'}`, $('#section_id'),'Select Section',selected);
            }

            function loadEmployees(companyId, branchId, divisionId, departmentId, sectionId, selected=null){
                return ajaxLoad(`/get-employees/${companyId}/${branchId ?? 'null'}/${divisionId ?? 'null'}/${departmentId ?? 'null'}/${sectionId ?? 'null'}`, $('#employeeSelect'),'All Eligible Employees',selected);
            }

            $('#company_id').on('change',function(){
                let company = $(this).val();
                if(!company) return;
                loadBranches(company);
                loadDivisions(company);
                loadDepartments(company);
                loadSections(company);
                loadEmployees(company);
            });

            $('#branch_id').on('change',function(){
                let company = $('#company_id').val();
                let branch = $(this).val();
                loadDivisions(company,branch);
                loadDepartments(company,branch);
                loadSections(company,branch);
                loadEmployees(company,branch);
            });

            $('#division_id').on('change',function(){
                let company = $('#company_id').val();
                let branch = $('#branch_id').val();
                let division = $(this).val();
                loadDepartments(company,branch,division);
                loadSections(company,branch,division);
                loadEmployees(company,branch,division);
            });

            $('#department_id').on('change',function(){
                let company = $('#company_id').val();
                let branch = $('#branch_id').val();
                let division = $('#division_id').val();
                let department = $(this).val();
                loadSections(company,branch,division,department);
                loadEmployees(company,branch,division,department);
            });

            $('#section_id').on('change',function(){
                let company = $('#company_id').val();
                let branch = $('#branch_id').val();
                let division = $('#division_id').val();
                let department = $('#department_id').val();
                let section = $(this).val();
                loadEmployees(company,branch,division,department,section);
            });

            @if($isEdit)
            const editData = {
                company: "{{ $advanceData->company_id ?? '' }}",
                branch: "{{ $advanceData->branch_id ?? '' }}",
                division: "{{ $advanceData->division_id ?? '' }}",
                department: "{{ $advanceData->department_id ?? '' }}",
                section: "{{ $advanceData->section_id ?? '' }}",
                employee: "{{ $firstItem->employee_id ?? '' }}"
            };

            loadBranches(editData.company, editData.branch)
                .then(()=> loadDivisions(editData.company, editData.branch, editData.division))
                .then(()=> loadDepartments(editData.company, editData.branch, editData.division, editData.department))
                .then(()=> loadSections(editData.company, editData.branch, editData.division, editData.department, editData.section))
                .then(()=> loadEmployees(editData.company, editData.branch, editData.division, editData.department, editData.section, editData.employee));
            @endif

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

            function handlePayGroupChange() {
                let selectedOption = $('#pay_group_id').find(':selected');
                let frequency = selectedOption.data('frequency');
                
                if (!frequency) {
                    $('#salary_month_container').removeClass('d-none');
                    $('.custom-date-container').addClass('d-none');
                    $('#salary_month').attr('required', true);
                    $('#start_date, #end_date').removeAttr('required');
                    return;
                }

                if (frequency === 'monthly') {
                    $('#salary_month_container').removeClass('d-none');
                    $('.custom-date-container').addClass('d-none');
                    $('#salary_month').attr('required', true);
                    $('#start_date, #end_date').removeAttr('required').val('');
                } else {
                    $('#salary_month_container').addClass('d-none');
                    $('.custom-date-container').removeClass('d-none');
                    $('#salary_month').removeAttr('required').val('');
                    $('#start_date, #end_date').attr('required', true);
                }
            }

            $('#pay_group_id').on('change', handlePayGroupChange);
            handlePayGroupChange();

            // AXIOS SUBMIT FOR ADVANCE SALARY FORM
            $('#advanceSalaryForm').on('submit', function(e) {
                e.preventDefault();
                
                const $empSelect = $('#employeeSelect');
                const optionCount = $empSelect.find('option').length;
                const selectedVal = $empSelect.val();

                @if(!$isEdit)
                // If "All Eligible Employees" is selected, we need to ensure some options exists (count > 1)
                if (!selectedVal && optionCount <= 1) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Eligible Employees Found',
                        text: 'No employees match the selected filters. Please check or change organization filters.',
                        confirmButtonColor: '#3085d6'
                    });
                    return false;
                }
                @endif

                const form = this;
                const submitBtn = $(form).find('button[type="submit"]');
                
                $('.invalid-feedback').remove();
                $('.is-invalid').removeClass('is-invalid');
                submitBtn.prop('disabled', true);

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
                                window.location.href = res.redirect_url;
                            });
                        }
                    })
                    .catch(error => {
                        submitBtn.prop('disabled', false);
                        if (error.response && error.response.status === 422) {
                            const errors = error.response.data.errors;
                            if (errors) {
                                Object.keys(errors).forEach(key => {
                                    const input = form.querySelector(`[name="${key}"]`);
                                    if (input) {
                                        $(input).addClass('is-invalid');
                                        const errorDiv = document.createElement('div');
                                        errorDiv.className = 'invalid-feedback';
                                        errorDiv.innerText = errors[key][0];
                                        
                                        if ($(input).hasClass('select2_list') || $(input).hasClass('select2')) {
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
@endsection
