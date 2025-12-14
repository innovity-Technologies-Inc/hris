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
            <td>{{ $application->getPlan->name }}</td>
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
                <button type="button" class="btn btn-secondary btn-sm" title="View"
                        data-bs-toggle="modal" data-bs-target="#viewLeaveModal">
                    <i style="height: 12px; width: 12px" data-feather="eye"></i>
                </button>
                {{-- Include View Modal --}}
                @include('leaves.partials.view_modal')
                @if ($application->status == 'pending')
                    <form class="d-inline" action="{{ route('leaves.change_status') }}"
                          method="post">
                        @method('put')
                        @csrf
                        <input type="hidden" name="id" value="{{ $application->id }}">
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="btn btn-success btn-sm confirmApprove"
                                title="Approve">
                            <i style="height: 12px; width: 12px" data-feather="check"></i>
                        </button>
                    </form>
                    <form class="d-inline" method="post"
                          action="{{ route('leaves.change_status') }}">
                        @method('put')
                        @csrf
                        <input type="hidden" name="id"
                               value="{{ $application->id }}">
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="btn btn-danger btn-sm confirmReject"
                                title="Reject">
                            <i style="height: 12px; width: 12px" data-feather="x"></i>
                        </button>
                    </form>
                @endif
                <form action="{{ route('leaves.destroy', $application->id) }}"
                      method="POST" class="d-inline">
                    @csrf
                    @method('delete')
                    <button type="submit"
                            class="btn btn-outline-danger btn-sm confirmDelete"
                            title="Delete">
                        <i style="height: 12px; width: 12px" data-feather="trash-2"></i>
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
