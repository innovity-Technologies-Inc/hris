@extends('structure.master')

@section('content')
    @php
        // Dummy employee data as objects
        $dummyEmployees = collect([
            (object) [
                'id' => 1,
                'full_name' => 'John Doe',
                'applicant_id' => 'EMP001',
                'system_id' => 'SYS001',
                'photo_path' => null,
            ],
            (object) [
                'id' => 2,
                'full_name' => 'Jane Smith',
                'applicant_id' => 'EMP002',
                'system_id' => 'SYS002',
                'photo_path' => null,
            ],
            (object) [
                'id' => 3,
                'full_name' => 'Mike Johnson',
                'applicant_id' => 'EMP003',
                'system_id' => 'SYS003',
                'photo_path' => null,
            ],
            (object) [
                'id' => 4,
                'full_name' => 'Sarah Williams',
                'applicant_id' => 'EMP004',
                'system_id' => 'SYS004',
                'photo_path' => null,
            ],
            (object) [
                'id' => 5,
                'full_name' => 'David Brown',
                'applicant_id' => 'EMP005',
                'system_id' => 'SYS005',
                'photo_path' => null,
            ],
        ]);

        // Dummy leave plans as objects
        $dummyLeavePlans = collect([
            (object) ['id' => 1, 'name' => 'Annual Leave', 'days' => 20],
            (object) ['id' => 2, 'name' => 'Sick Leave', 'days' => 14],
            (object) ['id' => 3, 'name' => 'Casual Leave', 'days' => 10],
            (object) ['id' => 4, 'name' => 'Maternity Leave', 'days' => 120],
            (object) ['id' => 5, 'name' => 'Paternity Leave', 'days' => 7],
        ]);

        // Dummy leave applications as objects (using field names from migration)
        $dummyLeaveApplications = collect([
            (object) [
                'id' => 1,
                'employee_id' => 1,
                'plan_id' => 1,
                'getEmployee' => (object) [
                    'id' => 1,
                    'full_name' => 'John Doe',
                    'applicant_id' => 'EMP001',
                    'system_id' => 'SYS001',
                ],
                'getPlan' => (object) ['id' => 1, 'name' => 'Annual Leave'],
                'leave_count' => 5,
                'from' => '2025-12-05',
                'to' => '2025-12-10',
                'reason' => 'Family vacation to visit relatives',
                'status' => 'pending',
                'created_at' => '2025-12-01',
            ],
            (object) [
                'id' => 2,
                'employee_id' => 2,
                'plan_id' => 2,
                'getEmployee' => (object) [
                    'id' => 2,
                    'full_name' => 'Jane Smith',
                    'applicant_id' => 'EMP002',
                    'system_id' => 'SYS002',
                ],
                'getPlan' => (object) ['id' => 2, 'name' => 'Sick Leave'],
                'leave_count' => 3,
                'from' => '2025-12-02',
                'to' => '2025-12-04',
                'reason' => 'Medical appointment and recovery',
                'status' => 'approved',
                'created_at' => '2025-12-01',
            ],
            (object) [
                'id' => 3,
                'employee_id' => 3,
                'plan_id' => 3,
                'getEmployee' => (object) [
                    'id' => 3,
                    'full_name' => 'Mike Johnson',
                    'applicant_id' => 'EMP003',
                    'system_id' => 'SYS003',
                ],
                'getPlan' => (object) ['id' => 3, 'name' => 'Casual Leave'],
                'leave_count' => 1,
                'from' => '2025-12-03',
                'to' => '2025-12-03',
                'reason' => 'Personal work',
                'status' => 'rejected',
                'created_at' => '2025-12-01',
            ],
            (object) [
                'id' => 4,
                'employee_id' => 4,
                'plan_id' => 1,
                'getEmployee' => (object) [
                    'id' => 4,
                    'full_name' => 'Sarah Williams',
                    'applicant_id' => 'EMP004',
                    'system_id' => 'SYS004',
                ],
                'getPlan' => (object) ['id' => 1, 'name' => 'Annual Leave'],
                'leave_count' => 10,
                'from' => '2025-12-15',
                'to' => '2025-12-25',
                'reason' => 'Year-end vacation',
                'status' => 'pending',
                'created_at' => '2025-12-02',
            ],
            (object) [
                'id' => 5,
                'employee_id' => 5,
                'plan_id' => 5,
                'getEmployee' => (object) [
                    'id' => 5,
                    'full_name' => 'David Brown',
                    'applicant_id' => 'EMP005',
                    'system_id' => 'SYS005',
                ],
                'getPlan' => (object) ['id' => 5, 'name' => 'Paternity Leave'],
                'leave_count' => 7,
                'from' => '2025-12-10',
                'to' => '2025-12-17',
                'reason' => 'Birth of child',
                'status' => 'approved',
                'created_at' => '2025-12-02',
            ],
        ]);
    @endphp

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
                                            <label for="keywordSearch" class="form-label text-muted small fw-semibold mb-1">
                                                Keyword Search
                                            </label>
                                            <div class="input-group input-group-md">
                                                <input type="text" class="form-control border-end-0" id="keywordSearch"
                                                    name="keyword" placeholder="Search by employee name, ID, or leave plan"
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
                        <a type="button" class="btn btn-warning btn-sm" href="#"
                            onclick="window.location.href='{{ url('leaves/create') }}'">
                            <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
                        </a>
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                            data-bs-target="#bulkUploadModal">
                            <i style="height: 12px; width: 12px" data-feather="upload"></i> Upload Bulk
                        </button>
                    </div>

                    @if ($dummyLeaveApplications->isEmpty())
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
                                    @foreach ($dummyLeaveApplications as $application)
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
                                                    data-bs-toggle="modal" data-bs-target="#viewLeaveModal"
                                                    data-id="{{ $application->id }}"
                                                    data-employee="{{ $application->getEmployee->full_name }}"
                                                    data-employee-id="{{ $application->getEmployee->applicant_id }}"
                                                    data-system-id="{{ $application->getEmployee->system_id }}"
                                                    data-plan="{{ $application->getPlan->name }}"
                                                    data-days="{{ $application->leave_count }}"
                                                    data-from="{{ $application->from }}" data-to="{{ $application->to }}"
                                                    data-reason="{{ $application->reason }}"
                                                    data-status="{{ $application->status }}"
                                                    data-created="{{ $application->created_at }}">
                                                    <i style="height: 12px; width: 12px" data-feather="eye"></i>
                                                </button>
                                                @if ($application->status == 'pending')
                                                    <button type="button" class="btn btn-success btn-sm" title="Approve">
                                                        <i style="height: 12px; width: 12px" data-feather="check"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm" title="Reject">
                                                        <i style="height: 12px; width: 12px" data-feather="x"></i>
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-outline-danger btn-sm" title="Delete">
                                                    <i style="height: 12px; width: 12px" data-feather="trash-2"></i>
                                                </button>
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

    {{-- Include View Modal --}}
    @include('leaves.partials.view_modal')

    {{-- Include Import Modal --}}
    @include('leaves.partials.import_modal')

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        $(document).ready(function() {
            // Reset filters
            $('#resetFilters').on('click', function() {
                $('#filterForm')[0].reset();
                $('.select2_list').val(null).trigger('change');
            });
        });
    </script>
@endsection
