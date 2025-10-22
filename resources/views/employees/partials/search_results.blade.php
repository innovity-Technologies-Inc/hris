<div class="card-body p-0">
        <a type="button" class="btn btn-warning btn-sm me-3 mb-3 " href="{{ route('employees.general_informations.create') }}">
            <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
        </a>

    @if ($employees->isEmpty())
        <div class="text-center py-4 text-muted">No employees found.</div>
    @else
    <div class="card-body">
                    <div class="table-responsive">
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th scope="col" >#</th>
                    <th scope="col">Profile</th>
                    <th scope="col">System ID</th>
                    <th scope="col">Employee ID</th>
                    <th scope="col">Employee Name</th>
                    <th scope="col" style="width: 120px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @php($i = 1)
                @foreach ($employees as $employee)
                    <tr>
                        <th scope="row">{{ $i++ }}</th>
                        <td>
                            @if ($employee->photo_path)
                                <img src="{{ asset('storage/' . $employee->photo_path) }}" alt="Profile"
                                    class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
                            @else
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 32px; height: 32px; font-size: 12px; color: white;">
                                    {{ strtoupper(substr($employee->full_name, 0, 1)) }}
                                </div>
                            @endif
                        </td>
                        <td>{{ $employee->system_id }}</td>
                        <td>{{ $employee->applicant_id }}</td>
                        <td>{{ $employee->full_name }}</td>
                        <td>
                            <a href="{{ route('employees.profile', $employee->id) }}" class="btn btn-secondary btn-sm"
                                title="View">
                                <i style="height: 12px; width: 12px" data-feather="eye"></i>
                            </a>
                            <a href="{{ route('employees.general_informations.edit', $employee->id) }}" class="btn btn-primary btn-sm"
                                title="Edit">
                                <i style="height: 12px; width: 12px" data-feather="edit"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

        <div class="mt-3">
            {{ $employees->appends(request()->query())->links() }}
        </div>
    @endif

    {{-- Reinitialize Feather Icons after AJAX load --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script>

</div>
