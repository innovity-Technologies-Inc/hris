@extends('structure.master')
@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{--    Form--}}

    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">{{isset($company) ? 'Edit' : 'Add'}} Company</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form action="{{isset($company) ? route('companies.update', $company->id) : route('companies.store')}}"
                                  method="post" enctype="multipart/form-data">
                                @csrf

                                @if(isset($company))
                                    @method('PUT')
                                @endif

                                <div class="row">

                                    <div class="col-lg-8 mb-2">
                                        <label for="simpleinput" class="form-label">Company Name<span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="simpleinput" class="form-control" name="name"
                                               placeholder="Enter Company Name"
                                               value="{{ isset($company)? $company->name : old('name')}}">
                                        @error('name')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="col-lg-4 mb-2">
                                        <label for="simpleinput" class="form-label">Short Name<span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="simpleinput" class="form-control" name="short_name"
                                               placeholder="Enter Company Short Name" value="{{isset($company)? $company->short_name: old('short_name')}}">
                                        @error('short_name')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="col-lg-12 mb-2">
                                        <label for="simpleinput" class="form-label">Address<span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="simpleinput" class="form-control" name="address"
                                               placeholder="Enter Company Address" value="{{isset($company)? $company->address: old('address')}}">
                                        @error('address')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="col-lg-6 mb-2">
                                        <label for="example-select" class="form-label">Company Group<span
                                                class="text-danger">*</span></label>
                                        <select class="form-select select2_list" name="group_id">
                                            <option value="">Choose Group</option>
                                            @foreach($groups as $item)
                                                <option value="{{$item->id}}"
                                                        @if(isset($company) && $company->group_id == $item->id) selected @endif>
                                                    {{$item->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('group_id')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="col-lg-6 mb-2">
                                        <label for="example-select" class="form-label">Company Type<span
                                                class="text-danger">*</span></label>
                                        <select class="form-select select2_list" name="type_id">
                                            <option value="">Choose Company Type</option>
                                            @foreach($company_types as $item)
                                                <option value="{{$item->id}}"
                                                        @if(isset($company) && $company->type_id == $item->id) selected @endif>
                                                    {{$item->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('type_id')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>


                                    <div class="col-lg-6 mb-2">
                                        <label for="simpleinput" class="form-label">Fax<span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="simpleinput" class="form-control" name="fax"
                                               placeholder="Enter Company Fax" value="{{ isset($company)? $company->fax : old('fax')}}">
                                        @error('fax')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="col-lg-6 mb-2">
                                        <label for="simpleinput" class="form-label">Telephone<span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="simpleinput" class="form-control" name="telephone"
                                               placeholder="Enter Company Telephone" value="{{ isset($company)? $company->telephone : old('telephone')}}">
                                        @error('telephone')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="col-lg-6 mb-2">
                                        <label for="simpleinput" class="form-label">Email<span
                                                class="text-danger">*</span></label>
                                        <input type="email" id="simpleinput" class="form-control" name="email"
                                               placeholder="Enter Company Email" value="{{ isset($company)? $company->email : old('email')}}">
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

                                    <div class="col-lg-12 mb-2">
                                        <label for="example-select" class="form-label">Upload Logo</label>
                                        <div class="input-group mb-3">
                                            <input type="file" class="form-control filepond" name="logo">
                                        </div>
                                    </div>
                                    @error('logo')
                                    <small class="text-danger">{{$message}}</small>
                                    @enderror

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
