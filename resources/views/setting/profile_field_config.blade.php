@extends('structure.master')

@section('content')
    <div class="py-4" style="max-width: 1200px; margin: 0 auto;">

        {{-- Page Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3 d-inline-flex align-items-center justify-content-center">
                    <i class="bi bi-sliders text-primary fs-3"></i>
                </div>
                <div>
                    <h2 class="fs-4 fw-bold mb-0">Profile Field Configuration</h2>
                    <p class="text-muted mb-0 small">Configure which employee profile fields are required or optional</p>
                </div>
            </div>
        </div>

        {{-- Info Alert --}}
        <div class="alert alert-info border-0 shadow-sm rounded-3 d-flex align-items-center mb-4" role="alert">
            <i class="bi bi-info-circle-fill text-info fs-5 me-3 flex-shrink-0"></i>
            <div>
                <strong>How it works:</strong> Check the <span class="badge bg-danger">Required</span> toggle to make a field mandatory.
                Unchecked fields will be optional. Changes apply immediately to both create and edit forms, including backend validation.
            </div>
        </div>

        <form action="{{ route('setting.profile_field_config.save') }}" method="POST" id="profileFieldConfigForm">
            @csrf

            {{-- Section Icons Map --}}
            @php
                $sectionIcons = [
                    'general' => 'bi-person-badge',
                    'office-information' => 'bi-building',
                    'employee-policy' => 'bi-calendar-check',
                    'education' => 'bi-mortarboard',
                    'employment_history' => 'bi-briefcase',
                    'emergency_contact' => 'bi-telephone',
                    'salary-breakdown' => 'bi-cash-stack',
                    'employee-bank-account' => 'bi-bank',
                ];

                $sectionColors = [
                    'general' => '#974063',
                    'office-information' => '#0d6efd',
                    'employee-policy' => '#198754',
                    'education' => '#6f42c1',
                    'employment_history' => '#fd7e14',
                    'emergency_contact' => '#dc3545',
                    'salary-breakdown' => '#20c997',
                    'employee-bank-account' => '#0dcaf0',
                ];
            @endphp

            {{-- Accordion Sections --}}
            <div class="accordion" id="profileFieldAccordion">
                @foreach($configs as $section => $fields)
                    @php
                        $sectionIcon = $sectionIcons[$section] ?? 'bi-gear';
                        $sectionColor = $sectionColors[$section] ?? '#6c757d';
                        $sectionLabel = $sectionLabels[$section] ?? ucfirst(str_replace(['-', '_'], ' ', $section));
                        $requiredCount = $fields->where('is_required', true)->count();
                        $totalCount = $fields->count();
                    @endphp
                    <div class="card border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                        <div class="card-header bg-white border-bottom-0 py-0 px-0" id="heading-{{ $section }}">
                            <button class="btn w-100 text-start d-flex align-items-center py-3 px-4 collapsed"
                                    type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse-{{ $section }}"
                                    aria-expanded="false" aria-controls="collapse-{{ $section }}"
                                    style="text-decoration: none;">
                                <div class="rounded-circle p-2 me-3 d-inline-flex align-items-center justify-content-center"
                                     style="background: {{ $sectionColor }}15; width: 44px; height: 44px;">
                                    <i class="bi {{ $sectionIcon }}" style="color: {{ $sectionColor }}; font-size: 1.25rem;"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-0 text-dark">{{ $sectionLabel }}</h6>
                                    <small class="text-muted">
                                        <span class="badge bg-danger bg-opacity-10 text-danger me-1 section-required-count" data-section="{{ $section }}">{{ $requiredCount }} required</span>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $totalCount }} fields</span>
                                    </small>
                                </div>
                                <i class="bi bi-chevron-down text-muted fs-5 accordion-chevron"></i>
                            </button>
                        </div>
                        <div id="collapse-{{ $section }}" class="collapse"
                             aria-labelledby="heading-{{ $section }}"
                             data-bs-parent="#profileFieldAccordion">
                            <div class="card-body px-4 pb-4 pt-2">
                                {{-- Bulk Actions --}}
                                <div class="d-flex justify-content-end mb-3 gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-danger select-all-btn" data-section="{{ $section }}">
                                        <i class="bi bi-check-all me-1"></i> Select All
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary deselect-all-btn" data-section="{{ $section }}">
                                        <i class="bi bi-x-lg me-1"></i> Deselect All
                                    </button>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="border-0 fw-semibold text-muted" style="width: 50%;">Field Name</th>
                                                <th class="border-0 fw-semibold text-muted" style="width: 25%;">Database Column</th>
                                                <th class="border-0 fw-semibold text-muted text-center" style="width: 25%;">Required</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($fields as $field)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <i class="bi bi-dot text-muted fs-4 me-1"></i>
                                                            <span class="fw-medium">{{ $field->label }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <code class="text-muted bg-light rounded px-2 py-1 small">{{ $field->field_name }}</code>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="form-check form-switch d-flex justify-content-center mb-0">
                                                            <input class="form-check-input field-toggle"
                                                                   type="checkbox"
                                                                   name="required_fields[]"
                                                                   value="{{ $field->id }}"
                                                                   data-section="{{ $section }}"
                                                                   id="field-{{ $field->id }}"
                                                                   role="switch"
                                                                   {{ $field->is_required ? 'checked' : '' }}
                                                                   style="width: 3rem; height: 1.5rem; cursor: pointer;">
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Sticky Save Button --}}
            <div class="position-sticky bottom-0 bg-body pt-3 pb-4" style="z-index: 100;">
                <div class="card border-0 shadow-lg rounded-3">
                    <div class="card-body d-flex align-items-center justify-content-between py-3 px-4">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-shield-check text-success fs-4 me-2"></i>
                            <span class="text-muted small">Changes will affect all employee create/edit forms and backend validation rules.</span>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm">
                            <i class="bi bi-check2-circle me-2"></i> Save Configuration
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Update section required counts
        function updateSectionCount(section) {
            const toggles = document.querySelectorAll(`.field-toggle[data-section="${section}"]`);
            const checkedCount = Array.from(toggles).filter(t => t.checked).length;
            const badge = document.querySelector(`.section-required-count[data-section="${section}"]`);
            if (badge) {
                badge.textContent = checkedCount + ' required';
            }
        }

        // Field toggle change handler
        document.querySelectorAll('.field-toggle').forEach(toggle => {
            toggle.addEventListener('change', function() {
                updateSectionCount(this.dataset.section);
            });
        });

        // Select All per section
        document.querySelectorAll('.select-all-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const section = this.dataset.section;
                document.querySelectorAll(`.field-toggle[data-section="${section}"]`).forEach(t => {
                    t.checked = true;
                });
                updateSectionCount(section);
            });
        });

        // Deselect All per section
        document.querySelectorAll('.deselect-all-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const section = this.dataset.section;
                document.querySelectorAll(`.field-toggle[data-section="${section}"]`).forEach(t => {
                    t.checked = false;
                });
                updateSectionCount(section);
            });
        });
    });
</script>
@endpush

@push('styles')
<style>
    .accordion-chevron {
        transition: transform 0.3s ease;
    }
    [aria-expanded="true"] .accordion-chevron {
        transform: rotate(180deg);
    }
    .form-check-input:checked {
        background-color: #dc3545;
        border-color: #dc3545;
    }
    .form-switch .form-check-input:focus {
        box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
    }
    .table tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    [data-bs-theme="dark"] .table-light {
        background-color: rgba(255, 255, 255, 0.05);
    }
    [data-bs-theme="dark"] code.text-muted {
        background-color: rgba(255, 255, 255, 0.1) !important;
        color: #adb5bd !important;
    }
</style>
@endpush
