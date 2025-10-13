@extends('structure.master')

@section('content')
    {{-- List of Designations --}}
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <a type="button" class="btn btn-warning btn-sm" href="{{ route('designations.create') }}">
                        <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
                    </a>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Company Name</th>
                                    <th scope="col">Location Name</th>
                                    <th scope="col">Division Name</th>
                                    <th scope="col">Designation Level</th>
                                    <th scope="col">Company Designation</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($i = 1)
                                @foreach ($designations as $designation)
                                    <tr>
                                        <th scope="row">{{ $i++ }}</th>
                                        <td>{{ $designation->getCompany->name }}</td>
                                        <td>{{ $designation->getLocation->unit_name }}</td>
                                        <td>{{ $designation->getDivision->division_name }}</td>
                                        <td>{{ $designation->designation_level }}</td>
                                        <td>{{ $designation->company_designation }}</td>
                                        <td>
                                            @if($designation->status == 'active')
                                                <span class="badge text-bg-success">Active</span>
                                            @else
                                                <span class="badge text-bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('designations.edit', $designation->id) }}" class="btn btn-primary btn-sm">
                                                <i style="height: 12px; width: 12px" data-feather="edit"></i>
                                            </a>

                                            <form action="{{ route('designations.delete', $designation->id) }}" method="POST" style="display: inline-block">
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
                            {{ $designations->links() }}
                        </div>
                    </div>
                </div>
            </div><!-- end card -->
        </div>
    </div><!-- end row -->
@endsection
