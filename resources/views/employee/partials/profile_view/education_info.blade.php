<div class="row mt-3">
    <div class="col-12">
        @if(auth()->user()->user_type === \App\Enums\UserType::Employee && ((isset($employeeData) && $employeeData->status === 'incomplete') || empty($employeeData)))
            <!-- Incomplete Profile Warning for Employees -->
            <div class="card border-0 shadow-none mb-3" style="background-color: rgba(151, 64, 99, 0.05); border: 1px solid rgba(151, 64, 99, 0.2) !important;">
                <div class="card-body text-center py-5">
                    <div class="avatar-lg rounded-circle mx-auto mb-4 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background-color: rgba(151, 64, 99, 0.1);">
                        <i class="fas fa-graduation-cap" style="font-size: 40px; color: #974063;"></i>
                    </div>
                    <h4 class="fw-bold text-dark">Incomplete Education & Experience</h4>
                    <p class="text-muted mb-4 mx-auto" style="max-width: 500px;">
                        Please provide your education, work experience, and training details to proceed with the profile verification.
                    </p>
                    @can('employee-management.create')
                    <a href="{{ isset($employeeData) ? route('employee.education_information.edit', $employee->id) : route('employee.education_information.create', $employee->id) }}"
                        class="btn btn-lg px-5 shadow-sm text-white" style="background-color: #974063;">
                        <i class="fas fa-plus me-1"></i> Complete Education Now
                    </a>
                    @endcan
                </div>
            </div>
        @elseif(!empty($employeeData))
            <div class="card shadow-sm border-0">
                    <div class="card-body pt-0">
                        <ul class="nav nav-underline border-bottom pt-2" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active p-2" id="education_tab" data-bs-toggle="tab" href="#education"
                                   role="tab" aria-controls="education" aria-selected="true">
                                    <span class="d-none d-sm-block">
                                        <i class="mdi mdi-school-outline me-1"></i>Education
                                        @if(!empty($educations) && count($educations) > 0)
                                            <span class="badge bg-success ms-1">{{ count($educations) }}</span>
                                        @endif
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link p-2" id="training_tab" data-bs-toggle="tab" href="#training"
                                   role="tab" aria-controls="training" aria-selected="false">
                                    <span class="d-none d-sm-block">
                                        <i class="mdi mdi-certificate-outline me-1"></i>Training
                                        @if(!empty($trainings) && count($trainings) > 0)
                                            <span class="badge bg-warning ms-1">{{ count($trainings) }}</span>
                                        @endif
                                    </span>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content text-muted">
                            <!-- Education Tab -->
                            <div class="tab-pane active show pt-4" id="education" role="tabpanel"
                                 aria-labelledby="education_tab">
                                @if(!empty($educations) && count($educations) > 0)
                                    @foreach($educations as $index => $education)
                                        <div class="row mb-4">
                                            <div class="col-12">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar-sm rounded-circle bg-success-subtle">
                                                            <span
                                                                class="avatar-title rounded-circle text-success fs-3">{{ $index + 1 }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h5 class="fs-16 text-dark fw-semibold mb-1">{{ $education['education_title'] ?? 'N/A' }}</h5>
                                                        <p class="text-muted mb-0">{{ $education['institute'] ?? 'N/A' }}
                                                            | {{ $education['passing_year'] ?? 'N/A' }}</p>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="table-responsive">
                                                            <table class="table table-borderless mb-0">
                                                                <tbody>
                                                                <tr>
                                                                    <td class="fw-semibold" style="width: 40%;">
                                                                        Institute
                                                                    </td>
                                                                    <td>{{ $education['institute'] ?? 'N/A' }}</td>
                                                                </tr>
                                                                @if(!empty($education['group_major']))
                                                                    <tr>
                                                                        <td class="fw-semibold">Group/Major</td>
                                                                        <td>{{ $education['group_major'] ?? 'N/A' }}</td>
                                                                    </tr>
                                                                @endif
                                                                @if(!empty($education['board_university']))
                                                                    <tr>
                                                                        <td class="fw-semibold">Board/University</td>
                                                                        <td>{{ $education['board_university'] ?? 'N/A' }}</td>
                                                                    </tr>
                                                                @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="table-responsive">
                                                            <table class="table table-borderless mb-0">
                                                                <tbody>
                                                                @if(!empty($education['result_grade']))
                                                                    <tr>
                                                                        <td class="fw-semibold" style="width: 40%;">
                                                                            Result/Grade
                                                                        </td>
                                                                        <td><span
                                                                                class="badge bg-success">{{ $education['result_grade'] ?? 'N/A' }}</span>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                                <tr>
                                                                    <td class="fw-semibold">Passing Year</td>
                                                                    <td>{{ $education['passing_year'] ?? 'N/A' }}</td>
                                                                </tr>
                                                                @if(!empty($education['gpa_cgpa']))
                                                                    <tr>
                                                                        <td class="fw-semibold">GPA/CGPA</td>
                                                                        <td><span
                                                                                class="badge bg-info">{{ $education['gpa_cgpa'] ?? 'N/A' }}</span>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if($index < count($educations) - 1)
                                            <hr class="my-4">
                                        @endif
                                    @endforeach
                                @else
                                    <div class="text-center py-5">
                                        <div class="avatar-lg mx-auto mb-4">
                                            <div class="avatar-title bg-light text-muted rounded-circle fs-2">
                                                <i class="mdi mdi-school-outline"></i>
                                            </div>
                                        </div>
                                        <h5 class="text-muted">No Education Records Found</h5>
                                        <p class="text-muted mb-0">This employee has no education information yet.</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Training Tab -->
                            <div class="tab-pane pt-4" id="training" role="tabpanel" aria-labelledby="training_tab">
                                @if(!empty($trainings) && count($trainings) > 0)
                                    @foreach($trainings as $index => $training)
                                        <div class="row mb-4">
                                            <div class="col-12">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar-sm rounded-circle bg-warning-subtle">
                                                            <span
                                                                class="avatar-title rounded-circle text-warning fs-3">{{ $index + 1 }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <h5 class="fs-16 text-dark fw-semibold mb-1">{{ $training['training_title'] ?? 'N/A' }}</h5>
                                                        <p class="text-muted mb-0">
                                                            {{ $training['institute'] ?? 'N/A' }} |
                                                            @if(!empty($training['from_date']) && !empty($training['to_date']))
                                                                {{ \Carbon\Carbon::parse($training['from_date'])->format('M j') }}
                                                                -{{ \Carbon\Carbon::parse($training['to_date'])->format('j, Y') }}
                                                            @else
                                                                N/A
                                                            @endif
                                                        </p>
                                                    </div>
                                                    @if(!empty($training['duration']))
                                                        <div class="flex-shrink-0">
                                                            <span
                                                                class="badge bg-success">{{ $training['duration'] ?? 'N/A' }}</span>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="table-responsive">
                                                            <table class="table table-borderless mb-0">
                                                                <tbody>
                                                                @if(!empty($training['course_name']))
                                                                    <tr>
                                                                        <td class="fw-semibold" style="width: 40%;">
                                                                            Course Name
                                                                        </td>
                                                                        <td>{{ $training['course_name'] ?? 'N/A' }}</td>
                                                                    </tr>
                                                                @endif
                                                                @if(!empty($training['training_code']))
                                                                    <tr>
                                                                        <td class="fw-semibold">Training Code</td>
                                                                        <td><span
                                                                                class="badge bg-secondary">{{ $training['training_code'] ?? 'N/A' }}</span>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                                @if(!empty($training['institute']))
                                                                    <tr>
                                                                        <td class="fw-semibold">Institute</td>
                                                                        <td>{{ $training['institute'] ?? 'N/A' }}</td>
                                                                    </tr>
                                                                @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="table-responsive">
                                                            <table class="table table-borderless mb-0">
                                                                <tbody>
                                                                @if(!empty($training['location']) || !empty($training['country']))
                                                                    <tr>
                                                                        <td class="fw-semibold" style="width: 40%;">
                                                                            Location
                                                                        </td>
                                                                        <td>
                                                                            @if(!empty($training['location']) && !empty($training['country']))
                                                                                {{ $training['location'] ?? 'N/A' }}
                                                                                , {{ $training['country'] ?? 'N/A' }}
                                                                            @elseif(!empty($training['location']))
                                                                                {{ $training['location'] ?? 'N/A' }}
                                                                            @else
                                                                                {{ $training['country'] ?? 'N/A' }}
                                                                            @endif
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                                @if(!empty($training['duration']))
                                                                    <tr>
                                                                        <td class="fw-semibold">Duration</td>
                                                                        <td>{{ $training['duration'] ?? 'N/A' }}</td>
                                                                    </tr>
                                                                @endif
                                                                @if(!empty($training['from_date']) && !empty($training['to_date']))
                                                                    <tr>
                                                                        <td class="fw-semibold">Period</td>
                                                                        <td>
                                                                            {{ \Carbon\Carbon::parse($training['from_date'])->format('F j') }}
                                                                            -
                                                                            {{ \Carbon\Carbon::parse($training['to_date'])->format('j, Y') }}
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if($index < count($trainings) - 1)
                                            <hr class="my-4">
                                        @endif
                                    @endforeach
                                @else
                                    <div class="text-center py-5">
                                        <div class="avatar-lg mx-auto mb-4">
                                            <div class="avatar-title bg-light text-muted rounded-circle fs-2">
                                                <i class="mdi mdi-certificate-outline"></i>
                                            </div>
                                        </div>
                                        <h5 class="text-muted">No Training Records Found</h5>
                                        <p class="text-muted mb-0">This employee has no training information yet.</p>
                                    </div>
                                @endif
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
                            @can('employee-management.create')
                            <a href="{{route('employee.education_information.create', $employee->id)}}"
                               class="btn btn-primary btn-lg px-5 rounded-pill">
                                Add Information
                            </a>
                            @endcan
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
                            @can('employee-management.edit')
                                <a href="{{ route('employee.education_information.edit', $employee->id) }}"
                                   class="btn btn-primary">Edit</a>
                            @endcan
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

