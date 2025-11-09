@extends('structure.master')
@section('content')
    {{--    Form --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header">
                    <h5 class="card-title mb-0">Search Groups</h5>
                </div><!-- end card header -->
                <div class="card-header border-bottom p-4">
                    <div class="row align-items-start">

                        {{-- Filter Section --}}
                        <div class="col-md-12">
                            <div class="border rounded shadow-sm p-3 filter-section-bg">
                                <form id="filterForm">
                                    {{-- First Row: Keyword Search --}}
                                    <div class="row mb-2">
                                        <div class="col-12">
                                            <label for="keywordSearch" class="form-label text-muted small fw-semibold mb-1">
                                                Keyword Search
                                            </label>
                                            <div class="input-group input-group-md">
                                                <input type="text" class="form-control border-end-0" id="keywordSearch"
                                                    name="keyword" placeholder="Search groups by name or ID"
                                                    aria-label="Keyword Search" value="{{ request('keyword') }}">
                                                <span class="input-group-text border-start-0 input-group-bg">
                                                    <i class="mdi mdi-magnify text-muted"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Reset Button --}}
                                    <div class="row">
                                        <div class="col-12 text-end">
                                            <button type="button" id="resetFilters"
                                                class="btn btn-outline-secondary btn-sm">
                                                <i class="mdi mdi-refresh"></i> Reset
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">Add Group</h5>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form action="{{ route('groups.save') }}" method="post">
                                @csrf
                                <div class="mb-3 row">
                                    <div class="col-lg-8">
                                        <label for="simpleinput" class="form-label">Group Name<span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="simpleinput" class="form-control" name="name"
                                            placeholder="Enter Group Name" value="{{ old('name') }}">
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
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

    {{--    list --}}

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
                                @php($i = 1)
                                @foreach ($groups as $group)
                                    <tr>
                                        <th scope="row">{{ $i++ }}</th>
                                        <td>{{ $group->name }}</td>
                                        <td>
                                            @if ($group->status == 'active')
                                                <span class="badge text-bg-success">Active</span>
                                            @else
                                                <span class="badge text-bg-danger">Inactive</span>
                                            @endif
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#group-edit{{ $group->id }}">
                                                <i style="height: 12px; width: 12px" data-feather="edit"></i>
                                            </button>

                                            <form action="{{ route('groups.delete', $group->id) }}" method="POST"
                                                style="display: inline-block">
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
                        <div class="mt-3">
                            {{ $groups->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        document.getElementById('resetFilters').addEventListener('click', function() {
            document.getElementById('keywordSearch').value = '';
            document.getElementById('filterForm').submit();
        });
    </script>
@endsection
