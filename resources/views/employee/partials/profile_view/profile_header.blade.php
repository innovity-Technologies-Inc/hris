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
                            <h4 class="m-0 text-dark fs-20 mt-2 mt-md-0">
                                {{ $employee?->first_name ?? 'N/A' }} {{ $employee?->middle_name ?? '' }} {{ $employee?->last_name ?? '' }}
                                @if($employee?->is_nid_verified)
                                    <span class="badge bg-success ms-1 d-inline-flex align-items-center px-2 py-1" title="NID Verified">
                                        <i class="mdi mdi-check-circle-outline me-1 fs-14"></i> Verified
                                    </span>
                                @endif
                            </h4>
                            <p class="my-1 text-muted fs-16">
                                Employee ID: {{ $employee?->applicant_id ?? 'N/A' }}</p>
                            <span class="fs-15 d-inline-flex align-items-center flex-wrap">
                                <i class="mdi mdi-phone me-2 align-middle"></i>
                                <span class="d-inline">{{ $employee?->personal_mobile ?? 'N/A' }}</span>
                                <i class="mdi mdi-email ms-3 me-2 align-middle"></i>
                                <span class="d-inline">{{ $employee?->personal_email ?? 'N/A' }}</span>
                            </span>
                        </div>
                        <div class="ms-auto">
                            <div class="d-flex align-items-center gap-3">
                                @if(auth()->user()->can('employee-management.edit') && auth()->user()->user_type !== \App\Enums\UserType::Employee)
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

