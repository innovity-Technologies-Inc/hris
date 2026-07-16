@extends('structure.master')

@section('content')
    <div class="py-4" style="max-width: 1000px; margin: 0 auto;">
        <!-- Main Card -->
        <div class="card shadow-lg border-0 rounded-4">

            <!-- Form Body -->
            <div class="card-body p-4 p-md-5">
                <form id="googleMapApiForm" action="{{ route('setting.google_map_api.save') }}" method="POST">
                    @csrf

                    <!-- API Keys Section -->
                    <div class="mb-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3 d-inline-flex align-items-center justify-content-center">
                                <i class="bi bi-key-fill text-primary fs-4"></i>
                            </div>
                            <h2 class="fs-4 fw-bold text-dark mb-0">Google Map API Management</h2>
                        </div>

                        <!-- Google Maps API Key -->
                        <div class="card border shadow-sm mb-4">
                            <div class="card-body p-4">
                                <label for="googleMapsApiKey" class="form-label fw-semibold text-dark mb-3 d-flex align-items-center">
                                    <i class="bi bi-geo-alt-fill text-danger me-2 fs-5"></i>
                                    <span>Google Maps API Key</span>
                                    <span class="badge bg-danger ms-2">Required</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-shield-lock text-primary fs-5"></i>
                                    </span>
                                    <input type="password" class="form-control form-control-lg border-start-0" id="googleMapsApiKey"
                                        name="google_maps_api_key" placeholder="Enter your Google Maps API Key"
                                        value="{{ isset($apiKey) ? $apiKey->google_maps_api_key : old('google_maps_api_key') }}"
                                        required>
                                    <span class="input-group-text border-start-0">
                                        <i class="fas fa-eye password-toggle"></i>
                                    </span>
                                </div>
                                <div class="form-text mt-2">
                                    <i class="bi bi-lightbulb text-warning me-1"></i>
                                    This API key will be used for Google Maps integration across your application
                                </div>
                                <div class="alert alert-info mt-3 d-flex align-items-start" role="alert">
                                    <i class="bi bi-info-circle-fill me-2 mt-1"></i>
                                    <div>
                                        <strong>How to get your API Key:</strong>
                                        <ol class="mb-0 mt-2 ps-3">
                                            <li>Go to <a href="https://console.cloud.google.com/" target="_blank" class="alert-link">Google Cloud Console</a></li>
                                            <li>Create a new project or select an existing one</li>
                                            <li>Enable the "Maps JavaScript API, Directions API ,Distance Matrix API , Places API (New) , Routes API , Places API ,Geolocation API ,Geocoding API , Distance Matrix API"</li>
                                            <li>Navigate to "Credentials" and create an API key</li>
                                            <li>Copy and paste the API key above</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end gap-3">
                        <a href="{{ route('dashboard.index') }}" class="btn btn-lg btn-outline-secondary px-4 px-md-5 rounded-3">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-lg btn-dark px-4 px-md-5 rounded-3 shadow" id="btnSaveApiKey">
                            <i class="bi bi-check-circle-fill me-2"></i>Save API Key
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer Info -->
        <div class="text-center mt-4 text-muted small">
            <i class="bi bi-info-circle me-1"></i>
            Changes will take effect immediately after saving
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('googleMapApiForm');
    const apiKeyInput = document.getElementById('googleMapsApiKey');



    // Input validation styling
    apiKeyInput.addEventListener('blur', function() {
        if (this.value.trim() === '') {
            this.classList.add('border-danger');
            this.classList.remove('border-success');
        } else {
            this.classList.add('border-success');
            this.classList.remove('border-danger');
        }
    });

    // Axios Form Submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const keyVal = apiKeyInput.value.trim();
        if (keyVal === '') {
            apiKeyInput.classList.add('border-danger');
            apiKeyInput.focus();
            toastr.error('Please enter a Google Maps API Key');
            return;
        }

        const btnSave = document.getElementById('btnSaveApiKey');
        btnSave.disabled = true;
        btnSave.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Saving...';

        axios.post(form.action, {
            google_maps_api_key: keyVal
        })
        .then(response => {
            btnSave.disabled = false;
            btnSave.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i>Save API Key';
            if (response.data.success) {
                toastr.success(response.data.message);
                apiKeyInput.classList.remove('border-danger');
                apiKeyInput.classList.add('border-success');
            } else {
                toastr.error(response.data.message || 'Failed to save Google Maps API Key.');
            }
        })
        .catch(error => {
            btnSave.disabled = false;
            btnSave.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i>Save API Key';
            if (error.response && error.response.status === 422) {
                const errors = error.response.data.errors;
                let errorMsg = '';
                Object.keys(errors).forEach(key => {
                    errorMsg += `${errors[key][0]}<br>`;
                });
                toastr.error(errorMsg);
            } else {
                console.error(error);
                toastr.error('An error occurred while saving.');
            }
        });
    });
});
</script>
@endpush
