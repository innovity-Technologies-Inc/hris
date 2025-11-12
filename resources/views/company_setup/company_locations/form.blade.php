@extends('structure.master')
@section('content')
    {{--    Form --}}

    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">{{ isset($company_location) ? 'Edit' : 'Add' }} Company Branch</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form
                                action="{{ isset($company_location) ? route('company_locations.update', $company_location->id) : route('company_locations.store') }}"
                                method="post">
                                @csrf
                                @if (isset($company_location))
                                    @method('PUT')
                                @endif

                                <div class="row">

                                    <div class="col-lg-12 mb-2">
                                        <label for="company_id" class="form-label">Company <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select select2_list" name="company_id" id="company_id" required>
                                            <option value="">Choose Company</option>
                                            @foreach ($companies as $item)
                                                <option value="{{ $item->id }}"
                                                    @if (isset($company_location) && $company_location->company_id == $item->id) selected @endif>
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('company_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-lg-12 mb-2">
                                        <label for="name" class="form-label">Branch Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="name" class="form-control" name="name"
                                            placeholder="Enter Branch Name"
                                            value="{{ isset($company_location) ? $company_location->name : old('name') }}"
                                            required maxlength="255">
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-lg-12 mb-2">
                                        <label for="location_address" class="form-label">Location Address <span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="location_address" class="form-control"
                                            name="location_address" placeholder="Enter Location Address"
                                            value="{{ isset($company_location) ? $company_location->location_address : old('location_address') }}"
                                            required maxlength="255">
                                        @error('location_address')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-lg-6 mb-2">
                                        <label for="state" class="form-label">State</label>
                                        <input type="text" id="state" class="form-control" name="state"
                                            placeholder="Enter State"
                                            value="{{ isset($company_location) ? $company_location->state : old('state') }}"
                                            maxlength="255">
                                        @error('state')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-lg-6 mb-2">
                                        <label for="division" class="form-label">Division</label>
                                        <input type="text" id="division" class="form-control" name="division"
                                            placeholder="Enter Division"
                                            value="{{ isset($company_location) ? $company_location->division : old('division') }}"
                                            maxlength="255">
                                        @error('division')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-lg-6 mb-2">
                                        <label for="city" class="form-label">City</label>
                                        <input type="text" id="city" class="form-control" name="city"
                                            placeholder="Enter City"
                                            value="{{ isset($company_location) ? $company_location->city : old('city') }}"
                                            maxlength="255">
                                        @error('city')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-lg-6 mb-2">
                                        <label for="country" class="form-label">Country</label>
                                        <input type="text" id="country" class="form-control" name="country"
                                            placeholder="Enter Country"
                                            value="{{ isset($company_location) ? $company_location->country : old('country') }}"
                                            maxlength="255">
                                        @error('country')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-lg-12 mb-2">
                                        <label for="example-select" class="form-label">Status</label>
                                        <select class="form-select" name="status">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">Submit</button>

                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
