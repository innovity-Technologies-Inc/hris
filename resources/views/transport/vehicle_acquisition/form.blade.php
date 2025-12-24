@extends('structure.master')

@section('content')
    <style>
        .form-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            transition: all 0.3s ease;
        }

        .form-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .section-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 8px 8px 0 0;
            margin: -20px -20px 20px -20px;
        }

        .section-header.success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        .section-header.warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }

        .file-upload-wrapper {
            position: relative;
            margin-top: 10px;
        }

        .file-preview {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 12px;
            background: #f8f9fa;
            border-radius: 6px;
            font-size: 0.875rem;
        }

        .submit-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
        }
    </style>

    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 text-white">
                            <i data-feather="truck" class="me-2"></i>
                            {{ isset($vehicleAcquisition) ? 'Edit' : 'New' }} Vehicle Acquisition Request
                        </h5>
                        <a href="{{ route('transport.vehicle_acquisitions.index') }}" class="btn btn-light btn-sm">
                            <i data-feather="arrow-left" style="width: 14px; height: 14px;"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form
                        action="{{ isset($vehicleAcquisition) ? route('transport.vehicle_acquisitions.update', $vehicleAcquisition->id) : route('transport.vehicle_acquisitions.store') }}"
                        method="post" enctype="multipart/form-data">
                        @csrf
                        @if (isset($vehicleAcquisition))
                            @method('PUT')
                        @endif

                        {{-- Vehicle Basic Information --}}
                        <div class="form-card mb-4">
                            <div class="section-header">
                                <h6 class="mb-0">
                                    <i data-feather="info" style="width: 18px; height: 18px;"></i>
                                    Vehicle Basic Information
                                </h6>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="vehicle_category" class="form-label">
                                        Vehicle Category <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('vehicle_category') is-invalid @enderror"
                                        name="vehicle_category" id="vehicle_category" required>
                                        <option value="">Select Category</option>
                                        @foreach (['Car', 'Bus', 'Micro Bus', 'Truck', 'Bike', 'Van', 'Airplane', 'Ship'] as $category)
                                            <option value="{{ $category }}"
                                                {{ (isset($vehicleAcquisition) && $vehicleAcquisition->vehicle_category == $category) || old('vehicle_category') == $category ? 'selected' : '' }}>
                                                {{ $category }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('vehicle_category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="model_number" class="form-label">
                                        Model Number <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="model_number"
                                        class="form-control @error('model_number') is-invalid @enderror" name="model_number"
                                        placeholder="e.g., Toyota Corolla 2023"
                                        value="{{ isset($vehicleAcquisition) ? $vehicleAcquisition->model_number : old('model_number') }}"
                                        required>
                                    @error('model_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="manufacture_year" class="form-label">
                                        Manufacture Year <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" id="manufacture_year"
                                        class="form-control @error('manufacture_year') is-invalid @enderror"
                                        name="manufacture_year" placeholder="e.g., 2023" min="1900"
                                        max="{{ date('Y') + 1 }}"
                                        value="{{ isset($vehicleAcquisition) ? $vehicleAcquisition->manufacture_year : old('manufacture_year') }}"
                                        required>
                                    @error('manufacture_year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="body_type" class="form-label">Body Type</label>
                                    <input type="text" id="body_type"
                                        class="form-control @error('body_type') is-invalid @enderror" name="body_type"
                                        placeholder="e.g., Sedan, SUV"
                                        value="{{ isset($vehicleAcquisition) ? $vehicleAcquisition->body_type : old('body_type') }}">
                                    @error('body_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="fuel_type" class="form-label">
                                        Fuel Type <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('fuel_type') is-invalid @enderror" name="fuel_type"
                                        id="fuel_type" required>
                                        <option value="">Select Fuel Type</option>
                                        @foreach (['Petrol', 'Diesel', 'CNG', 'Electric'] as $fuel)
                                            <option value="{{ $fuel }}"
                                                {{ (isset($vehicleAcquisition) && $vehicleAcquisition->fuel_type == $fuel) || old('fuel_type') == $fuel ? 'selected' : '' }}>
                                                {{ $fuel }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('fuel_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="engine_capacity" class="form-label">Engine Capacity (CC)</label>
                                    <input type="text" id="engine_capacity"
                                        class="form-control @error('engine_capacity') is-invalid @enderror"
                                        name="engine_capacity" placeholder="e.g., 1500 CC"
                                        value="{{ isset($vehicleAcquisition) ? $vehicleAcquisition->engine_capacity : old('engine_capacity') }}">
                                    @error('engine_capacity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3">
                                    <label for="seating_capacity" class="form-label">Seating Capacity</label>
                                    <input type="number" id="seating_capacity"
                                        class="form-control @error('seating_capacity') is-invalid @enderror"
                                        name="seating_capacity" placeholder="e.g., 5" min="1" max="500"
                                        value="{{ isset($vehicleAcquisition) ? $vehicleAcquisition->seating_capacity : old('seating_capacity') }}">
                                    @error('seating_capacity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="color" class="form-label">Color</label>
                                    <input type="text" id="color"
                                        class="form-control @error('color') is-invalid @enderror" name="color"
                                        placeholder="e.g., White, Black"
                                        value="{{ isset($vehicleAcquisition) ? $vehicleAcquisition->color : old('color') }}">
                                    @error('color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="mileage" class="form-label">Mileage (KM/L)</label>
                                    <input type="number" step="0.01" id="mileage"
                                        class="form-control @error('mileage') is-invalid @enderror" name="mileage"
                                        placeholder="e.g., 15.5" min="0"
                                        value="{{ isset($vehicleAcquisition) ? $vehicleAcquisition->mileage : old('mileage') }}">
                                    @error('mileage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="status" class="form-label">
                                        Status <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('status') is-invalid @enderror" name="status"
                                        id="status" required>
                                        @foreach (['Active', 'Inactive'] as $status)
                                            <option value="{{ $status }}"
                                                {{ (isset($vehicleAcquisition) && $vehicleAcquisition->status == $status) || old('status') == $status || (!isset($vehicleAcquisition) && $status == 'Active') ? 'selected' : '' }}>
                                                {{ $status }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- License & Documents --}}
                        <div class="form-card mb-4">
                            <div class="section-header success">
                                <h6 class="mb-0">
                                    <i data-feather="file-text" style="width: 18px; height: 18px;"></i>
                                    License & Documents
                                </h6>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="license_number" class="form-label">License Number</label>
                                    <input type="text" id="license_number"
                                        class="form-control @error('license_number') is-invalid @enderror"
                                        name="license_number" placeholder="e.g., DHAKA-1234-XY"
                                        value="{{ isset($vehicleAcquisition) ? $vehicleAcquisition->license_number : old('license_number') }}">
                                    @error('license_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="license_document" class="form-label">License Document</label>
                                    <input type="file" id="license_document"
                                        class="form-control @error('license_document') is-invalid @enderror"
                                        name="license_document" accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="text-muted">PDF, JPG, PNG (Max: 5MB)</small>
                                    @if (isset($vehicleAcquisition) && $vehicleAcquisition->license_document)
                                        <div class="file-preview">
                                            <a href="{{ asset('storage/' . $vehicleAcquisition->license_document) }}"
                                                target="_blank" class="btn btn-sm btn-outline-info">
                                                <i data-feather="eye" style="width: 14px; height: 14px;"></i> View Current
                                            </a>
                                        </div>
                                    @endif
                                    @error('license_document')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="vehicle_image" class="form-label">Vehicle Image</label>
                                    <input type="file" id="vehicle_image"
                                        class="form-control @error('vehicle_image') is-invalid @enderror"
                                        name="vehicle_image" accept="image/*">
                                    <small class="text-muted">JPG, PNG, GIF (Max: 5MB)</small>
                                    @if (isset($vehicleAcquisition) && $vehicleAcquisition->vehicle_image)
                                        <div class="file-preview">
                                            <img src="{{ asset('storage/' . $vehicleAcquisition->vehicle_image) }}"
                                                alt="Vehicle" class="rounded" style="max-height: 60px;">
                                        </div>
                                    @endif
                                    @error('vehicle_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Purchase Information --}}
                        <div class="form-card mb-4">
                            <div class="section-header warning">
                                <h6 class="mb-0">
                                    <i data-feather="shopping-cart" style="width: 18px; height: 18px;"></i>
                                    Purchase Information
                                </h6>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="purchase_type" class="form-label">
                                        Purchase Type <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('purchase_type') is-invalid @enderror"
                                        name="purchase_type" id="purchase_type" required>
                                        <option value="">Select Purchase Type</option>
                                        @foreach (['Purchase', 'Lease', 'Rent'] as $type)
                                            <option value="{{ $type }}"
                                                {{ (isset($vehicleAcquisition) && $vehicleAcquisition->purchase_type == $type) || old('purchase_type') == $type ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('purchase_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="purchase_date" class="form-label">Purchase Date</label>
                                    <input type="date" id="purchase_date"
                                        class="form-control @error('purchase_date') is-invalid @enderror"
                                        name="purchase_date"
                                        value="{{ isset($vehicleAcquisition) && $vehicleAcquisition->purchase_date ? \Carbon\Carbon::parse($vehicleAcquisition->purchase_date)->format('Y-m-d') : old('purchase_date') }}">
                                    @error('purchase_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="purchase_price" class="form-label">
                                        Purchase Price ({{ \App\HelperClass::getGeneralSetting()->currency ?? 'Tk' }})
                                    </label>
                                    <input type="number" step="0.01" id="purchase_price"
                                        class="form-control @error('purchase_price') is-invalid @enderror"
                                        name="purchase_price" placeholder="e.g., 2500000" min="0"
                                        value="{{ isset($vehicleAcquisition) ? $vehicleAcquisition->purchase_price : old('purchase_price') }}">
                                    @error('purchase_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="purchase_document" class="form-label">Invoice / Purchase Document</label>
                                    <input type="file" id="purchase_document"
                                        class="form-control @error('purchase_document') is-invalid @enderror"
                                        name="purchase_document" accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="text-muted">PDF, JPG, PNG (Max: 5MB)</small>
                                    @if (isset($vehicleAcquisition) && $vehicleAcquisition->purchase_document)
                                        <div class="file-preview">
                                            <a href="{{ asset('storage/' . $vehicleAcquisition->purchase_document) }}"
                                                target="_blank" class="btn btn-sm btn-outline-info">
                                                <i data-feather="eye" style="width: 14px; height: 14px;"></i> View Current
                                            </a>
                                        </div>
                                    @endif
                                    @error('purchase_document')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="ownership_type" class="form-label">
                                        Ownership Type <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('ownership_type') is-invalid @enderror"
                                        name="ownership_type" id="ownership_type" required>
                                        <option value="">Select Ownership Type</option>
                                        @foreach (['Company-owned', 'Third-party'] as $ownership)
                                            <option value="{{ $ownership }}"
                                                {{ (isset($vehicleAcquisition) && $vehicleAcquisition->ownership_type == $ownership) || old('ownership_type') == $ownership ? 'selected' : '' }}>
                                                {{ $ownership }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('ownership_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6" id="third_party_name_wrapper" style="display: none;">
                                    <label for="third_party_name" class="form-label">
                                        Third Party Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="third_party_name"
                                        class="form-control @error('third_party_name') is-invalid @enderror"
                                        name="third_party_name" placeholder="Enter third party name"
                                        value="{{ isset($vehicleAcquisition) ? $vehicleAcquisition->third_party_name : old('third_party_name') }}">
                                    @error('third_party_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Submit Section --}}
                        <div class="submit-section">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('transport.vehicle_acquisitions.index') }}" class="btn btn-secondary">
                                    <i data-feather="x" style="width: 14px; height: 14px;"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i data-feather="save" style="width: 14px; height: 14px;"></i>
                                    {{ isset($vehicleAcquisition) ? 'Update' : 'Submit' }} Acquisition
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ownershipTypeSelect = document.getElementById('ownership_type');
            const thirdPartyNameWrapper = document.getElementById('third_party_name_wrapper');
            const thirdPartyNameInput = document.getElementById('third_party_name');

            function toggleThirdPartyName() {
                if (ownershipTypeSelect.value === 'Third-party') {
                    thirdPartyNameWrapper.style.display = 'block';
                    thirdPartyNameInput.setAttribute('required', 'required');
                } else {
                    thirdPartyNameWrapper.style.display = 'none';
                    thirdPartyNameInput.removeAttribute('required');
                    thirdPartyNameInput.value = '';
                }
            }

            // Initial check
            toggleThirdPartyName();

            // Listen for changes
            ownershipTypeSelect.addEventListener('change', toggleThirdPartyName);
        });
    </script>
@endsection
