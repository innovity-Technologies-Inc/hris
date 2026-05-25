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
                            <h4 class="m-0 text-dark fs-20 mt-2 mt-md-0">{{ $employee?->first_name ?? 'N/A' }}
                                {{ $employee?->middle_name ?? '' }} {{ $employee?->last_name ?? '' }}</h4>
                            <p class="my-1 text-muted fs-16">
                                {{--                                        Senior Software Engineer - --}}
                                Employee ID: {{ $employee?->applicant_id ?? 'N/A' }}</p>
                            <span class="fs-15">
                                <i class="mdi mdi-phone me-2 align-middle"></i>
                                <span>{{ $employee?->personal_mobile ?? 'N/A' }}</span>
                                <i class="mdi mdi-email ms-3 me-2 align-middle"></i>
                                <span>{{ $employee?->personal_email ?? 'N/A' }}</span>
                            </span>
                        </div>
                        <div class="ms-auto">
                            <div class="d-flex align-items-center gap-3">
                                @php
                                    $isOwner = isset($employee) && (auth()->user()->employee_id == $employee->id || auth()->user()->id == $employee->user_id);
                                    $canEdit = auth()->user()->can('employee-management.edit');
                                @endphp
                                <!-- Edit Login Info Button -->
                                @if($isOwner || $canEdit)
                                <button type="button" class="btn text-white d-flex align-items-center" style="background-color: #974063;" data-bs-toggle="modal" data-bs-target="#editLoginInfoModal">
                                    <i class="mdi mdi-account-key me-1"></i> Edit Login Info
                                </button>
                                @endif

                                <!-- Detailed View Button -->
                                <button type="button" class="btn btn-primary d-flex align-items-center" id="openDetailedView">
                                    <i class="mdi mdi-account-details me-1"></i> Detailed View
                                </button>

                                <!-- ID Card Action Button -->
                                @include('employee.partials.id_card_button', ['employee' => $employee])

                                <!-- Status Toggle -->
                                @if(auth()->user()->can('employee-management.edit') && auth()->user()->user_type !== 'Employee')
                                @if($employee?->status === 'pending')
                                <button type="button" class="btn btn-info text-white d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#reviewProfileModal">
                                    <i class="mdi mdi-clipboard-check me-1"></i> Review Profile
                                </button>
                                @endif
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
                                    <span class="badge rounded-pill {{ $statusClass }} px-3 py-2">
                                        {{ ucfirst($employee?->status ?? 'active') }}
                                    </span>
                                </div>
                                @endif
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
                        <p class="text-muted">{{ $employee?->applicant_id ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-2"><strong>System ID:</strong></p>
                        <p class="text-muted">{{ $employee?->system_id ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="mb-2"><strong>Punch Card No:</strong></p>
                        <p class="text-muted">{{ $employee?->punch_card_no ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('employee.partials.modal.edit_login_modal')
@include('employee.partials.modal.review_profile_modal')
@include('employee.partials.modal.detailed_view_modal')

<script>
document.addEventListener('DOMContentLoaded', function() {
    const openDetailedViewBtn = document.getElementById('openDetailedView');
    const detailedViewModal = new bootstrap.Modal(document.getElementById('detailedViewModal'));
    
    if (openDetailedViewBtn) {
        openDetailedViewBtn.addEventListener('click', function() {
            detailedViewModal.show();
            fetchDetailedInfo();
        });
    }

    function fetchDetailedInfo() {
        const loading = document.getElementById('modalLoading');
        const error = document.getElementById('modalError');
        const content = document.getElementById('modalContent');
        
        loading.classList.remove('d-none');
        error.classList.add('d-none');
        content.classList.add('d-none');

        axios.get('{{ route('employee.profile.detailed_json', $employee->id) }}')
            .then(response => {
                if (response.data.success) {
                    populateModal(response.data.data);
                    loading.classList.add('d-none');
                    content.classList.remove('d-none');
                } else {
                    showError(response.data.message || 'Failed to fetch details');
                }
            })
            .catch(err => {
                console.error(err);
                showError('An error occurred while fetching details.');
            });
    }

    function populateModal(data) {
        // Set photo
        const photo = document.getElementById('detailed_photo');
        if (data.photo_path) {
            photo.src = '{{ asset('storage') }}/' + data.photo_path;
        } else {
            photo.src = '{{ asset('assets/images/small/user-image.jpg') }}';
        }

        // Set basic info
        document.getElementById('detailed_full_name').textContent = data.full_name;
        document.getElementById('detailed_ids').textContent = `ID: ${data.applicant_id} | System ID: ${data.system_id}`;
        
        // Set contact info
        document.getElementById('detailed_personal_email').textContent = data.personal_email || 'N/A';
        document.getElementById('detailed_personal_mobile').textContent = data.personal_mobile || 'N/A';
        document.getElementById('detailed_work_email').textContent = data.work_email || 'N/A';
        document.getElementById('detailed_work_mobile').textContent = data.work_mobile || 'N/A';
        
        // Set personal info
        document.getElementById('detailed_father_name').textContent = data.father_name || 'N/A';
        document.getElementById('detailed_mother_name').textContent = data.mother_name || 'N/A';
        document.getElementById('detailed_spouse_name').textContent = data.spouse_name || 'N/A';
        document.getElementById('detailed_dob').textContent = data.date_of_birth ? new Date(data.date_of_birth).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }) : 'N/A';
        document.getElementById('detailed_gender').textContent = data.gender || 'N/A';
        document.getElementById('detailed_marital_status').textContent = data.marital_status || 'N/A';
        document.getElementById('detailed_religion').textContent = data.religion || 'N/A';
        document.getElementById('detailed_nationality').textContent = data.nationality || 'N/A';
        document.getElementById('detailed_blood_group').textContent = data.blood_group || 'N/A';
        
        // Set documents
        document.getElementById('detailed_tin').textContent = data.tin || 'N/A';
        document.getElementById('detailed_passport_no').textContent = data.passport_no || 'N/A';
        document.getElementById('detailed_passport_expiry').textContent = data.passport_expiry ? new Date(data.passport_expiry).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }) : 'N/A';
        document.getElementById('detailed_residency_id').textContent = data.residency_id_number || 'N/A';
        document.getElementById('detailed_license_no').textContent = data.license_no || 'N/A';
        document.getElementById('detailed_license_expiry').textContent = data.license_expiry ? new Date(data.license_expiry).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }) : 'N/A';
        
        // Set address
        if (data.present_address) {
            const addr = typeof data.present_address === 'string' ? JSON.parse(data.present_address) : data.present_address;
            document.getElementById('detailed_present_address').innerHTML = `
                ${addr.address_line || ''}<br>
                ${addr.village || ''}, ${addr.post_office || ''}<br>
                ${addr.thana || ''}, ${addr.district || ''}<br>
                ${addr.state || ''} - ${addr.zip_code || ''}<br>
                ${addr.country || ''}
            `;
        }

        if (data.permanent_address) {
            const addr = typeof data.permanent_address === 'string' ? JSON.parse(data.permanent_address) : data.permanent_address;
            document.getElementById('detailed_permanent_address').innerHTML = `
                ${addr.address_line || ''}<br>
                ${addr.village || ''}, ${addr.post_office || ''}<br>
                ${addr.thana || ''}, ${addr.district || ''}<br>
                ${addr.state || ''} - ${addr.zip_code || ''}<br>
                ${addr.country || ''}
            `;
        } else {
            document.getElementById('detailed_permanent_address').textContent = 'Same as Present Address';
        }

        // Set PDF link
        document.getElementById('downloadPdfBtn').href = '{{ route('employee.profile.download_pdf', $employee->id) }}';
    }

    function showError(msg) {
        document.getElementById('modalLoading').classList.add('d-none');
        document.getElementById('modalContent').classList.add('d-none');
        document.getElementById('modalError').classList.remove('d-none');
        document.getElementById('errorMessage').textContent = msg;
    }
});
</script>

