@if ($company_types->isEmpty())
    <div class="text-center py-4 text-muted">No Company Type found.</div>
@else
    <table class="table table-bordered mb-0">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Short Name</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sl = \App\HelperClass::indexNumberSerialization($company_types);
            @endphp
            @foreach ($company_types as $company_type)
                <tr>
                    <th scope="row">{{ $sl++ }}</th>
                    <td>{{ $company_type->name }}</td>
                    <td>{{ $company_type->short_name }}</td>
                    <td>
                        @if ($company_type->status == 'active')
                            <span class="badge text-bg-success">Active</span>
                        @else
                            <span class="badge text-bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        @can('company-types.edit')
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#company_type-edit{{ $company_type->id }}">
                            <i style="height: 12px; width: 12px" data-feather="edit"></i>
                        </button>
                        @endcan

                        @can('company-types.delete')
                        <form action="{{ route('company_types.delete', $company_type->id) }}" method="POST"
                            style="display: inline-block">
                            @csrf
                            @method('DELETE')

                            <button class ="btn btn-sm btn-danger confirmDelete">
                                <i style="height: 12px; width: 12px" data-feather="trash"></i>
                            </button>
                        </form>
                        @endcan
                    </td>

                    @include('company_setup.modal.company_type_edit')

                </tr>
            @endforeach

        </tbody>
    </table>
@endif
