{{--
================================================
SEARCH RESULTS PARTIAL - Employee Promotions
================================================
This partial is loaded via AJAX in index.blade.php

Expected variables from controller:
- $promotions: LengthAwarePaginator with promotion objects

Each promotion object should have:
- getEmployee (object with full_name, applicant_id)
- getPreviousDesignation (object with company_designation)
- getNewDesignation (object with company_designation)
- increment_method, increment_amount, increment_base
- effective_from, effective_to (Carbon instances)
- status (pending/approved/rejected)
- getStatusBadgeClass() method
--}}

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
                            <th scope="col">Increment Summary</th>
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
                                        @if (isset($promotion->getEmployee->photo_path) && $promotion->getEmployee->photo_path)
                                            <img src="{{ asset('storage/' . $promotion->getEmployee->photo_path) }}"
                                                alt="Profile" class="rounded-circle"
                                                style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px; font-size: 14px; font-weight: bold; color: white;">
                                                {{ strtoupper(substr($promotion->getEmployee->full_name ?? 'U', 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ $promotion->getEmployee->full_name ?? 'N/A' }}
                                            </div>
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
                                    <div class="small">
                                        <span class="badge bg-info mb-1">
                                            {{ ucfirst($promotion->increment_method) }}
                                        </span>
                                        <div class="fw-semibold text-primary">
                                            @if ($promotion->increment_method === 'percentage')
                                                {{ $promotion->increment_amount }}%
                                            @else
                                                ৳{{ number_format($promotion->increment_amount, 2) }}
                                            @endif
                                        </div>
                                        <small class="text-muted">
                                            on {{ ucfirst(str_replace('_', ' ', $promotion->increment_base)) }}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $promotion->status_badge_class }}">
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

                                        @if ($promotion->status == 'approved' || $promotion->status == 'rejected')
                                            {{-- View Only --}}
                                            <span
                                                class="badge bg-secondary small">{{ ucfirst($promotion->status) }}</span>
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
</div>
