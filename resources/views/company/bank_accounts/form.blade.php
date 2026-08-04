@extends('structure.master')
@section('content')

    {{--    Form--}}

    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">{{isset($bank_account) ? 'Edit' : 'Add'}} Bank Account</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form action="{{isset($bank_account) ? route('bank_accounts.update', $bank_account->id) : route('bank_accounts.store')}}"
                                  method="post" enctype="multipart/form-data">
                                @csrf

                                @if(isset($bank_account))
                                    @method('PUT')
                                @endif

                                <div class="row">

                                    <div class="col-lg-6 mb-2">
                                        <label for="simpleinput" class="form-label">Account No<span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="simpleinput" class="form-control" name="account_no"
                                               placeholder="Enter Bank Account No"
                                               value="{{ isset($bank_account)? $bank_account->account_no : old('account_no')}}">
                                        @error('account_no')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>


                                    <div class="col-lg-6 mb-2">
                                        <label for="simpleinput" class="form-label">Account Holder Name<span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="simpleinput" class="form-control" name="holder_name"
                                               placeholder="Enter Account Holder Name" value="{{isset($bank_account)? $bank_account->holder_name: old('holder_name')}}">
                                        @error('holder_name')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="col-lg-4 mb-2">
                                        <label for="bank_id" class="form-label">Select Bank<span
                                                class="text-danger">*</span></label>
                                        <select id="bank_id" class="form-select select2_list" name="bank_id">
                                            <option value="">Choose Bank</option>
                                            @foreach($banks as $item)
                                                <option value="{{$item->id}}"
                                                        @if(isset($bank_account) && $bank_account->bank_id == $item->id) selected @endif>
                                                    {{$item->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('bank_id')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="col-lg-4 mb-2">
                                        <label for="branch_id" class="form-label">Select Branch<span
                                                class="text-danger">*</span></label>
                                        <select id="branch_id" class="form-select select2_list" name="branch_id">
                                            <option value="">Choose Branch</option>
                                        </select>
                                        @error('branch_id')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>



                                    <div class="col-lg-4 mb-2">
                                        <label for="example-select" class="form-label">Account Type<span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" name="account_type">
                                            <option value="current">Current</option>
                                            <option value="savings">Savings</option>
                                            <option value="credit">Credit</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-6 mb-2">
                                        <label for="simpleinput" class="form-label">Contact Person<span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="simpleinput" class="form-control" name="contact_person"
                                               placeholder="Enter Contact Person Name" value="{{ isset($bank)? $bank->contact_person : old('contact_person')}}">
                                        @error('contact_person')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="col-lg-6 mb-2">
                                        <label for="simpleinput" class="form-label">Contact Person No<span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="simpleinput" class="form-control" name="contact_person_no"
                                               placeholder="Enter Contact Person No" value="{{ isset($bank)? $bank->contact_person_no : old('contact_person_no')}}">
                                        @error('contact_person_no')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>


                                    <div class="col-lg-6 mb-2">
                                        <label for="simpleinput" class="form-label">Email Address<span
                                                class="text-danger">*</span></label>
                                        <input type="email" id="simpleinput" class="form-control" name="email"
                                               placeholder="Enter Email Address" value="{{ isset($bank_account)? $bank_account->email : old('email')}}">
                                        @error('email')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="col-lg-6 mb-2">
                                        <label for="example-select" class="form-label">Status</label>
                                        <select class="form-select" name="status">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>

                                </div>

                                <button type="submit" class="btn btn-primary">Submit</button>


                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{asset('assets/libs/jquery/jquery.min.js')}}"></script>
    <script>
        $(function() {
            function loadBranches(bankId, selectedBranch = null) {
                if (bankId) {
                    $.get('/get-branches/' + bankId, function(data) {
                        let $branchSelect = $('#branch_id');
                        $branchSelect.html('<option value="">Choose Branch</option>');
                        $.each(data, function(key, value) {
                            let selected = (selectedBranch == value.id) ? 'selected' : '';
                            let label = value.name + (value.routing_no ? ' - ' + value.routing_no : '');
                            $branchSelect.append('<option value="'+ value.id +'" '+selected+'>'+ label +'</option>');
                        });
                        $branchSelect.trigger('change');
                    });
                } else {
                    $('#branch_id').html('<option value="">Choose Branch</option>').trigger('change');
                }
            }

            // --- Change Event ---
            $('#bank_id').on('change', function() {
                loadBranches($(this).val());
            });

            // --- Auto-load existing values ---
            let bankId = "{{ old('bank_id', $bank_account->bank_id ?? '') }}";
            let branchId  = "{{ old('branch_id', $bank_account->branch_id ?? '') }}";

            if (bankId) {
                loadBranches(bankId, branchId);
            }
        });
    </script>
@endpush

