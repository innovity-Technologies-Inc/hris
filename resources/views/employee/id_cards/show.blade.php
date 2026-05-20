@extends('structure.master')

@section('content')
    <div class="py-4" style="max-width: 800px; margin: 0 auto;">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('employee.id_cards.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
                <i class="bi bi-arrow-left me-1"></i> Back to ID Cards
            </a>
            <h2 class="fs-3 fw-bold text-dark mb-1">ID Card Details</h2>
            <p class="text-muted mb-0">View employee ID card information</p>
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

        <!-- Main Card -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <!-- Status Badge -->
                <div class="text-end mb-3">
                    @if ($employeeIdCard->status === 'active')
                        @if ($employeeIdCard->isExpired())
                            <span class="badge bg-warning fs-6 px-3 py-2">
                                <i class="bi bi-exclamation-triangle me-1"></i>Expired
                            </span>
                        @else
                            <span class="badge bg-success fs-6 px-3 py-2">
                                <i class="bi bi-check-circle me-1"></i>Active
                            </span>
                        @endif
                    @else
                        <span class="badge bg-secondary fs-6 px-3 py-2">Inactive</span>
                    @endif
                </div>

                <!-- Employee Info -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-person text-primary me-2"></i>Employee Information
                        </h5>
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted" style="width: 40%;">Name</td>
                                <td class="fw-semibold">{{ $employeeIdCard->employee?->full_name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Employee ID</td>
                                <td><code>{{ $employeeIdCard->employee?->system_id ?? 'N/A' }}</code></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-card-heading text-primary me-2"></i>Card Information
                        </h5>
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted" style="width: 40%;">Card Number</td>
                                <td><code class="fs-6">{{ $employeeIdCard->card_number ?? 'N/A' }}</code></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Design Used</td>
                                <td>{{ $employeeIdCard->idCardDesign?->theme_name ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <!-- Dates -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-calendar-check text-primary me-2"></i>Issue Date
                        </h5>
                        <p class="fs-5 mb-0">{{ $employeeIdCard->issue_date?->format('F d, Y') ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-calendar-x text-primary me-2"></i>Expiry Date
                        </h5>
                        <p class="fs-5 mb-0 {{ $employeeIdCard->isExpired() ? 'text-danger' : '' }}">
                            {{ $employeeIdCard->expiry_date?->format('F d, Y') ?? 'N/A' }}
                            @if ($employeeIdCard->isExpired())
                                <span class="badge bg-danger ms-2">Expired</span>
                            @endif
                        </p>
                    </div>
                </div>

                <hr>

                <!-- Actions -->
                <div class="d-flex gap-2 flex-wrap">
                    @if ($employeeIdCard->pdfExists())
                        <a href="{{ route('employee.id_card.view', $employeeIdCard->employee_id) }}"
                            class="btn btn-primary" target="_blank">
                            <i class="bi bi-eye me-2"></i>View PDF
                        </a>
                        <a href="{{ route('employee.id_card.download', $employeeIdCard->employee_id) }}"
                            class="btn btn-outline-primary">
                            <i class="bi bi-download me-2"></i>Download PDF
                        </a>
                    @else
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            PDF file not found. Please regenerate the ID card.
                        </div>
                    @endif

                    @if ($employeeIdCard->status === 'active')
                        <form action="{{ route('employee.id_card.regenerate', $employeeIdCard->employee_id) }}"
                            method="POST" class="d-inline"
                            onsubmit="return confirm('Regenerate will invalidate the current ID card. Continue?')">
                            @csrf
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-arrow-repeat me-2"></i>Regenerate
                            </button>
                        </form>

                        <form action="{{ route('employee.id_card.deactivate', $employeeIdCard->employee_id) }}"
                            method="POST" class="d-inline"
                            onsubmit="return confirm('Are you sure you want to deactivate this ID card?')">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-x-circle me-2"></i>Deactivate
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Footer -->
            <div class="card-footer bg-light">
                <small class="text-muted">
                    <i class="bi bi-clock me-1"></i>
                    Created: {{ $employeeIdCard->created_at?->format('M d, Y H:i') ?? 'N/A' }}
                    @if ($employeeIdCard->updated_at && $employeeIdCard->updated_at != $employeeIdCard->created_at)
                        | Updated: {{ $employeeIdCard->updated_at->format('M d, Y H:i') }}
                    @endif
                </small>
            </div>
        </div>
    </div>
@endsection

