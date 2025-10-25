@extends('structure.master')
@section('content')

        <!-- Tabbed Content -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body pt-0">
                        <ul class="nav nav-underline border-bottom pt-2" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active p-2" id="payroll_info_tab" data-bs-toggle="tab" href="#payroll_info" role="tab">
                                    <span class="d-none d-sm-block">Payroll Information</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link p-2" id="joining_info_tab" data-bs-toggle="tab" href="#joining_info" role="tab">
                                    <span class="d-none d-sm-block">Joining Information</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link p-2" id="current_info_tab" data-bs-toggle="tab" href="#current_info" role="tab">
                                    <span class="d-none d-sm-block">Current Information</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link p-2" id="orientation_tab" data-bs-toggle="tab" href="#orientation" role="tab">
                                    <span class="d-none d-sm-block">Orientation</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link p-2" id="duration_tab" data-bs-toggle="tab" href="#duration" role="tab">
                                    <span class="d-none d-sm-block">Duration & Cycles</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link p-2" id="schedule_tab" data-bs-toggle="tab" href="#schedule" role="tab">
                                    <span class="d-none d-sm-block">Work Schedule</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link p-2" id="benefits_tab" data-bs-toggle="tab" href="#benefits" role="tab">
                                    <span class="d-none d-sm-block">Eligibility & Benefits</span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content text-muted">
                            <!-- Payroll Information Tab -->
                            <div class="tab-pane active show pt-4" id="payroll_info" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="fs-16 text-dark fw-semibold mb-3">Employee Information</h5>
                                        <div class="table-responsive">
                                            <table class="table table-borderless mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td class="fw-semibold" style="width: 40%;">Employee Name</td>
                                                        <td>John Doe (EMP-2024-001)</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Employee Type</td>
                                                        <td><span class="badge bg-success">Permanent</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Pay Grade</td>
                                                        <td>Grade-5</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">HR File Number</td>
                                                        <td>HR-2024-1234</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Act (Tofsil)</td>
                                                        <td>TL-567</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <h5 class="fs-16 text-dark fw-semibold mb-3">File Notes</h5>
                                        <div class="card bg-light border-0">
                                            <div class="card-body">
                                                <p class="mb-0">Employee has excellent performance record. Eligible for promotion in Q2 2025. Special consideration for overseas training program.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Joining Information Tab -->
                            <div class="tab-pane pt-4" id="joining_info" role="tabpanel">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            <i class="mdi mdi-information-outline me-2"></i>
                                            <strong>Date of Joining:</strong> January 15, 2020
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="fs-16 text-dark fw-semibold mb-3">Organizational Details</h5>
                                        <div class="table-responsive">
                                            <table class="table table-borderless mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td class="fw-semibold" style="width: 40%;">Company</td>
                                                        <td>ABC Textiles Ltd.</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Business Unit</td>
                                                        <td>Manufacturing Division</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Division</td>
                                                        <td>Operations</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Department</td>
                                                        <td>Production Management</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <h5 class="fs-16 text-dark fw-semibold mb-3">Position Details</h5>
                                        <div class="table-responsive">
                                            <table class="table table-borderless mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td class="fw-semibold" style="width: 40%;">Designation</td>
                                                        <td>Assistant Manager</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Section</td>
                                                        <td>Quality Control</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Current Information Tab -->
                            <div class="tab-pane pt-4" id="current_info" role="tabpanel">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="alert alert-success">
                                            <i class="mdi mdi-calendar-check me-2"></i>
                                            <strong>Current Information Effective Date:</strong> July 1, 2024
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="fs-16 text-dark fw-semibold mb-3">Current Organizational Details</h5>
                                        <div class="table-responsive">
                                            <table class="table table-borderless mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td class="fw-semibold" style="width: 40%;">Company</td>
                                                        <td>ABC Textiles Ltd.</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Business Unit</td>
                                                        <td>Manufacturing Division</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Division</td>
                                                        <td>Operations</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Department</td>
                                                        <td>Quality Assurance</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <h5 class="fs-16 text-dark fw-semibold mb-3">Current Position Details</h5>
                                        <div class="table-responsive">
                                            <table class="table table-borderless mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td class="fw-semibold" style="width: 40%;">Designation</td>
                                                        <td><span class="badge bg-info">Manager</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Section</td>
                                                        <td>Quality Assurance</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Orientation Tab -->
                            <div class="tab-pane pt-4" id="orientation" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="fs-16 text-dark fw-semibold mb-3">Orientation Program</h5>
                                        <div class="table-responsive">
                                            <table class="table table-borderless mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td class="fw-semibold" style="width: 40%;">Orientation Required</td>
                                                        <td><span class="badge bg-success">Yes</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Orientation From</td>
                                                        <td>January 15, 2020</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Orientation To</td>
                                                        <td>January 29, 2020</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Orientation Type</td>
                                                        <td>General & Department Specific</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Orientation Days</td>
                                                        <td>15 days</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Duration & Cycles Tab -->
                            <div class="tab-pane pt-4" id="duration" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="fs-16 text-dark fw-semibold mb-3">Employment Duration</h5>
                                        <div class="table-responsive">
                                            <table class="table table-borderless mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td class="fw-semibold" style="width: 40%;">Confirmation Date</td>
                                                        <td>July 15, 2020</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Probation Duration</td>
                                                        <td>6 months</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Next Promotion Date</td>
                                                        <td>July 1, 2026</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <h5 class="fs-16 text-dark fw-semibold mb-3">Review Cycles</h5>
                                        <div class="table-responsive">
                                            <table class="table table-borderless mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td class="fw-semibold" style="width: 40%;">Promotion Cycle</td>
                                                        <td>Every 2 years</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Increment Cycle</td>
                                                        <td>Annual (Every July)</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Work Schedule Tab -->
                            <div class="tab-pane pt-4" id="schedule" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="fs-16 text-dark fw-semibold mb-3">Work Schedule</h5>
                                        <div class="table-responsive">
                                            <table class="table table-borderless mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td class="fw-semibold" style="width: 40%;">Weekends</td>
                                                        <td>
                                                            <span class="badge bg-secondary me-1">Friday</span>
                                                            <span class="badge bg-secondary">Saturday</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Alternate Off Day</td>
                                                        <td>Sunday (Bi-weekly)</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Eligibility & Benefits Tab -->
                            <div class="tab-pane pt-4" id="benefits" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="fs-16 text-dark fw-semibold mb-3">Salary & Benefits</h5>
                                        <div class="table-responsive">
                                            <table class="table table-borderless mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td class="fw-semibold" style="width: 40%;">Salary Type</td>
                                                        <td>Monthly</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <h5 class="fs-16 text-dark fw-semibold mb-3 mt-4">Compensation Benefits</h5>
                                        <div class="card border">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <div>
                                                        <i class="mdi mdi-clock-check text-success fs-20 me-2"></i>
                                                        <span class="fw-semibold">OT Allowed</span>
                                                    </div>
                                                    <span class="badge bg-success">Yes</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <div>
                                                        <i class="mdi mdi-piggy-bank text-info fs-20 me-2"></i>
                                                        <span class="fw-semibold">PF Eligible</span>
                                                    </div>
                                                    <span class="badge bg-success">Yes</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <div>
                                                        <i class="mdi mdi-bus text-primary fs-20 me-2"></i>
                                                        <span class="fw-semibold">Transport Eligible</span>
                                                    </div>
                                                    <span class="badge bg-success">Yes</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <i class="mdi mdi-gift text-warning fs-20 me-2"></i>
                                                        <span class="fw-semibold">Gratuity Eligible</span>
                                                    </div>
                                                    <span class="badge bg-success">Yes</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <h5 class="fs-16 text-dark fw-semibold mb-3">Financial Benefits</h5>
                                        <div class="card border">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <div>
                                                        <i class="mdi mdi-cash-multiple text-success fs-20 me-2"></i>
                                                        <span class="fw-semibold">Can Apply Loan</span>
                                                    </div>
                                                    <span class="badge bg-success">Yes</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <i class="mdi mdi-cash-fast text-primary fs-20 me-2"></i>
                                                        <span class="fw-semibold">Can Apply Advance</span>
                                                    </div>
                                                    <span class="badge bg-success">Yes</span>
                                                </div>
                                            </div>
                                        </div>

                                        <h5 class="fs-16 text-dark fw-semibold mb-3 mt-4">Fund Details</h5>
                                        <div class="table-responsive">
                                            <table class="table table-borderless mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td class="fw-semibold" style="width: 40%;">PF Effective Date</td>
                                                        <td>July 15, 2020</td>
                                                    </tr>
                                                </tbody>
                                            </table>
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
