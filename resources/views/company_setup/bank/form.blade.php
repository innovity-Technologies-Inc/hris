@extends('structure.master')
@section('content')

    {{--    Form--}}

    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">{{isset($bank) ? 'Edit' : 'Add'}} Bank</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form action="{{isset($bank) ? route('banks.update', $bank->id) : route('banks.store')}}"
                                  method="post" enctype="multipart/form-data">
                                @csrf

                                @if(isset($bank))
                                    @method('PUT')
                                @endif

                                <div class="row">

                                    <div class="col-lg-8 mb-2">
                                        <label for="simpleinput" class="form-label">Bank Name</label>
                                        <input type="text" id="simpleinput" class="form-control" name="name"
                                               placeholder="Enter Bank Name"
                                               value="{{ isset($bank)? $bank->name : old('name')}}">
                                        @error('name')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="col-lg-4 mb-2">
                                        <label for="simpleinput" class="form-label">Short Name</label>
                                        <input type="text" id="simpleinput" class="form-control" name="short_name"
                                               placeholder="Enter Bank Short Name" value="{{isset($bank)? $bank->short_name: old('short_name')}}">
                                        @error('short_name')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="col-lg-6 mb-2">
                                        <label for="simpleinput" class="form-label">Bank Code</label>
                                        <input type="text" id="simpleinput" class="form-control" name="bank_code"
                                               placeholder="Enter Bank Code" value="{{isset($bank)? $bank->bank_code: old('bank_code')}}">
                                        @error('bank_code')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>


                                    <div class="col-lg-6 mb-2">
                                        <label for="simpleinput" class="form-label">Contact No</label>
                                        <input type="text" id="simpleinput" class="form-control" name="contact_no"
                                               placeholder="Enter Bank Contact No" value="{{ isset($bank)? $bank->contact_no : old('contact_no')}}">
                                        @error('contact_no')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="col-lg-6 mb-2">
                                        <label for="simpleinput" class="form-label">Contact Person</label>
                                        <input type="text" id="simpleinput" class="form-control" name="contact_person"
                                               placeholder="Enter Contact Person Name" value="{{ isset($bank)? $bank->contact_person : old('contact_person')}}">
                                        @error('contact_person')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="col-lg-6 mb-2">
                                        <label for="simpleinput" class="form-label">Contact Person No</label>
                                        <input type="text" id="simpleinput" class="form-control" name="contact_person_no"
                                               placeholder="Enter Contact Person No" value="{{ isset($bank)? $bank->contact_person_no : old('contact_person_no')}}">
                                        @error('contact_person_no')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="col-lg-12 mb-2">
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
