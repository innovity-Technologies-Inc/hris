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
        {{-- Employee HAS an active ID card - Show View ID Card button with dropdown --}}
        <div class="btn-group">
            <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-vcard me-2"></i>View ID Card
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="{{ route('employees.id_card.view', $employee->id) }}" target="_blank">
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
                    <button type="button" class="dropdown-item text-warning"
                        onclick="confirmRegenerateIdCard({{ $employee->id }}, '{{ $employee->full_name }}')">
                        <i class="bi bi-arrow-repeat me-2"></i>Regenerate
                    </button>
                    <form action="{{ route('employees.id_card.regenerate', $employee->id) }}" method="POST"
                        id="regenerateIdCardForm-{{ $employee->id }}" style="display: none;">
                        @csrf
                    </form>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <button type="button" class="dropdown-item text-danger"
                        onclick="confirmCancelIdCard({{ $employee->id }}, '{{ $employee->full_name }}')">
                        <i class="bi bi-x-circle me-2"></i>Cancel ID
                    </button>
                    <form action="{{ route('employees.id_card.deactivate', $employee->id) }}" method="POST"
                        id="cancelIdCardForm-{{ $employee->id }}" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    @else
        {{-- Employee does NOT have an active ID card - Show Generate button --}}
        @if ($hasActiveDesign)
            <button type="button" class="btn btn-primary" id="generateIdCardBtn-{{ $employee->id }}"
                onclick="confirmGenerateIdCard({{ $employee->id }}, '{{ $employee->full_name }}')">
                <i class="bi bi-plus-circle me-2"></i>Generate ID Card
            </button>

            <form action="{{ route('employees.id_card.generate', $employee->id) }}" method="POST"
                id="generateIdCardForm-{{ $employee->id }}" style="display: none;">
                @csrf
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

<script>
    function confirmGenerateIdCard(employeeId, employeeName) {
        Swal.fire({
            title: 'Are you sure you want to generate ID card?',
            text: 'Generate ID card for ' + employeeName + '?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                // Show loading state
                const btn = document.getElementById('generateIdCardBtn-' + employeeId);
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-2"></span>Generating...';
                }

                // Submit the form
                document.getElementById('generateIdCardForm-' + employeeId).submit();

                // Return promise to keep loading state
                return new Promise((resolve) => {
                    // Don't resolve, let page reload handle it
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        });
    }

    function confirmRegenerateIdCard(employeeId, employeeName) {
        Swal.fire({
            title: 'Are you sure you want to regenerate ID card?',
            text: 'This will invalidate the current ID card and create a new one for ' + employeeName + '.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                // Submit the form
                document.getElementById('regenerateIdCardForm-' + employeeId).submit();

                // Return promise to keep loading state
                return new Promise((resolve) => {
                    // Don't resolve, let page reload handle it
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        });
    }

    function confirmCancelIdCard(employeeId, employeeName) {
        Swal.fire({
            title: 'Are you sure you want to cancel ID card?',
            text: 'This will deactivate the card and make it inactive for ' + employeeName + '.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Confirm',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                // Submit the form
                document.getElementById('cancelIdCardForm-' + employeeId).submit();

                // Return promise to keep loading state
                return new Promise((resolve) => {
                    // Don't resolve, let page reload handle it
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        });
    }
</script>
