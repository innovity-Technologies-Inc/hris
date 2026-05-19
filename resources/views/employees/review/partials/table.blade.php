<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="bg-light">
            <tr>
                <th class="ps-4 py-3 text-muted small fw-bold text-uppercase">Employee</th>
                <th class="py-3 text-muted small fw-bold text-uppercase">ID</th>
                <th class="py-3 text-muted small fw-bold text-uppercase">Organization</th>
                <th class="py-3 text-muted small fw-bold text-uppercase">Designation</th>
                <th class="py-3 text-muted small fw-bold text-uppercase">Submission Date</th>
                <th class="py-3 text-center text-muted small fw-bold text-uppercase" style="width: 120px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $employee)
            <tr>
                <td class="ps-4">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm rounded-circle bg-light d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                            <i class="fas fa-user text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">{{ $employee->full_name }}</h6>
                            <small class="text-muted">{{ $employee->work_email }}</small>
                        </div>
                    </div>
                </td>
                <td><span class="badge bg-light text-dark fw-normal">{{ $employee->system_id }}</span></td>
                <td>
                    <div class="small">
                        <span class="text-dark fw-semibold">{{ optional($employee->officeInfo->getCurrentCompany)->name }}</span><br>
                        <span class="text-muted">{{ optional($employee->officeInfo->getCurrentDepartment)->department_name }}</span>
                    </div>
                </td>
                <td>{{ optional($employee->officeInfo->getCurrentDesignation)->company_designation }}</td>
                <td>{{ $employee->updated_at->format('d M, Y') }}</td>
                <td class="text-center">
                    <a href="{{ route('employees.profile.general_informations', $employee->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                        <i class="fas fa-eye me-1"></i> View
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-5">
                    <div class="text-muted">
                        <i class="fas fa-clipboard-check mb-3" style="font-size: 48px; opacity: 0.5;"></i>
                        <p class="mb-0">No pending profiles found for review.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($employees->hasPages())
<div class="d-flex justify-content-center p-4 border-top">
    {{ $employees->links() }}
</div>
@endif
