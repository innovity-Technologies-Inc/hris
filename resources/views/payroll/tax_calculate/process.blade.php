@extends('structure.master')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white py-3 border-bottom border-light">
                    <h5 class="card-title mb-0 fw-bold text-dark d-flex align-items-center">
                        <i data-feather="cpu" class="me-2 text-primary" style="width: 22px; height: 22px;"></i>
                        Calculate Employee Taxes
                    </h5>
                </div>
                <div class="card-body p-4">
                    {{-- Instructions alert panel --}}
                    <div class="alert alert-info border-0 rounded-4 bg-info-subtle text-dark p-3 mb-4">
                        <h6 class="fw-bold mb-2"><i class="bi bi-info-circle-fill me-1 text-info"></i> Tax Calculation Instructions:</h6>
                        <ul class="mb-0 small text-muted-dark ps-3">
                            <li class="mb-1">This process recalculates the estimated annual tax liability and monthly tax rate for all active employees.</li>
                            <li class="mb-1">The calculation respects the active <strong>Tax Exemption Policy</strong>, zero-tax thresholds, and configured tax slabs.</li>
                            <li class="mb-1">Tax calculation is only applied to employees belonging to <strong>Applicable Pay Groups</strong> checked in the policy. For other pay groups, the monthly tax is computed as 0.00.</li>
                            <li class="mb-1">For small employee groups (up to 500), calculations complete synchronously. Larger employee groups are processed safely via background worker jobs.</li>
                        </ul>
                    </div>

                    <div class="text-center py-4">
                        <div class="mb-4 text-muted small">
                            Ready to compute progressive tax slabs? Click the button below to start.
                        </div>
                        @can('tax-policy.edit')
                        <button type="button" class="btn btn-primary btn-lg rounded-pill px-5 shadow fw-bold text-uppercase py-3" id="calculateTaxBtn">
                            <i data-feather="play-circle" class="me-1 fs-5 align-middle" style="width: 20px; height: 20px;"></i> Start Tax Calculation
                        </button>
                        @else
                        <div class="text-danger small fw-semibold">
                            <i class="bi bi-shield-lock me-1"></i> You do not have permission to execute tax calculations.
                        </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            // Trigger Tax Calculation batch processing
            $('#calculateTaxBtn').on('click', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will run the tax calculation formula for all active employees. Existing tax logs will be updated.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, calculate now!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let progressInterval = null;

                        Swal.fire({
                            title: 'Calculating...',
                            html: `
                                <div class="text-center">
                                    <p id="taxProgressMessage" class="mb-2 text-muted">Starting employee tax bracket processing...</p>
                                    <div class="progress mt-3" style="height: 25px; border-radius: 12px; overflow: hidden; background-color: rgba(0,0,0,0.05);">
                                        <div id="taxProgressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 0%; font-weight: bold; line-height: 25px;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                                    </div>
                                    <small class="text-muted mt-2 d-block" id="taxProgressCounter">Preparing calculation parameters...</small>
                                </div>
                            `,
                            allowOutsideClick: false,
                            showConfirmButton: false
                        });

                        // Start polling progress
                        progressInterval = setInterval(() => {
                            axios.get("{{ route('tax-calculate.progress') }}")
                                .then(res => {
                                    const data = res.data;
                                    if (data && data.total > 0) {
                                        const percentage = Math.round((data.processed / data.total) * 100);
                                        $('#taxProgressBar').css('width', percentage + '%').attr('aria-valuenow', percentage).text(percentage + '%');
                                        $('#taxProgressCounter').text(`Processed ${data.processed} of ${data.total} employees`);
                                        if (data.status === 'completed') {
                                            clearInterval(progressInterval);
                                        } else if (data.status === 'failed') {
                                            clearInterval(progressInterval);
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Calculation Failed',
                                                text: data.error || 'Failed to process calculations.'
                                            });
                                        }
                                    }
                                })
                                .catch(err => {
                                    console.error('Error fetching progress:', err);
                                });
                        }, 800);

                        axios.post("{{ route('tax-calculate.calculate') }}")
                            .then(response => {
                                clearInterval(progressInterval);
                                Swal.close();
                                if (response.data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Calculated!',
                                        text: response.data.message,
                                        timer: 2000,
                                        showConfirmButton: false
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Calculation Failed',
                                        text: response.data.message
                                    });
                                }
                            })
                            .catch(error => {
                                clearInterval(progressInterval);
                                Swal.close();
                                const msg = error.response?.data?.message || 'Failed to trigger tax calculation process.';
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: msg
                                });
                            });
                    }
                });
            });
        });
    </script>
@endsection
