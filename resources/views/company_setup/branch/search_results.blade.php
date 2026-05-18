@if ($branches->isEmpty())
    <div class="text-center py-4 text-muted">No Branch found.</div>
@else
    <table class="table table-bordered mb-0">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Bank Name</th>
                <th scope="col">Routing No</th>
                <th scope="col">Swift Code</th>
                <th scope="col">Address</th>
                <th scope="col">Remarks</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sl = \App\HelperClass::indexNumberSerialization($branches);
            @endphp
            @foreach ($branches as $item)
                <tr>
                    <th scope="row">{{ $sl++ }}</th>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->getBank->name ?? 'N/A' }}</td>
                    <td>{{ $item->routing_no }}</td>
                    <td>{{ $item->swift_code }}</td>
                    <td>{{ $item->address }}</td>
                    <td>{{ $item->remarks }}</td>

                    <td>
                        @if ($item->status == 'active')
                            <span class="badge text-bg-success">Active</span>
                        @else
                            <span class="badge text-bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        @can('bank-branches.edit')
                            <a type="button" class="btn btn-primary btn-sm" href="{{ route('branches.edit', $item->id) }}">
                                <i style="height: 12px; width: 12px" data-feather="edit"></i>
                            </a>
                        @endcan

                        @can('bank-branches.delete')
                            <form action="{{ route('branches.delete', $item->id) }}" method="POST"
                                style="display: inline-block">
                                @csrf
                                @method('DELETE')

                                <button class ="btn btn-sm btn-danger confirmDelete">
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
