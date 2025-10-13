@extends('structure.master')
@section('content')

    {{--    Form--}}

    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">{{isset($salary_grade) ? 'Edit' : 'Add'}} Salary Grade</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form
                                action="{{isset($salary_grade) ? route('salary_grades.update', $salary_grade->id) : route('salary_grades.store')}}"
                                method="post" enctype="multipart/form-data">
                                @csrf

                                @if(isset($salary_grade))
                                    @method('PUT')
                                @endif

                                <div class="row">

                                    <div class="col-lg-12 mb-2">
                                        <label for="simpleinput" class="form-label">Salary Grade<span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="simpleinput" class="form-control" name="name"
                                               placeholder="Enter Salary Grade"
                                               value="{{ isset($salary_grade)? $salary_grade->name : old('name')}}">
                                        @error('name')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="col-lg-12 mb-2">
                                        <label for="example-select" class="form-label">Tofsil Name<span
                                                class="text-danger">*</span></label>
                                        <select class="form-select select2_list" name="tofsil_id">
                                            <option value="">Choose Tofsil Name</option>
                                            @foreach($tofsils as $item)
                                                <option value="{{$item->id}}"
                                                        @if(isset($salary_grade) && $salary_grade->tofsil_id == $item->id) selected @endif>
                                                    {{$item->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('tofsil_id')
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
