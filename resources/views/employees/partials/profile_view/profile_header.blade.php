<div class="row">
    <div class="col-12">
        <div class="card">
            <img src="{{ asset('assets/images/small/user-image.jpg') }}" class="rounded-top-2 img-fluid" alt="cover image">
            <div class="card-body">
                <div class="align-items-center">
                    <div class="hando-main-sections">
                        <div class="hando-profile-main">
                            {!! \App\HelperClass::generateAvatar(
                                $employee?->photo_path ?? null,
                                $employee?->full_name,
                                100,
                                '#974063',
                                'rounded-circle img-fluid avatar-xxl img-thumbnail float-start',
                                $employee?->id,
                            ) !!}
                        </div>
                        <div class="overflow-hidden ms-md-4 ms-0">
                            <h4 class="m-0 text-dark fs-20 mt-2 mt-md-0">{{ $employee?->first_name }}
                                {{ $employee?->middle_name }} {{ $employee?->last_name }}</h4>
                            <p class="my-1 text-muted fs-16">
                                {{--                                        Senior Software Engineer - --}}
                                Employee ID: {{ $employee?->applicant_id }}</p>
                            <span class="fs-15">
                                <i class="mdi mdi-phone me-2 align-middle"></i>
                                <span>{{ $employee?->personal_mobile }}</span>
                                <i class="mdi mdi-email ms-3 me-2 align-middle"></i>
                                <span>{{ $employee?->personal_email }}</span>
                            </span>
                        </div>
                        <div class="ms-auto">
                            <div class="d-flex align-items-center gap-3">
                                <!-- Edit Login Info Button -->
                                <button type="button" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#editLoginInfoModal">
                                    <i class="mdi mdi-account-key me-1"></i> Edit Login Info
                                </button>

                                <!-- ID Card Action Button -->
                                @include('employee.partials.id_card_button', ['employee' => $employee])

                                <!-- Status Toggle -->
                                @can('employee-management.edit')
                                <div class="d-flex align-items-center">
                                    <span class="me-2 fw-semibold">Status:</span>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="employeeStatusToggle"
                                            {{ $employee?->status == 'active' ? 'checked' : '' }}
                                            style="width: 3rem; height: 1.5rem; cursor: pointer;">
                                        <label class="form-check-label ms-2 fw-bold" for="employeeStatusToggle"
                                            id="statusLabel"
                                            style="color: {{ $employee?->status == 'active' ? '#28a745' : '#dc3545' }};">
                                            {{ ucfirst($employee?->status ?? 'active') }}
                                        </label>
                                    </div>
                                </div>
                                @else
                                <div class="d-flex align-items-center">
                                    <span class="me-2 fw-semibold">Status:</span>
                                    @php
                                        $statusClass = 'bg-success';
                                        if ($employee?->status == 'inactive') $statusClass = 'bg-danger';
                                        elseif ($employee?->status == 'incomplete') $statusClass = 'bg-warning text-dark';
                                        elseif ($employee?->status == 'pending') $statusClass = 'bg-info';
                                    @endphp
                                    <span class="badge rounded-pill {{ $statusClass }}">
                                        {{ ucfirst($employee?->status ?? 'active') }}
                                    </span>
                                </div>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- System Identifiers Card -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">System Identifiers</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <p class="mb-2"><strong>Employee ID:</strong></p>
                        <p class="text-muted">{{ $employee?->applicant_id }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-2"><strong>System ID:</strong></p>
                        <p class="text-muted">{{ $employee?->system_id }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-2"><strong>Punch Card No:</strong></p>
                        <p class="text-muted">{{ $employee?->punch_card_no }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('employees.partials.modal.edit_login_modal')
