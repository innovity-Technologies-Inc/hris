@extends('structure.master')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    @can('movement-types.create')
                    <button type="button" class="btn btn-warning btn-sm" id="btnCreateMovementType">
                        <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
                    </button>
                    @endcan
                </div><!-- end card header -->

                <div class="card-body">
                    <form id="filterForm">
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="input-group input-group-md">
                                    <input type="text" class="form-control border-end-0" id="searchKeyword"
                                           name="keyword" placeholder="Search by name or description"
                                           aria-label="Keyword Search">
                                    <span class="input-group-text border-start-0 input-group-bg">
                                        <i class="mdi mdi-magnify text-muted"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive" id="movementTypeContainer">
                        <div class="text-center py-4 text-muted">Loading Data...</div>
                    </div>
                </div>
            </div><!-- end card -->
        </div>
    </div><!-- end row -->

    {{-- Movement Type Modal --}}
    <div class="modal fade" id="movementTypeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header text-white border-0" style="background-color: #974063;">
                    <h5 class="modal-title" id="movementTypeModalLabel">Add Movement Type</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="movementTypeForm">
                    @csrf
                    <input type="hidden" id="movementTypeId" name="id">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="e.g. Annual, Promotion, Transfer" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Brief description of the movement type"></textarea>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="status" id="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn rounded-pill px-4 text-white" id="btnSaveMovementType" style="background-color: #974063;">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('movementTypeContainer');
    const searchInput = document.getElementById('searchKeyword');
    const modal = new bootstrap.Modal(document.getElementById('movementTypeModal'));
    const form = document.getElementById('movementTypeForm');

    // Initial Load
    fetchMovementTypes();

    // Search Logic
    let searchTimer;
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(fetchMovementTypes, 500);
    });

    function fetchMovementTypes(url = "{{ route('movement_types.index') }}") {
        const keyword = searchInput.value;
        const fullUrl = `${url}${url.includes('?') ? '&' : '?'}keyword=${keyword}`;

        axios.get(fullUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => {
                container.innerHTML = response.data;
                bindActionButtons();
                if (window.feather) {
                    feather.replace();
                }
            })
            .catch(error => {
                console.error(error);
                container.innerHTML = '<div class="text-danger text-center py-4">Failed to load data.</div>';
            });
    }

    function bindActionButtons() {
        // Edit Button
        document.querySelectorAll('.edit-movement-type').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                axios.get(`/company-setup/movement-types/${id}/edit`)
                    .then(response => {
                        const data = response.data.data;
                        document.getElementById('movementTypeId').value = data.id;
                        document.getElementById('name').value = data.name;
                        document.getElementById('description').value = data.description || '';
                        document.getElementById('status').value = data.status;
                        
                        document.getElementById('movementTypeModalLabel').innerText = 'Edit Movement Type';
                        modal.show();
                    });
            });
        });

        // Delete Button
        document.querySelectorAll('.delete-movement-type').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
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
                        axios.delete(`/company-setup/movement-types/${id}/delete`, {
                            data: { _token: "{{ csrf_token() }}" }
                        }).then(response => {
                            Swal.fire('Deleted!', response.data.message, 'success');
                            fetchMovementTypes();
                        }).catch(error => {
                            Swal.fire('Error!', error.response?.data?.message || 'Failed to delete movement type.', 'error');
                        });
                    }
                });
            });
        });
    }

    // Create Button Click
    const btnCreate = document.getElementById('btnCreateMovementType');
    if (btnCreate) {
        btnCreate.addEventListener('click', function() {
            form.reset();
            document.getElementById('movementTypeId').value = '';
            document.getElementById('movementTypeModalLabel').innerText = 'Add Movement Type';
            modal.show();
        });
    }

    // Form Submission (Add/Edit)
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const id = document.getElementById('movementTypeId').value;
        const formData = new FormData(form);
        const url = id ? `/company-setup/movement-types/${id}/update` : '/company-setup/movement-types/store';
        
        // Handle Laravel PUT request via FormData method tunneling
        if (id) {
            formData.append('_method', 'PUT');
        }

        axios.post(url, formData)
            .then(response => {
                if (response.data.success) {
                    modal.hide();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.data.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    fetchMovementTypes();
                } else {
                    Swal.fire('Error', response.data.message, 'error');
                }
            })
            .catch(error => {
                console.error(error);
                if (error.response && error.response.status === 422) {
                    const errors = error.response.data.errors;
                    let errorMsg = '';
                    Object.keys(errors).forEach(key => {
                        errorMsg += `${errors[key][0]}<br>`;
                    });
                    Swal.fire('Validation Error', errorMsg, 'error');
                } else {
                    Swal.fire('Error', error.response?.data?.message || 'Something went wrong.', 'error');
                }
            });
    });
});
</script>
@endpush
