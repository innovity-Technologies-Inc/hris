@extends('structure.master')

@section('content')
    @php
        $generalSettings = \App\HelperClass::getGeneralSetting();
    @endphp

    <div class="row">
        <div class="col-xl-12">
            <!-- Filter Card -->
            <div class="card mb-4 border-dashed bg-light">
                <div class="card-body">
                    <form id="searchForm" class="row g-3 align-items-center">
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Search Keywords</label>
                            <input type="text" name="keyword" id="keyword" class="form-control form-control-sm" placeholder="Title or content..." value="{{ request('keyword') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Company</label>
                            <select name="company_id" id="company_id" class="form-select form-select-sm">
                                <option value="">Global (All Companies)</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if($generalSettings->branch_status == 1)
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Branch</label>
                                <select name="branch_id" id="branch_id" class="form-select form-select-sm">
                                    <option value="">Global (All Branches)</option>
                                </select>
                            </div>
                        @endif
                        @if($generalSettings->division_status == 1)
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Division</label>
                                <select name="division_id" id="division_id" class="form-select form-select-sm">
                                    <option value="">Global (All Divisions)</option>
                                </select>
                            </div>
                        @endif
                        @if($generalSettings->department_status == 1)
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Department</label>
                                <select name="department_id" id="department_id" class="form-select form-select-sm">
                                    <option value="">Global (All Departments)</option>
                                </select>
                            </div>
                        @endif
                        @if($generalSettings->section_status == 1)
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Section</label>
                                <select name="section_id" id="section_id" class="form-select form-select-sm">
                                    <option value="">Global (All Sections)</option>
                                </select>
                            </div>
                        @endif
                        <div class="col-md-2 pt-4">
                            <button type="button" id="resetBtn" class="btn btn-secondary btn-sm w-100">Reset Filters</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table Card -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-semibold text-primary">Announcements & Notices</h5>
                    @can('announcements.create')
                        <a class="btn btn-warning btn-sm" href="{{ route('announcements.create') }}">
                            <i style="height: 12px; width: 12px" data-feather="plus"></i> Post Announcement
                        </a>
                    @endcan
                </div>

                <div class="card-body" id="tableContainer">
                    @include('announcement.partials.table')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Unified Hierarchy Loader ---
        let silenceChangeEvents = false;

        function ajaxLoad(url, $select, placeholder, selectedValue = null) {
            if (!$select.length) return Promise.resolve();
            return $.get(url).then(function (data) {
                $select.html(`<option value="">${placeholder}</option>`);
                $.each(data, function (_, item) {
                    $select.append(
                        `<option value="${item.id}">${item.name ?? item.department_name ?? item.full_name}</option>`
                    );
                });
                if (selectedValue && selectedValue !== 'null' && selectedValue !== '') {
                    $select.val(selectedValue);
                } else {
                    $select.val('');
                }
            }).catch(function () {
                $select.html('<option value="">Error loading data</option>');
            });
        }

        function loadHierarchy(companyId, branchId = null, divisionId = null, departmentId = null, sectionId = null) {
            if (!companyId) {
                if ($('#branch_id').length) $('#branch_id').html('<option value="">Global (All Branches)</option>');
                if ($('#division_id').length) $('#division_id').html('<option value="">Global (All Divisions)</option>');
                if ($('#department_id').length) $('#department_id').html('<option value="">Global (All Departments)</option>');
                if ($('#section_id').length) $('#section_id').html('<option value="">Global (All Sections)</option>');
                return Promise.resolve();
            }

            let branchPromise = Promise.resolve();
            if ($('#branch_id').length) {
                branchPromise = ajaxLoad(`/get-units/${companyId}`, $('#branch_id'), 'Global (All Branches)', branchId);
            }

            return branchPromise.then(() => {
                const currentBranchId = $('#branch_id').val() || 'null';
                return ajaxLoad(`/get-divisions/${companyId}/${currentBranchId}`, $('#division_id'), 'Global (All Divisions)', divisionId);
            }).then(() => {
                const currentBranchId = $('#branch_id').val() || 'null';
                const currentDivisionId = $('#division_id').val() || 'null';
                return ajaxLoad(`/get-departments/${companyId}/${currentBranchId}/${currentDivisionId}`, $('#department_id'), 'Global (All Departments)', departmentId);
            }).then(() => {
                const currentBranchId = $('#branch_id').val() || 'null';
                const currentDivisionId = $('#division_id').val() || 'null';
                const currentDeptId = $('#department_id').val() || 'null';
                return ajaxLoad(`/get-sections/${companyId}/${currentBranchId}/${currentDivisionId}/${currentDeptId}`, $('#section_id'), 'Global (All Sections)', sectionId);
            });
        }

        // Change Events for Cascading Selector
        $('#company_id').on('change', function () {
            if (silenceChangeEvents) return;
            silenceChangeEvents = true;
            loadHierarchy($(this).val()).then(() => {
                silenceChangeEvents = false;
                performSearch();
            });
        });

        $('#branch_id').on('change', function () {
            if (silenceChangeEvents) return;
            silenceChangeEvents = true;
            loadHierarchy($('#company_id').val(), $(this).val()).then(() => {
                silenceChangeEvents = false;
                performSearch();
            });
        });

        $('#division_id').on('change', function () {
            if (silenceChangeEvents) return;
            const companyId = $('#company_id').val();
            const branchId = $('#branch_id').val() || 'null';
            const divisionId = $(this).val() || 'null';

            silenceChangeEvents = true;
            ajaxLoad(`/get-departments/${companyId}/${branchId}/${divisionId}`, $('#department_id'), 'Global (All Departments)')
                .then(() => {
                    const deptId = $('#department_id').val() || 'null';
                    return ajaxLoad(`/get-sections/${companyId}/${branchId}/${divisionId}/${deptId}`, $('#section_id'), 'Global (All Sections)');
                }).then(() => {
                    silenceChangeEvents = false;
                    performSearch();
                });
        });

        $('#department_id').on('change', function () {
            if (silenceChangeEvents) return;
            const companyId = $('#company_id').val();
            const branchId = $('#branch_id').val() || 'null';
            const divisionId = $('#division_id').val() || 'null';
            const deptId = $(this).val() || 'null';

            silenceChangeEvents = true;
            ajaxLoad(`/get-sections/${companyId}/${branchId}/${divisionId}/${deptId}`, $('#section_id'), 'Global (All Sections)')
                .then(() => {
                    silenceChangeEvents = false;
                    performSearch();
                });
        });

        $('#section_id').on('change', function() {
            performSearch();
        });

        // Initialize values on load if requested
        const initialCompanyId = "{{ request('company_id') }}";
        const initialBranchId = "{{ request('branch_id') }}";
        const initialDivisionId = "{{ request('division_id') }}";
        const initialDepartmentId = "{{ request('department_id') }}";
        const initialSectionId = "{{ request('section_id') }}";

        if (initialCompanyId) {
            silenceChangeEvents = true;
            loadHierarchy(initialCompanyId, initialBranchId, initialDivisionId, initialDepartmentId, initialSectionId).then(() => {
                silenceChangeEvents = false;
            });
        }

        // --- Live Search ---
        let debounceTimer;

        function performSearch(page = 1) {
            const data = {
                page: page,
                keyword: $('#keyword').val(),
                company_id: $('#company_id').val(),
                branch_id: $('#branch_id').val() || '',
                division_id: $('#division_id').val() || '',
                department_id: $('#department_id').val() || '',
                section_id: $('#section_id').val() || ''
            };

            axios.get('{{ route('announcements.index') }}', { params: data })
                .then(response => {
                    $('#tableContainer').html(response.data.html);
                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }
                    attachDeleteHandlers();
                })
                .catch(error => {
                    console.error('Search error:', error);
                });
        }

        function debouncedSearch() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                performSearch();
            }, 300);
        }

        $('#keyword').on('input', debouncedSearch);

        // Reset Filters Button
        $('#resetBtn').on('click', function() {
            $('#keyword').val('');
            $('#company_id').val('');
            silenceChangeEvents = true;
            loadHierarchy('').then(() => {
                silenceChangeEvents = false;
                performSearch();
            });
        });

        // Intercept Pagination Clicks for AJAX Search
        $(document).on('click', '#paginationContainer a', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            if (url) {
                const urlParams = new URLSearchParams(url.split('?')[1]);
                const page = urlParams.get('page') || 1;
                performSearch(page);
            }
        });

        // Delete Handler Setup
        function attachDeleteHandlers() {
            const deleteBtns = document.querySelectorAll('.delete-btn');
            deleteBtns.forEach(btn => {
                btn.addEventListener('click', function(event) {
                    event.preventDefault();
                    const url = this.getAttribute('data-url');
                    
                    Swal.fire({
                        title: 'Are you sure you want to delete?',
                        text: 'This action cannot be reverted!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Confirm'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            axios.delete(url, {
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(response => {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: response.data.message || 'Announcement has been deleted.',
                                    icon: 'success'
                                }).then(() => {
                                    performSearch();
                                });
                            })
                            .catch(error => {
                                let errorMsg = 'Something went wrong. Please try again later.';
                                if (error.response && error.response.data && error.response.data.message) {
                                    errorMsg = error.response.data.message;
                                }
                                Swal.fire({
                                    title: 'Error!',
                                    text: errorMsg,
                                    icon: 'error'
                                });
                            });
                        }
                    });
                });
            });
        }

        // Initialize Delete Handlers on first page load
        attachDeleteHandlers();
    });
</script>
@endpush
