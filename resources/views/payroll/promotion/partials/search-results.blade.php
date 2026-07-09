<div class="p-0">

    @if ($promotions->isEmpty())
        <div class="text-center py-4 text-muted">No promotion records found.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Employee</th>
                        <th scope="col">Previous Designation</th>
                        <th scope="col">New Designation</th>
                        <th scope="col">New Gross Salary</th>
                        <th scope="col">Effective From</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $sl = ($promotions->currentPage() - 1) * $promotions->perPage() + 1; @endphp
                    @foreach ($promotions as $promotion)
                        <tr>
                            <th scope="row">{{ $sl++ }}</th>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    {!! \App\HelperClass::generateAvatar(
                                        $promotion->getEmployee->photo_path ?? null,
                                        $promotion->getEmployee->full_name ?? 'N/A',
                                        40,
                                        '#974063',
                                        '',
                                        $promotion->employee_id,
                                    ) !!}
                                    <div>
                                        <a href="{{ route('employee.profile.general_informations', $promotion->employee_id) }}"
                                            class="text-decoration-none">
                                            <div class="fw-semibold text-dark">
                                                {{ $promotion->getEmployee->full_name ?? 'N/A' }}</div>
                                        </a>
                                        <small
                                            class="text-muted">{{ $promotion->getEmployee->applicant_id ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span
                                    class="text-muted">{{ $promotion->getPreviousDesignation->company_designation ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="fw-semibold text-success">
                                    {{ $promotion->getNewDesignation->company_designation ?? 'N/A' }}
                                </span>
                                @if($promotion->movementType)
                                    <br>
                                    <span class="badge bg-secondary mt-1">
                                        {{ $promotion->movementType->name }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold text-success fs-6">
                                    ৳{{ number_format($promotion->new_gross_salary, 2) }}
                                </div>
                            </td>
                            <td>
                                <span
                                    class="small">{{ \Carbon\Carbon::parse($promotion->effective_from)->format('d M Y') }}</span>
                            </td>
                            <td>
                                <span
                                    class="badge @if ($promotion->status == 'pending') bg-warning @elseif($promotion->status == 'approved') bg-success
                                    @else bg-danger @endif">
                                    {{ ucfirst($promotion->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    {{-- View Button --}}
                                    @can('promotions.view')
                                    <a href="{{ route('promotion.show', $promotion->id) }}"
                                        class="btn btn-info btn-sm" title="View Details">
                                        <i style="height: 12px; width: 12px" data-feather="eye"></i>
                                    </a>
                                    @endcan

                                    @if ($promotion->status == 'pending')
                                        {{-- Edit Button --}}
                                        @can('promotions.edit')
                                        <a href="{{ route('promotion.edit', $promotion->id) }}"
                                            class="btn btn-primary btn-sm" title="Edit">
                                            <i style="height: 12px; width: 12px" data-feather="edit"></i>
                                        </a>
                                        @endcan

                                    @endif
                                    @can('promotions.delete')
                                    <form class="d-inline"
                                            action="{{ route('promotion.delete', $promotion->id) }}"
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
        @if ($promotions->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $promotions->links() }}
            </div>
        @endif
    @endif
</div>

