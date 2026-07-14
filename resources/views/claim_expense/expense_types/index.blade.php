@extends('structure.master')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    @can('expense-types.create')
                    <button type="button" class="btn btn-warning btn-sm" id="btnCreateExpenseType">
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
                                           name="keyword" placeholder="Search by name, description or company"
                                           aria-label="Keyword Search">
                                    <span class="input-group-text border-start-0 input-group-bg">
                                        <i class="mdi mdi-magnify text-muted"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive" id="expenseTypeContainer">
                        <div class="text-center py-4 text-muted">Loading Data...</div>
                    </div>
                </div>
            </div><!-- end card -->
        </div>
    </div><!-- end row -->

    {{-- Expense Type Modal --}}
    <div class="modal fade" id="expenseTypeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header text-white border-0" style="background-color: #974063;">
                    <h5 class="modal-title" id="expenseTypeModalLabel">Add Expense Type</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="expenseTypeForm">
                    @csrf
                    <input type="hidden" id="expenseTypeId" name="id">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="e.g. Travel Expense, Entertainment, Office Supply" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Brief description of the expense type"></textarea>
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
                        <button type="submit" class="btn rounded-pill px-4 text-white" id="btnSaveExpenseType" style="background-color: #974063;">Save Changes</button>
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
    const container = document.getElementById('expenseTypeContainer');
    const searchInput = document.getElementById('searchKeyword');
    const modal = new bootstrap.Modal(document.getElementById('expenseTypeModal'));
    const form = document.getElementById('expenseTypeForm');

    // Initial Load
    fetchExpenseTypes();

    // Search Logic
    let searchTimer;
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(fetchExpenseTypes, 500);
    });

    function fetchExpenseTypes(url = "{{ route('expense_types.index') }}") {
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
        document.querySelectorAll('.edit-expense-type').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                axios.get(`/claim-expense/expense-types/${id}/edit`)
                    .then(response => {
                        const data = response.data.data;
                        document.getElementById('expenseTypeId').value = data.id;
                        document.getElementById('name').value = data.name;
                        document.getElementById('description').value = data.description || '';
                        document.getElementById('status').value = data.status;
                        
                        document.getElementById('expenseTypeModalLabel').innerText = 'Edit Expense Type';
                        modal.show();
                    });
            });
        });

        // Delete Button
        document.querySelectorAll('.delete-expense-type').forEach(btn => {
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
                        axios.delete(`/claim-expense/expense-types/${id}/delete`, {
                            data: { _token: "{{ csrf_token() }}" }
                        }).then(response => {
                            Swal.fire('Deleted!', response.data.message, 'success');
                            fetchExpenseTypes();
                        }).catch(error => {
                            Swal.fire('Error!', error.response.data.message || 'Deletion failed', 'error');
                        });
                    }
                });
            });
        });
    }

    // Open Modal for Create
    const createBtn = document.getElementById('btnCreateExpenseType');
    if (createBtn) {
        createBtn.addEventListener('click', function() {
            form.reset();
            document.getElementById('expenseTypeId').value = '';
            document.getElementById('expenseTypeModalLabel').innerText = 'Add Expense Type';
            modal.show();
        });
    }

    // Submit Form (Store/Update)
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('expenseTypeId').value;
        const url = id ? `/claim-expense/expense-types/${id}/update` : '/claim-expense/expense-types/store';
        const method = id ? 'put' : 'post';

        const formData = {
            name: document.getElementById('name').value,
            description: document.getElementById('description').value,
            status: document.getElementById('status').value,
            _token: "{{ csrf_token() }}"
        };

        axios({
            method: method,
            url: url,
            data: formData
        }).then(response => {
            modal.hide();
            Swal.fire('Success!', response.data.message, 'success');
            fetchExpenseTypes();
        }).catch(error => {
            let errorMsg = 'Failed to save changes.';
            if (error.response && error.response.data && error.response.data.errors) {
                errorMsg = Object.values(error.response.data.errors).flat().join('<br>');
            } else if (error.response && error.response.data && error.response.data.message) {
                errorMsg = error.response.data.message;
            }
            Swal.fire('Error!', errorMsg, 'error');
        });
    });

    // Pagination
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        fetchExpenseTypes($(this).attr('href'));
    });
});
</script>
@endpush
