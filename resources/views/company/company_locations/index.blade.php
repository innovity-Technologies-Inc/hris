@extends('structure.master')

@section('content')
    {{-- List of Company Locations --}}
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Company Branches</h5>
                    @can('company-branches.create')
                    <button type="button" class="btn btn-warning btn-sm rounded-pill px-3" id="btnCreateLocation">
                        <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
                    </button>
                    @endcan
                </div>
                {{-- Search Filter Form --}}
                <form id="filterForm">
                    <div class="row mb-1 mt-2 mx-4">
                        <div class="col-12">
                            <div class="input-group input-group-md">
                                <input type="text" class="form-control border-end-0" id="keywordSearch" name="keyword"
                                    placeholder="Search branches by keyword" aria-label="Keyword Search">
                                <span class="input-group-text border-start-0 input-group-bg">
                                    <i class="mdi mdi-magnify text-muted"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="card-body" id="search-result">
                    @include('company.company_locations.search_results')
                </div>
            </div><!-- end card -->
        </div>
    </div><!-- end row -->

    <!-- Add/Edit Modal -->
    <div class="modal fade" id="companyLocationModal" tabindex="-1" aria-labelledby="companyLocationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header text-white border-0" style="background-color: #974063;">
                    <h5 class="modal-title" id="companyLocationModalLabel">Add Company Branch</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="companyLocationForm">
                    @csrf
                    <input type="hidden" name="id" id="locationId">
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-lg-12 mb-3">
                                <label for="modal_company_id" class="form-label fw-semibold">Company <span class="text-danger">*</span></label>
                                <select class="form-select select2_list" name="company_id" id="modal_company_id" required>
                                    <option value="">Choose Company</option>
                                    @foreach ($companies as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-12 mb-3">
                                <label for="modal_name" class="form-label fw-semibold">Branch Name <span class="text-danger">*</span></label>
                                <input type="text" id="modal_name" class="form-control" name="name" placeholder="Enter Branch Name" required maxlength="255">
                            </div>

                            <div class="col-lg-12 mb-3">
                                <label for="location_address" class="form-label fw-semibold">Location Address <span class="text-danger">*</span></label>
                                <input type="text" id="location_address" class="form-control" name="location_address" placeholder="Search Location on Google Maps..." required maxlength="255">
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="latitude" class="form-label fw-semibold">Latitude</label>
                                <input type="text" id="latitude" class="form-control" name="latitude" placeholder="Latitude" readonly>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="longitude" class="form-label fw-semibold">Longitude</label>
                                <input type="text" id="longitude" class="form-control" name="longitude" placeholder="Longitude" readonly>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="state" class="form-label fw-semibold">State</label>
                                <input type="text" id="state" class="form-control" name="state" placeholder="Enter State" maxlength="255">
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="division" class="form-label fw-semibold">Division</label>
                                <input type="text" id="division" class="form-control" name="division" placeholder="Enter Division" maxlength="255">
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="city" class="form-label fw-semibold">City</label>
                                <input type="text" id="city" class="form-control" name="city" placeholder="Enter City" maxlength="255">
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="country" class="form-label fw-semibold">Country</label>
                                <input type="text" id="country" class="form-control" name="country" placeholder="Enter Country" maxlength="255">
                            </div>

                            <div class="col-lg-12 mb-0">
                                <label for="modal_status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <select class="form-select" name="status" id="modal_status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn rounded-pill px-4 text-white" id="btnSaveLocation" style="background-color: #974063;">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&libraries=places&callback=initAutocomplete" async defer></script>
<script>
let autocomplete;

function initAutocomplete() {
    const input = document.getElementById('location_address');
    if (!input) {
        document.addEventListener('DOMContentLoaded', initAutocomplete);
        return;
    }
    if (typeof google === 'undefined' || !google.maps || !google.maps.places) return;
    
    autocomplete = new google.maps.places.Autocomplete(input, {
        componentRestrictions: { country: 'bd' },
        fields: ['address_components', 'geometry', 'formatted_address']
    });

    autocomplete.addListener('place_changed', function() {
        const place = autocomplete.getPlace();
        if (!place.geometry) {
            return;
        }

        let streetAddress = place.formatted_address || '';
        let city = '';
        let state = '';
        let division = '';
        let country = '';
        let lat = place.geometry.location.lat();
        let lng = place.geometry.location.lng();

        if (place.address_components) {
            place.address_components.forEach(component => {
                const types = component.types;
                if (types.includes('locality')) {
                    city = component.long_name;
                } else if (types.includes('administrative_area_level_2')) {
                    if (!city) city = component.long_name;
                } else if (types.includes('administrative_area_level_1')) {
                    division = component.long_name;
                    state = component.long_name;
                } else if (types.includes('country')) {
                    country = component.long_name;
                }
            });
        }

        document.getElementById('location_address').value = streetAddress;
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        if (city) document.getElementById('city').value = city;
        if (state) document.getElementById('state').value = state;
        if (division) document.getElementById('division').value = division;
        if (country) document.getElementById('country').value = country;
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('search-result');
    const searchInput = document.getElementById('keywordSearch');
    const modalEl = document.getElementById('companyLocationModal');
    const modal = new bootstrap.Modal(modalEl);
    const form = document.getElementById('companyLocationForm');

    // Re-initialize select2 with dropdownParent to fix search input focus in bootstrap modal
    $('#modal_company_id').select2({
        width: '100%',
        theme: 'bootstrap-5',
        dropdownParent: $('#companyLocationModal'),
        placeholder: "Choose Company",
        allowClear: true
    });

    // Load locations via Axios (handles pagination clicks as well)
    function fetchLocations(url = "{{ route('company_locations.index') }}") {
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

    // Intercept pagination clicks inside Axios loaded container
    container.addEventListener('click', function(e) {
        const pageLink = e.target.closest('.pagination a');
        if (pageLink) {
            e.preventDefault();
            fetchLocations(pageLink.getAttribute('href'));
        }
    });

    // Search input binding with debounce
    let searchTimer;
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => fetchLocations(), 500);
    });

    // Show Create Modal
    const btnCreate = document.getElementById('btnCreateLocation');
    if (btnCreate) {
        btnCreate.addEventListener('click', () => {
            form.reset();
            $('#modal_company_id').val('').trigger('change');
            document.getElementById('locationId').value = '';
            document.getElementById('latitude').value = '';
            document.getElementById('longitude').value = '';
            document.getElementById('companyLocationModalLabel').innerText = 'Add Company Branch';
            modal.show();
        });
    }

    function bindActionButtons() {
        // Edit Action
        document.querySelectorAll('.edit-location').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                axios.get(`/company-setup/company-locations/${id}/edit`)
                    .then(response => {
                        const data = response.data;
                        document.getElementById('locationId').value = data.id;
                        $('#modal_company_id').val(data.company_id).trigger('change');
                        document.getElementById('modal_name').value = data.name;
                        document.getElementById('location_address').value = data.location_address;
                        document.getElementById('latitude').value = data.latitude || '';
                        document.getElementById('longitude').value = data.longitude || '';
                        document.getElementById('state').value = data.state || '';
                        document.getElementById('division').value = data.division || '';
                        document.getElementById('city').value = data.city || '';
                        document.getElementById('country').value = data.country || '';
                        document.getElementById('modal_status').value = data.status;

                        document.getElementById('companyLocationModalLabel').innerText = 'Edit Company Branch';
                        modal.show();
                    })
                    .catch(err => {
                        console.error(err);
                        toastr.error('Failed to load company location details.');
                    });
            });
        });

        // Delete Action
        document.querySelectorAll('.delete-location').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                if (confirm('Are you sure you want to delete this company branch/location?')) {
                    axios.delete(`/company-setup/company-locations/${id}`)
                        .then(response => {
                            if (response.data.success) {
                                toastr.success(response.data.message);
                                fetchLocations();
                            } else {
                                toastr.error(response.data.message || 'Failed to delete company location.');
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            toastr.error('An error occurred while deleting the company location.');
                        });
                }
            });
        });
    }

    // Initial binding
    bindActionButtons();

    // Submit Form (Axios Store / Update)
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('locationId').value;
        const url = id ? `/company-setup/company-locations/${id}` : "{{ route('company_locations.store') }}";
        const method = id ? 'PUT' : 'POST';

        const formData = {
            company_id: document.getElementById('modal_company_id').value,
            name: document.getElementById('modal_name').value,
            location_address: document.getElementById('location_address').value,
            latitude: document.getElementById('latitude').value,
            longitude: document.getElementById('longitude').value,
            state: document.getElementById('state').value,
            division: document.getElementById('division').value,
            city: document.getElementById('city').value,
            country: document.getElementById('country').value,
            status: document.getElementById('modal_status').value
        };

        axios({
            method: method,
            url: url,
            data: formData
        })
        .then(response => {
            if (response.data.success) {
                toastr.success(response.data.message);
                modal.hide();
                fetchLocations();
            } else {
                toastr.error(response.data.message || 'Failed to save company location.');
            }
        })
        .catch(error => {
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
