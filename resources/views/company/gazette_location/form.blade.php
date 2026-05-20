@extends('structure.master')
@section('content')

    {{--    Form--}}

    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">{{isset($gazette_location) ? 'Edit' : 'Add'}} Gazette Location</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form
                                action="{{isset($gazette_location) ? route('gazette_locations.update', $gazette_location->id) : route('gazette_locations.store')}}"
                                method="post" enctype="multipart/form-data">
                                @csrf

                                @if(isset($gazette_location))
                                    @method('PUT')
                                @endif

                                <div class="row">

                                    <div class="col-lg-12 mb-2">
                                        <label for="simpleinput" class="form-label">Gazette Location<span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="simpleinput" class="form-control" name="name"
                                               placeholder="Enter Gazette Location"
                                               value="{{ isset($gazette_location)? $gazette_location->name : old('name')}}">
                                        @error('name')
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

