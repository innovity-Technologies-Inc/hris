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
            background: var(--primary-color);
            color: white;
            padding: 15px 20px;
            border-radius: 8px 8px 0 0;
            margin: -20px -20px 20px -20px;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }

        .submit-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
        }
        
        .via-point-badge {
            font-size: 0.9rem;
            border-radius: 20px;
        }
    </style>

    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 text-white">
                            <i data-feather="map" class="me-2"></i>
                            {{ isset($routeMap) ? 'Edit' : 'New' }} Route Map
                        </h5>
                        <a href="{{ route('transport.route_maps.index') }}" class="btn btn-light btn-sm">
                            <i data-feather="arrow-left" style="width: 14px; height: 14px;"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form
                        action="{{ isset($routeMap) ? route('transport.route_maps.update', $routeMap->id) : route('transport.route_maps.store') }}"
                        method="post">
                        @csrf
                        @if (isset($routeMap))
                            @method('PUT')
                        @endif

                        {{-- Route Basic Information --}}
                        <div class="form-card mb-4">
                            <div class="section-header">
                                <h6 class="mb-0 text-white">
                                    <i data-feather="info" class="me-2" style="width: 18px; height: 18px;"></i>
                                    Route Basic Information
                                </h6>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="route_name" class="form-label">
                                        Route Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="route_name"
                                        class="form-control @error('route_name') is-invalid @enderror" name="route_name"
                                        placeholder="e.g., Route A - Banani to Motijheel"
                                        value="{{ isset($routeMap) ? $routeMap->route_name : old('route_name') }}"
                                        required>
                                    @error('route_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="start_point" class="form-label">
                                        Start Point <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="start_point"
                                        class="form-control @error('start_point') is-invalid @enderror" name="start_point"
                                        placeholder="e.g., Banani Kakoli"
                                        value="{{ isset($routeMap) ? $routeMap->start_point : old('start_point') }}"
                                        required>
                                    @error('start_point')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="end_point" class="form-label">
                                        End Point <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="end_point"
                                        class="form-control @error('end_point') is-invalid @enderror" name="end_point"
                                        placeholder="e.g., Motijheel C/A"
                                        value="{{ isset($routeMap) ? $routeMap->end_point : old('end_point') }}"
                                        required>
                                    @error('end_point')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Stopovers & Route Details --}}
                        <div class="form-card mb-4">
                            <div class="section-header">
                                <h6 class="mb-0 text-white">
                                    <i data-feather="map-pin" class="me-2" style="width: 18px; height: 18px;"></i>
                                    Stopovers & Route Details
                                </h6>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">
                                        Via Points / Stopovers (Optional)
                                    </label>
                                    <div class="input-group mb-2">
                                        <input type="text" id="via_point_input" class="form-control" placeholder="Add via point, e.g., Mohakhali">
                                        <button class="btn btn-warning" type="button" id="add_via_point_btn">
                                            <i class="mdi mdi-plus"></i> Add
                                        </button>
                                    </div>
                                    <div id="via_points_list" class="d-flex flex-wrap gap-2 mt-2">
                                        @if(isset($routeMap) && is_array($routeMap->via_points))
                                            @foreach($routeMap->via_points as $point)
                                                <span class="badge bg-warning text-dark d-flex align-items-center gap-1 p-2 via-point-badge" data-value="{{ $point }}">
                                                    {{ $point }}
                                                    <i class="mdi mdi-close text-danger remove-via-point" style="cursor: pointer;"></i>
                                                </span>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div id="via_points_hidden_inputs">
                                        @if(isset($routeMap) && is_array($routeMap->via_points))
                                            @foreach($routeMap->via_points as $point)
                                                <input type="hidden" name="via_points[]" value="{{ $point }}">
                                            @endforeach
                                        @endif
                                    </div>
                                    @error('via_points')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <label for="route_details" class="form-label">
                                        Route Description
                                    </label>
                                    <textarea name="route_details" id="route_details" class="form-control @error('route_details') is-invalid @enderror"
                                        rows="3" placeholder="Provide additional route description or directions">{{ isset($routeMap) ? $routeMap->route_details : old('route_details') }}</textarea>
                                    @error('route_details')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="status" class="form-label">
                                        Status <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('status') is-invalid @enderror"
                                        name="status" id="status" required>
                                        <option value="Active" {{ (isset($routeMap) && $routeMap->status == 'Active') || old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                        <option value="Inactive" {{ (isset($routeMap) && $routeMap->status == 'Inactive') || old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="d-flex justify-content-end gap-2 submit-section">
                            <a href="{{ route('transport.route_maps.index') }}" class="btn btn-secondary px-4">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                {{ isset($routeMap) ? 'Update' : 'Save' }} Route Map
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            function addViaPoint() {
                const input = $('#via_point_input');
                const value = input.val().trim();
                if (!value) return;

                // Check if already exists to prevent duplicates
                let exists = false;
                $('#via_points_list .via-point-badge').each(function() {
                    if ($(this).attr('data-value').toLowerCase() === value.toLowerCase()) {
                        exists = true;
                    }
                });

                if (exists) {
                    input.val('');
                    return;
                }

                // Append badge
                $('#via_points_list').append(`
                    <span class="badge bg-warning text-dark d-flex align-items-center gap-1 p-2 via-point-badge" data-value="${value}">
                        ${value}
                        <i class="mdi mdi-close text-danger remove-via-point" style="cursor: pointer;"></i>
                    </span>
                `);

                // Append hidden input
                $('#via_points_hidden_inputs').append(`
                    <input type="hidden" name="via_points[]" value="${value}">
                `);

                input.val('');
            }

            $('#add_via_point_btn').on('click', function() {
                addViaPoint();
            });

            $('#via_point_input').on('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    addViaPoint();
                }
            });

            $(document).on('click', '.remove-via-point', function() {
                const badge = $(this).closest('.via-point-badge');
                const value = badge.attr('data-value');
                
                // Remove hidden input
                $(`#via_points_hidden_inputs input[value="${value}"]`).remove();
                // Remove badge
                badge.remove();
            });
        });
    </script>
@endpush
