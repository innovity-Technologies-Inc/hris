@if ($banks->isEmpty())
    <div class="text-center py-4 text-muted">No Bank found.</div>
@else
    <table class="table table-bordered mb-0">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Short Name</th>
                <th scope="col">Code</th>
                <th scope="col">Contact No</th>
                <th scope="col">Contact Person</th>
                <th scope="col">Contact Person No</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sl = \App\HelperClass::indexNumberSerialization($banks);
            @endphp
            @foreach ($banks as $item)
                <tr>
                    <th scope="row">{{ $sl++ }}</th>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->short_name }}</td>
                    <td>{{ $item->bank_code }}</td>
                    <td>{{ $item->contact_no }}</td>
                    <td>{{ $item->contact_person }}</td>
                    <td>{{ $item->contact_person_no }}</td>

                    <td>
                        @if ($item->status == 'active')
                            <span class="badge text-bg-success">Active</span>
                        @else
                            <span class="badge text-bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        @can('banks.edit')
                            <a type="button" class="btn btn-primary btn-sm" href="{{ route('banks.edit', $item->id) }}">
                                <i style="height: 12px; width: 12px" data-feather="edit"></i>
                            </a>
                        @endcan

                        @can('banks.delete')
                            <form action="{{ route('banks.delete', $item->id) }}" method="POST"
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

