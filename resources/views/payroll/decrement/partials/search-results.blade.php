<div class="card-body p-0">

    @if ($decrements->isEmpty())
        <div class="text-center py-4 text-muted">No decrement records found.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Employee</th>
                        <th scope="col">Current Designation</th>
                        <th scope="col">Decrement Summary</th>
                        <th scope="col">Effective From</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $sl = ($decrements->currentPage() - 1) * $decrements->perPage() + 1; @endphp
                    @foreach ($decrements as $decrement)
                        <tr>
                            <th scope="row">{{ $sl++ }}</th>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    {!! \App\HelperClass::generateAvatar(
                                        $decrement->getEmployee->photo_path ?? null,
                                        $decrement->getEmployee->full_name ?? 'N/A',
                                        40,
                                        '#974063',
                                        '',
                                        $decrement->employee_id,
                                    ) !!}
                                    <div>
                                        <a href="{{ route('employee.profile.general_informations', $decrement->employee_id) }}"
                                            class="text-decoration-none">
                                            <div class="fw-semibold text-dark">
                                                {{ $decrement->getEmployee->full_name ?? 'N/A' }}</div>
                                        </a>
                                        <small
                                            class="text-muted">{{ $decrement->getEmployee->applicant_id ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small">
                                    <span
                                        class="fw-semibold">{{ $decrement->getEmployee->officeInfo?->getCurrentDesignation?->company_designation ?? 'N/A' }}</span>
                                    <br>
                                </div>
                            </td>
                            <td>
                                <div class="small">
                                    <span class="badge bg-info mb-1">
                                        {{ ucfirst($decrement->decrement_method) }}
                                    </span>
                                    @if($decrement->movementType)
                                        <span class="badge bg-secondary mb-1">
                                            {{ $decrement->movementType->name }}
                                        </span>
                                    @endif
                                    <div class="fw-semibold text-primary">
                                        @if ($decrement->decrement_method === 'percentage')
                                            {{ $decrement->salary_decrease_amount }}%
                                        @else
                                            ৳{{ number_format($decrement->salary_decrease_amount, 2) }}
                                        @endif
                                    </div>
                                    <small class="text-muted">
                                        on {{ ucfirst(str_replace('_', ' ', $decrement->decrement_base)) }}
                                    </small>
                                </div>
                            </td>
                            <td>
                                <span
                                    class="small">{{ \Carbon\Carbon::parse($decrement->effective_from)->format('d M Y') }}</span>
                            </td>
                            <td>
                                <span
                                    class="badge @if ($decrement->status == 'pending') bg-warning @elseif($decrement->status == 'approved') bg-success
                                    @else bg-danger @endif">
                                    {{ ucfirst($decrement->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    {{-- View Button --}}
                                    @can('decrements.view')
                                    <a href="{{ route('decrement.show', $decrement->id) }}"
                                        class="btn btn-info btn-sm" title="View Details">
                                        <i style="height: 12px; width: 12px" data-feather="eye"></i>
                                    </a>
                                    @endcan

                                    @if ($decrement->status == 'pending')
                                        {{-- Edit Button --}}
                                        @can('decrements.edit')
                                        <a href="{{ route('decrement.edit', $decrement->id) }}"
                                            class="btn btn-primary btn-sm" title="Edit">
                                            <i style="height: 12px; width: 12px" data-feather="edit"></i>
                                        </a>
                                        @endcan
                                    @endif
                                    @can('decrements.delete')
                                    <form class="d-inline"
                                            action="{{ route('decrement.delete', $decrement->id) }}"
                                            method="POST">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger btn-sm confirmDelete"
                                                title="Delete">
                                            <i style="height: 12px; width: 12px" data-feather="trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($decrements->hasPages())
            <div class="d-flex justify-content-center mt-3 p-3">
                {{ $decrements->links() }}
            </div>
        @endif
    @endif
</div>
