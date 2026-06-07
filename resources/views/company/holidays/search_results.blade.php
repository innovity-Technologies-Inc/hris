@if ($holidays->isEmpty())
    <div class="text-center py-4 text-muted">No Holiday found.</div>
@else
    <table class="table table-bordered mb-0">
        <thead>
            <tr>
                <th scope="col">SL</th>
                <th scope="col">Title</th>
                <th scope="col">From Date</th>
                <th scope="col">To Date</th>
                <th scope="col">Duration (Days)</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sl = \App\HelperClass::indexNumberSerialization($holidays);
            @endphp
            @foreach ($holidays as $holiday)
                @php
                    $duration = $holiday->start_date->diffInDays($holiday->end_date) + 1;
                @endphp
                <tr>
                    <th scope="row">{{ $sl++ }}</th>
                    <td>{{ $holiday->title }}</td>
                    <td>{{ $holiday->start_date->format('d M Y') }}</td>
                    <td>{{ $holiday->end_date->format('d M Y') }}</td>
                    <td>{{ $duration }}</td>
                    <td>
                        @if ($holiday->status == 'active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        @can('holidays.edit')
                        <a href="{{ route('holidays.edit', $holiday->id) }}" class="btn btn-primary btn-sm">
                            <i style="height: 12px; width: 12px" data-feather="edit"></i>
                        </a>
                        @endcan

                        @can('holidays.delete')
                        <form action="{{ route('holidays.delete', $holiday->id) }}" method="POST"
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

