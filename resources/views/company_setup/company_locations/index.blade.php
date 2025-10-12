@extends('structure.master')

@section('content')
    {{-- List of Company Locations --}}
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                 <div class="card-header">
                    <a type="button" class="btn btn-warning btn-sm" href="{{route('company_locations.create')}}">
                        <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
                    </a>
                </div><!-- end card header -->
                <div class="card-header">
                    <h5 class="card-title mb-0">Company Location List</h5>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Unit Name</th>
                                    <th scope="col">Company Name</th>
                                    <th scope="col">Location Address</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($i = 1)
                                @foreach ($locations as $location)
                                    <tr>
                                        <th scope="row">{{ $i++ }}</th>
                                        <td>{{ $location->unit_name }}</td>
                                        <td>{{ $location->getCompany->name }}</td>
                                        <td>{{ $location->location_address }}</td>
                                        <td>
                                            <a href="{{ route('company_locations.edit', $location->id) }}"
                                                class="btn btn-primary btn-sm">
                                                <i style="height: 12px; width: 12px" data-feather="edit"></i>
                                            </a>

                                            <form action="{{ route('company_locations.destroy', $location->id) }}"
                                                method="POST" style="display: inline-block">
                                                @csrf
                                                @method('DELETE')

                                                <button class="btn btn-sm btn-danger confirmDelete">
                                                    <i style="height: 12px; width: 12px" data-feather="trash"></i>
                                                </button>
                                            </form>
                                        </td>

                                        {{-- @include('company_setup.modal.location_edit') --}}

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        <div class="mt-3">
                            {{$locations->links()}}
                        </div>
                    </div>
                </div>
            </div><!-- end card -->
        </div>
    </div><!-- end row -->
@endsection
