<!-- Tabbed Content -->


        <div class="row">
    <div class="col-12">
        @if(!empty($employee_office_info))
        <div class="card">
            <div class="card-body pt-0">
                <ul class="nav nav-underline border-bottom pt-2" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active p-2" id="payroll_info_tab" data-bs-toggle="tab" href="#payroll_info"
                           role="tab">
                            <span class="d-none d-sm-block">Payroll Information</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link p-2" id="joining_info_tab" data-bs-toggle="tab" href="#joining_info"
                           role="tab">
                            <span class="d-none d-sm-block">Joining Information</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link p-2" id="current_info_tab" data-bs-toggle="tab" href="#current_info"
                           role="tab">
                            <span class="d-none d-sm-block">Current Information</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link p-2" id="orientation_tab" data-bs-toggle="tab" href="#orientation"
                           role="tab">
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
                                            <td class="fw-semibold">Employee Type</td>
                                            <td><span class="badge px-2 py-1 bg-success">{{ucwords($employee_office_info->emp_type ?? 'N/A')}}</span></td>
                                        </tr>

                                        <tr>
                                            <td class="fw-semibold">HR File Number</td>
                                            <td>{{$employee_office_info->hr_file_no ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Pay Grade</td>
                                            <td>{{$employee_office_info->getGrade->grade_name  ?? 'N/A'}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h5 class="fs-16 text-dark fw-semibold mb-3">File Notes</h5>
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <p class="mb-0">{{$employee_office_info->file_note ?? 'N/A'}}</p>
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
                                    <strong>Date of Joining:</strong> {{$employee_office_info->date_of_join ?? 'N/A'}}
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
                                            <td>{{$employee_office_info->getJoiningCompany->name ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Business Unit</td>
                                            <td>{{$employee_office_info->getJoiningBusinessUnit->name ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Division</td>
                                            <td>{{$employee_office_info->getJoiningDivision->name ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Department</td>
                                            <td>{{$employee_office_info->getJoiningDepartment->department_name ?? 'N/A'}}</td>
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
                                            <td class="fw-semibold">Section</td>
                                            <td>{{$employee_office_info->getJoiningSection->name ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold" style="width: 40%;">Designation</td>
                                            <td>{{$employee_office_info->getJoiningDesignation->company_designation ?? 'N/A'}}</td>
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
                            <div class="col-md-6">
                                <h5 class="fs-16 text-dark fw-semibold mb-3">Current Organizational Details</h5>
                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0">
                                        <tbody>
                                        <tr>
                                            <td class="fw-semibold" style="width: 40%;">Company</td>
                                            <td>{{$employee_office_info->getCurrentCompany->name ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Business Unit</td>
                                            <td>{{$employee_office_info->getCurrentBusinessUnit->name ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Division</td>
                                            <td>{{$employee_office_info->getCurrentDivision->name ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Department</td>
                                            <td>{{$employee_office_info->getCurrentDepartment->department_name ?? 'N/A'}}</td>
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
                                            <td class="fw-semibold">Section</td>
                                            <td>{{$employee_office_info->getCurrentSection->name ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold" style="width: 40%;">Designation</td>
                                            <td><span class="badge px-2 py-1 bg-info">{{$employee_office_info->getCurrentDesignation->company_designation ?? 'N/A'}}</span></td>
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
                                            <td>
                                                @if($employee_office_info->orientation_required == 'yes')
                                                <span class="badge px-2 py-1 bg-success">Yes</span>
                                                    @else
                                                        <span class="badge px-2 py-1 bg-danger">No</span>
                                                    @endif

                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Orientation From</td>
                                            <td>{{$employee_office_info->orientation_from ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Orientation To</td>
                                            <td>{{$employee_office_info->orientation_to ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Orientation Type</td>
                                            <td>{{$employee_office_info->orientation_type ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Orientation Days</td>
                                            <td>{{$employee_office_info->orientation_days ?? 0 }} days</td>
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
                                            <td>{{$employee_office_info->confirmation_date ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Probation Duration</td>
                                            <td>{{$employee_office_info->probation_duration ?? 0 }} Days</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Next Promotion Date</td>
                                            <td>{{$employee_office_info->next_promotion_date ?? 'N/A'}}</td>
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
                                            <td>{{$employee_office_info->promotion_cycle ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Increment Cycle</td>
                                            <td>{{$employee_office_info->increment_cycle ?? 'N/A'}}</td>
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
                                                @if(!empty($employee_office_info->weekends))
                                                @foreach($employee_office_info->weekends as $item)
                                                <span class="badge px-2 py-1 bg-secondary">{{$item ?? 'N/A'}}</span>
                                                @endforeach
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold">Alternate Off Day</td>
                                            <td>
                                                @if(!empty($employee_office_info->alternate_off_day))

                                                @foreach($employee_office_info->alternate_off_day as $item)
                                                    <span class="badge px-2 py-1 bg-secondary">{{$item ?? 'N/A'}}</span>
                                                @endforeach
                                                @endif
                                            </td>

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
                                <h5 class="fs-16 text-dark fw-semibold mb-3">Compensation Benefits</h5>
                                <div class="card border">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div>
                                                <i class="mdi mdi-clock-check text-success fs-20 me-2"></i>
                                                <span class="fw-semibold">OT Allowed</span>
                                            </div>
                                            @if(($employee_office_info->ot_allowed ?? 'no') == 'yes')
                                                <span class="badge px-2 py-1 bg-success">Yes</span>
                                            @else
                                                <span class="badge px-2 py-1 bg-danger">No</span>
                                            @endif                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div>
                                                <i class="mdi mdi-piggy-bank text-info fs-20 me-2"></i>
                                                <span class="fw-semibold">PF Eligible</span>
                                            </div>
                                            @if(($employee_office_info->pf_eligible ?? 'no') == 'yes')
                                                <span class="badge px-2 py-1 bg-success">Yes</span>
                                            @else
                                                <span class="badge px-2 py-1 bg-danger">No</span>
                                            @endif                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div>
                                                <i class="mdi mdi-bus text-primary fs-20 me-2"></i>
                                                <span class="fw-semibold">Transport Eligible</span>
                                            </div>
                                            @if(($employee_office_info->transport_eligible ?? 'no') == 'yes')
                                                <span class="badge px-2 py-1 bg-success">Yes</span>
                                            @else
                                                <span class="badge px-2 py-1 bg-danger">No</span>
                                            @endif
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="mdi mdi-gift text-warning fs-20 me-2"></i>
                                                <span class="fw-semibold">Gratuity Eligible</span>
                                            </div>
                                            @if(($employee_office_info->gratuity_eligible ?? 'no') == 'yes')
                                                <span class="badge px-2 py-1 bg-success">Yes</span>
                                            @else
                                                <span class="badge px-2 py-1 bg-danger">No</span>
                                            @endif
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
                                            @if(($employee_office_info->can_apply_loan ?? 'no') == 'yes')
                                                <span class="badge px-2 py-1 bg-success">Yes</span>
                                            @else
                                                <span class="badge px-2 py-1 bg-danger">No</span>
                                            @endif                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="mdi mdi-cash-fast text-primary fs-20 me-2"></i>
                                                <span class="fw-semibold">Can Apply Advance</span>
                                            </div>
                                            @if(($employee_office_info->can_apply_advance ?? 'no') == 'yes')
                                                <span class="badge px-2 py-1 bg-success">Yes</span>
                                            @else
                                                <span class="badge px-2 py-1 bg-danger">No</span>
                                            @endif                                        </div>
                                    </div>
                                </div>

                                <h5 class="fs-16 text-dark fw-semibold mb-3 mt-4">Fund Details</h5>
                                <div class="table-responsive">
                                    <table class="table table-borderless mb-0">
                                        <tbody>
                                        <tr>
                                            <td class="fw-semibold" style="width: 40%;">PF Effective Date</td>
                                            <td>{{$employee_office_info->pf_effective_date ?? 'N/A'}}</td>
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
                            @if(auth()->user()->user_type !== \App\Enums\UserType::Employee)
                                @can('employee-management.create')
                                <a href="{{route('employee.office_informations.create', $employee->id)}}" class="btn btn-primary btn-lg px-5 rounded-pill">
                                    Add Information
                                </a>
                                @endcan
                            @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
        </div>


@if(!empty($employee_office_info))
<!-- Action Buttons -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-end gap-2">
                    @if(auth()->user()->user_type !== \App\Enums\UserType::Employee)
                        @can('employee-management.edit')
                            <a href="{{ route('employee.office_informations.edit', $employee->id) }}"
                               class="btn btn-primary">
                                <i class="mdi mdi-pencil me-1"></i> Edit Company Information
                            </a>
                        @endcan
                    @endif
                </div>            </div>
        </div>
    </div>
</div>
@endif

