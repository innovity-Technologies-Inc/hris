@extends('structure.master')

@section('content')
    <div class="py-4" style="max-width: 1000px; margin: 0 auto;">
        <!-- Main Card -->
        <div class="card shadow-lg border-0 rounded-4">

            <!-- Card Body -->
            <div class="card-body p-4 p-md-5">

                <!-- Header -->
                <div class="mb-5">
                    <div class="d-flex align-items-center mb-4">
                        <div
                            class="bg-success bg-opacity-10 rounded-circle p-3 me-3 d-inline-flex align-items-center justify-content-center">
                            <i class="bi bi-database-fill-down text-success fs-4"></i>
                        </div>
                        <h2 class="fs-4 fw-bold text-dark mb-0">Database Backup</h2>
                    </div>

                    <!-- Backup Card -->
                    <div class="card border shadow-sm">
                        <div class="card-body p-4">

                            <div class="mb-4">
                                <h5 class="fw-semibold text-dark d-flex align-items-center">
                                    <i class="bi bi-shield-lock-fill text-primary me-2 fs-5"></i>
                                    Secure Database Backup
                                </h5>
                                <p class="text-muted mb-0">
                                    Create an instant backup of your database to keep your data safe and recoverable.
                                </p>
                            </div>

                            <!-- Info Alert -->
                            <div class="alert alert-info d-flex align-items-start" role="alert">
                                <i class="bi bi-info-circle-fill me-2 mt-1"></i>
                                <div>
                                    <strong>What this does:</strong>
                                    <ul class="mb-0 mt-2 ps-3">
                                        <li>Creates a full backup of the current database</li>
                                        <li>Stores the backup securely on the server</li>
                                        <li>Allows recovery in case of accidental data loss</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Warning Alert -->
                            <div class="alert alert-warning d-flex align-items-start" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>
                                <div>
                                    Backup may take a few seconds depending on database size.
                                    Please do not refresh the page during the process.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <!-- Backup Button -->
                <div class="d-flex justify-content-center">
                    <button id="backupBtn"
                            class="btn btn-lg btn-success px-4 px-md-5 rounded-3 shadow">
                        <i class="bi bi-cloud-arrow-down-fill me-2"></i>
                        Create Database Backup
                    </button>
                </div>

                <iframe id="downloadFrame" style="display:none;"></iframe>


            </div>
        </div>

        <!-- Footer Info -->
    </div>

{{--    downloading--}}
    <script>
        document.getElementById('backupBtn').addEventListener('click', function () {
            const downloadUrl = "{{ route('flex_db_dump') }}";
            const btn = this;

            // 1️⃣ Trigger download FIRST
            document.getElementById('downloadFrame').src = downloadUrl;

            // 2️⃣ Let browser register navigation
            setTimeout(() => {

                // Show your global loader here
                if (typeof showLoader === 'function') {
                    showLoader();
                }

                // Disable button
                btn.disabled = true;
                btn.innerHTML = '<i class="bi bi-arrow-repeat spin me-2"></i>Creating Backup...';

            }, 100);

            // 3️⃣ Redirect or reload later
            setTimeout(() => {
                window.location.href = "{{ url()->previous() }}";
            }, 2500);
        });
    </script>

@endsection

