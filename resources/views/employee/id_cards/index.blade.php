@extends('structure.master')

@section('content')
    <div class="py-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fs-3 fw-bold text-dark mb-1">Employee ID Cards</h2>
                <p class="text-muted mb-0">Manage generated employee ID cards</p>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- ID Cards Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                @if ($employeeIds->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Employee</th>
                                    <th>Card Number</th>
                                    <th>Design</th>
                                    <th>Status</th>
                                    <th>Issue Date</th>
                                    <th>Expiry Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employeeIds as $idCard)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <strong>{{ $idCard->employee?->full_name ?? 'N/A' }}</strong>
                                                    <br>
                                                    <small
                                                        class="text-muted">{{ $idCard->employee?->system_id ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <code>{{ $idCard->card_number ?? 'N/A' }}</code>
                                        </td>
                                        <td>{{ $idCard->idCardDesign?->theme_name ?? 'N/A' }}</td>
                                        <td>
                                            @if ($idCard->status === 'active')
                                                @if ($idCard->isExpired())
                                                    <span class="badge bg-warning">
                                                        <i class="bi bi-exclamation-triangle me-1"></i>Expired
                                                    </span>
                                                @else
                                                    <span class="badge bg-success">
                                                        <i class="bi bi-check-circle me-1"></i>Active
                                                    </span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">Inactive</span>
                                            @endif
                                        </td>
                                        <td>{{ $idCard->issue_date?->format('M d, Y') ?? 'N/A' }}</td>
                                        <td>{{ $idCard->expiry_date?->format('M d, Y') ?? 'N/A' }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                @if ($idCard->pdfExists())
                                                    <a href="{{ route('employees.id_card.view', $idCard->employee_id) }}"
                                                        class="btn btn-outline-primary" target="_blank" title="View PDF">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('employees.id_card.download', $idCard->employee_id) }}"
                                                        class="btn btn-outline-secondary" title="Download PDF">
                                                        <i class="bi bi-download"></i>
                                                    </a>
                                                @endif
                                                @if ($idCard->status === 'active')
                                                    <form
                                                        action="{{ route('employees.id_card.regenerate', $idCard->employee_id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Regenerate will invalidate the current ID card. Continue?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-warning"
                                                            title="Regenerate">
                                                            <i class="bi bi-arrow-repeat"></i>
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

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $employeeIds->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-person-vcard text-muted" style="font-size: 4rem;"></i>
                        </div>
                        <h4 class="text-muted mb-3">No ID Cards Generated Yet</h4>
                        <p class="text-muted mb-0">ID cards will appear here once they are generated for employees</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
