@if ($departments->isEmpty())
    <div class="text-center py-4 text-muted">No Department found.</div>
@else
    <table class="table table-bordered mb-0">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Company</th>
                @if (\App\HelperClass::getGeneralSetting()->branch_status == '1')
                    <th scope="col">Branch</th>
                @endif
                @if (\App\HelperClass::getGeneralSetting()->division_status == '1')
                    <th scope="col">Division</th>
                @endif
                <th scope="col">Department Name</th>
                <th scope="col">Short Name</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sl = \App\HelperClass::indexNumberSerialization($departments);
            @endphp
            @foreach ($departments as $department)
                <tr>
                    <th scope="row">{{ $sl++ }}</th>
                    <td>{{ $department->getCompany->name ?? 'N/A' }}</td>
                    @if (\App\HelperClass::getGeneralSetting()->branch_status == '1')
                        <td>{{ $department->getLocation->name ?? 'N/A' }}</td>
                    @endif
                    @if (\App\HelperClass::getGeneralSetting()->division_status == '1')
                        <td>{{ $department->getDivision->name ?? 'N/A' }}</td>
                    @endif
                    <td>{{ $department->department_name ?? 'N/A' }}</td>
                    <td>{{ $department->short_name }}</td>
                    <td>
                        @can('departments.edit')
                            <a href="{{ route('departments.edit', $department->id) }}" class="btn btn-primary btn-sm">
                                <i style="height: 12px; width: 12px" data-feather="edit"></i>
                            </a>
                        @endcan

                        @can('departments.delete')
                            <form action="{{ route('departments.delete', $department->id) }}" method="POST"
                                style="display: inline-block">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-sm btn-danger confirmDelete">
                                    <i style="height: 12px; width: 12px" data-feather="trash"></i>
                                </button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
