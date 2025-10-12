@extends('structure.master')
@section('content')

    {{--    Form--}}

    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">Add Company Type</h5>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form action="{{route('company_types.save')}}" method="post">
                                @csrf
                                <div class="mb-3 row">
                                    <div class="col-lg-4">
                                        <label for="simpleinput" class="form-label">Company Type Name</label>
                                        <input type="text" id="simpleinput" class="form-control" name="name"
                                               placeholder="Enter Company Type Name" value="{{old('name')}}">

                                        @error('name')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror

                                    </div>

                                    <div class="col-lg-4">
                                        <label for="simpleinput" class="form-label">Short Name</label>
                                        <input type="text" id="simpleinput" class="form-control" name="short_name"
                                               placeholder="Enter Short Name" value="{{old('short_name')}}">
                                        @error('short_name')
                                        <small class="text-danger">{{$message}}</small>
                                        @enderror
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="example-select" class="form-label">Status</label>
                                        <select class="form-select" id="example-select" name="status">
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

    {{--    list--}}

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Company Type List</h5>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Short Name</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($i=1)
                            @foreach($company_types as $company_type)
                                <tr>
                                    <th scope="row">{{$i++}}</th>
                                    <td>{{$company_type->name}}</td>
                                    <td>{{$company_type->short_name}}</td>
                                    <td>
                                        @if($company_type->status == 'active')
                                            <span class="badge text-bg-success">Active</span>
                                        @else
                                            <span class="badge text-bg-danger">Inactive</span>

                                    @endif
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm"  data-bs-toggle="modal" data-bs-target="#company_type-edit{{$company_type->id}}">
                                            <i style="height: 12px; width: 12px" data-feather="edit"></i>
                                        </button>

                                        <form action="{{route('company_types.delete', $company_type->id)}}" method="POST" style="display: inline-block">
                                            @csrf
                                            @method('DELETE')

                                            <button class ="btn btn-sm btn-danger confirmDelete">
                                                <i style="height: 12px; width: 12px" data-feather="trash"></i>
                                            </button>


                                        </form>

                                    </td>

                                    @include('company_setup.modal.company_type_edit')

                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                        <div class="mt-3">
                            {{$company_types->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>




@endsection
