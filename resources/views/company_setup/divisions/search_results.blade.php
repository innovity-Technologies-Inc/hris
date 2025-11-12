@if ($divisions->isEmpty())
    <div class="text-center py-4 text-muted">No Division found.</div>
@else
    <table class="table table-bordered mb-0">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Division Name</th>
                <th scope="col">Short Name</th>
                <th scope="col">Company</th>
                <th scope="col">Location</th>
                <th scope="col">Remarks</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sl = \App\HelperClass::indexNumberSerialization($divisions);
            @endphp
            @foreach ($divisions as $division)
                <tr>
                    <th scope="row">{{ $sl++ }}</th>
                    <td>{{ $division->name }}</td>
                    <td>{{ $division->short_name }}</td>
                    <td>{{ $division->getCompany->name }}</td>
                    <td>{{ $division->getLocation->name }}</td>
                    <td>{{ $division->remarks }}</td>
                    <td>
                        <a href="{{ route('divisions.edit', $division->id) }}" class="btn btn-primary btn-sm">
                            <i style="height: 12px; width: 12px" data-feather="edit"></i>
                        </a>

                        <form action="{{ route('divisions.delete', $division->id) }}" method="POST"
                            style="display: inline-block">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-sm btn-danger confirmDelete">
                                <i style="height: 12px; width: 12px" data-feather="trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
