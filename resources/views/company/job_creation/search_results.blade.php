@if ($job_creations->isEmpty())
    <div class="text-center py-4 text-muted">No Job Creation found.</div>
@else
    <table class="table table-bordered mb-0">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Designation</th>
                <th scope="col">Department</th>
                <th scope="col">Job Ind.</th>
                <th scope="col">Display Designation</th>
                <th scope="col">Display Serial</th>
                <th scope="col">Remarks</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sl = \App\HelperClass::indexNumberSerialization($job_creations);
            @endphp
            @foreach ($job_creations as $jobCreation)
                <tr>
                    <th scope="row">{{ $sl++ }}</th>
                    <td>{{ $jobCreation->getDesignation->company_designation ?? 'N/A' }}</td>
                    <td>{{ $jobCreation->getDepartment->department_name ?? 'N/A' }}</td>
                    <td>{{ $jobCreation->job_ind }}</td>
                    <td>{{ $jobCreation->display_designation }}</td>
                    <td>{{ $jobCreation->display_serial }}</td>
                    <td>{{ $jobCreation->remarks }}</td>
                    <td>
                        @if ($jobCreation->status == 'active')
                            <span class="badge text-bg-success">Active</span>
                        @else
                            <span class="badge text-bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        @can('job-creations.edit')
                        <a href="{{ route('job_creations.edit', $jobCreation->id) }}" class="btn btn-primary btn-sm">
                            <i style="height: 12px; width: 12px" data-feather="edit"></i>
                        </a>
                        @endcan

                        @can('job-creations.delete')
                        <form action="{{ route('job_creations.delete', $jobCreation->id) }}" method="POST"
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

