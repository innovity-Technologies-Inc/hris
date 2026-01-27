{{--
    Employee ID Card Action Component

    Usage:
    @include('employee.partials.id_card_button', ['employee' => $employee])

    Shows "Generate ID Card" or "View ID Card" based on whether the employee has an active ID card.
--}}

@php
    $hasActiveIdCard = $employee->hasActiveIdCard();
    $activeIdCard = $hasActiveIdCard ? $employee->getActiveIdCard() : null;
    $hasActiveDesign = \App\Models\IDCardDesign::where('status', 'active')->exists();
@endphp

<div class="id-card-action">
    @if ($hasActiveIdCard && $activeIdCard)
        {{-- Employee HAS an active ID card - Show View ID Card button --}}
        <div class="btn-group" role="group">
            <a href="{{ route('employees.id_card.view', $employee->id) }}" class="btn btn-success" target="_blank"
                title="View ID Card">
                <i class="bi bi-person-vcard me-2"></i>View ID Card
            </a>
            <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown"
                aria-expanded="false">
                <span class="visually-hidden">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="{{ route('employees.id_card.view', $employee->id) }}"
                        target="_blank">
                        <i class="bi bi-eye me-2"></i>View PDF
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('employees.id_card.download', $employee->id) }}">
                        <i class="bi bi-download me-2"></i>Download PDF
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <form action="{{ route('employees.id_card.regenerate', $employee->id) }}" method="POST"
                        class="d-inline"
                        onsubmit="return confirm('Regenerate will invalidate the current ID card and create a new one. Continue?')">
                        @csrf
                        <button type="submit" class="dropdown-item text-warning">
                            <i class="bi bi-arrow-repeat me-2"></i>Regenerate
                        </button>
                    </form>
                </li>
            </ul>
        </div>

        {{-- Card Status Info --}}
        @if ($activeIdCard->isExpired())
            <small class="d-block text-warning mt-1">
                <i class="bi bi-exclamation-triangle me-1"></i>
                Card expired on {{ $activeIdCard->expiry_date->format('M d, Y') }}
            </small>
        @else
            <small class="d-block text-muted mt-1">
                Card #{{ $activeIdCard->card_number }} | Valid until
                {{ $activeIdCard->expiry_date?->format('M d, Y') ?? 'N/A' }}
            </small>
        @endif
    @else
        {{-- Employee does NOT have an active ID card - Show Generate button --}}
        @if ($hasActiveDesign)
            <form action="{{ route('employees.id_card.generate', $employee->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-primary"
                    onclick="return confirm('Generate ID Card for {{ $employee->full_name }}?')">
                    <i class="bi bi-plus-circle me-2"></i>Generate ID Card
                </button>
            </form>
        @else
            <button type="button" class="btn btn-secondary" disabled title="No active ID card design available">
                <i class="bi bi-exclamation-circle me-2"></i>Generate ID Card
            </button>
            <small class="d-block text-danger mt-1">
                <i class="bi bi-info-circle me-1"></i>
                No active ID card design. <a href="{{ route('settings.id_design.index') }}">Activate a design</a>
            </small>
        @endif
    @endif
</div>
