<div class="table-responsive">
    <table class="table table-bordered align-middle mb-0">
        <thead>
            <tr>
                <th scope="col" class="text-muted small fw-bold text-uppercase">Employee</th>
                <th scope="col" class="text-muted small fw-bold text-uppercase">System ID</th>
                <th scope="col" class="text-muted small fw-bold text-uppercase">Organization</th>
                <th scope="col" class="text-muted small fw-bold text-uppercase">Designation</th>
                <th scope="col" class="text-muted small fw-bold text-uppercase">Submission Date</th>
                <th scope="col" class="text-center text-muted small fw-bold text-uppercase" style="width: 120px;">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $employee)
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        {!! \App\HelperClass::generateAvatar(
                            $employee->photo_path,
                            $employee->full_name,
                            32,
                            '#974063',
                            'me-2',
                            $employee->id,
                        ) !!}
                        <div>
                            <h6 class="mb-0 fw-bold">{{ $employee->full_name }}</h6>
                            <small class="text-muted">{{ $employee->work_email }}</small>
                        </div>
                    </div>
                </td>
                <td><span class="badge bg-light text-dark fw-normal px-2 py-1">{{ $employee->system_id }}</span></td>
                <td>
                    <div class="small">
                        <span class="text-dark fw-semibold">{{ optional($employee->officeInfo->getCurrentCompany)->name }}</span><br>
                        <span class="text-muted">{{ optional($employee->officeInfo->getCurrentDepartment)->department_name }}</span>
                    </div>
                </td>
                <td>{{ optional($employee->officeInfo->getCurrentDesignation)->company_designation }}</td>
                <td>{{ $employee->updated_at->format('d M, Y') }}</td>
                <td class="text-center">
                    <a href="{{ route('employees.profile.general_informations', $employee->id) }}" class="btn btn-secondary btn-sm" title="View">
                        <i style="height: 12px; width: 12px" data-feather="eye"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-5">
                    <div class="text-muted">
                        <i data-feather="clipboard" class="mb-3" style="height: 48px; width: 48px; opacity: 0.5;"></i>
                        <p class="mb-0">No pending profiles found for review.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($employees->hasPages())
<div class="mt-3">
    {{ $employees->appends(request()->query())->links() }}
</div>
@endif

<script>
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
</script>
