@extends('structure.master')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center p-4">
                    <h4 class="card-title fw-bold text-dark mb-0">Organizations</h4>
                    <button type="button" class="btn btn-warning btn-sm px-3 rounded-pill text-white" id="btnCreateOrg" style="background-color: var(--bs-dashboard-accent, #1e88e5); border-color: var(--bs-dashboard-accent, #1e88e5);">
                        <i class="fas fa-plus me-1"></i> Add Organization
                    </button>
                </div><!-- end card header -->

                <div class="card-body p-4">
                    <form id="filterForm" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-9 col-12">
                                <div class="input-group">
                                    <input type="text" class="form-control border-end-0 rounded-start-pill px-3" id="searchKeyword"
                                           name="keyword" placeholder="Search by organization name, slug or email..."
                                           aria-label="Keyword Search">
                                    <span class="input-group-text border-start-0 bg-white rounded-end-pill px-3">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-3 col-12">
                                <select class="form-select rounded-pill px-3" id="statusFilter">
                                    <option value="">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 70px;">Logo</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Slug</th>
                                    <th scope="col">Contact</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" style="width: 150px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="orgTableBody">
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Loading Organizations...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4" id="paginationContainer">
                        <!-- Will be populated dynamically -->
                    </div>
                </div>
            </div><!-- end card -->
        </div>
    </div><!-- end row -->

    {{-- Organization Form Modal --}}
    <div class="modal fade" id="orgModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header text-white border-0 p-4" style="background-color: var(--bs-dashboard-accent, #1e88e5);">
                    <h5 class="modal-title fw-bold" id="orgModalLabel">Add Organization</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="orgForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="orgId" name="id">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold">Organization Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control rounded-3" name="name" id="name" placeholder="e.g. Apex Industries" required>
                                <div class="invalid-feedback" id="error_name"></div>
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold">Unique Slug <span class="text-danger">*</span></label>
                                <input type="text" class="form-control rounded-3" name="slug" id="slug" placeholder="e.g. apex-industries" required>
                                <div class="invalid-feedback" id="error_slug"></div>
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold">Email Address</label>
                                <input type="email" class="form-control rounded-3" name="email" id="email" placeholder="e.g. contact@apex.com">
                                <div class="invalid-feedback" id="error_email"></div>
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold">Phone Number</label>
                                <input type="text" class="form-control rounded-3" name="phone" id="phone" placeholder="e.g. +8801700000000">
                                <div class="invalid-feedback" id="error_phone"></div>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Address</label>
                                <textarea class="form-control rounded-3" name="address" id="address" rows="2" placeholder="Organization headquarters address"></textarea>
                                <div class="invalid-feedback" id="error_address"></div>
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <select class="form-select rounded-3" name="status" id="status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                <div class="invalid-feedback" id="error_status"></div>
                            </div>
                            <div class="col-md-6 col-12">
                                <label class="form-label fw-semibold">Logo Image</label>
                                <input type="file" class="form-control rounded-3" name="logo" id="logo" accept="image/*">
                                <div class="invalid-feedback" id="error_logo"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn rounded-pill px-4 text-white" id="btnSaveOrg" style="background-color: var(--bs-dashboard-accent, #1e88e5);">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.getElementById('orgTableBody');
    const searchInput = document.getElementById('searchKeyword');
    const statusFilter = document.getElementById('statusFilter');
    const paginationContainer = document.getElementById('paginationContainer');
    const modalElement = document.getElementById('orgModal');
    const modal = new bootstrap.Modal(modalElement);
    const form = document.getElementById('orgForm');

    let currentPage = 1;

    // Load Data
    fetchOrgs();

    // Auto-slugify name input when creating
    document.getElementById('name').addEventListener('input', function() {
        if (!document.getElementById('orgId').value) {
            const slug = this.value
                .toLowerCase()
                .replace(/[^a-z0-9 -]/g, '') // remove invalid chars
                .replace(/\s+/g, '-')        // collapse whitespace and replace by -
                .replace(/-+/g, '-');        // collapse dashes
            document.getElementById('slug').value = slug;
        }
    });

    // Filters & Search
    let searchTimer;
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            currentPage = 1;
            fetchOrgs();
        }, 400);
    });

    statusFilter.addEventListener('change', () => {
        currentPage = 1;
        fetchOrgs();
    });

    // Create Action
    document.getElementById('btnCreateOrg').addEventListener('click', function() {
        form.reset();
        document.getElementById('orgId').value = '';
        document.getElementById('orgModalLabel').innerText = 'Add Organization';
        
        // Clear error states
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('.invalid-feedback').forEach(el => el.innerText = '');
        
        modal.show();
    });

    // Save/Update Submit handler
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Clear error states
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('.invalid-feedback').forEach(el => el.innerText = '');

        const id = document.getElementById('orgId').value;
        const formData = new FormData(form);
        
        let requestUrl = "{{ route('organizations.store') }}";
        
        if (id) {
            requestUrl = `/organizations/${id}`;
            // Laravel requires _method=PUT to handle multipart files in resource updates
            formData.append('_method', 'PUT');
        }

        const btnSave = document.getElementById('btnSaveOrg');
        btnSave.disabled = true;
        btnSave.innerText = 'Saving...';

        axios.post(requestUrl, formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            modal.hide();
            Swal.fire({
                title: 'Success!',
                text: response.data.message,
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });
            fetchOrgs();
        })
        .catch(error => {
            if (error.response && error.response.status === 422) {
                const errors = error.response.data.errors;
                Object.keys(errors).forEach(key => {
                    const inputElement = document.getElementById(key);
                    const errorElement = document.getElementById(`error_${key}`);
                    if (inputElement && errorElement) {
                        inputElement.classList.add('is-invalid');
                        errorElement.innerText = errors[key][0];
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: error.response?.data?.message || 'Something went wrong.',
                    icon: 'error'
                });
            }
        })
        .finally(() => {
            btnSave.disabled = false;
            btnSave.innerText = 'Save Changes';
        });
    });

    function fetchOrgs() {
        tableBody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-muted"><i class="fas fa-spinner fa-spin me-2"></i>Loading Organizations...</td></tr>';
        
        const keyword = searchInput.value;
        const status = statusFilter.value;

        axios.get("{{ route('organizations.index') }}", {
            params: {
                search: keyword,
                status: status,
                page: currentPage
            },
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => {
            const result = response.data.data;
            renderTable(result.data);
            renderPagination(result);
        })
        .catch(error => {
            console.error(error);
            tableBody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-danger">Failed to load organizations.</td></tr>';
        });
    }

    function renderTable(organizations) {
        if (!organizations || organizations.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-muted">No organizations found.</td></tr>';
            return;
        }

        let html = '';
        organizations.forEach(org => {
            const logoUrl = org.logo ? `/storage/${org.logo}` : '';
            const logoMarkup = logoUrl 
                ? `<img src="${logoUrl}" alt="${org.name}" class="rounded-circle border border-light" style="width: 40px; height: 40px; object-fit: contain;">`
                : `<div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px; font-size: 16px;">${org.name.charAt(0).toUpperCase()}</div>`;
            
            const statusClass = org.status === 'active' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger';

            html += `
                <tr>
                    <td>${logoMarkup}</td>
                    <td>
                        <h6 class="fw-bold mb-1 text-dark">${org.name}</h6>
                        <span class="text-muted small">ID: ${org.id}</span>
                    </td>
                    <td><code>/${org.slug}</code></td>
                    <td>
                        <div class="mb-0 text-dark"><i class="far fa-envelope text-muted me-1"></i>${org.email || 'N/A'}</div>
                        <div class="text-muted small"><i class="fas fa-phone-alt text-muted me-1"></i>${org.phone || 'N/A'}</div>
                    </td>
                    <td>
                        <span class="badge rounded-pill px-3 py-1 fs-12 ${statusClass}">${org.status.toUpperCase()}</span>
                    </td>
                    <td>
                        <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 me-1 edit-org" data-id="${org.id}">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3 delete-org" data-id="${org.id}">
                            <i class="fas fa-trash-alt"></i> Delete
                        </button>
                    </td>
                </tr>
            `;
        });

        tableBody.innerHTML = html;
        bindActionButtons();
    }

    function renderPagination(pager) {
        if (!pager || pager.last_page <= 1) {
            paginationContainer.innerHTML = '';
            return;
        }

        let html = `<div class="text-muted small">Showing ${pager.from ?? 0} to ${pager.to ?? 0} of ${pager.total} entries</div>`;
        html += `<ul class="pagination pagination-rounded mb-0">`;
        
        // Previous page
        html += `
            <li class="page-item ${pager.current_page === 1 ? 'disabled' : ''}">
                <button class="page-link" data-page="${pager.current_page - 1}"><i class="fas fa-chevron-left"></i></button>
            </li>
        `;

        // Page Numbers
        for (let i = 1; i <= pager.last_page; i++) {
            html += `
                <li class="page-item ${pager.current_page === i ? 'active' : ''}">
                    <button class="page-link" data-page="${i}">${i}</button>
                </li>
            `;
        }

        // Next page
        html += `
            <li class="page-item ${pager.current_page === pager.last_page ? 'disabled' : ''}">
                <button class="page-link" data-page="${pager.current_page + 1}"><i class="fas fa-chevron-right"></i></button>
            </li>
        `;
        html += `</ul>`;

        paginationContainer.innerHTML = html;

        // Bind Pagination Clicks
        paginationContainer.querySelectorAll('.page-link').forEach(btn => {
            btn.addEventListener('click', function() {
                const targetPage = parseInt(this.getAttribute('data-page'));
                if (targetPage && targetPage !== currentPage) {
                    currentPage = targetPage;
                    fetchOrgs();
                }
            });
        });
    }

    function bindActionButtons() {
        // Edit button
        document.querySelectorAll('.edit-org').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                
                // Clear error states
                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                document.querySelectorAll('.invalid-feedback').forEach(el => el.innerText = '');

                axios.get(`/organizations/${id}/edit`)
                    .then(response => {
                        const org = response.data.data;
                        document.getElementById('orgId').value = org.id;
                        document.getElementById('name').value = org.name;
                        document.getElementById('slug').value = org.slug;
                        document.getElementById('email').value = org.email || '';
                        document.getElementById('phone').value = org.phone || '';
                        document.getElementById('address').value = org.address || '';
                        document.getElementById('status').value = org.status;
                        
                        document.getElementById('orgModalLabel').innerText = 'Edit Organization';
                        modal.show();
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to fetch organization details.',
                            icon: 'error'
                        });
                    });
            });
        });

        // Delete button
        document.querySelectorAll('.delete-org').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "All associated company locations, employees, and resources inside this organization will be detached or modified. This cannot be reverted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete(`/organizations/${id}`, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' }
                        })
                        .then(response => {
                            Swal.fire({
                                title: 'Deleted!',
                                text: response.data.message,
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            });
                            fetchOrgs();
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Error!',
                                text: error.response?.data?.message || 'Deletion failed.',
                                icon: 'error'
                            });
                        });
                    }
                });
            });
        });
    }
});
</script>
@endpush
