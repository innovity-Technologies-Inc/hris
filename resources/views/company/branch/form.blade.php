@extends('structure.master')
@section('content')

    {{--    Form--}}

    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">{{isset($branch) ? 'Edit' : 'Add'}} Branch</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form action="{{isset($branch) ? route('branches.update', $branch->id) : route('branches.store')}}"
                                  method="post" enctype="multipart/form-data">
                                @csrf

                                @if(isset($branch))
                                    @method('PUT')
                                @endif

                                <div class="row">

                                    <div class="col-lg-6 mb-2">
                                        <label for="simpleinput" class="form-label">Branch Name<span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="simpleinput" class="form-control" name="name"
                                               placeholder="Enter Branch Name"
                                               value="{{ isset($branch)? $branch->name : old('name')}}">
                                        @error('name')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="col-lg-6 mb-2">
                                        <label for="simpleinput" class="form-label">Select Bank<span
                                                class="text-danger">*</span></label>
                                        <select class="form-select select2_list" name="bank_id">
                                            <option value="">Choose Bank</option>
                                            @foreach($banks as $item)
                                                <option value="{{$item->id}}"
                                                        @if(isset($branch) && $branch->bank_id == $item->id) selected @endif>
                                                    {{$item->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('bank_id')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="col-lg-6 mb-2">
                                        <label for="simpleinput" class="form-label">Routing No<span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="simpleinput" class="form-control" name="routing_no"
                                               placeholder="Enter Routing No" value="{{isset($branch)? $branch->routing_no: old('routing_no')}}">
                                        @error('routing_no')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>


                                    <div class="col-lg-6 mb-2">
                                        <label for="simpleinput" class="form-label">Swift Code<span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="simpleinput" class="form-control" name="swift_code"
                                               placeholder="Enter Swift Code" value="{{ isset($branch)? $branch->swift_code : old('swift_code')}}">
                                        @error('swift_code')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="col-lg-12 mb-2">
                                        <label for="simpleinput" class="form-label">Address<span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="simpleinput" class="form-control" name="address"
                                               placeholder="Enter Branch Address" value="{{ isset($branch)? $branch->address : old('address')}}">
                                        @error('address')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="col-lg-12 mb-2">
                                        <label for="simpleinput" class="form-label">Remarks<span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="simpleinput" class="form-control" name="remarks"
                                               placeholder="Enter Remarks" value="{{ isset($branch)? $branch->remarks : old('remarks')}}">
                                        @error('remarks')
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

