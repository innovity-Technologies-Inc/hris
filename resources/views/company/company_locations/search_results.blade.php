@if ($locations->isEmpty())
    <div class="text-center py-4 text-muted">No Company Location found.</div>
@else
    <table class="table table-bordered mb-0">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Branch Name</th>
                <th scope="col">Company Name</th>
                <th scope="col">Location Address</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sl = \App\HelperClass::indexNumberSerialization($locations);
            @endphp
            @foreach ($locations as $location)
                <tr>
                    <th scope="row">{{ $sl++ }}</th>
                    <td>{{ $location->name }}</td>
                    <td>{{ $location->getCompany->name ?? 'N/A' }}</td>
                    <td>{{ Str::limit($location->location_address, 30) }}</td>
                    <td>
                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                            data-bs-target="#viewLocationModal{{ $location->id }}">
                            <i style="height: 12px; width: 12px" data-feather="eye"></i>
                        </button>

                        <a href="{{ route('company_locations.edit', $location->id) }}" class="btn btn-primary btn-sm">
                            <i style="height: 12px; width: 12px" data-feather="edit"></i>
                        </a>

                        <form action="{{ route('company_locations.destroy', $location->id) }}" method="POST"
                            style="display: inline-block">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-sm btn-danger confirmDelete">
                                <i style="height: 12px; width: 12px" data-feather="trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                @include('company.company_locations.view_modal')
            @endforeach
        </tbody>
    </table>
@endif

