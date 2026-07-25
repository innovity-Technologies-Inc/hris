<div class="p-0">
    @php
        $currency = \App\HelperClass::getCurrency() ?? '৳';
    @endphp

    @if ($deductions->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-info-circle fs-3 d-block mb-2 text-secondary"></i>
            No tax deduction records found.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered mb-0 align-middle">
                <thead>
                    <tr class="table-light text-muted small text-uppercase">
                        <th scope="col" style="width: 60px;">#</th>
                        <th scope="col">Employee</th>
                        <th scope="col" class="text-center">Salary Month</th>
                        <th scope="col" class="text-center">Deduction Date</th>
                        <th scope="col" class="text-center">Frequency</th>
                        <th scope="col" class="text-center">Hours/Days Worked</th>
                        <th scope="col" class="text-end">Annual Tax Payable</th>
                        <th scope="col" class="text-end">Monthly Tax Rate</th>
                        <th scope="col" class="text-end text-success fw-bold">Deducted Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php $sl = ($deductions->currentPage() - 1) * $deductions->perPage() + 1; @endphp
                    @foreach ($deductions as $deduction)
                        <tr>
                            <th scope="row" class="fw-semibold">{{ $sl++ }}</th>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    {!! \App\HelperClass::generateAvatar(
                                        $deduction->employee->photo_path ?? null,
                                        $deduction->employee->full_name ?? 'N/A',
                                        40,
                                        '#974063',
                                        '',
                                        $deduction->employee_id,
                                    ) !!}
                                    <div>
                                        <a href="{{ route('employee.profile.general_informations', $deduction->employee_id) }}"
                                            class="text-decoration-none fw-semibold text-dark">
                                            {{ $deduction->employee->full_name ?? 'N/A' }}
                                        </a>
                                        <div class="text-muted small">ID: {{ $deduction->employee->applicant_id ?? $deduction->employee->system_id ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center fw-semibold text-dark">
                                {{ Carbon\Carbon::parse($deduction->salary_month . '-01')->format('M Y') }}
                            </td>
                            <td class="text-center">
                                {{ $deduction->deduction_date ? $deduction->deduction_date->format('d M Y') : 'N/A' }}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 rounded">
                                    {{ ucfirst($deduction->frequency) }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($deduction->frequency === 'hourly')
                                    <span class="text-dark fw-semibold">{{ number_format($deduction->hours_worked, 2) }}</span> <span class="text-muted small">hrs</span>
                                @elseif($deduction->frequency === 'daily')
                                    <span class="text-dark fw-semibold">{{ number_format($deduction->days_worked, 1) }}</span> <span class="text-muted small">days</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-end fw-semibold text-dark">
                                {{ $currency }}{{ number_format($deduction->annual_tax_payable, 2) }}
                            </td>
                            <td class="text-end text-muted">
                                {{ $currency }}{{ number_format($deduction->monthly_tax_rate, 2) }}
                            </td>
                            <td class="text-end fw-bold text-success fs-6">
                                {{ $currency }}{{ number_format($deduction->amount, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($deductions->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3 px-3">
                <div class="text-muted small">
                    Showing {{ $deductions->firstItem() }} to {{ $deductions->lastItem() }} of {{ $deductions->total() }} entries
                </div>
                <div>
                    {!! $deductions->links('vendor.pagination.bootstrap-5') !!}
                </div>
            </div>
        @endif
    @endif
</div>
