<div class="table-responsive">
    <table class="table table-bordered mb-0">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Title</th>
                <th scope="col">Grade</th>
                <th scope="col">Pay Group</th>
                <th scope="col">Min Salary</th>
                <th scope="col">Max Salary</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sl = \App\HelperClass::indexNumberSerialization($payScales);
            @endphp
            @forelse($payScales as $scale)
                <tr>
                    <th scope="row">{{ $sl++ }}</th>
                    <td class="fw-bold">{{ $scale->title }}</td>
                    <td>
                        <span class="text-dark">{{ $scale->grade?->grade_code ?? 'N/A' }}</span>
                    </td>
                    <td>
                        {{ $scale->payGroup?->title ?? 'N/A' }}
                    </td>
                    <td>{{ \App\HelperClass::getCurrency() }} {{ number_format($scale->min_salary, 2) }}</td>
                    <td>{{ \App\HelperClass::getCurrency() }} {{ number_format($scale->max_salary, 2) }}</td>
                    <td>
                        @if ($scale->status === 'active')
                            <span class="badge text-bg-success">Active</span>
                        @else
                            <span class="badge text-bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td class="text-end">
                        @can('pay-scales.edit')
                        <button type="button" class="btn btn-primary btn-sm me-1 edit-pay-scale" 
                                data-id="{{ $scale->id }}" title="Edit">
                            <i style="height: 12px; width: 12px" data-feather="edit"></i>
                        </button>
                        @endcan
                        @can('pay-scales.delete')
                        <button type="button" class="btn btn-danger btn-sm delete-pay-scale" 
                                data-id="{{ $scale->id }}" title="Delete">
                            <i style="height: 12px; width: 12px" data-feather="trash"></i>
                        </button>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">No pay scales found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $payScales->links('pagination::bootstrap-5') }}
</div>
