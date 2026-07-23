<div class="p-0">
    @php
        $currency = \App\HelperClass::getCurrency() ?? '৳';
    @endphp

    @if ($calculations->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-info-circle fs-3 d-block mb-2 text-secondary"></i>
            No tax calculation records found. Trigger the calculation using the button above.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered mb-0 align-middle">
                <thead>
                    <tr class="table-light text-muted small text-uppercase">
                        <th scope="col" style="width: 60px;">#</th>
                        <th scope="col">Employee</th>
                        <th scope="col">Annual Gross Salary</th>
                        <th scope="col">Exemption Amount</th>
                        <th scope="col">Taxable Income</th>
                        <th scope="col">Slab Details (JSON)</th>
                        <th scope="col" class="text-center">Slabs Reached</th>
                        <th scope="col">Total Tax</th>
                        <th scope="col">Tax Payable (Annual)</th>
                        <th scope="col" class="text-success fw-bold">Tax Per Month</th>
                        <th scope="col">Calculation Date</th>
                    </tr>
                </thead>
                <tbody>
                    @php $sl = ($calculations->currentPage() - 1) * $calculations->perPage() + 1; @endphp
                    @foreach ($calculations as $calculation)
                        <tr>
                            <th scope="row" class="fw-semibold">{{ $sl++ }}</th>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    {!! \App\HelperClass::generateAvatar(
                                        $calculation->employee->photo_path ?? null,
                                        $calculation->employee->full_name ?? 'N/A',
                                        40,
                                        '#974063',
                                        '',
                                        $calculation->employee_id,
                                    ) !!}
                                    <div>
                                        <a href="{{ route('employee.profile.general_informations', $calculation->employee_id) }}"
                                            class="text-decoration-none fw-semibold text-dark">
                                            {{ $calculation->employee->full_name ?? 'N/A' }}
                                        </a>
                                        <div class="text-muted small">ID: {{ $calculation->employee->applicant_id ?? $calculation->employee->system_id ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">
                                    {{ $currency }}{{ number_format($calculation->gross_salary, 2) }}
                                </div>
                                <small class="text-muted">Monthly: {{ $currency }}{{ number_format($calculation->gross_salary / 12, 2) }}</small>
                            </td>
                            <td>
                                <div class="text-muted">
                                    {{ $currency }}{{ number_format($calculation->exemption_amount, 2) }}
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold text-primary">
                                    {{ $currency }}{{ number_format($calculation->taxable_amount, 2) }}
                                </div>
                            </td>
                            <td>
                                @if(!empty($calculation->slab_taxes))
                                    <div class="slab-badges d-flex flex-wrap gap-1">
                                        @foreach($calculation->slab_taxes as $slab)
                                            <span class="badge bg-light text-dark border p-1" title="Slab {{ $slab['slab_index'] }} (Limit: {{ $slab['taxable_limit'] }})">
                                                S{{ $slab['slab_index'] }} ({{ $slab['percentage'] }}%): <strong>{{ $currency }}{{ number_format($slab['taxed_amount'], 2) }}</strong>
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted small">No tax applied</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary rounded-pill px-2.5 py-1">
                                    {{ $calculation->slabs_reached }}
                                </span>
                            </td>
                            <td>
                                <div class="text-dark small">
                                    {{ $currency }}{{ number_format($calculation->total_tax_amount, 2) }}
                                </div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">
                                    {{ $currency }}{{ number_format($calculation->tax_payable, 2) }}
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-success fs-6">
                                    {{ $currency }}{{ number_format($calculation->tax_per_month, 2) }}
                                </div>
                            </td>
                            <td>
                                <span class="small text-muted">{{ $calculation->updated_at->format('d M Y, h:i A') }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($calculations->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $calculations->links() }}
            </div>
        @endif
    @endif
</div>
