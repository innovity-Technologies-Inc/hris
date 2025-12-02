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

        // Dummy leave applications as objects
        $dummyLeaveApplications = collect([
            (object) [
                'id' => 1,
                'employee' => (object) [
                    'id' => 1,
                    'full_name' => 'John Doe',
                    'applicant_id' => 'EMP001',
                    'system_id' => 'SYS001',
                ],
                'leave_plan' => (object) ['id' => 1, 'name' => 'Annual Leave'],
                'days' => 5,
                'from_date' => '2025-12-05',
                'to_date' => '2025-12-10',
                'reason' => 'Family vacation to visit relatives',
                'status' => 'pending',
                'created_at' => '2025-12-01',
            ],
            (object) [
                'id' => 2,
                'employee' => (object) [
                    'id' => 2,
                    'full_name' => 'Jane Smith',
                    'applicant_id' => 'EMP002',
                    'system_id' => 'SYS002',
                ],
                'leave_plan' => (object) ['id' => 2, 'name' => 'Sick Leave'],
                'days' => 3,
                'from_date' => '2025-12-02',
                'to_date' => '2025-12-04',
                'reason' => 'Medical appointment and recovery',
                'status' => 'approved',
                'created_at' => '2025-12-01',
            ],
            (object) [
                'id' => 3,
                'employee' => (object) [
                    'id' => 3,
                    'full_name' => 'Mike Johnson',
                    'applicant_id' => 'EMP003',
                    'system_id' => 'SYS003',
                ],
                'leave_plan' => (object) ['id' => 3, 'name' => 'Casual Leave'],
                'days' => 1,
                'from_date' => '2025-12-03',
                'to_date' => '2025-12-03',
                'reason' => 'Personal work',
                'status' => 'rejected',
                'created_at' => '2025-12-01',
            ],
            (object) [
                'id' => 4,
                'employee' => (object) [
                    'id' => 4,
                    'full_name' => 'Sarah Williams',
                    'applicant_id' => 'EMP004',
                    'system_id' => 'SYS004',
                ],
                'leave_plan' => (object) ['id' => 1, 'name' => 'Annual Leave'],
                'days' => 10,
                'from_date' => '2025-12-15',
                'to_date' => '2025-12-25',
                'reason' => 'Year-end vacation',
                'status' => 'pending',
                'created_at' => '2025-12-02',
            ],
            (object) [
                'id' => 5,
                'employee' => (object) [
                    'id' => 5,
                    'full_name' => 'David Brown',
                    'applicant_id' => 'EMP005',
                    'system_id' => 'SYS005',
                ],
                'leave_plan' => (object) ['id' => 5, 'name' => 'Paternity Leave'],
                'days' => 7,
                'from_date' => '2025-12-10',
                'to_date' => '2025-12-17',
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

                                    {{-- Second Row: Employee Name, Employee ID, and System ID --}}
                                    <div class="row mb-2">
                                        <div class="col-md-4">
                                            <label for="employeeName" class="form-label text-muted small fw-semibold mb-1">
                                                Employee Name
                                            </label>
                                            <select id="employeeName" name="employee_name"
                                                class="form-select form-select-sm select2_list"
                                                data-placeholder="Select employee name">
                                                <option value="">Choose One</option>
                                                @foreach ($dummyEmployees as $employee)
                                                    <option value="{{ $employee->full_name }}"
                                                        {{ request('employee_name') == $employee->full_name ? 'selected' : '' }}>
                                                        {{ $employee->full_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="employeeId" class="form-label text-muted small fw-semibold mb-1">
                                                Employee ID
                                            </label>
                                            <select id="employeeId" name="employee_id"
                                                class="form-select form-select-sm select2_list"
                                                data-placeholder="Select employee ID">
                                                <option value="">Choose One</option>
                                                @foreach ($dummyEmployees as $employee)
                                                    <option value="{{ $employee->applicant_id }}"
                                                        {{ request('employee_id') == $employee->applicant_id ? 'selected' : '' }}>
                                                        {{ $employee->applicant_id }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="systemId" class="form-label text-muted small fw-semibold mb-1">
                                                System ID
                                            </label>
                                            <select id="systemId" name="system_id"
                                                class="form-select form-select-sm select2_list"
                                                data-placeholder="Select system ID">
                                                <option value="">Choose One</option>
                                                @foreach ($dummyEmployees as $employee)
                                                    <option value="{{ $employee->system_id }}"
                                                        {{ request('system_id') == $employee->system_id ? 'selected' : '' }}>
                                                        {{ $employee->system_id }}
                                                    </option>
                                                @endforeach
                                            </select>
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

        <div class="col-lg-12">
            <div class="card-header border-bottom p-4">
                <div id="search-result" class="card-body p-0">
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
                        <div class="card-body">
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
                                                <td>{{ $application->employee->full_name }}</td>
                                                <td>{{ $application->leave_plan->name }}</td>
                                                <td>{{ $application->days }}</td>
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
                                                        data-employee="{{ $application->employee->full_name }}"
                                                        data-employee-id="{{ $application->employee->applicant_id }}"
                                                        data-system-id="{{ $application->employee->system_id }}"
                                                        data-plan="{{ $application->leave_plan->name }}"
                                                        data-days="{{ $application->days }}"
                                                        data-from="{{ $application->from_date }}"
                                                        data-to="{{ $application->to_date }}"
                                                        data-reason="{{ $application->reason }}"
                                                        data-status="{{ $application->status }}"
                                                        data-created="{{ $application->created_at }}">
                                                        <i style="height: 12px; width: 12px" data-feather="eye"></i>
                                                    </button>
                                                    @if ($application->status == 'pending')
                                                        <button type="button" class="btn btn-success btn-sm"
                                                            title="Approve">
                                                            <i style="height: 12px; width: 12px" data-feather="check"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            title="Reject">
                                                            <i style="height: 12px; width: 12px" data-feather="x"></i>
                                                        </button>
                                                    @endif
                                                    <button type="button" class="btn btn-outline-danger btn-sm"
                                                        title="Delete">
                                                        <i style="height: 12px; width: 12px" data-feather="trash-2"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
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
