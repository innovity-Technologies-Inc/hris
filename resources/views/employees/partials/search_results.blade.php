<div class="card-body p-0">
    @can('employee-management.create')
    <a type="button" class="btn btn-warning btn-sm me-3 mb-3 "
        href="{{ route('employees.general_informations.create') }}">
        <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
    </a>
    @endcan

    @if ($employees->isEmpty())
        <div class="text-center py-4 text-muted">No employees found.</div>
    @else
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Profile</th>
                            <th scope="col">System ID</th>
                            <th scope="col">Employee ID</th>
                            <th scope="col">Employee Name</th>
                            @can('employee-management.view')
                            <th scope="col" style="width: 120px;">Action</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $sl = \App\HelperClass::indexNumberSerialization($employees);
                        @endphp
                        @foreach ($employees as $employee)
                            <tr>
                                <th scope="row">{{ $sl++ }}</th>
                                <td>
                                    {!! \App\HelperClass::generateAvatar(
                                        $employee->photo_path,
                                        $employee->full_name,
                                        32,
                                        '#974063',
                                        '',
                                        $employee->id,
                                    ) !!}
                                </td>
                                <td>{{ $employee->system_id }}</td>
                                <td>{{ $employee->applicant_id }}</td>
                                <td>{{ $employee->full_name }}</td>
                                @can('employee-management.view')
                                <td>
                                    <a href="{{ route('employees.profile.general_informations', $employee->id) }}"
                                        class="btn btn-secondary btn-sm" title="View">
                                        <i style="height: 12px; width: 12px" data-feather="eye"></i>
                                    </a>
                                </td>
                                @endcan
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
