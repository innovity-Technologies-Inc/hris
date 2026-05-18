@if ($sections->isEmpty())
    <div class="text-center py-4 text-muted">No Section found.</div>
@else
    <table class="table table-bordered mb-0">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Company</th>
                @if (App\HelperClass::getGeneralSetting()->branch_status == 1)
                    <th scope="col">Branch</th>
                @endif
                @if (App\HelperClass::getGeneralSetting()->division_status == 1)
                    <th scope="col">Division</th>
                @endif
                @if (App\HelperClass::getGeneralSetting()->department_status == 1)
                    <th scope="col">Department</th>
                @endif
                <th scope="col">Section Name</th>
                <th scope="col">Short Name</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sl = \App\HelperClass::indexNumberSerialization($sections);
            @endphp
            @foreach ($sections as $section)
                <tr>
                    <th scope="row">{{ $sl++ }}</th>
                    <td>{{ $section->getCompany->name ?? 'N/A' }}</td>
                    @if (App\HelperClass::getGeneralSetting()->branch_status == 1)
                        <td>{{ $section->getLocation->name ?? 'N/A' }}</td>
                    @endif
                    @if (App\HelperClass::getGeneralSetting()->division_status == 1)
                        <td>{{ $section->getDivision->name ?? 'N/A' }}</td>
                    @endif
                    @if (App\HelperClass::getGeneralSetting()->department_status == 1)
                        <td>{{ $section->getDepartment->department_name ?? 'N/A' }}</td>
                    @endif
                    <td>{{ $section->name }}</td>
                    <td>{{ $section->short_name }}</td>
                    <td>
                        @can('sections.edit')
                            <a href="{{ route('sections.edit', $section->id) }}" class="btn btn-primary btn-sm">
                                <i style="height: 12px; width: 12px" data-feather="edit"></i>
                            </a>
                        @endcan

                        @can('sections.delete')
                            <form action="{{ route('sections.delete', $section->id) }}" method="POST"
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
