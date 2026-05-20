@if ($designations->isEmpty())
    <div class="text-center py-4 text-muted">No Designation found.</div>
@else
    <table class="table table-bordered mb-0">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Designation Level</th>
                <th scope="col">Company Designation</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sl = \App\HelperClass::indexNumberSerialization($designations);
            @endphp
            @foreach ($designations as $designation)
                <tr>
                    <th scope="row">{{ $sl++ }}</th>
                    <td>{{ $designation->designation_level }}</td>
                    <td>{{ $designation->company_designation }}</td>
                    <td>
                        @if ($designation->status == 'active')
                            <span class="badge text-bg-success">Active</span>
                        @else
                            <span class="badge text-bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        @can('designations.edit')
                            <a href="{{ route('designations.edit', $designation->id) }}" class="btn btn-primary btn-sm">
                                <i style="height: 12px; width: 12px" data-feather="edit"></i>
                            </a>
                        @endcan

                        @can('designations.delete')
                            <form action="{{ route('designations.delete', $designation->id) }}" method="POST"
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

