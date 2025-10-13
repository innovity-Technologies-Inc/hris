@extends('structure.master')
@section('content')

    {{--    Form--}}

    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">{{isset($tofsil) ? 'Edit' : 'Add'}} Tofsil</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form
                                action="{{isset($tofsil) ? route('tofsils.update', $tofsil->id) : route('tofsils.store')}}"
                                method="post" enctype="multipart/form-data">
                                @csrf

                                @if(isset($tofsil))
                                    @method('PUT')
                                @endif

                                <div class="row">

                                    <div class="col-lg-12 mb-2">
                                        <label for="simpleinput" class="form-label">Tofsil Name<span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="simpleinput" class="form-control" name="name"
                                               placeholder="Enter Tofsil Name"
                                               value="{{ isset($tofsil)? $tofsil->name : old('name')}}">
                                        @error('name')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>


                                    <div class="col-lg-12 mb-2">
                                        <label for="simpleinput" class="form-label">Description<span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control" name="description" id="editor1"
                                                  cols="30">{{ isset($tofsil)? $tofsil->description : old('description')}}</textarea>
                                        @error('description')
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
