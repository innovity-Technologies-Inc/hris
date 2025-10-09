@extends('structure.master')
@section('content')

    {{--    Form--}}

    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">Add Group</h5>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form action="{{route('groups.save')}}" method="post">
                                @csrf
                                <div class="mb-3 row">
                                    <div class="col-lg-8">
                                        <label for="simpleinput" class="form-label">Group Name</label>
                                        <input type="text" id="simpleinput" class="form-control" name="name"
                                               placeholder="Enter Group Name" value="{{old('name')}}">
                                        @error('name')
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
                    <h5 class="card-title mb-0">Group List</h5>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($i=1)
                            @foreach($groups as $group)
                                <tr>
                                    <th scope="row">{{$i++}}</th>
                                    <td>{{$group->name}}</td>
                                    <td>
                                        @if($group->status == 'active')
                                            <span class="badge text-bg-success">Active</span>
                                        @else
                                            <span class="badge text-bg-danger">Inactive</span>

                                    @endif
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm"  data-bs-toggle="modal" data-bs-target="#group-edit{{$group->id}}">
                                            <i style="height: 12px; width: 12px" data-feather="edit"></i>
                                        </button>

                                        <form action="{{route('groups.delete', $group->id)}}" method="POST" style="display: inline-block">
                                            @csrf
                                            @method('DELETE')

                                            <button class ="btn btn-sm btn-danger confirmDelete">
                                                <i style="height: 12px; width: 12px" data-feather="trash"></i>
                                            </button>


                                        </form>

                                    </td>

                                    @include('company_setup.modal.group_edit')

                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>




@endsection
