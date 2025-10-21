@extends('structure.master')

@section('content')
    {{-- Employee List --}}
    <div class="row">
        <div class="col-xl-12">

            <div class="card border-0 shadow-sm rounded">
                <div
                    class="card-header bg-white border-bottom d-flex justify-content-between align-items-center flex-wrap gap-3 p-4">

                    <a type="button" class="btn btn-warning btn-sm" href="#">
                        <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
                    </a>
                    
                    <h4 class="mb-0 fw-semibold fs-5 text-dark">
                        <i class="mdi mdi-account-multiple me-2"></i>Employee List
                    </h4>

                    <div class="d-flex gap-2 align-items-center flex-wrap">

                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle" id="employeeTable">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-uppercase text-secondary fw-semibold small py-3 px-3"
                                        style="width: 80px;">SL NO</th>
                                    <th class="text-uppercase text-secondary fw-semibold small py-3 px-3"
                                        style="width: 100px;">PROFILE</th>
                                    <th class="text-uppercase text-secondary fw-semibold small py-3 px-3">SYSTEM ID</th>
                                    <th class="text-uppercase text-secondary fw-semibold small py-3 px-3">EMPLOYEE NAME</th>
                                    <th class="text-uppercase text-secondary fw-semibold small py-3 px-3"
                                        style="width: 250px;">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody id="employeeTableBody">
                                <!-- Employee 1 -->
                                <tr>
                                    <td class="px-3">1</td>
                                    <td class="px-3">
                                        <img src="assets/images/users/user-1.jpg" alt="Employee" class="rounded-circle"
                                            style="width: 40px; height: 40px; object-fit: cover;">
                                    </td>
                                    <td class="px-3"><span class="badge bg-light text-dark">EMP-2024-001</span></td>
                                    <td class="px-3">Mohammad Rahman Khan</td>
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

                                <!-- Employee 2 -->
                                <tr>
                                    <td class="px-3">2</td>
                                    <td class="px-3">
                                        <img src="assets/images/users/user-2.jpg" alt="Employee" class="rounded-circle"
                                            style="width: 40px; height: 40px; object-fit: cover;">
                                    </td>
                                    <td class="px-3"><span class="badge bg-light text-dark">EMP-2024-002</span></td>
                                    <td class="px-3">Ayesha Siddiqua</td>
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

                                <!-- Employee 3 -->
                                <tr>
                                    <td class="px-3">3</td>
                                    <td class="px-3">
                                        <img src="assets/images/users/user-3.jpg" alt="Employee" class="rounded-circle"
                                            style="width: 40px; height: 40px; object-fit: cover;">
                                    </td>
                                    <td class="px-3"><span class="badge bg-light text-dark">EMP-2024-003</span></td>
                                    <td class="px-3">Kamal Ahmed</td>
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

                                <!-- Employee 4 -->
                                <tr>
                                    <td class="px-3">4</td>
                                    <td class="px-3">
                                        <img src="assets/images/users/user-4.jpg" alt="Employee" class="rounded-circle"
                                            style="width: 40px; height: 40px; object-fit: cover;">
                                    </td>
                                    <td class="px-3"><span class="badge bg-light text-dark">EMP-2024-004</span></td>
                                    <td class="px-3">Fatima Begum</td>
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

                                <!-- Employee 5 -->
                                <tr>
                                    <td class="px-3">5</td>
                                    <td class="px-3">
                                        <img src="assets/images/users/user-5.jpg" alt="Employee" class="rounded-circle"
                                            style="width: 40px; height: 40px; object-fit: cover;">
                                    </td>
                                    <td class="px-3"><span class="badge bg-light text-dark">EMP-2024-005</span></td>
                                    <td class="px-3">Rafiqul Islam</td>
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

                                <!-- Employee 6 -->
                                <tr>
                                    <td class="px-3">6</td>
                                    <td class="px-3">
                                        <img src="assets/images/users/user-6.jpg" alt="Employee" class="rounded-circle"
                                            style="width: 40px; height: 40px; object-fit: cover;">
                                    </td>
                                    <td class="px-3"><span class="badge bg-light text-dark">EMP-2024-006</span></td>
                                    <td class="px-3">Nusrat Jahan</td>
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

                                <!-- Employee 7 -->
                                <tr>
                                    <td class="px-3">7</td>
                                    <td class="px-3">
                                        <img src="assets/images/users/user-7.jpg" alt="Employee" class="rounded-circle"
                                            style="width: 40px; height: 40px; object-fit: cover;">
                                    </td>
                                    <td class="px-3"><span class="badge bg-light text-dark">EMP-2024-007</span></td>
                                    <td class="px-3">Abdul Jabbar</td>
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

                                <!-- Employee 8 -->
                                <tr>
                                    <td class="px-3">8</td>
                                    <td class="px-3">
                                        <img src="assets/images/users/user-8.jpg" alt="Employee" class="rounded-circle"
                                            style="width: 40px; height: 40px; object-fit: cover;">
                                    </td>
                                    <td class="px-3"><span class="badge bg-light text-dark">EMP-2024-008</span></td>
                                    <td class="px-3">Sadia Afroz</td>
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

                                <!-- Employee 9 -->
                                <tr>
                                    <td class="px-3">9</td>
                                    <td class="px-3">
                                        <img src="assets/images/users/user-9.jpg" alt="Employee" class="rounded-circle"
                                            style="width: 40px; height: 40px; object-fit: cover;">
                                    </td>
                                    <td class="px-3"><span class="badge bg-light text-dark">EMP-2024-009</span></td>
                                    <td class="px-3">Tariqul Hasan</td>
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

                                <!-- Employee 10 -->
                                <tr>
                                    <td class="px-3">10</td>
                                    <td class="px-3">
                                        <img src="assets/images/users/user-10.jpg" alt="Employee" class="rounded-circle"
                                            style="width: 40px; height: 40px; object-fit: cover;">
                                    </td>
                                    <td class="px-3"><span class="badge bg-light text-dark">EMP-2024-010</span></td>
                                    <td class="px-3">Rubaiya Sultana</td>
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
