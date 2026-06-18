@extends('structure.master')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-file-invoice-dollar me-2"></i>Payroll Details - {{ $payroll->getEmployee->full_name }}
                    </h5>
                    <div>
                        <a href="{{ route('salary.payroll.payslip', $payroll->id) }}" class="btn btn-white text-danger btn-sm me-2 border-danger" target="_blank" style="background: white;">
                            <i class="fas fa-file-pdf me-1"></i>Generate Payslip
                        </a>
                        <a href="{{ url()->previous() }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Employee & Batch Header --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    {!! \App\HelperClass::generateAvatar(
                                        $payroll->getEmployee->photo_path,
                                        $payroll->getEmployee->full_name,
                                        80,
                                        '#974063',
                                        'rounded-circle shadow-sm',
                                        $payroll->getEmployee->id,
                                    ) !!}
                                </div>
                                <div>
                                    <h4 class="mb-1 text-primary">{{ $payroll->getEmployee->full_name }}</h4>
                                    <p class="text-muted mb-0"><strong>ID:</strong> {{ $payroll->getEmployee->applicant_id }}</p>
                                    <p class="text-muted mb-0"><strong>System ID:</strong> {{ $payroll->getEmployee->system_id }}</p>
                                    <p class="text-muted mb-0"><strong>Department:</strong> {{ $payroll->getEmployee->officeInfo->getCurrentDepartment->department_name ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end mt-3 mt-md-0">
                            <div class="p-3 bg-light rounded shadow-sm border-start border-4 border-primary d-inline-block text-start">
                                <h6 class="text-muted small text-uppercase fw-bold mb-2">Payroll Information</h6>
                                <p class="mb-1"><strong>Batch ID:</strong> <span class="text-primary">{{ $payroll->batch_id }}</span></p>
                                <p class="mb-0"><strong>Salary Month:</strong> {{ $payroll->getBatch ? \Carbon\Carbon::parse($payroll->getBatch->salary_month)->format('F, Y') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Earnings Breakdown --}}
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm bg-success bg-opacity-10 border-top border-4 border-success">
                                <div class="card-body">
                                    <h6 class="card-title text-success fw-bold mb-3">
                                        <i class="fas fa-plus-circle me-2"></i>Earnings Breakdown
                                    </h6>
                                    <ul class="list-group list-group-flush bg-transparent">
                                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0 border-light">
                                            Gross Salary
                                            <span class="fw-bold">৳ {{ number_format($payroll->salary, 2) }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0 border-light">
                                            Overtime ({{ $payroll->overtime_count }} hrs)
                                            <span class="fw-bold">৳ {{ number_format($payroll->overtime_amount, 2) }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0 border-light">
                                            Off-day Work ({{ $payroll->offday_work_count }} days)
                                            <span class="fw-bold">৳ {{ number_format($payroll->offday_work_salary, 2) }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0 border-light">
                                            Bonus Amount
                                            <span class="fw-bold">৳ {{ number_format($payroll->bonus_amount, 2) }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0 border-light mt-2 pt-2 border-top border-success border-2">
                                            <strong>Total Earnings</strong>
                                            <strong class="text-success">৳ {{ number_format($payroll->salary + $payroll->overtime_amount + $payroll->offday_work_salary + $payroll->bonus_amount, 2) }}</strong>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Deductions Breakdown --}}
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm bg-danger bg-opacity-10 border-top border-4 border-danger">
                                <div class="card-body">
                                    <h6 class="card-title text-danger fw-bold mb-3">
                                        <i class="fas fa-minus-circle me-2"></i>Deductions & Losses
                                    </h6>
                                    <ul class="list-group list-group-flush bg-transparent">
                                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0 border-light">
                                            Late Deduction
                                            <span class="fw-bold text-danger">৳ {{ number_format($payroll->late_deduction_amount, 2) }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0 border-light">
                                            Excessive Late Deduction
                                            <span class="fw-bold text-danger">৳ {{ number_format($payroll->excessive_late_deduction_amount, 2) }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0 border-light">
                                            Absent Deduction
                                            <span class="fw-bold text-danger">৳ {{ number_format($payroll->absent_deduction_amount, 2) }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0 border-light">
                                            Early Exit Deduction
                                            <span class="fw-bold text-danger">৳ {{ number_format($payroll->early_exit_deduction_amount, 2) }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0 border-light">
                                            Penalty Amount
                                            <span class="fw-bold text-danger">৳ {{ number_format($payroll->penalty_amount, 2) }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0 border-light">
                                            Advance Deduction
                                            <span class="fw-bold text-danger">৳ {{ number_format($payroll->advance_deduction_amount, 2) }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0 border-light mt-2 pt-2 border-top border-danger border-2">
                                            <strong>Total Deductions</strong>
                                            <strong class="text-danger">৳ {{ number_format($payroll->deduction_amount + $payroll->penalty_amount + $payroll->advance_deduction_amount, 2) }}</strong>
                                        </li>
                                        <div class="mt-4 pt-3 border-top border-danger border-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <strong>Net Payable</strong>
                                                <h4 class="text-primary mb-0 fw-bold">৳ {{ number_format($payroll->total_salary, 2) }}</h4>
                                            </div>
                                        </div>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Attendance Stats --}}
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm border-top border-4 border-info bg-info bg-opacity-10">
                                <div class="card-body">
                                    <h6 class="card-title text-info fw-bold mb-3">
                                        <i class="fas fa-calendar-check me-2"></i>Attendance Statistics
                                    </h6>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="p-2 rounded shadow-sm text-center">
                                                <small class="text-muted d-block">Late</small>
                                                <span class="fw-bold">{{ $payroll->late_count }}</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-2 rounded shadow-sm text-center border-start border-3 border-danger">
                                                <small class="text-muted d-block">Excess Late</small>
                                                <span class="fw-bold text-danger">{{ $payroll->excessive_late_count }}</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-2 rounded shadow-sm text-center">
                                                <small class="text-muted d-block">Early Exit</small>
                                                <span class="fw-bold">{{ $payroll->early_exit_count }}</span>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="p-2 rounded shadow-sm text-center border-start border-3 border-danger">
                                                <small class="text-muted d-block">Absents</small>
                                                <span class="fw-bold text-danger">{{ $payroll->absent_count }}</span>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-2">
                                            <div class="p-2 rounded shadow-sm">
                                                <small class="text-muted d-block mb-1">Absent Dates:</small>
                                                @if($payroll->absent_dates && count($payroll->absent_dates) > 0)
                                                    <div class="d-flex flex-wrap gap-1">
                                                        @foreach($payroll->absent_dates as $date)
                                                            <span class="badge bg-danger bg-opacity-75 small">{{ \Carbon\Carbon::parse($date)->format('d M') }}</span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-success small">No absent dates recorded</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-12 mt-2">
                                            <div class="p-2 rounded shadow-sm text-center">
                                                <small class="text-muted d-block">Leaves Taken</small>
                                                <span class="fw-bold text-primary">{{ $payroll->leaves_count }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

