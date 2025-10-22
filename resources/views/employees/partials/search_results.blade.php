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
                        <img src="{{ asset('storage/' . $employee->photo_path) }}" alt="Employee"
                             class="rounded-circle"
                             style="width: 40px; height: 40px; object-fit: cover;">
                    </td>
                    <td class="px-3"><span
                            class="badge bg-light text-dark">{{ $employee->system_id }}</span></td>
                    <td class="px-3"><span
                            class="badge bg-light text-dark">{{ $employee->applicant_id }}</span></td>
                    <td class="px-3"><span
                            class="badge bg-light text-dark">{{ $employee->full_name }}</span></td>
                    <td class="px-3">
                        <a href="#" class="btn btn-sm btn-outline-primary me-1">
                            <i class="mdi mdi-eye"></i> View
                        </a>
                        <a href="#" class="btn btn-sm btn-outline-success me-1">
                            <i class="mdi mdi-pencil"></i> Edit
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
