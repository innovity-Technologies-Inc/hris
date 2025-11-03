
<div class="container-fluid py-4 bg-light">

        @if(!empty($employeeData))
        @php
            $benefits = [$employeeData->house_allowance, $employeeData->transport_allowance,
            $employeeData->food_allowance, $employeeData->medical_allowance, $employeeData->other_earnings];
            $total_benefits = array_sum($benefits);
        @endphp


            <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="bg-white p-4 border-bottom border-3 border-dark">
                    <h3 class="fw-bold text-dark mb-1">Salary Breakdown Statement</h3>
                    <p class="text-muted mb-0 small">Official Compensation Details</p>
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
                                    <th class="fw-semibold text-dark text-end">Amount ({{ $employeeData->currency }})</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-dark fw-semibold">Basic Salary</td>
                                    <td class="text-dark text-end fw-bold">{{ number_format($employeeData->basic_salary, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="bg-light">
                                        <span class="fw-semibold text-dark small">ALLOWANCES & BENEFITS</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-dark ps-4">House Allowance</td>
                                    <td class="text-dark text-end">{{ number_format($employeeData->house_allowance, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-dark ps-4">Transport Allowance</td>
                                    <td class="text-dark text-end">{{ number_format($employeeData->transport_allowance, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-dark ps-4">Food Allowance</td>
                                    <td class="text-dark text-end">{{ number_format($employeeData->food_allowance, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-dark ps-4">Medical Allowance</td>
                                    <td class="text-dark text-end">{{ number_format($employeeData->medical_allowance, 2) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-dark ps-4">Other Earnings</td>
                                    <td class="text-dark text-end">{{ number_format($employeeData->other_earnings, 2) }}</td>
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
                                    <td class="text-dark text-end fw-bold">{{ number_format($employeeData->basic_salary, 2) }} {{ $employeeData->currency }}</td>
                                </tr>
                                <tr>
                                    <td class="bg-light fw-semibold text-dark">Total Allowances & Benefits</td>
                                    <td class="text-dark text-end fw-bold">{{ number_format($total_benefits, 2) }} {{ $employeeData->currency }}</td>
                                </tr>
                                <tr class="table-secondary">
                                    <td class="fw-bold text-dark fs-5">GROSS SALARY</td>
                                    <td class="text-dark text-end fw-bold fs-5">{{ number_format($employeeData->gross_salary, 2) }} {{ $employeeData->currency }}</td>
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
                        <h3 class="fw-bold text-dark mb-0">{{ number_format($employeeData->basic_salary, 2) }}</h3>
                        <p class="text-muted small mb-0">{{ $employeeData->currency }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border border-secondary border-2 shadow-sm h-100">
                    <div class="card-body text-center py-4">
                        <p class="text-muted text-uppercase small fw-semibold mb-2">Total Benefits</p>
                        <h3 class="fw-bold text-dark mb-0">{{ number_format($total_benefits, 2) }}</h3>
                        <p class="text-muted small mb-0">{{ $employeeData->currency }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border border-dark border-3 shadow-sm h-100 bg-light">
                    <div class="card-body text-center py-4">
                        <p class="text-dark text-uppercase small fw-bold mb-2">Gross Salary</p>
                        <h2 class="fw-bold text-dark mb-0">{{ number_format($employeeData->gross_salary, 2) }}</h2>
                        <p class="text-dark small fw-semibold mb-0">{{ $employeeData->currency }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Note -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="bg-white p-3 border-start border-dark border-4">
                    <p class="text-muted small mb-0"><strong>Note:</strong> This is an official salary breakdown statement. All amounts are in {{ $employeeData->currency }}. For any discrepancies, please contact the HR department.</p>
                </div>
            </div>
        </div>

        @else
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6 col-md-8 col-sm-10">
                        <div class="card shadow-sm border-0 mt-5 mb-5">
                            <div class="card-body text-center p-5">

                                <!-- Empty State Circle -->
                                <div class="d-flex justify-content-center mb-4">
                                    <div class="rounded-circle bg-light border border-2 border-secondary d-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                                        <span class="display-1 text-secondary fw-light">?</span>
                                    </div>
                                </div>

                                <!-- Heading -->
                                <h3 class="fw-bold text-dark mb-3">Employee Information Not Found</h3>

                                <!-- Divider -->
                                <hr class="w-50 mx-auto opacity-25 mb-4">

                                <!-- Message -->
                                <p class="text-muted mb-4 fs-6 lh-base px-lg-5">
                                    No employee records are currently available in the system.
                                    Please add employee information to get started.
                                </p>

                                <!-- Action Button -->
                                <a href="{{route('employees.salary_breakdown.create', $employee->id)}}" class="btn btn-primary btn-lg px-5 rounded-pill">
                                    Add Information
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @if(!empty($employeeData))
        <!-- Action Buttons -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route('employees.salary_breakdown.edit', $employee->id) }}"
                               class="btn btn-primary">
                                <i class="mdi mdi-pencil me-1"></i> Edit Salary Information
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


