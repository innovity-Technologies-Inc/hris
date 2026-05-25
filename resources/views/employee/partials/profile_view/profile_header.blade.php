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
        document.getElementById('detailed_ids').textContent = `ID: ${data.applicant_id} | System ID: ${data.system_id}`;
        
        // Set contact info
        document.getElementById('detailed_personal_email').textContent = data.personal_email || 'N/A';
        document.getElementById('detailed_personal_mobile').textContent = data.personal_mobile || 'N/A';
        document.getElementById('detailed_home_phone').textContent = data.home_phone || 'N/A';
        document.getElementById('detailed_work_email').textContent = data.work_email || 'N/A';
        document.getElementById('detailed_work_mobile').textContent = data.work_mobile || 'N/A';
        document.getElementById('detailed_work_phone').textContent = data.work_phone || 'N/A';
        
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
        document.getElementById('detailed_height').textContent = data.height_feet ? `${data.height_feet}' ${data.height_inches}"` : 'N/A';
        document.getElementById('detailed_children_count').textContent = data.children_count || '0';
        document.getElementById('detailed_birth_country').textContent = data.birth_country || 'N/A';
        document.getElementById('detailed_birth_reg_no').textContent = data.birth_reg_no || 'N/A';
        document.getElementById('detailed_punch_card_no').textContent = data.punch_card_no || 'N/A';
        document.getElementById('detailed_status').textContent = data.status || 'active';
        
        // Set documents
        document.getElementById('detailed_tin').textContent = data.tin || 'N/A';
        document.getElementById('detailed_passport_no').textContent = data.passport_no || 'N/A';
        document.getElementById('detailed_passport_expiry').textContent = data.passport_expiry ? new Date(data.passport_expiry).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }) : 'N/A';
        document.getElementById('detailed_residency_id').textContent = data.residency_id_number || 'N/A';
        document.getElementById('detailed_license_no').textContent = data.license_no || 'N/A';
        document.getElementById('detailed_license_expiry').textContent = data.license_expiry ? new Date(data.license_expiry).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }) : 'N/A';
        document.getElementById('detailed_visa_expiry').textContent = data.visa_expiry ? new Date(data.visa_expiry).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }) : 'N/A';
        document.getElementById('detailed_work_expiry').textContent = data.work_expiry ? new Date(data.work_expiry).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }) : 'N/A';
        
        // Set addresses
        function renderAddressFields(addr) {
            if (!addr) return '<p class="mb-0 text-muted">N/A</p>';
            const a = typeof addr === 'string' ? JSON.parse(addr) : addr;
            if (Object.keys(a).length === 0) return '<p class="mb-0 text-muted">N/A</p>';
            
            return `
                <div class="mb-2"><label>Address Line</label><span>${a.address_line || 'N/A'}</span></div>
                <div class="mb-2"><label>Village / Area</label><span>${a.village || 'N/A'}</span></div>
                <div class="row g-2">
                    <div class="col-6"><label>Post Office</label><span>${a.post_office || 'N/A'}</span></div>
                    <div class="col-6"><label>Thana / Upazila</label><span>${a.thana || 'N/A'}</span></div>
                    <div class="col-6"><label>District / City</label><span>${a.district || a.city || 'N/A'}</span></div>
                    <div class="col-6"><label>State / Province</label><span>${a.state || 'N/A'}</span></div>
                    <div class="col-6"><label>Zip Code</label><span>${a.zip_code || 'N/A'}</span></div>
                    <div class="col-6"><label>Country</label><span>${a.country || 'N/A'}</span></div>
                </div>
            `;
        }

        document.getElementById('detailed_present_address_fields').innerHTML = renderAddressFields(data.present_address);
        document.getElementById('detailed_permanent_address_fields').innerHTML = renderAddressFields(data.permanent_address || data.present_address);
        document.getElementById('detailed_reference_address_fields').innerHTML = renderAddressFields(data.reference_address);

        // Office Info
        const sectionOffice = document.getElementById('section_office_info');
        if (data.office_info) {
            sectionOffice.classList.remove('d-none');
            document.getElementById('detailed_company').textContent = data.office_info.get_current_company?.name || 'N/A';
            document.getElementById('detailed_designation').textContent = data.office_info.get_current_designation?.name || 'N/A';
            document.getElementById('detailed_department').textContent = data.office_info.get_current_department?.name || 'N/A';
            document.getElementById('detailed_division').textContent = data.office_info.get_current_division?.name || 'N/A';
            document.getElementById('detailed_section').textContent = data.office_info.get_current_section?.name || 'N/A';
            document.getElementById('detailed_join_date').textContent = data.office_info.date_of_join ? new Date(data.office_info.date_of_join).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }) : 'N/A';
        } else {
            sectionOffice.classList.add('d-none');
        }

        // Education
        const sectionEdu = document.getElementById('section_education');
        const eduBody = document.getElementById('detailed_education_body');
        if (data.education_info && data.education_info.educations && data.education_info.educations.length > 0) {
            sectionEdu.classList.remove('d-none');
            eduBody.innerHTML = data.education_info.educations.map(edu => `
                <div class="col-12 mb-2">
                    <div class="p-3 border rounded-3 bg-light-subtle">
                        <div class="row g-3">
                            <div class="col-md-4"><label>Degree / Title</label><span class="fw-bold text-primary">${edu.education_title || 'N/A'}</span></div>
                            <div class="col-md-4"><label>Institute / University</label><span>${edu.institute || 'N/A'}</span></div>
                            <div class="col-md-4"><label>Board</label><span>${edu.board_university || 'N/A'}</span></div>
                            <div class="col-md-4"><label>Group / Major</label><span>${edu.group_major || 'N/A'}</span></div>
                            <div class="col-md-4"><label>Passing Year</label><span>${edu.passing_year || 'N/A'}</span></div>
                            <div class="col-md-4"><label>Result & GPA</label><span>${edu.result_grade || 'N/A'} ${edu.gpa_cgpa ? '(' + edu.gpa_cgpa + ')' : ''}</span></div>
                        </div>
                    </div>
                </div>
            `).join('');
        } else {
            sectionEdu.classList.add('d-none');
        }

        // Training
        const sectionTrn = document.getElementById('section_training');
        const trnBody = document.getElementById('detailed_training_body');
        if (data.education_info && data.education_info.trainings && data.education_info.trainings.length > 0) {
            sectionTrn.classList.remove('d-none');
            trnBody.innerHTML = data.education_info.trainings.map(trn => `
                <div class="col-12 mb-2">
                    <div class="p-3 border rounded-3 bg-light-subtle">
                        <div class="row g-3">
                            <div class="col-md-4"><label>Training Title</label><span class="fw-bold text-warning">${trn.training_title || 'N/A'}</span></div>
                            <div class="col-md-4"><label>Course Name</label><span>${trn.course_name || 'N/A'}</span></div>
                            <div class="col-md-4"><label>Training Code</label><span>${trn.training_code || 'N/A'}</span></div>
                            <div class="col-md-6"><label>Institute & Location</label><span>${trn.institute || 'N/A'} ${trn.location ? '(' + trn.location + ')' : ''}</span></div>
                            <div class="col-md-2"><label>Country</label><span>${trn.country || 'N/A'}</span></div>
                            <div class="col-md-2"><label>Duration</label><span>${trn.duration || 'N/A'}</span></div>
                            <div class="col-md-2"><label>Dates</label><span>${trn.from_date || 'N/A'} to ${trn.to_date || 'N/A'}</span></div>
                        </div>
                    </div>
                </div>
            `).join('');
        } else {
            sectionTrn.classList.add('d-none');
        }

        // History
        const sectionHistory = document.getElementById('section_history');
        const historyBody = document.getElementById('detailed_history_body');
        if (data.employment_history && data.employment_history.histories && data.employment_history.histories.length > 0) {
            sectionHistory.classList.remove('d-none');
            historyBody.innerHTML = data.employment_history.histories.map(h => `
                <div class="col-12 mb-2">
                    <div class="p-3 border rounded-3 bg-light-subtle">
                        <div class="row g-3">
                            <div class="col-md-4"><label>Company Name</label><span class="fw-bold text-info">${h.company_name || 'N/A'}</span></div>
                            <div class="col-md-4"><label>Designation</label><span>${h.designation || 'N/A'}</span></div>
                            <div class="col-md-4"><label>Period</label><span>${h.joining_date || 'N/A'} to ${h.end_date || 'Present'}</span></div>
                            <div class="col-md-6"><label>Job Description</label><span>${h.job_description || 'N/A'}</span></div>
                            <div class="col-md-6"><label>Achievements</label><span>${h.achievements || 'N/A'}</span></div>
                        </div>
                    </div>
                </div>
            `).join('');
        } else {
            sectionHistory.classList.add('d-none');
        }

        // Nominee
        const sectionNominee = document.getElementById('section_nominee');
        const nomineeBody = document.getElementById('detailed_nominee_body');
        if (data.nominee_info) {
            sectionNominee.classList.remove('d-none');
            nomineeBody.innerHTML = `
                <div class="mb-2"><label>Name</label><span>${data.nominee_info.nominee_name || 'N/A'}</span></div>
                <div class="mb-2"><label>Relation</label><span>${data.nominee_info.relation || 'N/A'}</span></div>
                <div class="mb-0"><label>Mobile</label><span>${data.nominee_info.nominee_mobile || 'N/A'}</span></div>
            `;
        } else {
            sectionNominee.classList.add('d-none');
        }

        // Bank Account
        const sectionBank = document.getElementById('section_bank');
        if (data.bank_account) {
            sectionBank.classList.remove('d-none');
            document.getElementById('detailed_bank_name').textContent = data.bank_account.get_bank?.name || 'N/A';
            document.getElementById('detailed_bank_branch').textContent = data.bank_account.get_branch?.name || 'N/A';
            document.getElementById('detailed_account_name').textContent = data.bank_account.account_holder_name || 'N/A';
            document.getElementById('detailed_account_number').textContent = data.bank_account.account_number || 'N/A';
        } else {
            sectionBank.classList.add('d-none');
        }

        // Policy Tags (Eligibility)
        const sectionPolicy = document.getElementById('section_policy');
        const policyBody = document.getElementById('detailed_policy_body');
        if (data.employee_eligibility) {
            sectionPolicy.classList.remove('d-none');
            const elig = data.employee_eligibility;
            let tags = [];
            if (elig.shift_plan_status === 'active') tags.push('Shift Plan');
            if (elig.leave_plan_status === 'active') tags.push('Leave Plan');
            if (elig.ot_plan_status === 'active') tags.push('OT Plan');
            if (elig.roster_plans_status === 'active') tags.push('Roster Plan');
            if (elig.bonus_plan_status === 'active') tags.push('Bonus Plan');
            if (elig.meal_plan_status === 'active') tags.push('Meal Plan');
            
            policyBody.innerHTML = tags.length > 0 ? tags.map(t => `<span class="badge bg-soft-primary text-primary border border-primary-subtle me-1 mb-1" style="display:inline-block">${t}</span>`).join('') : 'No active policies';
        } else {
            sectionPolicy.classList.add('d-none');
        }

        // Salary Breakdown
        const sectionSalary = document.getElementById('section_salary');
        const salaryBody = document.getElementById('detailed_salary_body');
        if (data.salary_breakdown) {
            sectionSalary.classList.remove('d-none');
            salaryBody.innerHTML = `
                <div class="col-md-6 mb-2"><label>Gross Salary</label><span>${data.salary_breakdown.gross_salary || '0'}</span></div>
                <div class="col-md-6 mb-2"><label>Basic Salary</label><span>${data.salary_breakdown.basic_salary || '0'}</span></div>
                <div class="col-md-6 mb-2"><label>House Rent</label><span>${data.salary_breakdown.house_rent || '0'}</span></div>
                <div class="col-md-6 mb-0"><label>Medical</label><span>${data.salary_breakdown.medical_allowance || '0'}</span></div>
            `;
        } else {
            sectionSalary.classList.add('d-none');
        }

        // Current Plans
        const sectionPlans = document.getElementById('section_plans');
        const plansBody = document.getElementById('detailed_plans_body');
        let plansHtml = '';
        if (data.shift && data.shift.length > 0) {
            data.shift.forEach(s => plansHtml += `<div class="col-md-3"><label>Shift</label><span>${s.name || 'Active Shift'}</span></div>`);
        }
        if (data.roster && data.roster.length > 0) {
            data.roster.filter(r => r.status === 'active').forEach(r => plansHtml += `<div class="col-md-3"><label>Roster</label><span>${r.name || 'Active Roster'}</span></div>`);
        }
        if (plansHtml) {
            sectionPlans.classList.remove('d-none');
            plansBody.innerHTML = plansHtml;
        } else {
            sectionPlans.classList.add('d-none');
        }

        // Leave Info / Balance
        const sectionLeaveInfo = document.getElementById('section_leave_info');
        const leaveInfoBody = document.getElementById('detailed_leave_info_body');
        if (data.leave_balances && data.leave_balances.length > 0) {
            sectionLeaveInfo.classList.remove('d-none');
            leaveInfoBody.innerHTML = data.leave_balances.map(l => `
                <div class="d-flex justify-content-between mb-1 border-bottom pb-1">
                    <span class="text-muted small">${l.leave_type || 'Leave'}</span>
                    <span class="fw-bold">${l.leave_count || 0} / ${l.total_leave || 0}</span>
                </div>
            `).join('');
        } else {
            sectionLeaveInfo.classList.add('d-none');
        }

        // Leave History
        const sectionLeaveHistory = document.getElementById('section_leave_history');
        const leaveHistoryBody = document.getElementById('detailed_leave_history_body');
        if (data.leave_applications && data.leave_applications.length > 0) {
            sectionLeaveHistory.classList.remove('d-none');
            leaveHistoryBody.innerHTML = data.leave_applications.slice(0, 5).map(l => `
                <div class="mb-2 pb-1 border-bottom">
                    <div class="d-flex justify-content-between">
                        <span class="small fw-bold">${l.get_plan?.name || 'Leave'}</span>
                        <span class="badge ${l.status === 'Approved' ? 'bg-success' : (l.status === 'Pending' ? 'bg-warning' : 'bg-danger')}">${l.status}</span>
                    </div>
                    <div class="small text-muted">${l.from} to ${l.to} (${l.leave_count} days)</div>
                </div>
            `).join('');
        } else {
            sectionLeaveHistory.classList.add('d-none');
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

