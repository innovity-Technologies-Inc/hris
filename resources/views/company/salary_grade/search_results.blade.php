@if ($salary_grades->isEmpty())
    <div class="text-center py-4 text-muted">No Salary Grade found.</div>
@else
    <table class="table table-bordered mb-0">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Salary Grade</th>
                <th scope="col">Act</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sl = \App\HelperClass::indexNumberSerialization($salary_grades);
            @endphp
            @foreach ($salary_grades as $item)
                <tr>
                    <th scope="row">{{ $sl++ }}</th>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->getTofsil->name ?? 'N/A' }}</td>

                    <td>
                        @if ($item->status == 'active')
                            <span class="badge text-bg-success">Active</span>
                        @else
                            <span class="badge text-bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <a type="button" class="btn btn-primary btn-sm"
                            href="{{ route('salary_grades.edit', $item->id) }}">
                            <i style="height: 12px; width: 12px" data-feather="edit"></i>
                        </a>

                        <form action="{{ route('salary_grades.delete', $item->id) }}" method="POST"
                            style="display: inline-block">
                            @csrf
                            @method('DELETE')

                            <button class ="btn btn-sm btn-danger confirmDelete">
                                <i style="height: 12px; width: 12px" data-feather="trash"></i>
                            </button>
                        </form>

                    </td>

                </tr>
            @endforeach

        </tbody>
    </table>
@endif

