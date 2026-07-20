<table class="table table-bordered mb-0">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Employee Name</th>
        <th scope="col">Plan Name</th>
        <th scope="col">Days</th>
        <th scope="col">From</th>
        <th scope="col">To</th>
        <th scope="col">Status</th>
        <th scope="col" style="width: 180px;">Action</th>
    </tr>
    </thead>
    <tbody>
    @php $sl = 1; @endphp
    @foreach ($leaves as $application)
        <tr>
            <th scope="row">{{ $sl++ }}</th>
            <td>{{ $application->getEmployee->full_name }}</td>
            <td>{{ $application->leave_category_type === 'compensatory' ? 'Compensatory Leave' : ($application->getPlan?->name ?? '-') }}</td>
            <td>{{ $application->leave_count }}</td>
            <td>{{ $application->from }}</td>
            <td>{{ $application->to }}</td>

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
                @can('leaves.view')
                <a href="{{ route('leave.show', $application->id) }}" class="btn btn-secondary btn-sm" title="View">
                    <i style="height: 12px; width: 12px" data-feather="eye"></i>
                </a>
                @endcan


                @can('leaves.delete')
                <form action="{{ route('leave.destroy', $application->id) }}"
                      method="POST" class="d-inline">
                    @csrf
                    @method('delete')
                    <button type="submit"
                            class="btn btn-outline-danger btn-sm confirmDelete"
                            title="Delete">
                        <i style="height: 12px; width: 12px" data-feather="trash-2"></i>
                    </button>
                </form>
                @endcan
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

