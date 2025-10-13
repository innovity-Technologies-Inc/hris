@extends('structure.master')

@section('content')
    {{-- Form for Designation --}}

    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">{{ isset($designation) ? 'Edit' : 'Add' }} Designation</h5>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form
                                action="{{ isset($designation) ? route('designations.update', $designation->id) : route('designations.store') }}"
                                method="post">
                                @csrf
                                @if (isset($designation))
                                    @method('PUT')
                                @endif

                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label for="company_id" class="form-label">Company <span class="text-danger">*</span></label>
                                        <select id="company_id" class="form-select select2_list" name="company_id" required>
                                            <option value="">Select Company</option>
                                            @foreach ($companies as $company)
                                                <option value="{{ $company->id }}"
                                                    @if (isset($designation) && $designation->company_id == $company->id) selected @endif>
                                                    {{ $company->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('company_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                   <div class="col-md-6 mb-2">
                                        <label for="location_id" class="form-label">Location <span
                                                class="text-danger">*</span></label>
                                        <select id="location_id" class="form-select select2_list" name="location_id"
                                            required>
                                            <option value="">Select Location</option>
                                            @foreach ($locations as $location)
                                                <option value="{{ $location->id }}"
                                                    {{ isset($designation) && $designation->location_id == $location->id ? 'selected' : '' }}>
                                                    {{ $location->unit_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('location_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <label for="division_id" class="form-label">Division <span class="text-danger">*</span></label>
                                        <select id="division_id" class="form-select select2_list" name="division_id" required>
                                            <option value="">Select Division</option>
                                            @foreach ($divisions as $division)
                                                <option value="{{ $division->id }}"
                                                    @if (isset($designation) && $designation->division_id == $division->id) selected @endif>
                                                    {{ $division->division_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('division_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <label for="designation_level" class="form-label">Designation Level <span class="text-danger">*</span></label>
                                        <input type="text" id="designation_level" class="form-control" name="designation_level"
                                            placeholder="Enter Designation Level"
                                            value="{{ isset($designation) ? $designation->designation_level : old('designation_level') }}"
                                            required maxlength="255">
                                        @error('designation_level')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <label for="company_designation" class="form-label">Company Designation <span class="text-danger">*</span></label>
                                        <input type="text" id="company_designation" class="form-control" name="company_designation"
                                            placeholder="Enter Company Designation"
                                            value="{{ isset($designation) ? $designation->company_designation : old('company_designation') }}"
                                            required maxlength="255">
                                        @error('company_designation')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-select" name="status">
                                            <option value="active" @if (isset($designation) && $designation->status == 'active') selected @endif>
                                                Active
                                            </option>
                                            <option value="inactive" @if (isset($designation) && $designation->status == 'inactive') selected @endif>
                                                Inactive
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
