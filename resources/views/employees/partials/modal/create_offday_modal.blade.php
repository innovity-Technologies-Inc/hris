{{-- Create Off Day Plan Assignment Modal --}}
<div class="modal fade" id="createOffDayPlanModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="mdi mdi-calendar-blank me-2"></i>Create Off Day Plan Assignment</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="createOffDayPlanForm" method="POST" action="/assign-off-day-plan">
                    @csrf
                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="modal_offday_plan_id" class="form-label fw-semibold">
                                <i class="mdi mdi-calendar-blank text-primary me-1"></i>
                                Select Off Day Plan <span class="text-danger">*</span>
                            </label>
                            <select id="modal_offday_plan_id" name="plan_id" class="form-select" required>
                                <option value="">-- Choose Plan --</option>
                                @foreach ($offDayPlans as $plan)
                                    <option value="{{ $plan->id }}">
                                        {{ $plan->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="modal_offday_effective_from" class="form-label fw-semibold">
                                <i class="mdi mdi-calendar-start text-success me-1"></i>
                                Effective From <span class="text-danger">*</span>
                            </label>
                            <input type="date" id="modal_offday_effective_from" name="from"
                                class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="modal_offday_effective_to" class="form-label fw-semibold">
                                <i class="mdi mdi-calendar-end text-danger me-1"></i>
                                Effective To <span class="text-danger">*</span>
                            </label>
                            <input type="date" id="modal_offday_effective_to" name="to"
                                class="form-control" required>
                        </div>
                    </div>
                    <div id="modal-offday-plan-details" class="mt-4" style="display: none;">
                        <hr class="my-3">
                        <div class="card border-primary">
                            <div class="card-header bg-primary-subtle">
                                <h6 class="mb-0 text-primary fw-semibold"><i
                                        class="mdi mdi-information-outline me-2"></i>Selected Plan Details</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="border-start border-primary border-3 ps-3">
                                            <small class="text-muted d-block">Plan Name</small>
                                            <strong id="modal-offday-detail-name" class="text-dark">-</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="border-start border-secondary border-3 ps-3">
                                            <small class="text-muted d-block">Short Name</small>
                                            <strong id="modal-offday-detail-short" class="text-secondary">-</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="border-start border-success border-3 ps-3">
                                            <small class="text-muted d-block">Remuneration</small>
                                            <strong id="modal-offday-detail-remuneration"
                                                class="text-success fs-5">-</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3 mt-2">
                                    <div class="col-md-6">
                                        <div class="border-start border-info border-3 ps-3">
                                            <small class="text-muted d-block">Start Time</small>
                                            <strong id="modal-offday-detail-start" class="text-info">-</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border-start border-warning border-3 ps-3">
                                            <small class="text-muted d-block">End Time</small>
                                            <strong id="modal-offday-detail-end" class="text-warning">-</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                        class="mdi mdi-close me-1"></i> Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitOffDayModalForm()"><i
                        class="mdi mdi-check-circle me-1"></i> Create Assignment</button>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('assets/libs/jquery/jquery.min.js')}}"></script>


<script>
    $(function() {

        // ============================
        // 🚀 Show Off Day Plan Details
        // ============================
        $('#modal_offday_plan_id').on('change', function () {
            let planId = $(this).val();

            if (!planId) {
                $('#modal-offday-plan-details').hide();
                return;
            }

            $.ajax({
                url: "/get-offday-plan-details/" + planId,
                type: "GET",
                success: function (data) {

                    // Show the details box
                    $('#modal-offday-plan-details').show();

                    // Fill the details
                    $('#modal-offday-detail-name').text(data.name ?? '-');
                    $('#modal-offday-detail-short').text(data.short_name ?? '-');
                    $('#modal-offday-detail-remuneration').text(data.remuneration ?? '-');

                    $('#modal-offday-detail-start').text(data.start_time ?? '-');
                    $('#modal-offday-detail-end').text(data.end_time ?? '-');
                }
            });
        });

    });
</script>

<script>
    function submitOffDayModalForm() {

        let form = $('#createOffDayPlanForm');
        let submitBtn = $('.btn.btn-primary', form);

        // Disable button & show loading
        submitBtn.prop('disabled', true).html(`<i class="mdi mdi-loading mdi-spin me-1"></i> Saving...`);

        $.ajax({
            url: form.attr('action'),   // your form action URL
            type: "POST",
            data: form.serialize(),
            success: function (response) {

                // Show success message (Toastr, SweetAlert, etc.)
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.message ?? "Off Day Plan Assigned Successfully!",
                });

                // Reset form & hide modal
                form.trigger('reset');
                $('#modal-offday-plan-details').hide();
                $('#createOffDayPlanModal').modal('hide');

                // Optionally reload a table or list
                if (typeof loadOffdayTable === 'function') {
                    loadOffdayTable();
                }

                // Re-enable submit button
                submitBtn.prop('disabled', false).html(`<i class="mdi mdi-check-circle me-1"></i> Create Assignment`);
            },
            error: function (xhr) {

                // Handle Laravel validation errors
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let message = "";

                    $.each(errors, function (key, value) {
                        message += value + "<br>";
                    });

                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        html: message
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong!'
                    });
                }

                // Re-enable button
                submitBtn.prop('disabled', false).html(`<i class="mdi mdi-check-circle me-1"></i> Create Assignment`);
            }
        });
    }
</script>
