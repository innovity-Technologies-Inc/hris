@extends('structure.master')

@section('content')
    {{-- List of Company Locations --}}
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    @can('company-branches.create')
                    <a type="button" class="btn btn-warning btn-sm" href="{{ route('company_locations.create') }}">
                        <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
                    </a>
                    @endcan
                </div>
                {{-- Search Filter Form --}}
                <form id="filterForm">
                    <div class="row mb-1 mt-2 mx-4">
                        <div class="col-12">
                            <div class="input-group input-group-md">
                                <input type="text" class="form-control border-end-0" id="keywordSearch" name="keyword"
                                    placeholder="Search branches by keyword" aria-label="Keyword Search">
                                <span class="input-group-text border-start-0 input-group-bg">
                                    <i class="mdi mdi-magnify text-muted"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="card-body" id="search-result">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Branch Name</th>
                                    <th scope="col">Company Name</th>
                                    <th scope="col">Location Address</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $sl = \App\HelperClass::indexNumberSerialization($locations);
                                @endphp
                                @foreach ($locations as $location)
                                    <tr>
                                        <th scope="row">{{ $sl++ }}</th>
                                        <td>{{ $location->name }}</td>
                                        <td>{{ $location->getCompany->name }}</td>
                                        <td>{{ Str::limit($location->location_address, 30) }}</td>
                                        <td>
                                            @can('company-branches.view')
                                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#viewLocationModal{{ $location->id }}">
                                                <i style="height: 12px; width: 12px" data-feather="eye"></i>
                                            </button>
                                            @endcan

                                            @can('company-branches.edit')
                                            <a href="{{ route('company_locations.edit', $location->id) }}"
                                                class="btn btn-primary btn-sm">
                                                <i style="height: 12px; width: 12px" data-feather="edit"></i>
                                            </a>
                                            @endcan

                                            @can('company-branches.delete')
                                            <form action="{{ route('company_locations.destroy', $location->id) }}"
                                                method="POST" style="display: inline-block">
                                                @csrf
                                                @method('DELETE')

                                                <button class="btn btn-sm btn-danger confirmDelete">
                                                    <i style="height: 12px; width: 12px" data-feather="trash"></i>
                                                </button>
                                            </form>
                                            @endcan
                                        </td>
                                    </tr>

                                    @can('company-branches.view')
                                    @include('company.company_locations.view_modal')
                                    @endcan
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-3">
                            {{ $locations->links() }}
                        </div>
                    </div>
                </div>
            </div><!-- end card -->
        </div>
    </div><!-- end row -->
@endsection

