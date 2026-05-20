<div class="table-responsive">
    <table class="table table-bordered mb-0">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Plan Name</th>
                <th scope="col">Short Name</th>
                <th scope="col">Repetition Days</th>
                <th scope="col">Shift 1</th>
                <th scope="col">Shift 2</th>
                <th scope="col">Shift 3</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sl = 1;
            @endphp
            @foreach ($plans as $item)
                <tr>
                    <th scope="row">{{ $sl++ }}</th>
                    <td>{{ $item->name }}</td>
                    <td>
                        @if ($item->short_name)
                            <span class="badge text-bg-secondary">{{ $item->short_name }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge text-bg-info">{{ $item->swapping }} Days</span>
                    </td>
                    <td>
                        @if ($item->getFirstShift && $item->getFirstShift->name)
                            <span class="badge text-bg-primary">{{ $item->getFirstShift->name }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if ($item->getSecondShift && $item->getSecondShift->name)
                            <span class="badge text-bg-success">{{ $item->getSecondShift->name }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if ($item->getThirdShift && $item->getThirdShift->name)
                            <span class="badge text-bg-info">{{ $item->getThirdShift->name }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if ($item->status == 'active')
                            <span class="badge text-bg-success">Active</span>
                        @else
                            <span class="badge text-bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a type="button" class="btn btn-primary btn-sm"
                            href="{{ route('plan.roster_plans.show', $item->id) }}" title="View">
                            <i style="height: 12px; width: 12px" data-feather="eye"></i>
                        </a>

                        <a type="button" class="btn btn-warning btn-sm"
                            href="{{ route('plan.roster_plans.edit', $item->id) }}" title="Edit">
                            <i style="height: 12px; width: 12px" data-feather="edit"></i>
                        </a>

                        <form action="{{ route('plan.roster_plans.delete', $item->id) }}" method="POST"
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

    {{-- Pagination - Uncomment in production --}}
    <div class="mt-3">
        {{ $plans->links() }}
    </div>
</div>

