@extends('structure.master')

@section('content')

    @php
        $isEdit = isset($salaryData);
    @endphp

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">

                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        {{ $isEdit ? 'Edit Payroll Process Entry' : 'Payroll Process Entry' }}
                    </h5>
                </div>

                <div class="card-body p-4">

                    <form id="salaryProcessForm"
                          action="{{ $isEdit ? route('salary.update', $salaryData->id) : route('salary.store') }}"
                          method="POST">

                        @csrf
                        @if($isEdit)
                            @method('PUT')
                        @endif

                        {{-- ================= COMPANY ================= --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Company <span class="text-danger">*</span>
                                </label>
                                <select name="company_id" id="company_id"
                                        class="form-select select2_list" required>
                                    <option value="">Select Company</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}"
                                            {{ $isEdit && $salaryData->company_id == $company->id ? 'selected' : '' }}>
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Branch</label>
                                <select name="branch_id" id="branch_id"
                                        class="form-select select2_list">
                                    <option value="">Select Branch</option>
                                </select>
                            </div>
                        </div>

                        {{-- ================= DIVISION / DEPARTMENT ================= --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Division</label>
                                <select name="division_id" id="division_id"
                                        class="form-select select2_list">
                                    <option value="">Select Division</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Department</label>
                                <select name="department_id" id="department_id"
                                        class="form-select select2_list">
                                    <option value="">Select Department</option>
                                </select>
                            </div>
                        </div>

                        {{-- ================= SECTION ================= --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Section</label>
                                <select name="section_id" id="section_id"
                                        class="form-select select2_list">
                                    <option value="">Select Section</option>
                                </select>
                            </div>
                        </div>

                        {{-- ================= EMPLOYEE ================= --}}
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Employee</label>

                                <select id="employeeSelect"
                                        name="employee_id"
                                        class="form-select select2">

                                    <option value="">Select Employee</option>

                                    @if($isEdit && $salaryData->employee)
                                        <option value="{{ $salaryData->employee->id }}" selected>
                                            {{ $salaryData->employee->full_name }}
                                        </option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        {{-- ================= PAY GROUP & DATES ================= --}}
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">
                                    Pay Group <span class="text-danger">*</span>
                                </label>
                                <select name="pay_group_id" id="pay_group_id"
                                        class="form-select select2_list" required>
                                    <option value="">Select Pay Group</option>
                                    @foreach($payGroups as $payGroup)
                                        <option value="{{ $payGroup->id }}" data-frequency="{{ strtolower($payGroup->payroll_frequency) }}"
                                            {{ $isEdit && $salaryData->pay_group_id == $payGroup->id ? 'selected' : '' }}>
                                            {{ $payGroup->title }} ({{ $payGroup->payroll_frequency }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4" id="monthPickerContainer">
                                <label class="form-label fw-semibold">
                                    Salary Month <span class="text-danger">*</span>
                                </label>
                                <input type="month"
                                       name="salary_month"
                                       id="salary_month"
                                       class="form-control"
                                       value="{{ $isEdit ? $salaryData->salary_month : '' }}">
                            </div>

                            <div class="col-md-4 d-none" id="startDateContainer">
                                <label class="form-label fw-semibold">
                                    Start Date <span class="text-danger">*</span>
                                </label>
                                <input type="date"
                                       name="start_date"
                                       id="start_date"
                                       class="form-control"
                                       value="{{ $isEdit ? $salaryData->start_date : '' }}">
                            </div>

                            <div class="col-md-4 d-none" id="endDateContainer">
                                <label class="form-label fw-semibold">
                                    End Date <span class="text-danger">*</span>
                                </label>
                                <input type="date"
                                       name="end_date"
                                       id="end_date"
                                       class="form-control"
                                       value="{{ $isEdit ? $salaryData->end_date : '' }}">
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-4">
                                {{ $isEdit ? 'Update Salary Process' : 'Process Salary Process' }}
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(function(){

            $('.select2_list, .select2').select2({
                allowClear: true,
                width: '100%'
            });

            function ajaxLoad(url, $select, placeholder, selectedValue = null){
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
                    // Optional: handle ajax error if needed
                    $select.html('<option value="">Error loading data</option>');
                });
            }

            function loadBranches(companyId, selected=null){
                if(!companyId) return Promise.resolve();
                return ajaxLoad(`/get-units/${companyId}`,
                    $('#branch_id'),'Select Branch',selected);
            }

            function loadDivisions(companyId, branchId, selected=null){
                return ajaxLoad(`/get-divisions/${companyId}/${branchId ?? 'null'}`,
                    $('#division_id'),'Select Division',selected);
            }

            function loadDepartments(companyId, branchId, divisionId, selected=null){
                return ajaxLoad(`/get-departments/${companyId}/${branchId ?? 'null'}/${divisionId ?? 'null'}`,
                    $('#department_id'),'Select Department',selected);
            }

            function loadSections(companyId, branchId, divisionId, departmentId, selected=null){
                return ajaxLoad(`/get-sections/${companyId}/${branchId ?? 'null'}/${divisionId ?? 'null'}/${departmentId ?? 'null'}`,
                    $('#section_id'),'Select Section',selected);
            }

            function loadEmployees(companyId, branchId, divisionId, departmentId, sectionId, selected=null){
                return ajaxLoad(`/get-employees/${companyId}/${branchId ?? 'null'}/${divisionId ?? 'null'}/${departmentId ?? 'null'}/${sectionId ?? 'null'}`,
                    $('#employeeSelect'),'Select Employee (optional)',selected);
            }

            // Change handlers remain the same
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

            // EDIT MODE auto-load
            @if($isEdit)
            const editData = {
                company: "{{ $salaryData->company_id ?? '' }}",
                branch: "{{ $salaryData->branch_id ?? '' }}",
                division: "{{ $salaryData->division_id ?? '' }}",
                department: "{{ $salaryData->department_id ?? '' }}",
                section: "{{ $salaryData->section_id ?? '' }}",
                employee: "{{ $salaryData->employee_id ?? '' }}"
            };

            loadBranches(editData.company, editData.branch)
                .then(()=> loadDivisions(editData.company, editData.branch, editData.division))
                .then(()=> loadDepartments(editData.company, editData.branch, editData.division, editData.department))
                .then(()=> loadSections(editData.company, editData.branch, editData.division, editData.department, editData.section))
                .then(()=> loadEmployees(editData.company, editData.branch, editData.division, editData.department, editData.section, editData.employee));
            @endif

            // Pay Group Frequency Handling
            function handlePayGroupChange() {
                let selectedOption = $('#pay_group_id').find(':selected');
                let frequency = selectedOption.data('frequency');
                
                if (!frequency) {
                    $('#monthPickerContainer').removeClass('d-none');
                    $('#startDateContainer').addClass('d-none');
                    $('#endDateContainer').addClass('d-none');
                    $('#salary_month').attr('required', true);
                    $('#start_date, #end_date').removeAttr('required');
                    return;
                }

                if (frequency === 'monthly') {
                    $('#monthPickerContainer').removeClass('d-none');
                    $('#startDateContainer').addClass('d-none');
                    $('#endDateContainer').addClass('d-none');
                    $('#salary_month').attr('required', true);
                    $('#start_date, #end_date').removeAttr('required').val('');
                } else {
                    $('#monthPickerContainer').addClass('d-none');
                    $('#startDateContainer').removeClass('d-none');
                    $('#endDateContainer').removeClass('d-none');
                    $('#salary_month').removeAttr('required').val('');
                    $('#start_date, #end_date').attr('required', true);
                }
            }

            $('#pay_group_id').on('change', handlePayGroupChange);
            // Trigger on load for edit mode
            handlePayGroupChange();

            // BLOCK SUBMIT IF NO EMPLOYEES WERE LOADED (create mode only)
            @if(!$isEdit)
            $('#salaryProcessForm').on('submit', function(e) {
                const $empSelect = $('#employeeSelect');
                const optionCount = $empSelect.find('option').length;

                // Only the placeholder → no employees were loaded
                if (optionCount <= 1) {
                    e.preventDefault();

                    Swal.fire({
                        icon: 'warning',
                        title: 'No Eligible Employees Found',
                        html: 'No employees match the selected filters.<br><br>Please check or change company / branch / division / department / section.',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Understood'
                    });

                    return false;
                }

                // If at least one employee option exists → allow submit (even if nothing selected)
            });
            @endif

        });
    </script>
@endpush

