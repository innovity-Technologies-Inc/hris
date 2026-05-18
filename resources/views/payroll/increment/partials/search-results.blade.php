{{--
================================================
SEARCH RESULTS PARTIAL - Employee Increments
================================================
This partial is loaded via AJAX in index.blade.php

Expected variables from controller:
- $increments: LengthAwarePaginator with increment objects

Each increment object should have:
- getEmployee (object with full_name, applicant_id, officeInfo)
- increment_method, increment_amount, increment_base
- effective_from, effective_to (Carbon instances)
- status (pending/approved/rejected)
- getStatusBadgeClass() method
--}}

<div class="card-body p-0">
    @can('increments.create')
    <a type="button" class="btn btn-warning btn-sm me-3 mb-3" href="{{ route('increment.create') }}">
        <i style="height: 12px; width: 12px" data-feather="plus"></i> Create Increment
    </a>
    @endcan

    @if ($increments->isNotEmpty())
        @can('increments.hr-approve')
        <a type="button" class="btn btn-success btn-sm me-3 mb-3 float-end" href="{{ route('increment.adjustment') }}">
            <i style="height: 12px; width: 12px" data-feather="check"></i> Increment Adjustment
        </a>
        @endcan
    @endif

    @if ($increments->isEmpty())
        <div class="text-center py-4 text-muted">No increment records found.</div>
    @else
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Employee</th>
                            <th scope="col">Current Designation</th>
                            <th scope="col">Increment Summary</th>
                            <th scope="col">Effective From</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $sl = ($increments->currentPage() - 1) * $increments->perPage() + 1; @endphp
                        @foreach ($increments as $increment)
                            <tr>
                                <th scope="row">{{ $sl++ }}</th>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        {!! \App\HelperClass::generateAvatar(
                                            $increment->getEmployee->photo_path ?? null,
                                            $increment->getEmployee->full_name ?? 'N/A',
                                            40,
                                            '#974063',
                                            '',
                                            $increment->employee_id,
                                        ) !!}
                                        <div>
                                            <a href="{{ route('employees.profile.general_informations', $increment->employee_id) }}"
                                                class="text-decoration-none">
                                                <div class="fw-semibold text-dark">
                                                    {{ $increment->getEmployee->full_name ?? 'N/A' }}</div>
                                            </a>
                                            <small
                                                class="text-muted">{{ $increment->getEmployee->applicant_id ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small">
                                        <span
                                            class="fw-semibold">{{ $increment->getEmployee->officeInfo->getCurrentDesignation->company_designation ?? 'N/A' }}</span>
                                        <br>
                                    </div>
                                </td>
                                <td>
                                    <div class="small">
                                        <span class="badge bg-info mb-1">
                                            {{ ucfirst($increment->increment_method) }}
                                        </span>
                                        <div class="fw-semibold text-primary">
                                            @if ($increment->increment_method === 'percentage')
                                                {{ $increment->salary_increase_amount }}%
                                            @else
                                                ৳{{ number_format($increment->salary_increase_amount, 2) }}
                                            @endif
                                        </div>
                                        <small class="text-muted">
                                            on {{ ucfirst(str_replace('_', ' ', $increment->increment_base)) }}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="small">{{ \Carbon\Carbon::parse($increment->effective_from)->format('d M Y') }}</span>
                                </td>
                                <td>
                                    <span
                                        class="badge @if ($increment->status == 'pending') bg-warning @elseif($increment->status == 'approved') bg-success
                                     @else bg-danger @endif">
                                        {{ ucfirst($increment->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        {{-- View Button --}}
                                        @can('increments.view')
                                        <a href="{{ route('increment.show', $increment->id) }}"
                                            class="btn btn-info btn-sm" title="View Details">
                                            <i style="height: 12px; width: 12px" data-feather="eye"></i>
                                        </a>
                                        @endcan

                                        @if ($increment->status == 'pending')
                                            {{-- Edit Button --}}
                                            @can('increments.edit')
                                            <a href="{{ route('increment.edit', $increment->id) }}"
                                                class="btn btn-primary btn-sm" title="Edit">
                                                <i style="height: 12px; width: 12px" data-feather="edit"></i>
                                            </a>
                                            @endcan

                                            {{-- Approve Button --}}
                                            @can('increments.hr-approve')
                                            <form class="d-inline"
                                                action="{{ route('increment.status.update', $increment->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="btn btn-success btn-sm confirmApprove"
                                                    title="Approve">
                                                    <i style="height: 12px; width: 12px" data-feather="check"></i>
                                                </button>
                                            </form>

                                            {{-- Reject Button --}}
                                            <form class="d-inline" method="POST"
                                                action="{{ route('increment.status.update', $increment->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-danger btn-sm confirmReject"
                                                    title="Reject">
                                                    <i style="height: 12px; width: 12px" data-feather="x"></i>
                                                </button>
                                            </form>
                                            @endcan
                                        @endif
                                        @can('increments.delete')
                                        <form class="d-inline"
                                              action="{{ route('increment.delete', $increment->id) }}"
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
            @if ($increments->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $increments->links() }}
                </div>
            @endif

        </div>
    @endif
</div>
<div class="text-muted small">
    Showing {{ $increments->firstItem() ?? 0 }} to {{ $increments->lastItem() ?? 0 }}
    of {{ $increments->total() }} entries
</div>
<div>
    {{ $increments->links() }}
</div>
