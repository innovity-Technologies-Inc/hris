@extends('structure.master')
@section('content')
    {{--    list --}}

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <a type="button" class="btn btn-warning btn-sm" href="{{ route('companies.create') }}">
                        <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
                    </a>
                </div><!-- end card header -->
                
                {{-- Search Filter Form --}}
                <form id="filterForm">
                    <div class="row mb-1 mt-2 mx-4">
                        <div class="col-12">
                            <div class="input-group input-group-md">
                                <input type="text" class="form-control border-end-0" id="keywordSearch" name="keyword"
                                    placeholder="Search companies by name" aria-label="Keyword Search"
                                    value="{{ request('keyword') }}">
                                <span class="input-group-text border-start-0 input-group-bg">
                                    <i class="mdi mdi-magnify text-muted"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>


            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Logo</th>
                                <th scope="col">Name</th>
                                <th scope="col">Type</th>
                                <th scope="col">Group</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $sl = \App\HelperClass::indexNumberSerialization($companies);
                            @endphp
                            @foreach ($companies as $item)
                                <tr>
                                    <th scope="row">{{ $sl++ }}</th>
                                    <td>
                                        <img src="{{ asset('storage/' . $item->logo) }}" height="24px" alt="Logo">
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->getCompanyType->name }}</td>
                                    <td>{{ $item->getGroup->name }}</td>

                                    <td>
                                        @if ($item->status == 'active')
                                            <span class="badge text-bg-success">Active</span>
                                        @else
                                            <span class="badge text-bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#companyView{{ $item->id }}">
                                            <i style="height: 12px; width: 12px" data-feather="eye"></i>
                                        </button>

                                        <a type="button" class="btn btn-primary btn-sm"
                                            href="{{ route('companies.edit', $item->id) }}">
                                            <i style="height: 12px; width: 12px" data-feather="edit"></i>
                                        </a>

                                        @include('company_setup.company.modal.view')

                                        <form action="{{ route('companies.delete', $item->id) }}" method="POST"
                                            style="display: inline-block">
                                            @csrf
                                            @method('DELETE')

                                            <button class ="btn btn-sm btn-danger confirmDelete">
                                                <i style="height: 12px; width: 12px" data-feather="trash"></i>
                                            </button>


                                        </form>

                                    </td>

                                </tr>
                            @endforeach



                        </tbody>
                    </table>


                    <div class="mt-3">
                        {{ $companies->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    </div>
@endsection
