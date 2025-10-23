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
                                    <span class="d-none d-sm-block">Joining Details</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link p-2" id="current_info_tab" data-bs-toggle="tab" href="#current_info" role="tab">
                                    <span class="d-none d-sm-block">Current Position</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link p-2" id="orientation_tab" data-bs-toggle="tab" href="#orientation" role="tab">
                                    <span class="d-none d-sm-block">Orientation & Duration</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link p-2" id="benefits_tab" data-bs-toggle="tab" href="#benefits" role="tab">
                                    <span class="d-none d-sm-block">Benefits & Eligibility</span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content text-muted">
                            <!-- Payroll Information Tab -->
                            <div class="tab-pane active show pt-4" id="payroll_info" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="fs-16 text-dark fw-semibold mb-3">Employee Classification</h5>
                                        <div class="table-responsive">
                                            <table class="table table-borderless mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td class="fw-semibold" style="width: 40%;">Employee ID</td>
                                                        <td>EMP-2024-001</td>
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
                                                        <td class="fw-semibold">Category</td>
                                                        <td>Management</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">HR File Number</td>
                                                        <td>HR-2024-1234</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Totali</td>
                                                        <td>TL-567</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Status</td>
                                                        <td><span class="badge bg-primary">Active</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <h5 class="fs-16 text-dark fw-semibold mb-3">Salary & Payment</h5>
                                        <div class="table-responsive">
                                            <table class="table table-borderless mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td class="fw-semibold" style="width: 40%;">Salary Type</td>
                                                        <td>Monthly</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Imprest Fund</td>
                                                        <td>৳ 5,000.00</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Cash Collector</td>
                                                        <td>Mr. Jahangir Alam</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <h5 class="fs-16 text-dark fw-semibold mb-3 mt-4">File Notes</h5>
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
                                                    <tr>
                                                        <td class="fw-semibold">Subsection</td>
                                                        <td>Inspection Unit-A</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Floor</td>
                                                        <td>3rd Floor, Building-2</td>
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
                                                    <tr>
                                                        <td class="fw-semibold">Subsection</td>
                                                        <td>Final Inspection</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Floor</td>
                                                        <td>5th Floor, Building-1</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="card border border-primary">
                                            <div class="card-header bg-primary bg-opacity-10">
                                                <h6 class="card-title mb-0 text-primary">
                                                    <i class="mdi mdi-chart-line me-2"></i>Career Progression
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <span class="badge bg-light text-dark fs-14">Jan 2020</span>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h6 class="mb-1">Assistant Manager - Quality Control</h6>
                                                        <p class="text-muted mb-0">Joined as Assistant Manager</p>
                                                    </div>
                                                </div>
                                                <div class="border-start border-2 border-primary ms-3 ps-3 mt-3">
                                                    <div class="d-flex align-items-center mt-3">
                                                        <div class="flex-shrink-0">
                                                            <span class="badge bg-light text-dark fs-14">Jul 2024</span>
                                                        </div>
                                                        <div class="flex-grow-1 ms-3">
                                                            <h6 class="mb-1">Manager - Quality Assurance</h6>
                                                            <p class="text-muted mb-0">Promoted to Manager position</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Orientation & Duration Tab -->
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

                                        <h5 class="fs-16 text-dark fw-semibold mb-3 mt-4">Work Schedule</h5>
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

                                    <div class="col-md-6">
                                        <h5 class="fs-16 text-dark fw-semibold mb-3">Employment Duration</h5>
                                        <div class="table-responsive">
                                            <table class="table table-borderless mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td class="fw-semibold" style="width: 40%;">Probation Duration</td>
                                                        <td>6 months</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Confirmation Date</td>
                                                        <td>July 15, 2020</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold">Next Promotion Date</td>
                                                        <td>July 1, 2026</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <h5 class="fs-16 text-dark fw-semibold mb-3 mt-4">Review Cycles</h5>
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

                                <!-- Timeline Visualization -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="card border">
                                            <div class="card-header bg-light">
                                                <h5 class="card-title mb-0">Employment Timeline</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-3 text-center border-end">
                                                        <i class="mdi mdi-calendar-check fs-32 text-primary"></i>
                                                        <h6 class="mt-2">Join Date</h6>
                                                        <p class="text-muted">Jan 15, 2020</p>
                                                    </div>
                                                    <div class="col-md-3 text-center border-end">
                                                        <i class="mdi mdi-account-check fs-32 text-success"></i>
                                                        <h6 class="mt-2">Confirmed</h6>
                                                        <p class="text-muted">Jul 15, 2020</p>
                                                    </div>
                                                    <div class="col-md-3 text-center border-end">
                                                        <i class="mdi mdi-arrow-up-bold fs-32 text-info"></i>
                                                        <h6 class="mt-2">Last Promotion</h6>
                                                        <p class="text-muted">Jul 1, 2024</p>
                                                    </div>
                                                    <div class="col-md-3 text-center">
                                                        <i class="mdi mdi-clock-outline fs-32 text-warning"></i>
                                                        <h6 class="mt-2">Total Service</h6>
                                                        <p class="text-muted">4 years 9 months</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Benefits & Eligibility Tab -->
                            <div class="tab-pane pt-4" id="benefits" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="fs-16 text-dark fw-semibold mb-3">Compensation Benefits</h5>
                                        <div class="card border">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <div>
                                                        <i class="mdi mdi-clock-check text-success fs-20 me-2"></i>
                                                        <span class="fw-semibold">Overtime Allowed</span>
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
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <div>
                                                        <i class="mdi mdi-piggy-bank text-info fs-20 me-2"></i>
                                                        <span class="fw-semibold">Provident Fund Eligible</span>
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

                                        <h5 class="fs-16 text-dark fw-semibold mb-3 mt-4">Separation Details</h5>
                                        <div class="table-responsive">
                                            <table class="table table-borderless mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td class="fw-semibold" style="width: 40%;">Separation Type</td>
                                                        <td><span class="badge bg-secondary">Not Applicable</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Benefits Summary Card -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="card border border-success">
                                            <div class="card-header bg-success bg-opacity-10">
                                                <h5 class="card-title mb-0 text-success">
                                                    <i class="mdi mdi-check-circle me-2"></i>Benefits Summary
                                                </h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4 mb-3">
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm bg-success bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-2">
                                                                <i class="mdi mdi-check text-success fs-20"></i>
                                                            </div>
                                                            <div>
                                                                <p class="mb-0 text-muted small">Overtime</p>
                                                                <h6 class="mb-0">Eligible</h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm bg-success bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-2">
                                                                <i class="mdi mdi-check text-success fs-20"></i>
                                                            </div>
                                                            <div>
                                                                <p class="mb-0 text-muted small">Transport</p>
                                                                <h6 class="mb-0">Eligible</h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm bg-success bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-2">
                                                                <i class="mdi mdi-check text-success fs-20"></i>
                                                            </div>
                                                            <div>
                                                                <p class="mb-0 text-muted small">Provident Fund</p>
                                                                <h6 class="mb-0">Active</h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm bg-success bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-2">
                                                                <i class="mdi mdi-check text-success fs-20"></i>
                                                            </div>
                                                            <div>
                                                                <p class="mb-0 text-muted small">Gratuity</p>
                                                                <h6 class="mb-0">Eligible</h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm bg-success bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-2">
                                                                <i class="mdi mdi-check text-success fs-20"></i>
                                                            </div>
                                                            <div>
                                                                <p class="mb-0 text-muted small">Loan Facility</p>
                                                                <h6 class="mb-0">Available</h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 mb-3">
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm bg-success bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-2">
                                                                <i class="mdi mdi-check text-success fs-20"></i>
                                                            </div>
                                                            <div>
                                                                <p class="mb-0 text-muted small">Salary Advance</p>
                                                                <h6 class="mb-0">Available</h6>
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
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="#" class="btn btn-secondary">
                                <i class="mdi mdi-printer me-1"></i> Print Office Info
                            </a>
                            <a href="#" class="btn btn-primary">
                                <i class="mdi mdi-pencil me-1"></i> Edit Office Info
                            </a>
                            <a href="#" class="btn btn-success">
                                <i class="mdi mdi-account-arrow-right me-1"></i> Transfer Employee
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection

@push('scripts')
<script>
    // Feather Icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
</script>
@endpush
