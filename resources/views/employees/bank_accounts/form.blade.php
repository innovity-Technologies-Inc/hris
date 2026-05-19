@extends('structure.master')
@section('content')
    @if(Route::currentRouteNamed('employees.bank_accounts.create'))
        @include('employees.partials.creation_button')
    @endif
    {{--    Form --}}
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Bank Details</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <form id="employeeForm" method="POST" action="{{ isset($employeeData) ? route('employees.bank_accounts.update', $employeeData->id) : route('employees.bank_accounts.store') }}">
                        @if(isset($employeeData))
                            @method('PUT')
                        @endif
                        @csrf

                        <div class="row">

                            <div class="col-md-6 mb-2">
                                <label for="employee_id" class="form-label">Employee Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" readonly
                                       value="{{ $employee->full_name }}">

                                <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                            </div>

                            <div class="col-md-6 mb-2">
                                <label for="bank_id" class="form-label">Bank <span
                                        class="text-danger">*</span></label>
                                <select id="bank_id" class="form-select select2_list" name="bank_id" required>
                                    <option value="">Select Bank</option>
                                    @foreach ($banks as $bank)
                                        <option value="{{ $bank->id }}"
                                                @if (isset($employeeData) && $employeeData->bank_id == $bank->id) selected
                                                @elseif (old('bank_id') == $bank->id) selected @endif>
                                            {{ $bank->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('bank_id')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-2">
                                <label for="branch_id" class="form-label">Branch</label>
                                <select id="branch_id" class="form-select select2_list" name="branch_id">
                                    <option value="">--Select Branch--</option>
                                </select>
                                @error('branch_id')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-2">
                                <label for="account_holder_name" class="form-label">Account Holder Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="account_holder_name" class="form-control" name="account_holder_name"
                                       placeholder="Enter Account Holder Name"
                                       value="{{ isset($employeeData) ? $employeeData->account_holder_name : old('account_holder_name') }}"
                                       required maxlength="255">
                                @error('account_holder_name')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-2">
                                <label for="account_number" class="form-label">Account Number <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="account_number" class="form-control" name="account_number"
                                       placeholder="Enter Account Number"
                                       value="{{ isset($employeeData) ? $employeeData->account_number : old('account_number') }}"
                                       required maxlength="255">
                                @error('account_number')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-2">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" name="status" id="status">
                                    <option value="active" @if (isset($employeeData) && $employeeData->status == 'active') selected
                                            @elseif (old('status') == 'active' || !old('status')) selected @endif>Active
                                    </option>
                                    <option value="inactive" @if (isset($employeeData) && $employeeData->status == 'inactive') selected
                                            @elseif (old('status') == 'inactive') selected @endif>Inactive
                                    </option>
                                </select>
                                @error('status')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-2">
                                <label for="remarks" class="form-label">Remarks</label>
                                <textarea id="remarks" class="form-control" name="remarks" rows="3"
                                          placeholder="Enter Remarks (Optional)">{{ isset($employeeData) ? $employeeData->remarks : old('remarks') }}</textarea>
                                @error('remarks')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-12 d-flex gap-2">
                                <button type="button" id="previewBtn" class="btn btn-info text-white">
                                    <i class="mdi mdi-eye me-1"></i> Preview
                                </button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="#" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
    @include('employees.partials.preview_modal')

    <script src="{{asset('assets/libs/jquery/jquery.min.js')}}"></script>

    <script>
        $(function() {

            function loadGrades(bankId, selectedGrade = null) {
                if (bankId) {
                    $.get('/get-branches/' + bankId, function(data) {
                        let $gradeSelect = $('#branch_id');
                        $gradeSelect.html('<option value="">-- Select --</option>');
                        $.each(data, function(key, value) {
                            let selected = (selectedGrade == value.id) ? 'selected' : '';
                            $gradeSelect.append('<option value="'+ value.id +'" '+selected+'>'+ value.name +'</option>');
                        });
                    });
                }
            }

            // --- Change Event ---
            $('#bank_id').on('change', function() {
                loadGrades($(this).val());
            });

            // --- Auto-load existing values from DB when editing ---
            @if(isset($employeeData))
            let bankId = "{{ old('bank_id', $employeeData->bank_id ?? '') }}";
            let branchId  = "{{ old('branch_id', $employeeData->branch_id ?? '') }}";

            if (bankId) {
                loadGrades(bankId, branchId);
            }
            @endif

        });
    </script>

@endsection
