@extends('structure.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            @can('tax-policy.view')
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header bg-white py-3 border-bottom border-light">
                    <h5 class="card-title mb-0 fw-bold text-dark d-flex align-items-center">
                        <i data-feather="search" class="me-2 text-primary" style="width: 20px; height: 20px;"></i>
                        Search Tax Challans
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="border rounded shadow-sm p-3 filter-section-bg">
                        <form id="filterForm">
                            {{-- Row 1: Keyword & Company --}}
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="keywordSearch" class="form-label text-muted small fw-semibold mb-1">
                                        Keyword Search
                                    </label>
                                    <div class="input-group input-group-md">
                                        <input type="text" class="form-control border-end-0" id="keywordSearch"
                                               name="keyword" placeholder="Search by name, employee id, system id"
                                               aria-label="Keyword Search" value="{{ request('keyword') }}">
                                        <span class="input-group-text border-start-0 input-group-bg bg-white">
                                            <i class="mdi mdi-magnify text-muted"></i>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label for="search_company_id" class="form-label text-muted small fw-semibold mb-1">
                                        Company
                                    </label>
                                    <select id="search_company_id" name="company" class="form-select select2_list" data-placeholder="Select Company">
                                        <option value="">Choose One</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}" {{ request('company') == $company->id ? 'selected' : '' }}>
                                                {{ $company->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label for="search_from" class="form-label text-muted small fw-semibold mb-1">
                                        Paid Month From
                                    </label>
                                    <input type="month" class="form-control" id="search_from" name="from" value="{{ request('from') }}">
                                </div>

                                <div class="col-md-2">
                                    <label for="search_to" class="form-label text-muted small fw-semibold mb-1">
                                        Paid Month To
                                    </label>
                                    <input type="month" class="form-control" id="search_to" name="to" value="{{ request('to') }}">
                                </div>
                            </div>

                            {{-- Action buttons --}}
                            <div class="row mt-3">
                                <div class="col-12 text-end">
                                    <button type="button" id="resetFilters" class="btn btn-outline-secondary btn-md px-4 me-2">
                                        <i class="mdi mdi-refresh me-1"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endcan
        </div>

        <div class="col-lg-12 mt-3">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header bg-white py-3 border-bottom border-light d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold text-dark d-flex align-items-center">
                        <i data-feather="list" class="me-2 text-primary" style="width: 20px; height: 20px;"></i>
                        Tax Challan List
                    </h5>
                    @can('tax-challan.create')
                    <button type="button" class="btn btn-warning btn-sm" id="addChallanBtn">
                        <i class="bi bi-plus-lg me-1"></i> Add New Challan
                    </button>
                    @endcan
                </div>
                <div class="card-body">
                    <div id="search-result">
                        @include('payroll.tax_challan.partials.search_results')
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Create / Edit Modal --}}
    <div class="modal fade" id="challanModal" tabindex="-1" aria-labelledby="challanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom border-light">
                    <h5 class="modal-title fw-bold" id="challanModalLabel">Add New Tax Challan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="challanForm" enctype="multipart/form-data">
                    <div class="modal-body p-4">
                        <input type="hidden" id="challanId" name="id">
                        
                        {{-- Row 1: Company & Employee --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="company_id" class="form-label fw-semibold">Company <span class="text-danger">*</span></label>
                                <select id="company_id" name="company_id" class="form-select select2_modal" required>
                                    <option value="">Select Company</option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback d-block" id="error_company_id"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="employee_id" class="form-label fw-semibold">Employee</label>
                                <select id="employee_id" name="employee_id" class="form-select select2_modal">
                                    <option value="">Select Employee</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}">
                                            {{ $employee->full_name }} ({{ $employee->system_id ?? $employee->applicant_id }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback d-block" id="error_employee_id"></div>
                            </div>
                        </div>

                        {{-- Row 2: Paid Range (Month & Year) --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="tax_paid_from" class="form-label fw-semibold">Tax Paid From (Month/Year) <span class="text-danger">*</span></label>
                                <input type="month" class="form-control" id="tax_paid_from" name="tax_paid_from" required>
                                <div class="invalid-feedback d-block" id="error_tax_paid_from"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="tax_paid_to" class="form-label fw-semibold">Tax Paid To (Month/Year) <span class="text-danger">*</span></label>
                                <input type="month" class="form-control" id="tax_paid_to" name="tax_paid_to" required>
                                <div class="invalid-feedback d-block" id="error_tax_paid_to"></div>
                            </div>
                        </div>

                        {{-- Row 3: Attachments upload --}}
                        <div class="mb-3">
                            <label for="attachments" class="form-label fw-semibold">Upload Files (Multiple Allowed)</label>
                            <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
                            <div class="invalid-feedback d-block" id="error_attachments"></div>
                        </div>

                        {{-- Existing files manager (Only visible during edit) --}}
                        <div class="mb-3 d-none" id="existingAttachmentsGroup">
                            <label class="form-label fw-semibold">Current Attachments</label>
                            <div id="existingAttachmentsList" class="d-flex flex-wrap gap-2"></div>
                            <div id="removedAttachmentsContainer"></div>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-light">
                        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 fw-semibold" id="saveChallanBtn">
                            <span class="spinner-border spinner-border-sm me-1 d-none" id="saveSpinner"></span>
                            Save Challan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            const modalEl = document.getElementById('challanModal');
            const modal = new bootstrap.Modal(modalEl);
            let silenceChangeEvents = false;

            // Initialize select2 inside modal when it is opened
            $(modalEl).on('shown.bs.modal', function () {
                $('.select2_modal').select2({
                    dropdownParent: $(modalEl),
                    width: '100%'
                });
            });

            // Clean select2 elements when modal is hidden
            $(modalEl).on('hidden.bs.modal', function () {
                $('.select2_modal').val(null).trigger('change');
            });

            // Perform AJAX search
            function fetchData(url = "{{ route('tax-challan.index') }}") {
                const queryString = $('#filterForm').serialize();

                $.ajax({
                    url: url,
                    method: "GET",
                    data: queryString,
                    beforeSend: function() {
                        $('#search-result').html(
                            '<div class="text-center py-5 text-muted"><span class="spinner-border spinner-border-sm me-2"></span> Loading Data...</div>'
                        );
                    },
                    success: function(response) {
                        $('#search-result').html(response);
                        if (typeof feather !== 'undefined') {
                            feather.replace();
                        }
                        const newUrl = '?' + queryString;
                        window.history.pushState(null, '', newUrl || location.pathname);
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr.responseText);
                    }
                });
            }

            // Open creation modal
            $('#addChallanBtn').on('click', function() {
                $('#challanForm')[0].reset();
                $('#challanId').val('');
                $('.invalid-feedback').text('');
                $('#existingAttachmentsGroup').addClass('d-none');
                $('#existingAttachmentsList').html('');
                $('#removedAttachmentsContainer').html('');
                $('#challanModalLabel').text('Add New Tax Challan');
                modal.show();
            });

            // Handle edit modal opening
            $(document).on('click', '.edit-challan', function() {
                const id = $(this).data('id');
                $('.invalid-feedback').text('');
                $('#removedAttachmentsContainer').html('');
                
                axios.get(`/tax-challan/${id}/edit`)
                    .then(response => {
                        const data = response.data.data;
                        $('#challanId').val(data.id);
                        $('#company_id').val(data.company_id).trigger('change');
                        $('#employee_id').val(data.employee_id).trigger('change');
                        $('#tax_paid_from').val(data.tax_paid_from);
                        $('#tax_paid_to').val(data.tax_paid_to);
                        
                        // Populate existing attachments list
                        $('#existingAttachmentsList').html('');
                        if (data.attachments && data.attachments.length > 0) {
                            $('#existingAttachmentsGroup').removeClass('d-none');
                            data.attachments.forEach((path, idx) => {
                                $('#existingAttachmentsList').append(`
                                    <div class="d-flex align-items-center border rounded p-2 me-1 mb-1">
                                        <a href="/storage/${path}" target="_blank" class="text-decoration-none small text-truncate" style="max-width: 150px;">
                                            <i class="bi bi-file-earmark-arrow-down me-1"></i> File ${idx + 1}
                                        </a>
                                        <button type="button" class="btn-close ms-2 remove-existing-file" data-path="${path}" style="font-size: 0.65rem;"></button>
                                    </div>
                                `);
                            });
                        } else {
                            $('#existingAttachmentsGroup').addClass('d-none');
                        }

                        $('#challanModalLabel').text('Edit Tax Challan');
                        modal.show();
                    })
                    .catch(error => {
                        console.error('Failed to load edit payload:', error);
                        Swal.fire('Error', 'Failed to load tax challan details.', 'error');
                    });
            });

            // Track file removals from edit state
            $(document).on('click', '.remove-existing-file', function() {
                const path = $(this).data('path');
                $(this).closest('div').remove();
                $('#removedAttachmentsContainer').append(`<input type="hidden" name="removed_attachments[]" value="${path}">`);
                if ($('#existingAttachmentsList').children().length === 0) {
                    $('#existingAttachmentsGroup').addClass('d-none');
                }
            });

            // Handle Form Submit (Axios)
            $('#challanForm').on('submit', function(e) {
                e.preventDefault();
                $('.invalid-feedback').text('');

                const id = $('#challanId').val();
                const formData = new FormData(this);
                const isEdit = id !== '';
                const url = isEdit ? `/tax-challan/${id}/update` : '/tax-challan/store';

                $('#saveSpinner').removeClass('d-none');
                $('#saveChallanBtn').prop('disabled', true);

                axios.post(url, formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                })
                .then(res => {
                    $('#saveSpinner').addClass('d-none');
                    $('#saveChallanBtn').prop('disabled', false);
                    modal.hide();

                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: res.data.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        fetchData();
                    });
                })
                .catch(err => {
                    $('#saveSpinner').addClass('d-none');
                    $('#saveChallanBtn').prop('disabled', false);

                    if (err.response && err.response.status === 422) {
                        const errors = err.response.data.errors;
                        Object.keys(errors).forEach(key => {
                            $(`#error_${key}`).text(errors[key][0]);
                        });
                    } else {
                        const errMsg = err.response?.data?.message || 'Something went wrong. Please check your data.';
                        Swal.fire('Error', errMsg, 'error');
                    }
                });
            });

            // Handle deletion (Axios + SweetAlert2)
            $(document).on('click', '.delete-challan', function() {
                const id = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete(`/tax-challan/${id}`)
                            .then(res => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: res.data.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    fetchData();
                                });
                            })
                            .catch(err => {
                                console.error(err);
                                Swal.fire('Error', 'Failed to delete tax challan.', 'error');
                            });
                    }
                });
            });

            // Bind filter listeners
            $('#search_company_id').on('change', function () {
                if (silenceChangeEvents) return;
                fetchData();
            });

            $('#keywordSearch').on('input', function(e) {
                e.preventDefault();
                fetchData();
            });

            $('#search_from, #search_to').on('change', function(e) {
                e.preventDefault();
                fetchData();
            });

            // Reset filters
            $('#resetFilters').on('click', function() {
                silenceChangeEvents = true;
                $('#filterForm')[0].reset();
                $('.select2_list').val('').trigger('change.select2');
                silenceChangeEvents = false;
                fetchData();
            });

            // Pagination click handler
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                if (url) {
                    fetchData(url);
                }
            });
        });
    </script>
@endsection
