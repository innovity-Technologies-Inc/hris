@extends('structure.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-0 pb-0">
                    <h4 class="card-title">Submit Expense Claim</h4>
                </div>
                <div class="card-body">
                    <form id="expenseForm" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Employee <span class="text-danger">*</span></label>
                                @if($isEmployee)
                                    <input type="hidden" name="employee_id" id="employee_id" value="{{ $loggedInEmployeeId }}">
                                    <select class="form-select" disabled>
                                        @foreach($employees as $emp)
                                            <option value="{{ $emp->id }}" {{ $emp->id == $loggedInEmployeeId ? 'selected' : '' }}>
                                                {{ $emp->full_name }} ({{ $emp->applicant_id }})
                                            </option>
                                        @endforeach
                                    </select>
                                @else
                                    <select class="form-select" name="employee_id" id="employee_id" required>
                                        <option value="">Select Employee</option>
                                        @foreach($employees as $emp)
                                            <option value="{{ $emp->id }}">{{ $emp->full_name }} ({{ $emp->applicant_id }})</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Expense Type <span class="text-danger">*</span></label>
                                <select class="form-select" name="expense_type_id" id="expense_type_id" required>
                                    <option value="">Select Expense Type</option>
                                    @foreach($expenseTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Amount <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" name="amount" id="amount" placeholder="0.00" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Payment Method <span class="text-danger">*</span></label>
                                <select class="form-select" name="payment_method" id="payment_method" required>
                                    <option value="">Select Payment Method</option>
                                    <option value="cash">Cash</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="mobile_banking">Mobile Banking</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Purpose</label>
                            <textarea class="form-control" name="purpose" id="purpose" rows="3" placeholder="Describe the purpose of this expense"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Receipt / Bill Attachment</label>
                            <input type="file" class="form-control" name="receipt" id="receipt" accept="image/*,application/pdf">
                            <small class="text-muted">Allowed formats: JPG, PNG, PDF (Max 2MB)</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Remarks</label>
                            <textarea class="form-control" name="remarks" id="remarks" rows="2" placeholder="Any additional comments"></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning rounded-pill px-4 text-dark" id="btnSubmit">Submit Claim</button>
                            <a href="{{ route('claim_expenses.index') }}" class="btn btn-light rounded-pill px-4">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('expenseForm');
    const submitBtn = document.getElementById('btnSubmit');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        submitBtn.disabled = true;

        const formData = new FormData();
        formData.append('employee_id', document.getElementById('employee_id').value);
        formData.append('expense_type_id', document.getElementById('expense_type_id').value);
        formData.append('amount', document.getElementById('amount').value);
        formData.append('payment_method', document.getElementById('payment_method').value);
        formData.append('purpose', document.getElementById('purpose').value);
        formData.append('remarks', document.getElementById('remarks').value);
        
        const receiptFile = document.getElementById('receipt').files[0];
        if (receiptFile) {
            formData.append('receipt', receiptFile);
        }

        axios.post("{{ route('claim_expenses.store') }}", formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        }).then(response => {
            Swal.fire({
                title: 'Success!',
                text: response.data.message,
                icon: 'success'
            }).then(() => {
                window.location.href = "{{ route('claim_expenses.index') }}";
            });
        }).catch(error => {
            submitBtn.disabled = false;
            let errorMsg = 'Failed to submit application.';
            if (error.response && error.response.data && error.response.data.errors) {
                errorMsg = Object.values(error.response.data.errors).flat().join('<br>');
            } else if (error.response && error.response.data && error.response.data.message) {
                errorMsg = error.response.data.message;
            }
            Swal.fire('Error!', errorMsg, 'error');
        });
    });
});
</script>
@endpush
