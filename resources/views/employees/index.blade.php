@extends('structure.master')

@section('content')
    {{-- Employee List --}}
    <div class="row">
        <div class="col-xl-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header bg-white border-bottom p-4">
                    <div class="row align-items-start">
                        <form action="">

                            {{-- Right side: Search and Filter Section --}}
                            <div class="col-md-12">
                                <div class="border rounded shadow-sm p-3 bg-light">
                                    <form action="#" method="GET">
                                        {{-- First Row: Keyword Search --}}
                                        <div class="row mb-2">
                                            <div class="col-12">
                                                <label for="keywordSearch"
                                                    class="form-label text-muted small fw-semibold mb-1">
                                                    Keyword Search
                                                </label>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control border-end-0"
                                                        id="keywordSearch" name="keyword" placeholder="Search employees..."
                                                        aria-label="Keyword Search">
                                                    <span class="input-group-text bg-white border-start-0">
                                                        <i class="mdi mdi-magnify text-muted"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Second Row: Employee Name, Employee ID, and System ID --}}
                                        <div class="row mb-2">
                                            <div class="col-md-4">
                                                <label for="employeeName"
                                                    class="form-label text-muted small fw-semibold mb-1">
                                                    Employee Name
                                                </label>
                                                <select id="employeeName" name="employee_name"
                                                    class="form-select form-select-sm select2_list"
                                                    data-placeholder="Select employee name" aria-label="Employee Name">
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="employeeId"
                                                    class="form-label text-muted small fw-semibold mb-1">
                                                    Employee ID
                                                </label>
                                                <select id="employeeId" name="employee_id"
                                                    class="form-select form-select-sm select2_list"
                                                    data-placeholder="Select employee ID" aria-label="Employee ID">
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="systemId" class="form-label text-muted small fw-semibold mb-1">
                                                    System ID
                                                </label>
                                                <select id="systemId" name="system_id"
                                                    class="form-select form-select-sm select2_list"
                                                    data-placeholder="Select system ID" aria-label="System ID">
                                                </select>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="p-3">

                        <a type="button" class="btn btn-warning btn-sm me-3 " href="#">
                            <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
                        </a>
                    </div>


                    <div class="table-responsive mt-3">
                        <table class="table table-hover mb-0 align-middle" id="employeeTable">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-uppercase text-secondary fw-semibold small py-3 px-3"
                                        style="width: 80px;">SL NO</th>
                                    <th class="text-uppercase text-secondary fw-semibold small py-3 px-3"
                                        style="width: 100px;">PROFILE</th>
                                    <th class="text-uppercase text-secondary fw-semibold small py-3 px-3">SYSTEM ID</th>
                                    <th class="text-uppercase text-secondary fw-semibold small py-3 px-3">EMPLOYEE ID</th>
                                    <th class="text-uppercase text-secondary fw-semibold small py-3 px-3">EMPLOYEE NAME</th>
                                    <th class="text-uppercase text-secondary fw-semibold small py-3 px-3"
                                        style="width: 250px;">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody id="employeeTableBody">
                                @php($i = 1)
                                @foreach ($employees as $employee)
                                    <!-- Employee 1 -->
                                    <tr>
                                        <td class="px-3">{{ $i++ }}</td>
                                        <td class="px-3">
                                            <img src="{{ assets('storage/' . $employee->photo_path) }}" alt="Employee"
                                                class="rounded-circle"
                                                style="width: 40px; height: 40px; object-fit: cover;">
                                        </td>
                                        <td class="px-3"><span
                                                class="badge bg-light text-dark">{{ $employee->system_id }}</span></td>
                                        <td class="px-3"><span
                                                class="badge bg-light text-dark">{{ $employee->employee_id }}</span></td>
                                        <td class="px-3"><span
                                                class="badge bg-light text-dark">{{ $employee->first_name }}
                                                {{ $employee->middle_name }} {{ $employee->last_name }}</span></td>
                                        <td class="px-3">
                                            <a href="#" class="btn btn-sm btn-outline-primary me-1">
                                                <i class="mdi mdi-eye"></i> View
                                            </a>
                                            <a href="#" class="btn btn-sm btn-outline-success me-1">
                                                <i class="mdi mdi-pencil"></i> Edit
                                            </a>
                                            <button class="btn btn-sm btn-outline-danger confirmDelete">
                                                <i class="mdi mdi-delete"></i> Delete
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- No Results Message -->
                        <div id="noResults" class="text-center p-5 text-muted" style="display: none;">
                            <i class="mdi mdi-account-search" style="font-size: 48px;"></i>
                            <p class="mt-3">No employees found matching your search.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
