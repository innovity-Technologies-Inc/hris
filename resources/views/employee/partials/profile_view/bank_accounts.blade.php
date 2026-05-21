    <div class="row">
        @if(!empty($employeeData))
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 text-dark">
                        <i class="fas fa-wallet text-primary"></i> Bank Account Details
                    </h5>
                </div>

                <div class="card-body p-4">
                    <!-- Employee Details -->
                    <div class="mb-4">
                        <h6 class="text-uppercase text-muted mb-3 fw-bold">
                            <i class="fas fa-user-tie text-info"></i> Employee Information
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-light-info me-3">
                                        <i class="fas fa-user text-info"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Employee Name</small>
                                        <strong>{{ $employeeData->getEmployee->full_name ?? 'N/A' }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Bank Details -->
                    <div class="mb-4">
                        <h6 class="text-uppercase text-muted mb-3 fw-bold">
                            <i class="fas fa-building text-success"></i> Bank Information
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-light-success me-3">
                                        <i class="fas fa-university text-success"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Bank Name</small>
                                        <strong>{{ $employeeData->getBank->name ?? 'N/A' }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-light-success me-3">
                                        <i class="fas fa-map-marker-alt text-success"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Branch Name</small>
                                        <strong>{{ $employeeData->getBranch->name ?? 'N/A' }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Account Details -->
                    <div class="mb-4">
                        <h6 class="text-uppercase text-muted mb-3 fw-bold">
                            <i class="fas fa-credit-card text-primary"></i> Account Information
                        </h6>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-light-primary me-3">
                                        <i class="fas fa-id-card text-primary"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Account Holder</small>
                                        <strong>{{ $employeeData->account_holder_name ?? 'N/A' }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-light-primary me-3">
                                        <i class="fas fa-hashtag text-primary"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Account Number</small>
                                        <strong class="text-monospace">{{ $employeeData->account_number ?? 'N/A' }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle me-3 {{ ($employeeData->status ?? '') == 'active' ? 'bg-light-success' : 'bg-light-danger' }}">
                                        <i class="fas {{ ($employeeData->status ?? '') == 'active' ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' }}"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Status</small>
                                        <span class="badge {{ ($employeeData->status ?? '') == 'active' ? 'bg-success' : 'bg-danger' }}">
                                            {{ ucfirst($employeeData->status ?? 'N/A') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($employeeData->remarks)
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="alert alert-light border-start border-4 border-info" role="alert">
                                    <div class="d-flex">
                                        <div class="me-3">
                                            <i class="fas fa-comment-dots text-info"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block mb-1">Remarks</small>
                                            <p class="mb-0">{{ $employeeData->remarks }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
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
                                    <div
                                        class="rounded-circle bg-light border border-2 border-secondary d-flex align-items-center justify-content-center"
                                        style="width: 120px; height: 120px;">
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
                                @if(auth()->user()->user_type !== 'Employee')
                                    @can('employee-management.create')
                                    <a href="{{route('employee.bank_accounts.create', $employee->id)}}"
                                       class="btn btn-primary btn-lg px-5 rounded-pill">
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

    @if(!empty($employeeData))
        <!-- Action Buttons -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('employee.index') }}" class="btn btn-secondary">Back to List</a>
                            @if($employeeData)
                                @if(auth()->user()->user_type !== 'Employee')
                                    @can('employee-management.edit')
                                        <a href="{{ route('employee.bank_accounts.edit', $employee->id) }}"
                                           class="btn btn-primary">Edit</a>
                                    @endcan
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif



