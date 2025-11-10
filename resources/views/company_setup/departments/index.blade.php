@extends('structure.master')

@section('content')
    {{-- List of Company Locations --}}
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                 <div class="card-header">
                    <a type="button" class="btn btn-warning btn-sm" href="{{route('departments.create')}}">
                        <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
                    </a>
                </div><!-- end card header -->


                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Division Name</th>
                                    <th scope="col">Department Name</th>
                                    <th scope="col">Short Name</th>
                                    <th scope="col">Job No. Code</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php
                                $sl = \App\HelperClass::indexNumberSerialization($departments);
                            @endphp
                                @foreach ($departments as $department)
                                    <tr>
                                        <th scope="row">{{ $sl++ }}</th>
                                        <td>{{ $department->getDivision->division_name }}</td>
                                        <td>{{ $department->department_name }}</td>
                                        <td>{{ $department->short_name }}</td>
                                        <td>{{ $department->job_number_code }}</td>
                                        <td>
                                            <a href="{{ route('departments.edit', $department->id) }}"
                                                class="btn btn-primary btn-sm">
                                                <i style="height: 12px; width: 12px" data-feather="edit"></i>
                                            </a>

                                            <form action="{{ route('departments.delete', $department->id) }}"
                                                method="POST" style="display: inline-block">
                                                @csrf
                                                @method('DELETE')

                                                <button class="btn btn-sm btn-danger confirmDelete">
                                                    <i style="height: 12px; width: 12px" data-feather="trash"></i>
                                                </button>
                                            </form>
                                        </td>


                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-3">
                            {{$departments->links()}}
                        </div>
                    </div>
                </div>
            </div><!-- end card -->
        </div>
    </div><!-- end row -->
@endsection
