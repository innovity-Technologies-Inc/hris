<div class="card-body p-0">
    <a type="button" class="btn btn-warning btn-sm me-3 mb-3" href="{{ route('promotion.create') }}">
        <i style="height: 12px; width: 12px" data-feather="plus"></i> Create Promotion
    </a>

    @if ($promotions->isEmpty())
        <div class="text-center py-4 text-muted">No promotion records found.</div>
    @else
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Employee</th>
                            <th scope="col">Previous Designation</th>
                            <th scope="col">New Designation</th>
                            <th scope="col">New Gross Salary</th>
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
                                            <a href="{{ route('employees.profile.general_informations', $promotion->employee_id) }}"
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
                                </td>
                                <td>
                                    <div class="fw-semibold text-success fs-6">
                                        ৳{{ number_format($promotion->new_gross_salary, 2) }}
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="badge @if ($promotion->status == 'pending') bg-warning @elseif($promotion->status == 'approved') bg-warning
                                     @else bg-danger @endif">
                                        {{ ucfirst($promotion->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        {{-- View Button --}}
                                        <a href="{{ route('promotion.show', $promotion->id) }}"
                                            class="btn btn-info btn-sm" title="View Details">
                                            <i style="height: 12px; width: 12px" data-feather="eye"></i>
                                        </a>

                                        @if ($promotion->status == 'pending')
                                            {{-- Edit Button --}}
                                            <a href="{{ route('promotion.edit', $promotion->id) }}"
                                                class="btn btn-primary btn-sm" title="Edit">
                                                <i style="height: 12px; width: 12px" data-feather="edit"></i>
                                            </a>

                                            {{-- Approve Button --}}
                                            <form class="d-inline"
                                                action="{{ route('promotion.approve', $promotion->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-success btn-sm confirmApprove"
                                                    title="Approve">
                                                    <i style="height: 12px; width: 12px" data-feather="check"></i>
                                                </button>
                                            </form>

                                            {{-- Reject Button --}}
                                            <form class="d-inline" method="POST"
                                                action="{{ route('promotion.reject', $promotion->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-danger btn-sm confirmReject"
                                                    title="Reject">
                                                    <i style="height: 12px; width: 12px" data-feather="x"></i>
                                                </button>
                                            </form>
                                        @endif
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

        </div>
    @endif
</div>
<div class="text-muted small">
    Showing {{ $promotions->firstItem() ?? 0 }} to {{ $promotions->lastItem() ?? 0 }}
    of {{ $promotions->total() }} entries
</div>
<div>
    {{ $promotions->links() }}
</div>
