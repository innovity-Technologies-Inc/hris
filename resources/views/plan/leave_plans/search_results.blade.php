<div class="table-responsive">
    <table class="table table-bordered mb-0">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Leave Name</th>
                <th scope="col">Short Name</th>
                <th scope="col">Leave Type</th>
                <th scope="col">Leave Limit</th>
                <th scope="col">Applicable For</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @php($i = 1)
            @foreach ($plans as $item)
                <tr>
                    <th scope="row">{{ $i++ }}</th>
                    <td>{{ $item->name }}</td>
                    <td>
                        <span class="badge bg-light text-dark">{{ $item->short_name ?? 'N/A' }}</span>
                    </td>
                    <td>
                        <span
                            class="badge text-bg-primary">{{ ucwords(str_replace('_', ' ', $item->leave_type)) }}</span>
                    </td>
                    <td>
                        <span class="badge bg-primary">{{ $item->leave_limit ?? 0 }} Days</span>
                    </td>
                    <td>
                        @if (strtolower($item->applicable_gender) == 'both')
                            <span class="badge bg-info">Both</span>
                        @elseif(strtolower($item->applicable_gender) == 'male')
                            <span class="badge bg-primary">Male</span>
                        @else
                            <span class="badge bg-danger">Female</span>
                        @endif
                    </td>
                    <td>
                        @if ($item->active_ind == 'active')
                            <span class="badge text-bg-success">Active</span>
                        @else
                            <span class="badge text-bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a type="button" class="btn btn-primary btn-sm"
                            href="{{ route('plan.leave_plans.show', $item->id) }}" title="View">
                            <i style="height: 12px; width: 12px" data-feather="eye"></i>
                        </a>

                        <a type="button" class="btn btn-warning btn-sm"
                            href="{{ route('plan.leave_plans.edit', $item->id) }}" title="Edit">
                            <i style="height: 12px; width: 12px" data-feather="edit"></i>
                        </a>

                        <form action="{{ route('plan.leave_plans.delete', $item->id) }}" method="POST"
                            style="display: inline-block">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-sm btn-danger confirmDelete" title="Delete" type="submit">
                                <i style="height: 12px; width: 12px" data-feather="trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>

    <div class="mt-3">
        {{ $plans->links() }}
    </div>
</div>

