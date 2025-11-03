@extends('structure.master')
@section('content')
    {{--    Form --}}

    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">{{ isset($employeeBankAccount) ? 'Edit' : 'Add' }} Employee Bank Account</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form
                                action="{{ isset($employeeBankAccount) ? route('employee-bank-accounts.update', $employeeBankAccount->id) : route('employee-bank-accounts.store') }}"
                                method="post">
                                @csrf
                                @if (isset($employeeBankAccount))
                                    @method('PUT')
                                @endif

                                <div class="row">

                                    <div class="col-md-6 mb-2">
                                        <label for="employee_id" class="form-label">Employee <span
                                                class="text-danger">*</span></label>
                                        <select id="employee_id" class="form-select select2_list" name="employee_id" required>
                                            <option value="">Select Employee</option>
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}"
                                                        @if (isset($employeeBankAccount) && $employeeBankAccount->employee_id == $employee->id) selected
                                                        @elseif (old('employee_id') == $employee->id) selected @endif>
                                                    {{ $employee->full_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('employee_id')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <label for="bank_id" class="form-label">Bank <span
                                                class="text-danger">*</span></label>
                                        <select id="bank_id" class="form-select select2_list" name="bank_id" required>
                                            <option value="">Select Bank</option>
                                            @foreach ($banks as $bank)
                                                <option value="{{ $bank->id }}"
                                                        @if (isset($employeeBankAccount) && $employeeBankAccount->bank_id == $bank->id) selected
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
                                            <option value="">Select Branch</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}" data-bank-id="{{ $branch->bank_id }}"
                                                        @if (isset($employeeBankAccount) && $employeeBankAccount->branch_id == $branch->id) selected
                                                        @elseif (old('branch_id') == $branch->id) selected @endif>
                                                    {{ $branch->name }}
                                                </option>
                                            @endforeach
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
                                               value="{{ isset($employeeBankAccount) ? $employeeBankAccount->account_holder_name : old('account_holder_name') }}"
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
                                               value="{{ isset($employeeBankAccount) ? $employeeBankAccount->account_number : old('account_number') }}"
                                               required maxlength="255">
                                        @error('account_number')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-select" name="status" id="status">
                                            <option value="active" @if (isset($employeeBankAccount) && $employeeBankAccount->status == 'active') selected
                                                    @elseif (old('status') == 'active' || !old('status')) selected @endif>Active
                                            </option>
                                            <option value="inactive" @if (isset($employeeBankAccount) && $employeeBankAccount->status == 'inactive') selected
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
                                                  placeholder="Enter Remarks (Optional)">{{ isset($employeeBankAccount) ? $employeeBankAccount->remarks : old('remarks') }}</textarea>
                                        @error('remarks')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <a href="#" class="btn btn-secondary">Cancel</a>
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
