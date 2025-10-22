<!-- Profile Header -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <img src="{{ asset('assets/images/small/user-image.jpg') }}" class="rounded-top-2 img-fluid" alt="cover image">
                <div class="card-body">
                    <div class="align-items-center">
                        <div class="hando-main-sections">
                            <div class="hando-profile-main">
                                <img src="{{ asset('storage/' . $employee->photo_path) }}"
                                    class="rounded-circle img-fluid avatar-xxl img-thumbnail float-start"
                                    alt="profile photo">

                            </div>
                            <div class="overflow-hidden ms-md-4 ms-0">
                                <h4 class="m-0 text-dark fs-20 mt-2 mt-md-0">{{ $employee->first_name }}
                                    {{ $employee->middle_name }} {{ $employee->last_name }}</h4>
                                <p class="my-1 text-muted fs-16">
                                    {{--                                        Senior Software Engineer - --}}
                                    Employee ID: {{ $employee->applicant_id }}</p>
                                <span class="fs-15">
                                    <i class="mdi mdi-phone me-2 align-middle"></i>
                                    <span>+880 1712-345678</span>
                                    <i class="mdi mdi-email ms-3 me-2 align-middle"></i>
                                    <span>mohammad.khan@company.com</span>
                                </span>
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
                            <p class="mb-2"><strong>Applicant ID:</strong></p>
                            <p class="text-muted">APP-2024-12345</p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-2"><strong>System ID:</strong></p>
                            <p class="text-muted">SYS-EMP-001-2024</p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-2"><strong>Punch Card No:</strong></p>
                            <p class="text-muted">PC-54321</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
