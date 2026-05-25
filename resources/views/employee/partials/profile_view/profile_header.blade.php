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

@push('scripts')
<!-- Axios CDN -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const openDetailedViewBtn = document.getElementById('openDetailedView');
    const detailedViewModalElement = document.getElementById('detailedViewModal');
    
    if (openDetailedViewBtn && detailedViewModalElement) {
        const detailedViewModal = new bootstrap.Modal(detailedViewModalElement);
        
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

        // Use window.axios or axios
        const ajax = window.axios || axios;

        ajax.get('{{ route('employee.profile.detailed_json', $employee->id) }}')
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
        document.getElementById('detailed_ids').textContent = `Applicant ID: ${data.applicant_id || 'N/A'} | System ID: ${data.system_id || 'N/A'} | Punch Card: ${data.punch_card_no || 'N/A'}`;
        document.getElementById('detailed_header_mobile').textContent = data.personal_mobile || 'N/A';
        document.getElementById('detailed_header_email').textContent = data.personal_email || 'N/A';

        // 2. Personal Information
        document.getElementById('detailed_first_name').textContent = data.first_name || 'N/A';
        document.getElementById('detailed_middle_name').textContent = data.middle_name || 'N/A';
        document.getElementById('detailed_last_name').textContent = data.last_name || 'N/A';
        document.getElementById('detailed_gender').textContent = data.gender || 'N/A';
        document.getElementById('detailed_marital_status').textContent = data.marital_status || 'N/A';
        document.getElementById('detailed_religion').textContent = data.religion || 'N/A';
        document.getElementById('detailed_nationality').textContent = data.nationality || 'N/A';
        document.getElementById('detailed_blood_group').textContent = data.blood_group || 'N/A';
        document.getElementById('detailed_height').textContent = (data.height_feet || 0) + "' " + (data.height_inches || 0) + '"';
        document.getElementById('detailed_children_count').textContent = data.children_count || '0';
        document.getElementById('detailed_father_name').textContent = data.father_name || 'N/A';
        document.getElementById('detailed_mother_name').textContent = data.mother_name || 'N/A';
        document.getElementById('detailed_spouse_name').textContent = data.spouse_name || 'N/A';
        document.getElementById('detailed_status').textContent = data.status || 'N/A';

        // 3. Birth & Documents
        document.getElementById('detailed_dob').textContent = data.date_of_birth || 'N/A';
        document.getElementById('detailed_birth_country').textContent = data.birth_country || 'N/A';
        document.getElementById('detailed_birth_reg_no').textContent = data.birth_reg_no || 'N/A';
        document.getElementById('detailed_tin').textContent = data.tin || 'N/A';
        document.getElementById('detailed_bgmea_id').textContent = data.bgmea_id || 'N/A';
        document.getElementById('detailed_residency_id').textContent = data.residency_id_number || 'N/A';
        document.getElementById('detailed_passport_no').textContent = data.passport_no || 'N/A';
        document.getElementById('detailed_passport_expiry').textContent = data.passport_expiry || 'N/A';
        document.getElementById('detailed_visa_expiry').textContent = data.visa_expiry || 'N/A';
        document.getElementById('detailed_work_expiry').textContent = data.work_expiry || 'N/A';
        document.getElementById('detailed_license_no').textContent = data.license_no || 'N/A';
        document.getElementById('detailed_license_expiry').textContent = data.license_expiry || 'N/A';

        // 4. Contact Information
        document.getElementById('detailed_personal_mobile').textContent = data.personal_mobile || 'N/A';
        document.getElementById('detailed_home_phone').textContent = data.home_phone || 'N/A';
        document.getElementById('detailed_personal_email').textContent = data.personal_email || 'N/A';
        document.getElementById('detailed_work_mobile').textContent = data.work_mobile || 'N/A';
        document.getElementById('detailed_work_phone').textContent = data.work_phone || 'N/A';
        document.getElementById('detailed_work_email').textContent = data.work_email || 'N/A';

        // 5 & 6. Addresses
        function renderAddressFields(addr) {
            if (!addr) return '<div class="col-12"><p class="mb-0 text-muted">N/A</p></div>';
            const a = typeof addr === 'string' ? JSON.parse(addr) : addr;
            if (Object.keys(a).length === 0) return '<div class="col-12"><p class="mb-0 text-muted">N/A</p></div>';
            
            return `
                <div class="col-md-6 mb-2"><label>Address Line 1</label><span>${a.line_1 || a.address_line || 'N/A'}</span></div>
                <div class="col-md-3 mb-2"><label>Village</label><span>${a.village || 'N/A'}</span></div>
                <div class="col-md-3 mb-2"><label>Post Office</label><span>${a.post_office || 'N/A'}</span></div>
                <div class="col-md-3 mb-2"><label>Thana / Upazila</label><span>${a.thana || 'N/A'}</span></div>
                <div class="col-md-3 mb-2"><label>District</label><span>${a.district || 'N/A'}</span></div>
                <div class="col-md-2 mb-2"><label>Division</label><span>${a.division || 'N/A'}</span></div>
                <div class="col-md-2 mb-2"><label>State</label><span>${a.state || 'N/A'}</span></div>
                <div class="col-md-2 mb-2"><label>Zip Code</label><span>${a.zip_code || 'N/A'}</span></div>
                <div class="col-md-12"><label>Country</label><span>${a.country || 'N/A'}</span></div>
            `;
        }

        document.getElementById('detailed_present_address_fields').innerHTML = renderAddressFields(data.present_address);
        document.getElementById('detailed_permanent_address_fields').innerHTML = renderAddressFields(data.permanent_address || data.present_address);

        // 7. Reference Information
        function renderReferenceFields(addr) {
            if (!addr) return '<div class="col-12"><p class="mb-0 text-muted">N/A</p></div>';
            const a = typeof addr === 'string' ? JSON.parse(addr) : addr;
            if (Object.keys(a).length === 0) return '<div class="col-12"><p class="mb-0 text-muted">N/A</p></div>';

            return `
                <div class="col-md-3 mb-2"><label>Reference ID</label><span>${a.emp_id || 'N/A'}</span></div>
                <div class="col-md-3 mb-2"><label>Reference Name</label><span class="fw-bold text-dark">${a.reference_name || 'N/A'}</span></div>
                <div class="col-md-3 mb-2"><label>Designation</label><span>${a.reference_designation || 'N/A'}</span></div>
                <div class="col-md-3 mb-2"><label>Phone / Mobile</label><span>${a.phone || a.mobile || 'N/A'}</span></div>
                <div class="col-md-3 mb-2"><label>Email</label><span>${a.email || 'N/A'}</span></div>
                <div class="col-md-9 mb-2"><label>Address</label><span>${a.line_1 || a.address_line || 'N/A'}, ${a.village || ''}, ${a.thana || ''}, ${a.district || ''}, ${a.state || ''} ${a.zip_code || ''}, ${a.country || ''}</span></div>
            `;
        }
        document.getElementById('detailed_reference_address_fields').innerHTML = renderReferenceFields(data.reference_address);

        // 8. Office Information
        if (data.office_info) {
            const oi = data.office_info;
            document.getElementById('detailed_emp_type').textContent = oi.emp_type || 'N/A';
            document.getElementById('detailed_hr_file_no').textContent = oi.hr_file_no || 'N/A';
            document.getElementById('detailed_pay_grade').textContent = oi.get_grade?.name || 'N/A';
            document.getElementById('detailed_tofsil').textContent = oi.get_tofsil?.name || 'N/A';
            document.getElementById('detailed_file_note').textContent = oi.file_note || 'N/A';
            
            document.getElementById('detailed_joining_company').textContent = oi.get_joining_company?.name || 'N/A';
            document.getElementById('detailed_joining_bu').textContent = oi.get_joining_business_unit?.name || 'N/A';
            document.getElementById('detailed_joining_division').textContent = oi.get_joining_division?.name || 'N/A';
            document.getElementById('detailed_joining_department').textContent = oi.get_joining_department?.department_name || 'N/A';
            document.getElementById('detailed_joining_section').textContent = oi.get_joining_section?.name || 'N/A';
            document.getElementById('detailed_joining_designation').textContent = oi.get_joining_designation?.company_designation || 'N/A';
            document.getElementById('detailed_join_date').textContent = oi.date_of_join || 'N/A';
            
            document.getElementById('detailed_current_company').textContent = oi.get_current_company?.name || 'N/A';
            document.getElementById('detailed_current_bu').textContent = oi.get_current_business_unit?.name || 'N/A';
            document.getElementById('detailed_current_division').textContent = oi.get_current_division?.name || 'N/A';
            document.getElementById('detailed_current_department').textContent = oi.get_current_department?.department_name || 'N/A';
            document.getElementById('detailed_current_section').textContent = oi.get_current_section?.name || 'N/A';
            document.getElementById('detailed_current_designation').textContent = oi.get_current_designation?.company_designation || 'N/A';

            document.getElementById('detailed_orientation_required').textContent = oi.orientation_required || 'no';
            document.getElementById('detailed_orientation_from').textContent = oi.orientation_from || 'N/A';
            document.getElementById('detailed_orientation_to').textContent = oi.orientation_to || 'N/A';
            document.getElementById('detailed_orientation_type').textContent = oi.orientation_type || 'N/A';
            document.getElementById('detailed_orientation_days').textContent = oi.orientation_days || '0';

            document.getElementById('detailed_confirmation_date').textContent = oi.confirmation_date || 'N/A';
            document.getElementById('detailed_probation').textContent = (oi.probation_duration || 0) + ' Days';
            document.getElementById('detailed_next_promotion').textContent = oi.next_promotion_date || 'N/A';
            document.getElementById('detailed_promotion_cycle').textContent = oi.promotion_cycle || 'N/A';
            document.getElementById('detailed_increment_cycle').textContent = oi.increment_cycle || 'N/A';

            document.getElementById('detailed_salary_type').textContent = oi.salary_type || 'N/A';
            document.getElementById('detailed_weekends').textContent = (oi.weekends || []).join(', ') || 'N/A';
            document.getElementById('detailed_alternate_off').textContent = (oi.alternate_off_day || []).join(', ') || 'N/A';

            document.getElementById('detailed_ot_allowed').innerHTML = oi.ot_allowed === 'yes' ? '<span class="badge badge-soft-success">Yes</span>' : '<span class="badge badge-soft-danger">No</span>';
            document.getElementById('detailed_pf_eligible').innerHTML = oi.pf_eligible === 'yes' ? '<span class="badge badge-soft-success">Yes</span>' : '<span class="badge badge-soft-danger">No</span>';
            document.getElementById('detailed_transport_eligible').innerHTML = oi.transport_eligible === 'yes' ? '<span class="badge badge-soft-success">Yes</span>' : '<span class="badge badge-soft-danger">No</span>';
            document.getElementById('detailed_gratuity_eligible').innerHTML = oi.gratuity_eligible === 'yes' ? '<span class="badge badge-soft-success">Yes</span>' : '<span class="badge badge-soft-danger">No</span>';
            document.getElementById('detailed_can_loan').innerHTML = oi.can_apply_loan === 'yes' ? '<span class="badge badge-soft-success">Yes</span>' : '<span class="badge badge-soft-danger">No</span>';
            document.getElementById('detailed_can_advance').innerHTML = oi.can_apply_advance === 'yes' ? '<span class="badge badge-soft-success">Yes</span>' : '<span class="badge badge-soft-danger">No</span>';
            document.getElementById('detailed_pf_effective').textContent = oi.pf_effective_date || 'N/A';
        }

        // 9. Education
        const sectionEdu = document.getElementById('section_education');
        const eduBody = document.getElementById('detailed_education_body');
        if (data.education_info && data.education_info.educations && data.education_info.educations.length > 0) {
            sectionEdu.classList.remove('d-none');
            eduBody.innerHTML = data.education_info.educations.map(edu => `
                <div class="col-12">
                    <div class="p-3 border rounded bg-light-subtle mb-2">
                        <div class="row g-2">
                            <div class="col-md-4"><label>Education Title</label><span class="fw-bold text-dark">${edu.education_title || 'N/A'}</span></div>
                            <div class="col-md-4"><label>Institute</label><span>${edu.institute || 'N/A'}</span></div>
                            <div class="col-md-4"><label>Group/Major</label><span>${edu.group_major || 'N/A'}</span></div>
                            <div class="col-md-4"><label>Board/University</label><span>${edu.board_university || 'N/A'}</span></div>
                            <div class="col-md-2"><label>Year</label><span>${edu.passing_year || 'N/A'}</span></div>
                            <div class="col-md-2"><label>Result</label><span>${edu.result_grade || 'N/A'}</span></div>
                            <div class="col-md-2"><label>GPA/CGPA</label><span>${edu.gpa_cgpa || 'N/A'}</span></div>
                        </div>
                    </div>
                </div>
            `).join('');
        } else {
            sectionEdu.classList.add('d-none');
        }

        // 10. Training
        const sectionTrn = document.getElementById('section_training');
        const trnBody = document.getElementById('detailed_training_body');
        if (data.education_info && data.education_info.trainings && data.education_info.trainings.length > 0) {
            sectionTrn.classList.remove('d-none');
            trnBody.innerHTML = data.education_info.trainings.map(trn => `
                <div class="col-12">
                    <div class="p-3 border rounded bg-light-subtle mb-2">
                        <div class="row g-2">
                            <div class="col-md-4"><label>Training Title</label><span class="fw-bold text-dark">${trn.training_title || 'N/A'}</span></div>
                            <div class="col-md-4"><label>Course Name</label><span>${trn.course_name || 'N/A'}</span></div>
                            <div class="col-md-4"><label>Training Code</label><span>${trn.training_code || 'N/A'}</span></div>
                            <div class="col-md-4"><label>Institute</label><span>${trn.institute || 'N/A'}</span></div>
                            <div class="col-md-2"><label>Country</label><span>${trn.country || 'N/A'}</span></div>
                            <div class="col-md-2"><label>Location</label><span>${trn.location || 'N/A'}</span></div>
                            <div class="col-md-2"><label>Duration</label><span>${trn.duration || 'N/A'}</span></div>
                            <div class="col-md-2"><label>From - To</label><span>${trn.from_date || ''} to ${trn.to_date || ''}</span></div>
                        </div>
                    </div>
                </div>
            `).join('');
        } else {
            sectionTrn.classList.add('d-none');
        }

        // 11. Employment History
        const sectionHistory = document.getElementById('section_history');
        const historyBody = document.getElementById('detailed_history_body');
        if (data.employment_history && data.employment_history.histories && data.employment_history.histories.length > 0) {
            sectionHistory.classList.remove('d-none');
            historyBody.innerHTML = data.employment_history.histories.map(h => `
                <div class="col-12">
                    <div class="p-3 border rounded bg-light-subtle mb-2">
                        <div class="row g-2">
                            <div class="col-md-4"><label>Previous Company</label><span class="fw-bold text-info">${h.company_name || h.company || 'N/A'}</span></div>
                            <div class="col-md-4"><label>Designation</label><span>${h.designation || 'N/A'}</span></div>
                            <div class="col-md-4"><label>Duration</label><span>${h.joining_date || h.from_date || 'N/A'} to ${h.end_date || h.to_date || 'Present'}</span></div>
                            <div class="col-md-12 mt-1"><label>Job Description</label><span class="small text-muted">${h.job_description || 'N/A'}</span></div>
                            <div class="col-md-12"><label>Achievements</label><span class="small text-muted">${h.achievements || 'N/A'}</span></div>
                        </div>
                    </div>
                </div>
            `).join('');
        } else {
            sectionHistory.classList.add('d-none');
        }

        // 12. Nominee
        const sectionNominee = document.getElementById('section_nominee');
        const nomineeBody = document.getElementById('detailed_nominee_body');
        if (data.nominee_info) {
            sectionNominee.classList.remove('d-none');
            const n = data.nominee_info;
            nomineeBody.innerHTML = `
                <div class="col-md-3"><label>Nominee Name</label><span class="fw-bold text-dark">${n.nominee_name || 'N/A'}</span></div>
                <div class="col-md-3"><label>Relation</label><span>${n.relation || 'N/A'}</span></div>
                <div class="col-md-3"><label>Mobile</label><span>${n.mobile || 'N/A'}</span></div>
                <div class="col-md-3"><label>Phone</label><span>${n.phone || 'N/A'}</span></div>
                <div class="col-md-3"><label>Gender</label><span>${n.gender || 'N/A'}</span></div>
                <div class="col-md-3"><label>DOB</label><span>${n.date_of_birth || 'N/A'}</span></div>
                <div class="col-md-3"><label>NID</label><span>${n.nid || 'N/A'}</span></div>
                <div class="col-md-3"><label>Birth Reg No</label><span>${n.birth_reg_no || 'N/A'}</span></div>
                <div class="col-md-3"><label>Blood Group</label><span>${n.blood_group || 'N/A'}</span></div>
                <div class="col-md-3"><label>Religion</label><span>${n.religion || 'N/A'}</span></div>
                <div class="col-md-3"><label>Nationality</label><span>${n.nationality || 'N/A'}</span></div>
                <div class="col-md-3"><label>Marital Status</label><span>${n.marital_status || 'N/A'}</span></div>
                <div class="col-12"><hr class="my-1"></div>
                <div class="col-md-12"><label>Address Line</label><span>${n.present_address_line || 'N/A'}</span></div>
                <div class="col-md-3"><label>Village</label><span>${n.village || 'N/A'}</span></div>
                <div class="col-md-3"><label>Post Office</label><span>${n.post_office || 'N/A'}</span></div>
                <div class="col-md-3"><label>Thana/Upazila</label><span>${n.thana || 'N/A'}</span></div>
                <div class="col-md-3"><label>District</label><span>${n.district || 'N/A'}</span></div>
                <div class="col-md-3"><label>State/Division</label><span>${n.state || 'N/A'}</span></div>
                <div class="col-md-3"><label>Zip Code</label><span>${n.zip_code || 'N/A'}</span></div>
                <div class="col-md-3"><label>Country</label><span>${n.country || 'N/A'}</span></div>
            `;
        } else {
            sectionNominee.classList.add('d-none');
        }

        // 13. Bank Account
        const sectionBank = document.getElementById('section_bank');
        if (data.bank_account) {
            sectionBank.classList.remove('d-none');
            document.getElementById('detailed_bank_name').textContent = data.bank_account.get_bank?.name || 'N/A';
            document.getElementById('detailed_bank_branch').textContent = data.bank_account.get_branch?.name || 'N/A';
            document.getElementById('detailed_account_name').textContent = data.bank_account.account_holder_name || 'N/A';
            document.getElementById('detailed_account_number').textContent = data.bank_account.account_number || 'N/A';
            document.getElementById('detailed_bank_status').textContent = data.bank_account.status || 'N/A';
            document.getElementById('detailed_bank_remarks').textContent = data.bank_account.remarks || 'N/A';
        } else {
            sectionBank.classList.add('d-none');
        }

        // 14. Salary Breakdown
        const sectionSalary = document.getElementById('section_salary');
        const salaryBody = document.getElementById('detailed_salary_body');
        if (data.salary_breakdown) {
            sectionSalary.classList.remove('d-none');
            const s = data.salary_breakdown;
            const currency = s.currency || 'N/A';
            salaryBody.innerHTML = `
                <div class="col-md-3"><label>Basic Salary</label><span class="fw-bold text-dark">${s.basic_salary || 0} ${currency}</span></div>
                <div class="col-md-3"><label>House Allowance</label><span>${s.house_allowance || 0} ${currency}</span></div>
                <div class="col-md-3"><label>Transport Allowance</label><span>${s.transport_allowance || 0} ${currency}</span></div>
                <div class="col-md-3"><label>Food Allowance</label><span>${s.food_allowance || 0} ${currency}</span></div>
                <div class="col-md-3"><label>Medical Allowance</label><span>${s.medical_allowance || 0} ${currency}</span></div>
                <div class="col-md-3"><label>Other Earnings</label><span>${s.other_earnings || 0} ${currency}</span></div>
                <div class="col-md-6"><label class="text-dark fw-bold">Gross Salary</label><span class="fw-bold fs-5 text-dark">${s.gross_salary || 0} ${currency}</span></div>
            `;
        } else {
            sectionSalary.classList.add('d-none');
        }

        // 15. Policies & Plans
        const policyBody = document.getElementById('detailed_policy_body');
        if (data.employee_eligibility) {
            const elig = data.employee_eligibility;
            let tags = [];
            if (elig.shift_plan_status === 'active') tags.push('Shift Plan');
            if (elig.leave_plan_status === 'active') tags.push('Leave Plan');
            if (elig.ot_plan_status === 'active') tags.push('OT Allowed');
            if (elig.roster_plans_status === 'active') tags.push('Roster Plan');
            if (elig.bonus_plan_status === 'active') tags.push('Bonus Eligible');
            if (elig.meal_plan_status === 'active') tags.push('Meal Plan');
            
            policyBody.innerHTML = tags.length > 0 ? tags.map(t => `<span class="badge bg-soft-primary text-primary border border-primary-subtle me-2 mb-2 p-2" style="font-size:0.75rem">${t}</span>`).join('') : '<p class="text-muted">No active policies</p>';
        }

        const plansBody = document.getElementById('detailed_plans_body');
        let plansHtml = '';
        if (data.shift && data.shift.length > 0) {
            data.shift.forEach(s => plansHtml += `<div class="p-2 border rounded mb-2"><span class="small text-muted d-block">Active Shift</span><span class="fw-bold text-dark">${s.name || 'N/A'}</span></div>`);
        }
        if (data.roster && data.roster.length > 0) {
            data.roster.filter(r => r.status === 'active').forEach(r => plansHtml += `<div class="p-2 border rounded mb-2"><span class="small text-muted d-block">Active Roster</span><span class="fw-bold text-dark">${r.name || 'N/A'}</span></div>`);
        }
        plansBody.innerHTML = plansHtml || '<p class="text-muted">No plans assigned</p>';

        // 16. Leave Info
        const leaveInfoBody = document.getElementById('detailed_leave_info_body');
        if (data.leave_balances && data.leave_balances.length > 0) {
            leaveInfoBody.innerHTML = data.leave_balances.map(l => `
                <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                    <span class="fw-semibold text-dark">${l.leave_type || 'Leave'}</span>
                    <span class="badge bg-primary rounded-pill px-3">${l.leave_count || 0} / ${l.total_leave || 0}</span>
                </div>
            `).join('');
        } else {
            leaveInfoBody.innerHTML = '<p class="text-muted">No leave balances found</p>';
        }

        const leaveHistoryBody = document.getElementById('detailed_leave_history_body');
        if (data.leave_applications && data.leave_applications.length > 0) {
            leaveHistoryBody.innerHTML = data.leave_applications.slice(0, 5).map(l => `
                <div class="p-2 border rounded mb-2 bg-light-subtle">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="fw-bold text-dark">${l.get_plan?.name || 'Leave'}</span>
                        <span class="badge ${l.status === 'approved' ? 'bg-success' : (l.status === 'pending' ? 'bg-warning' : 'bg-danger')}">${l.status}</span>
                    </div>
                    <div class="small text-muted"><i class="mdi mdi-calendar-range me-1"></i>${l.from} to ${l.to} (${l.leave_count} days)</div>
                </div>
            `).join('');
        } else {
            leaveHistoryBody.innerHTML = '<p class="text-muted">No recent leave history</p>';
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
@endpush
