@extends('structure.master')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card border-0 shadow-lg rounded-4 my-4">
            <div class="card-header border-bottom rounded-top-4 p-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3 d-inline-flex align-items-center justify-content-center">
                        <i class="mdi mdi-pencil text-warning fs-4"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0 text-dark fw-bold">Edit Resignation #{{ $resignation->id }}</h5>
                        <small class="text-muted">Update resignation parameters and status</small>
                    </div>
                </div>
                <a href="{{ route('resignation.index') }}" class="btn btn-outline-secondary btn-sm rounded-3">
                    <i class="mdi mdi-arrow-left me-1"></i> Back to List
                </a>
            </div>

            <div class="card-body p-4">
                <form id="resignationEditForm" action="{{ route('resignation.update', $resignation->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card border mb-4 rounded-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-semibold text-primary">
                                <i class="mdi mdi-account-card-details me-2"></i>Resignation Information
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                {{-- Target Employee (Readonly on Edit) --}}
                                <div class="col-md-6">
                                    <label for="employee_name" class="form-label fw-semibold">Employee</label>
                                    <input type="text" class="form-control" id="employee_name" value="{{ $resignation->employee->full_name }} (ID: {{ $resignation->employee->applicant_id ?? $resignation->employee->id }})" readonly>
                                    <input type="hidden" name="employee_id" value="{{ $resignation->employee_id }}">
                                </div>

                                {{-- Status --}}
                                <div class="col-md-6">
                                    <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="pending" {{ $resignation->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ $resignation->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ $resignation->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        <option value="cancelled" {{ $resignation->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>

                                {{-- Resignation Date --}}
                                <div class="col-md-4">
                                    <label for="resignation_date" class="form-label fw-semibold">Resignation Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="resignation_date" name="resignation_date" value="{{ \Carbon\Carbon::parse($resignation->resignation_date)->format('Y-m-d') }}" required>
                                </div>

                                {{-- Notice Period Days --}}
                                <div class="col-md-4">
                                    <label for="notice_period_days" class="form-label fw-semibold">Notice Period (Days) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="notice_period_days" name="notice_period_days" value="{{ $resignation->notice_period_days }}" min="0" required>
                                </div>

                                {{-- Last Working Day --}}
                                <div class="col-md-4">
                                    <label for="last_working_day" class="form-label fw-semibold">Last Working Day <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="last_working_day" name="last_working_day" value="{{ \Carbon\Carbon::parse($resignation->last_working_day)->format('Y-m-d') }}" required readonly>
                                </div>

                                {{-- Reason --}}
                                <div class="col-md-12">
                                    <label for="reason" class="form-label fw-semibold">Reason for Resignation <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="reason" name="reason" rows="3" required>{{ $resignation->reason }}</textarea>
                                </div>

                                {{-- Remarks --}}
                                <div class="col-md-12">
                                    <label for="remarks" class="form-label fw-semibold">Additional Remarks (Optional)</label>
                                    <textarea class="form-control" id="remarks" name="remarks" rows="2">{{ $resignation->remarks }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('resignation.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                        <button type="submit" class="btn btn-warning px-4 text-dark fw-semibold">
                            <i class="mdi mdi-check-circle me-1"></i> Update Resignation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(document).ready(function() {
    function calculateLastWorkingDay() {
        const resignationDateVal = $('#resignation_date').val();
        const noticeDays = parseInt($('#notice_period_days').val()) || 0;

        if (resignationDateVal) {
            const dateObj = new Date(resignationDateVal);
            dateObj.setDate(dateObj.getDate() + noticeDays);
            
            const year = dateObj.getFullYear();
            const month = String(dateObj.getMonth() + 1).padStart(2, '0');
            const day = String(dateObj.getDate()).padStart(2, '0');

            $('#last_working_day').val(`${year}-${month}-${day}`);
        }
    }

    $('#resignation_date, #notice_period_days').on('input change', calculateLastWorkingDay);

    // Axios Form Submission
    $('#resignationEditForm').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        const submitBtn = form.querySelector('button[type="submit"]');

        $('.invalid-feedback').remove();
        $('.is-invalid').removeClass('is-invalid');
        if (submitBtn) submitBtn.disabled = true;

        const formData = new FormData(form);

        axios.post(form.action, formData)
            .then(response => {
                const res = response.data;
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = res.redirect || "{{ route('resignation.index') }}";
                    });
                }
            })
            .catch(error => {
                if (submitBtn) submitBtn.disabled = false;
                if (error.response && error.response.status === 422) {
                    const errors = error.response.data.errors;
                    if (errors) {
                        Object.keys(errors).forEach(key => {
                            const input = form.querySelector(`[name="${key}"]`);
                            if (input) {
                                input.classList.add('is-invalid');
                                const feedback = document.createElement('div');
                                feedback.className = 'invalid-feedback d-block';
                                feedback.innerText = errors[key][0];
                                input.after(feedback);
                            }
                        });
                    }
                    const msg = error.response.data.message || 'Validation error. Please check your inputs.';
                    Swal.fire({ icon: 'error', title: 'Validation Error', text: msg });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.response?.data?.message || 'Something went wrong. Please try again later.'
                    });
                }
            });
    });
});
</script>
@endsection
