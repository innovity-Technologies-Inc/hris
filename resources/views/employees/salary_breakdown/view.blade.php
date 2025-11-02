@extends('structure.master')
@section('content')
    @php
        $employee = (object)[
            'id' => 1,
            'full_name' => 'Mohammad Rahman',
        ];
        $salaryBreakdown = (object)[
            'employee_id'         => 1,
            'effective_date'      => \Carbon\Carbon::parse('2025-11-01'),
            'basic_salary'        => 35000.00,
            'house_allowance'     => 8000.00,
            'transport_allowance' => 3500.00,
            'food_allowance'      => 2500.00,
            'medical_allowance'   => 2000.00,
            'performance_bonus'   => 5000.00,
            'overtime_pay'        => 1500.00,
            'other_earnings'      => 1000.00,
            'currency'            => 'BDT',
        ];
        $total_benefits =
            ($salaryBreakdown->house_allowance ?? 0) +
            ($salaryBreakdown->transport_allowance ?? 0) +
            ($salaryBreakdown->food_allowance ?? 0) +
            ($salaryBreakdown->medical_allowance ?? 0) +
            ($salaryBreakdown->performance_bonus ?? 0) +
            ($salaryBreakdown->overtime_pay ?? 0) +
            ($salaryBreakdown->other_earnings ?? 0);
        $gross_salary = $salaryBreakdown->basic_salary + $total_benefits;
    @endphp

    <div class="container-fluid py-4 bg-light">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="bg-white p-4 border-bottom border-3 border-dark">
                    <h3 class="fw-bold text-dark mb-1">Salary Breakdown Statement</h3>
                    <p class="text-muted mb-0 small">Official Compensation Details</p>
                </div>
            </div>
        </div>

        <!-- Employee Information -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-secondary text-white border-0 py-3">
                        <h6 class="mb-0 fw-bold text-uppercase small">Employee Information</h6>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-bordered mb-0">
                            <tbody>
                                <tr>
                                    <td class="bg-light fw-semibold text-dark" width="30%">Employee Name</td>
                                    <td class="text-dark">{{ $employee->full_name }}</td>
                                </tr>
                                <tr>
                                    <td class="bg-light fw-semibold text-dark">Effective Date</td>
                                    <td class="text-dark">{{ $salaryBreakdown->effective_date->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="bg-light fw-semibold text-dark">Currency</td>
                                    <td class="text-dark">{{ $salaryBreakdown->currency }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Salary Components -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-secondary text-white border-0 py-3">
                        <h6 class="mb-0 fw-bold text-uppercase small">Salary Components</h6>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="fw-semibold text-dark">Component</th>
                                    <th class="fw-semibold text-dark text-end">Amount ({{ $salaryBreakdown->currency }})</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-dark fw-semibold">Basic Salary</td>
                                    <td class="text-dark text-end fw-bold">{{ number_format($salaryBreakdown->basic_salary, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="bg-light">
                                        <span class="fw-semibold text-dark small">ALLOWANCES & BENEFITS</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-dark ps-4">House Allowance</td>
                                    <td class="text-dark text-end">{{ number_format($salaryBreakdown->house_allowance, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-dark ps-4">Transport Allowance</td>
                                    <td class="text-dark text-end">{{ number_format($salaryBreakdown->transport_allowance, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-dark ps-4">Food Allowance</td>
                                    <td class="text-dark text-end">{{ number_format($salaryBreakdown->food_allowance, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-dark ps-4">Medical Allowance</td>
                                    <td class="text-dark text-end">{{ number_format($salaryBreakdown->medical_allowance, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-dark ps-4">Performance Bonus</td>
                                    <td class="text-dark text-end">{{ number_format($salaryBreakdown->performance_bonus, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-dark ps-4">Overtime Pay</td>
                                    <td class="text-dark text-end">{{ number_format($salaryBreakdown->overtime_pay, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-dark ps-4">Other Earnings</td>
                                    <td class="text-dark text-end">{{ number_format($salaryBreakdown->other_earnings, 2) }}</td>
                                </tr>
                                <tr class="table-light">
                                    <td class="text-dark fw-bold">Total Benefits</td>
                                    <td class="text-dark text-end fw-bold">{{ number_format($total_benefits, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Salary Summary -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-secondary text-white border-0 py-3">
                        <h6 class="mb-0 fw-bold text-uppercase small">Salary Summary</h6>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-bordered mb-0">
                            <tbody>
                                <tr>
                                    <td class="bg-light fw-semibold text-dark" width="70%">Basic Salary</td>
                                    <td class="text-dark text-end fw-bold">{{ number_format($salaryBreakdown->basic_salary, 2) }} {{ $salaryBreakdown->currency }}</td>
                                </tr>
                                <tr>
                                    <td class="bg-light fw-semibold text-dark">Total Allowances & Benefits</td>
                                    <td class="text-dark text-end fw-bold">{{ number_format($total_benefits, 2) }} {{ $salaryBreakdown->currency }}</td>
                                </tr>
                                <tr class="table-secondary">
                                    <td class="fw-bold text-dark fs-5">GROSS SALARY</td>
                                    <td class="text-dark text-end fw-bold fs-5">{{ number_format($gross_salary, 2) }} {{ $salaryBreakdown->currency }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row g-3">
            <div class="col-md-4">
                <div class="card border border-secondary border-2 shadow-sm h-100">
                    <div class="card-body text-center py-4">
                        <p class="text-muted text-uppercase small fw-semibold mb-2">Basic Salary</p>
                        <h3 class="fw-bold text-dark mb-0">{{ number_format($salaryBreakdown->basic_salary, 2) }}</h3>
                        <p class="text-muted small mb-0">{{ $salaryBreakdown->currency }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border border-secondary border-2 shadow-sm h-100">
                    <div class="card-body text-center py-4">
                        <p class="text-muted text-uppercase small fw-semibold mb-2">Total Benefits</p>
                        <h3 class="fw-bold text-dark mb-0">{{ number_format($total_benefits, 2) }}</h3>
                        <p class="text-muted small mb-0">{{ $salaryBreakdown->currency }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border border-dark border-3 shadow-sm h-100 bg-light">
                    <div class="card-body text-center py-4">
                        <p class="text-dark text-uppercase small fw-bold mb-2">Gross Salary</p>
                        <h2 class="fw-bold text-dark mb-0">{{ number_format($gross_salary, 2) }}</h2>
                        <p class="text-dark small fw-semibold mb-0">{{ $salaryBreakdown->currency }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Note -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="bg-white p-3 border-start border-dark border-4">
                    <p class="text-muted small mb-0"><strong>Note:</strong> This is an official salary breakdown statement. All amounts are in {{ $salaryBreakdown->currency }}. For any discrepancies, please contact the HR department.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
