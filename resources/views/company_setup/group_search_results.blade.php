@if ($groups->isEmpty())
    <div class="text-center py-4 text-muted">No Group found.</div>
@else
    <div class="table-responsive" >
        <table class="table table-bordered mb-0">
            <thead>

            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>

            @php
                $sl = \App\HelperClass::indexNumberSerialization($groups);
            @endphp
            @foreach ($groups as $group)
                <tr>
                    <th scope="row">{{ $sl++ }}</th>
                    <td>{{ $group->name }}</td>
                    <td>
                        @if ($group->status == 'active')
                            <span class="badge text-bg-success">Active</span>
                        @else
                            <span class="badge text-bg-danger">Inactive</span>
                    @endif
                    <td>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#group-edit{{ $group->id }}">
                            <i style="height: 12px; width: 12px" data-feather="edit"></i>
                        </button>

                        <form action="{{ route('groups.delete', $group->id) }}" method="POST"
                              style="display: inline-block">
                            @csrf
                            @method('DELETE')

                            <button class ="btn btn-sm btn-danger confirmDelete">
                                <i style="height: 12px; width: 12px" data-feather="trash"></i>
                            </button>


                        </form>

                    </td>

                    @include('company_setup.modal.group_edit')

                </tr>
            @endforeach

            </tbody>
        </table>
        <div class="mt-3">
            {{ $groups->links() }}
        </div>
    </div>

@endif
