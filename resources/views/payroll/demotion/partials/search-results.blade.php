<div class="p-0">
    @if ($demotions->isNotEmpty())
        @can('demotions.hr-approve')
        <div class="text-end mb-3">
            <a type="button" class="btn btn-success btn-sm" href="{{ route('demotion.adjustment') }}">
                <i style="height: 12px; width: 12px" data-feather="check"></i> Demotion Adjustment
            </a>
        </div>
        @endcan
    @endif

    @if ($demotions->isEmpty())
        <div class="text-center py-4 text-muted">No demotion records found.</div>
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
                    @php $sl = ($demotions->currentPage() - 1) * $demotions->perPage() + 1; @endphp
                    @foreach ($demotions as $demotion)
                        <tr>
                            <th scope="row">{{ $sl++ }}</th>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    {!! \App\HelperClass::generateAvatar(
                                        $demotion->getEmployee->photo_path ?? null,
                                        $demotion->getEmployee->full_name ?? 'N/A',
                                        40,
                                        '#974063',
                                        '',
                                        $demotion->employee_id,
                                    ) !!}
                                    <div>
                                        <a href="{{ route('employee.profile.general_informations', $demotion->employee_id) }}"
                                            class="text-decoration-none">
                                            <div class="fw-semibold text-dark">
                                                {{ $demotion->getEmployee->full_name ?? 'N/A' }}</div>
                                        </a>
                                        <small
                                            class="text-muted">{{ $demotion->getEmployee->applicant_id ?? 'N/A' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span
                                    class="text-muted">{{ $demotion->getPreviousDesignation->company_designation ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="fw-semibold text-danger">
                                    {{ $demotion->getNewDesignation->company_designation ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-semibold text-danger fs-6">
                                    ৳{{ number_format($demotion->new_gross_salary, 2) }}
                                </div>
                            </td>
                            <td>
                                <span
                                    class="small">{{ \Carbon\Carbon::parse($demotion->effective_from)->format('d M Y') }}</span>
                            </td>
                            <td>
                                <span
                                    class="badge @if ($demotion->status == 'pending') bg-warning @elseif($demotion->status == 'approved') bg-success
                                    @else bg-danger @endif">
                                    {{ ucfirst($demotion->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    {{-- View Button --}}
                                    @can('demotions.view')
                                    <a href="{{ route('demotion.show', $demotion->id) }}"
                                        class="btn btn-info btn-sm" title="View Details">
                                        <i style="height: 12px; width: 12px" data-feather="eye"></i>
                                    </a>
                                    @endcan

                                    @if ($demotion->status == 'pending')
                                        {{-- Edit Button --}}
                                        @can('demotions.edit')
                                        <a href="{{ route('demotion.edit', $demotion->id) }}"
                                            class="btn btn-primary btn-sm" title="Edit">
                                            <i style="height: 12px; width: 12px" data-feather="edit"></i>
                                        </a>
                                        @endcan

                                    @endif
                                    @can('demotions.delete')
                                    <form class="d-inline"
                                            action="{{ route('demotion.delete', $demotion->id) }}"
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
        @if ($demotions->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $demotions->links() }}
            </div>
        @endif
    @endif
</div>