<!-- Employee Action Center -->
<div class="row">
    <div class="col-12">
        <div class="card border shadow-none mb-3">
            <div class="card-body p-3">
                <div class="d-flex flex-wrap align-items-center gap-3">
                    @php
                        $isOwner = isset($employee) && (auth()->user()->employee_id == $employee->id || auth()->user()->id == $employee->user_id);
                        $canEdit = auth()->user()->can('employee-management.edit');
                    @endphp
                    
                    <!-- Edit Login Info Button -->
                    @if($isOwner || $canEdit)
                    <button type="button" class="btn text-white d-flex align-items-center" style="background-color: #974063;" data-bs-toggle="modal" data-bs-target="#editLoginInfoModal">
                        <i class="mdi mdi-account-key me-1 fs-18"></i> Edit Login Info
                    </button>
                    @endif

                    <!-- Summary View Button -->
                    <button type="button" class="btn btn-warning d-flex align-items-center text-dark fw-semibold" id="openSummaryView">
                        <i class="mdi mdi-text-box-search-outline me-1 fs-18"></i> Summary View
                    </button>

                    <!-- Detailed View Button -->
                    <button type="button" class="btn btn-primary d-flex align-items-center px-4" id="openDetailedView">
                        <i class="mdi mdi-account-details me-1 fs-18"></i> Detailed View
                    </button>

                    <!-- NID Verification Button -->
                    @if(auth()->user()->can('employee-management.nid-verification') && auth()->user()->user_type !== \App\Enums\UserType::Employee && !$employee?->is_nid_verified)
                    <button type="button" class="btn btn-info text-white d-flex align-items-center fw-semibold" data-bs-toggle="modal" data-bs-target="#nidVerificationModal">
                        <i class="mdi mdi-card-account-details-outline me-1 fs-18"></i> NID Verification
                    </button>
                    @endif

                    <!-- ID Card Action Button -->
                    <div class="ms-md-auto">
                        @include('employee.partials.id_card_button', ['employee' => $employee])
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
@include('employee.partials.modal.summary_view_modal')
@include('employee.partials.modal.nid_verification_modal')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- Unified Fetch Logic ---
    const openDetailedViewBtn = document.getElementById('openDetailedView');
    const detailedViewModalElement = document.getElementById('detailedViewModal');
    if (openDetailedViewBtn && detailedViewModalElement) {
        const modal = new bootstrap.Modal(detailedViewModalElement);
        openDetailedViewBtn.addEventListener('click', () => { modal.show(); fetchInfo(populateDetailedModal, 'detailed'); });
    }

    const openSummaryViewBtn = document.getElementById('openSummaryView');
    const summaryViewModalElement = document.getElementById('summaryViewModal');
    if (openSummaryViewBtn && summaryViewModalElement) {
        const modal = new bootstrap.Modal(summaryViewModalElement);
        openSummaryViewBtn.addEventListener('click', () => { modal.show(); fetchInfo(populateSummaryModal, 'summary'); });
    }

    function fetchInfo(populateFn, type) {
        const loading = document.getElementById(type + 'Loading');
        const error = document.getElementById(type + 'Error');
        const content = document.getElementById(type + 'Content');
        loading.classList.remove('d-none'); error.classList.add('d-none'); content.classList.add('d-none');

        const ajax = window.axios || axios;
        ajax.get('{{ route('employee.profile.detailed_json', $employee->id) }}')
            .then(res => {
                const response = res.data;
                if (response.success) {
                    populateFn(response.data);
                    loading.classList.add('d-none'); content.classList.remove('d-none');
                } else { showError(type, response.message || 'Failed to fetch details'); }
            })
            .catch(err => { console.error(err); showError(type, 'An error occurred while fetching details.'); });
    }

    function populateSummaryModal(data) {
        const photo = document.getElementById('summary_photo');
        photo.src = data.photo_path ? '{{ asset('storage') }}/' + data.photo_path : '{{ asset('assets/images/small/user-image.jpg') }}';
        document.getElementById('summary_full_name').textContent = data.full_name;
        document.getElementById('summary_basic_identifiers').textContent = `ID: ${data.applicant_id || 'N/A'} | System ID: ${data.system_id || 'N/A'}`;

        document.getElementById('summary_personal_mobile').textContent = data.personal_mobile || 'N/A';
        document.getElementById('summary_personal_email').textContent = data.personal_email || 'N/A';
        document.getElementById('summary_nid').textContent = data.residency_id_number || 'N/A';
        document.getElementById('summary_dob').textContent = data.date_of_birth || 'N/A';

        if (data.office_info) {
            const oi = data.office_info;
            document.getElementById('summary_header_designation').textContent = oi.get_current_designation?.company_designation || 'N/A';
            document.getElementById('summary_current_company').textContent = oi.get_current_company?.name || 'N/A';
            document.getElementById('summary_current_designation').textContent = oi.get_current_designation?.company_designation || 'N/A';
            document.getElementById('summary_current_dept_section').textContent = `${oi.get_current_department?.department_name || 'N/A'} / ${oi.get_current_section?.name || 'N/A'}`;
            document.getElementById('summary_join_date').textContent = oi.date_of_join || 'N/A';
        }

        if (data.salary_breakdown) {
            const cur = (data.currency && data.currency !== 'N/A') ? data.currency : '';
            document.getElementById('summary_gross_salary').textContent = `${parseFloat(data.salary_breakdown.gross_salary || 0).toLocaleString(undefined, {minimumFractionDigits: 2})} ${cur}`;
        }

        const policyBody = document.getElementById('summary_policy_body');
        if (data.employee_eligibility) {
            const e = data.employee_eligibility; let t = [];
            if (e.shift_plan_status === 'active') t.push('Shift'); if (e.leave_plan_status === 'active') t.push('Leave');
            if (e.ot_plan_status === 'active') t.push('OT'); if (e.roster_plans_status === 'active') t.push('Roster');
            policyBody.innerHTML = t.length > 0 ? t.map(x => `<span class="badge badge-soft-primary border border-primary-subtle p-1" style="font-size:0.65rem">${x} Plan</span>`).join('') : '<p class="text-muted small italic">No active policies</p>';
        }

        const bPlans = document.getElementById('summary_plans_body'); let hPlans = '';
        if (data.shift?.length > 0) data.shift.forEach(s => hPlans += `<div class="p-1 border-bottom small fw-bold text-dark"><i class="mdi mdi-clock-outline me-1 text-primary"></i>${s.get_plan?.name || 'N/A'}</div>`);
        if (data.roster?.length > 0) data.roster.filter(r => r.status === 'active').forEach(r => hPlans += `<div class="p-1 border-bottom small fw-bold text-dark"><i class="mdi mdi-calendar-refresh me-1 text-info"></i>${r.get_plan?.name || 'N/A'}</div>`);
        bPlans.innerHTML = hPlans || '<p class="text-muted small">No plans assigned</p>';

        let totalYears = 0;
        if (data.employment_history?.histories?.length > 0) {
            data.employment_history.histories.forEach(h => {
                const start = new Date(h.joining_date || h.from_date);
                const end = h.end_date || h.to_date ? new Date(h.end_date || h.to_date) : new Date();
                if (!isNaN(start) && !isNaN(end)) { totalYears += (end - start) / (1000 * 60 * 60 * 24 * 365.25); }
            });
        }
        document.getElementById('summary_total_experience').textContent = totalYears > 0 ? `${totalYears.toFixed(1)} Years` : 'No history found';

        if (data.education_info?.educations?.length > 0) {
            document.getElementById('summary_education_title').textContent = data.education_info.educations[0].education_title || 'N/A';
        } else { document.getElementById('summary_education_title').textContent = 'No records'; }

        const trnBody = document.getElementById('summary_training_titles');
        if (data.education_info?.trainings?.length > 0) {
            trnBody.innerHTML = data.education_info.trainings.map(t => `<span class="badge bg-soft-info text-info border border-info-subtle p-2">${t.training_title}</span>`).join('');
        } else { trnBody.innerHTML = '<span class="text-muted small italic">No training records found</span>'; }

        if (data.nominee_info) {
            document.getElementById('summary_nominee_name').textContent = data.nominee_info.nominee_name || 'N/A';
            document.getElementById('summary_nominee_relation').textContent = data.nominee_info.relation || 'N/A';
        } else { document.getElementById('summary_nominee_name').textContent = 'N/A'; document.getElementById('summary_nominee_relation').textContent = 'N/A'; }
    }

    function populateDetailedModal(data) {
        const photo = document.getElementById('detailed_photo');
        photo.src = data.photo_path ? '{{ asset('storage') }}/' + data.photo_path : '{{ asset('assets/images/small/user-image.jpg') }}';
        document.getElementById('detailed_full_name').textContent = data.full_name;
        document.getElementById('detailed_ids').textContent = `Applicant ID: ${data.applicant_id || 'N/A'} | System ID: ${data.system_id || 'N/A'} | Punch Card: ${data.punch_card_no || 'N/A'}`;
        document.getElementById('detailed_header_mobile').textContent = data.personal_mobile || 'N/A';
        document.getElementById('detailed_header_email').textContent = data.personal_email || 'N/A';

        const fields = {
            'detailed_first_name': data.first_name, 'detailed_middle_name': data.middle_name, 'detailed_last_name': data.last_name,
            'detailed_gender': data.gender, 'detailed_marital_status': data.marital_status, 'detailed_religion': data.religion,
            'detailed_nationality': data.nationality, 'detailed_blood_group': data.blood_group,
            'detailed_height': (data.height_feet || 0) + "' " + (data.height_inches || 0) + '"',
            'detailed_children_count': data.children_count || '0', 'detailed_father_name': data.father_name,
            'detailed_mother_name': data.mother_name, 'detailed_spouse_name': data.spouse_name, 'detailed_status': data.status,
            'detailed_dob': data.date_of_birth, 'detailed_birth_country': data.birth_country, 'detailed_birth_reg_no': data.birth_reg_no,
            'detailed_tin': data.tin, 'detailed_bgmea_id': data.bgmea_id, 'detailed_residency_id': data.residency_id_number,
            'detailed_passport_no': data.passport_no, 'detailed_passport_expiry': data.passport_expiry,
            'detailed_visa_expiry': data.visa_expiry, 'detailed_work_expiry': data.work_expiry,
            'detailed_license_no': data.license_no, 'detailed_license_expiry': data.license_expiry,
            'detailed_personal_mobile': data.personal_mobile, 'detailed_home_phone': data.home_phone,
            'detailed_personal_email': data.personal_email, 'detailed_work_mobile': data.work_mobile,
            'detailed_work_phone': data.work_phone, 'detailed_work_email': data.work_email
        };
        for (let id in fields) { if (document.getElementById(id)) document.getElementById(id).textContent = fields[id] || 'N/A'; }

        function renderAddressFields(addr) {
            if (!addr) return '<div class="col-12"><p class="data-value text-muted">N/A</p></div>';
            const a = typeof addr === 'string' ? JSON.parse(addr) : addr;
            return `
                <div class="col-md-6 mb-2"><label class="data-label">Address Line 1</label><span class="data-value">${a.line_1 || a.address_line || 'N/A'}</span></div>
                <div class="col-md-3 mb-2"><label class="data-label">Village</label><span class="data-value">${a.village || 'N/A'}</span></div>
                <div class="col-md-3 mb-2"><label class="data-label">Post Office</label><span class="data-value">${a.post_office || 'N/A'}</span></div>
                <div class="col-md-3 mb-2"><label class="data-label">Thana / Upazila</label><span class="data-value">${a.thana || 'N/A'}</span></div>
                <div class="col-md-3 mb-2"><label class="data-label">District</label><span class="data-value">${a.district || 'N/A'}</span></div>
                <div class="col-md-2 mb-2"><label class="data-label">Division</label><span class="data-value">${a.division || 'N/A'}</span></div>
                <div class="col-md-2 mb-2"><label class="data-label">State</label><span class="data-value">${a.state || 'N/A'}</span></div>
                <div class="col-md-2 mb-2"><label class="data-label">Zip Code</label><span class="data-value">${a.zip_code || 'N/A'}</span></div>
                <div class="col-md-12"><label class="data-label">Country</label><span class="data-value">${a.country || 'N/A'}</span></div>
            `;
        }
        document.getElementById('detailed_present_address_fields').innerHTML = renderAddressFields(data.present_address);
        document.getElementById('detailed_permanent_address_fields').innerHTML = renderAddressFields(data.permanent_address || data.present_address);

        function renderReferenceFields(addr) {
            if (!addr) return '<div class="col-12"><p class="data-value text-muted">N/A</p></div>';
            const a = typeof addr === 'string' ? JSON.parse(addr) : addr;
            return `
                <div class="col-md-3 mb-2"><label class="data-label">Reference ID</label><span class="data-value">${a.emp_id || 'N/A'}</span></div>
                <div class="col-md-3 mb-2"><label class="data-label">Reference Name</label><span class="data-value fw-bold text-dark">${a.reference_name || 'N/A'}</span></div>
                <div class="col-md-3 mb-2"><label class="data-label">Designation</label><span class="data-value">${a.reference_designation || 'N/A'}</span></div>
                <div class="col-md-3 mb-2"><label class="data-label">Phone / Mobile</label><span class="data-value">${a.phone || a.mobile || 'N/A'}</span></div>
                <div class="col-md-3 mb-2"><label class="data-label">Email</label><span class="data-value">${a.email || 'N/A'}</span></div>
                <div class="col-md-9 mb-2"><label class="data-label">Address</label><span class="data-value">${a.line_1 || a.address_line || 'N/A'}, ${a.village || ''}, ${a.thana || ''}, ${a.district || ''}, ${a.country || ''}</span></div>
            `;
        }
        document.getElementById('detailed_reference_address_fields').innerHTML = renderReferenceFields(data.reference_address);

        if (data.office_info) {
            const oi = data.office_info;
            const officeFields = {
                'detailed_emp_type': oi.emp_type, 'detailed_hr_file_no': oi.hr_file_no, 'detailed_pay_grade': oi.get_grade?.grade_name,
                'detailed_file_note': oi.file_note, 'detailed_joining_company': oi.get_joining_company?.name,
                'detailed_joining_bu': oi.get_joining_business_unit?.name, 'detailed_joining_division': oi.get_joining_division?.name,
                'detailed_joining_department': oi.get_joining_department?.department_name, 'detailed_joining_section': oi.get_joining_section?.name,
                'detailed_joining_designation': oi.get_joining_designation?.company_designation, 'detailed_join_date': oi.date_of_join,
                'detailed_current_company': oi.get_current_company?.name, 'detailed_current_bu': oi.get_current_business_unit?.name,
                'detailed_current_division': oi.get_current_division?.name, 'detailed_current_department': oi.get_current_department?.department_name,
                'detailed_current_section': oi.get_current_section?.name, 'detailed_current_designation': oi.get_current_designation?.company_designation,
                'detailed_orientation_required': oi.orientation_required, 'detailed_orientation_from': oi.orientation_from,
                'detailed_orientation_to': oi.orientation_to, 'detailed_orientation_type': oi.orientation_type,
                'detailed_orientation_days': oi.orientation_days, 'detailed_confirmation_date': oi.confirmation_date,
                'detailed_probation': (oi.probation_duration || 0) + ' Days', 'detailed_next_promotion': oi.next_promotion_date,
                'detailed_promotion_cycle': oi.promotion_cycle, 'detailed_increment_cycle': oi.increment_cycle,
                'detailed_weekends': (oi.weekends || []).join(', '),
                'detailed_alternate_off': (oi.alternate_off_day || []).join(', '), 'detailed_pf_effective': oi.pf_effective_date
            };
            for (let id in officeFields) { if (document.getElementById(id)) document.getElementById(id).textContent = officeFields[id] || 'N/A'; }
            
            const setBadge = (id, val) => {
                const el = document.getElementById(id);
                if (el) el.innerHTML = val === 'yes' ? '<span class="badge badge-soft-success">Yes</span>' : '<span class="badge badge-soft-danger">No</span>';
            };
            setBadge('detailed_ot_allowed', oi.ot_allowed);
            setBadge('detailed_pf_eligible', oi.pf_eligible);
            setBadge('detailed_transport_eligible', oi.transport_eligible);
            setBadge('detailed_gratuity_eligible', oi.gratuity_eligible);
            setBadge('detailed_can_loan', oi.can_apply_loan);
            setBadge('detailed_can_advance', oi.can_apply_advance);
        }

        const populateDynamicList = (sId, bId, list, mapper) => {
            const s = document.getElementById(sId), b = document.getElementById(bId);
            if (list && list.length > 0) { s.classList.remove('d-none'); b.innerHTML = list.map(mapper).join(''); } else { s.classList.add('d-none'); }
        };

        populateDynamicList('section_education', 'detailed_education_body', data.education_info?.educations, e => `
            <div class="col-12 border-bottom mb-2 pb-2"><div class="row g-2">
                <div class="col-md-3"><label class="data-label">Education Title</label><span class="data-value fw-bold">${e.education_title || 'N/A'}</span></div>
                <div class="col-md-3"><label class="data-label">Institute</label><span class="data-value">${e.institute || 'N/A'}</span></div>
                <div class="col-md-2"><label class="data-label">Board / University</label><span class="data-value">${e.board_university || 'N/A'}</span></div>
                <div class="col-md-2"><label class="data-label">Group / Major</label><span class="data-value">${e.group_major || 'N/A'}</span></div>
                <div class="col-md-1"><label class="data-label">Year</label><span class="data-value">${e.passing_year || 'N/A'}</span></div>
                <div class="col-md-1"><label class="data-label">Result</label><span class="data-value">${e.result_grade || 'N/A'}</span></div>
                <div class="col-md-1"><label class="data-label">GPA / CGPA</label><span class="data-value">${e.gpa_cgpa || '0.00'}</span></div>
            </div></div>
        `);

        populateDynamicList('section_training', 'detailed_training_body', data.education_info?.trainings, t => `
            <div class="col-12 border-bottom mb-2 pb-2"><div class="row g-2">
                <div class="col-md-3"><label class="data-label">Training Title</label><span class="data-value fw-bold">${t.training_title || 'N/A'}</span></div>
                <div class="col-md-3"><label class="data-label">Course Name</label><span class="data-value">${t.course_name || 'N/A'}</span></div>
                <div class="col-md-2"><label class="data-label">Code</label><span class="data-value">${t.training_code || 'N/A'}</span></div>
                <div class="col-md-2"><label class="data-label">Institute</label><span class="data-value">${t.institute || 'N/A'}</span></div>
                <div class="col-md-1"><label class="data-label">Location</label><span class="data-value">${t.location || 'N/A'}</span></div>
                <div class="col-md-1"><label class="data-label">Country</label><span class="data-value">${t.country || 'N/A'}</span></div>
                <div class="col-md-2"><label class="data-label">Duration</label><span class="data-value">${t.duration || 'N/A'}</span></div>
            </div></div>
        `);

        populateDynamicList('section_history', 'detailed_history_body', data.employment_history?.histories, h => `
            <div class="col-12 border-bottom mb-2 pb-2"><div class="row g-2">
                <div class="col-md-4"><label class="data-label">Previous Company</label><span class="data-value fw-bold text-info">${h.company_name || h.company || 'N/A'}</span></div>
                <div class="col-md-4"><label class="data-label">Designation</label><span class="data-value">${h.designation || 'N/A'}</span></div>
                <div class="col-md-2"><label class="data-label">Join Date</label><span class="data-value">${h.joining_date || h.from_date || 'N/A'}</span></div>
                <div class="col-md-2"><label class="data-label">End Date</label><span class="data-value">${h.end_date || h.to_date || 'Present'}</span></div>
                <div class="col-12 mt-1"><label class="data-label">Description / Achievements</label><span class="data-value small text-muted">${h.job_description || ''} <br> ${h.achievements || ''}</span></div>
            </div></div>
        `);

        const sNom = document.getElementById('section_nominee'), bNom = document.getElementById('detailed_nominee_body');
        if (data.nominee_info) {
            const n = data.nominee_info; sNom.classList.remove('d-none');
            bNom.innerHTML = `<div class="row g-3">
                <div class="col-md-3"><label class="data-label">Emergency Contact Name</label><span class="data-value fw-bold">${n.nominee_name || 'N/A'}</span></div>
                <div class="col-md-3"><label class="data-label">Relation</label><span class="data-value">${n.relation || 'N/A'}</span></div>
                <div class="col-md-3"><label class="data-label">Mobile</label><span class="data-value">${n.nominee_mobile || 'N/A'}</span></div>
                <div class="col-md-3"><label class="data-label">Phone</label><span class="data-value">${n.phone || 'N/A'}</span></div>
                <div class="col-md-2"><label class="data-label">Gender</label><span class="data-value">${n.gender || 'N/A'}</span></div>
                <div class="col-md-2"><label class="data-label">DOB</label><span class="data-value">${n.date_of_birth || 'N/A'}</span></div>
                <div class="col-md-2"><label class="data-label">NID</label><span class="data-value">${n.nid || 'N/A'}</span></div>
                <div class="col-md-2"><label class="data-label">Birth Reg No</label><span class="data-value">${n.birth_reg_no || 'N/A'}</span></div>
                <div class="col-md-2"><label class="data-label">Religion</label><span class="data-value">${n.religion || 'N/A'}</span></div>
                <div class="col-md-2"><label class="data-label">Nationality</label><span class="data-value">${n.nationality || 'N/A'}</span></div>
                <div class="col-md-3"><label class="data-label">Marital Status</label><span class="data-value">${n.marital_status || 'N/A'}</span></div>
                <div class="col-md-3"><label class="data-label">Blood Group</label><span class="data-value">${n.blood_group || 'N/A'}</span></div>
                <div class="col-12"><hr class="my-1"></div>
                <div class="col-md-12"><label class="data-label">Address</label><span class="data-value">${n.present_address_line || ''}, ${n.village || ''}, ${n.thana || ''}, ${n.district || ''}, ${n.country || ''}</span></div>
            </div>`;
        } else { sNom.classList.add('d-none'); }

        const sSal = document.getElementById('section_salary'), bSal = document.getElementById('detailed_salary_body');
        if (data.salary_breakdown) {
            const s = data.salary_breakdown, cur = (data.currency && data.currency !== 'N/A') ? data.currency : '';
            const fmt = v => parseFloat(v || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
            const totB = [s.house_allowance, s.transport_allowance, s.food_allowance, s.medical_allowance, s.other_earnings].reduce((a, b) => a + parseFloat(b || 0), 0);
            sSal.classList.remove('d-none');
            bSal.innerHTML = `
                <div class="col-md-4"><label class="data-label">Basic Salary</label><span class="data-value fw-bold text-dark">${fmt(s.basic_salary)} ${cur}</span></div>
                <div class="col-md-4"><label class="data-label">House Allowance</label><span class="data-value">${fmt(s.house_allowance)} ${cur}</span></div>
                <div class="col-md-4"><label class="data-label">Transport Allowance</label><span class="data-value">${fmt(s.transport_allowance)} ${cur}</span></div>
                <div class="col-md-4"><label class="data-label">Food Allowance</label><span class="data-value">${fmt(s.food_allowance)} ${cur}</span></div>
                <div class="col-md-4"><label class="data-label">Medical Allowance</label><span class="data-value">${fmt(s.medical_allowance)} ${cur}</span></div>
                <div class="col-md-4"><label class="data-label">Other Earnings</label><span class="data-value">${fmt(s.other_earnings)} ${cur}</span></div>
                <div class="col-12"><hr class="my-1"></div>
                <div class="col-md-6"><label class="data-label text-primary">Total Allowances & Benefits</label><span class="data-value fw-bold text-primary">${fmt(totB)} ${cur}</span></div>
                <div class="col-md-6"><label class="data-label text-dark fw-bold">Gross Salary</label><span class="data-value fw-bold fs-5 text-dark">${fmt(s.gross_salary)} ${cur}</span></div>
            `;
        } else { sSal.classList.add('d-none'); }

        const policyBody = document.getElementById('detailed_policy_body');
        if (data.employee_eligibility) {
            const e = data.employee_eligibility; let t = [];
            if (e.shift_plan_status === 'active') t.push('Shift'); if (e.leave_plan_status === 'active') t.push('Leave');
            if (e.ot_plan_status === 'active') t.push('OT'); if (e.roster_plans_status === 'active') t.push('Roster');
            policyBody.innerHTML = t.length > 0 ? t.map(x => `<span class="badge bg-soft-primary text-primary me-2 mb-2 p-2">${x} Plan</span>`).join('') : '<p class="data-value text-muted">No active policies</p>';
        }

        const bPlans = document.getElementById('detailed_plans_body'); let hPlans = '';
        if (data.shift?.length > 0) data.shift.forEach(s => hPlans += `<div class="p-2 border rounded mb-2"><span class="data-value small text-muted d-block">Active Shift</span><span class="data-value fw-bold text-dark">${s.get_plan?.name || 'N/A'}</span></div>`);
        if (data.roster?.length > 0) data.roster.filter(r => r.status === 'active').forEach(r => hPlans += `<div class="p-2 border rounded mb-2"><span class="data-value small text-muted d-block">Active Roster</span><span class="data-value fw-bold text-dark">${r.get_plan?.name || 'N/A'}</span></div>`);
        bPlans.innerHTML = hPlans || '<p class="text-muted">No plans assigned</p>';

        const bLInfo = document.getElementById('detailed_leave_info_body');
        if (data.leave_balances?.length > 0) bLInfo.innerHTML = data.leave_balances.map(l => `<div class="d-flex justify-content-between align-items-center p-2 border-bottom"><span class="data-value fw-semibold text-dark">${l.leave_type}</span><span class="badge bg-primary rounded-pill px-3">${l.leave_count} / ${l.total_leave}</span></div>`).join('');
        else bLInfo.innerHTML = '<p class="text-muted">No balances</p>';

        const bLHist = document.getElementById('detailed_leave_history_body');
        if (data.leave_applications?.length > 0) bLHist.innerHTML = data.leave_applications.slice(0, 5).map(l => `<div class="p-2 border rounded mb-2 bg-light-subtle"><div class="d-flex justify-content-between"><span class="data-value fw-bold text-dark">${l.get_plan?.name || 'Leave'}</span><span class="badge ${l.status === 'approved' ? 'bg-success' : 'bg-warning'}">${l.status}</span></div><div class="data-value small text-muted">${l.from} to ${l.to} (${l.leave_count} days)</div></div>`).join('');
        else bLHist.innerHTML = '<p class="text-muted">No recent history</p>';

        document.getElementById('downloadPdfBtn').href = '{{ route('employee.profile.download_pdf', $employee->id) }}';
    }

    function showError(type, msg) {
        document.getElementById(type + 'Loading').classList.add('d-none');
        document.getElementById(type + 'Content').classList.add('d-none');
        document.getElementById(type + 'Error').classList.remove('d-none');
        document.getElementById(type + 'ErrorMessage').textContent = msg;
    }
});
</script>
@endpush
