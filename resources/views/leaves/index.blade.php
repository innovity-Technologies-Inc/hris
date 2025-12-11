@extends('structure.master')

@section('content')

    {{-- Leave Applications List --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header">
                    <h5 class="card-title mb-0">Search Leave Applications</h5>
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
                                            <label for="keywordSearch"
                                                   class="form-label text-muted small fw-semibold mb-1">
                                                Keyword Search
                                            </label>
                                            <div class="input-group input-group-md">
                                                <input type="text" class="form-control border-end-0" id="keywordSearch"
                                                       name="keyword"
                                                       placeholder="Search by employee name, ID, or leave plan"
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

        <div class="col-lg-12 mt-3">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header">
                    <h5 class="card-title mb-0">Leave Applications List</h5>
                </div>
                <div class="card-body">
                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-between mb-3">
                        <a type="button" class="btn btn-warning btn-sm" href="{{ route('leaves.create') }}">
                            <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
                        </a>
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                data-bs-target="#bulkUploadModal">
                            <i style="height: 12px; width: 12px" data-feather="upload"></i> Upload Bulk
                        </button>
                    </div>

                    @if ($leaves->isEmpty())
                        <div class="text-center py-4 text-muted">No leave applications found.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Employee Name</th>
                                    <th scope="col">Plan Name</th>
                                    <th scope="col">Days</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" style="width: 180px;">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $sl = 1; @endphp
                                @foreach ($leaves as $application)
                                    <tr>
                                        <th scope="row">{{ $sl++ }}</th>
                                        <td>{{ $application->getEmployee->full_name }}</td>
                                        <td>{{ $application->getPlan->name }}</td>
                                        <td>{{ $application->leave_count }}</td>
                                        <td>
                                            @if ($application->status == 'pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @elseif($application->status == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-secondary btn-sm" title="View"
                                                    data-bs-toggle="modal" data-bs-target="#viewLeaveModal">
                                                <i style="height: 12px; width: 12px" data-feather="eye"></i>
                                            </button>
                                            {{-- Include View Modal --}}
                                            @include('leaves.partials.view_modal')
                                            @if ($application->status == 'pending')
                                                <form class="d-inline"
                                                    action="{{ route('leaves.change_status') }}" method="post">
                                                    @method('put')
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{$application->id}}">
                                                    <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="btn btn-success btn-sm confirmApprove" title="Approve">
                                                    <i style="height: 12px; width: 12px" data-feather="check"></i>
                                                </button>
                                                </form>
                                                <form class="d-inline" method="post"
                                                    action="{{ route('leaves.change_status') }}">
                                                    @method('put')
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{$application->id}}">
                                                    <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-danger btn-sm confirmReject" title="Reject">
                                                    <i style="height: 12px; width: 12px" data-feather="x"></i>
                                                </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('leaves.destroy', $application->id) }}" method="POST"
                                                  class="d-inline">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-outline-danger btn-sm confirmDelete"
                                                        title="Delete">
                                                    <i style="height: 12px; width: 12px" data-feather="trash-2"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>



    {{-- Include Import Modal --}}
    @include('leaves.partials.import_modal')

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        $(document).ready(function () {
            // Reset filters
            $('#resetFilters').on('click', function () {
                $('#filterForm')[0].reset();
                $('.select2_list').val(null).trigger('change');
            });
        });
    </script>
@endsection
