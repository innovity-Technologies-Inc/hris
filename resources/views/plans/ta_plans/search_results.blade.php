<div class="table-responsive">
    <table class="table table-bordered mb-0">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">TA Plan Name</th>
                <th scope="col">Short Name</th>
                <th scope="col">Remuneration</th>
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
                        <span class="badge bg-success">{{ \App\HelperClass::getGeneralSetting()->currency ?? 'Tk' }}
                            {{ number_format($item->remuneration, 2) }} / KM</span>
                    </td>
                    <td>
                        @if ($item->status == 'active')
                            <span class="badge text-bg-success">Active</span>
                        @else
                            <span class="badge text-bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a type="button" class="btn btn-warning btn-sm"
                            href="{{ route('plans.ta_plans.edit', $item->id) }}" title="Edit">
                            <i style="height: 12px; width: 12px" data-feather="edit"></i>
                        </a>

                        <form action="{{ route('plans.ta_plans.delete', $item->id) }}" method="POST"
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
