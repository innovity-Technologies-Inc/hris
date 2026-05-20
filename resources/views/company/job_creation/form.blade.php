@extends('structure.master')

@section('content')
    {{-- Job Creation Form --}}

    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">{{ isset($job_creation) ? 'Edit' : 'Add' }} Job Creation</h5>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form
                                action="{{ isset($job_creation) ? route('job_creations.update', $job_creation->id) : route('job_creations.store') }}"
                                method="post">
                                @csrf
                                @if (isset($job_creation))
                                    @method('PUT')
                                @endif

                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label for="designation_id" class="form-label">Designation <span class="text-danger">*</span></label>
                                        <select id="designation_id" class="form-select select2_list" name="designation_id" required>
                                            <option value="">Select Designation</option>
                                            @foreach ($designations as $designation)
                                                <option value="{{ $designation->id }}"
                                                    @if (isset($job_creation) && $job_creation->designation_id == $designation->id) selected @endif>
                                                    {{ $designation->company_designation }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('designation_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <label for="department_id" class="form-label">Department <span class="text-danger">*</span></label>
                                        <select id="department_id" class="form-select select2_list" name="department_id" required>
                                            <option value="">Select Department</option>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->id }}"
                                                    @if (isset($job_creation) && $job_creation->department_id == $department->id) selected @endif>
                                                    {{ $department->department_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('department_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label for="job_ind" class="form-label">Job Ind. <span class="text-danger">*</span></label>
                                        <input type="text" id="job_ind" class="form-control" name="job_ind"
                                            placeholder="Enter Job Indicator"
                                            value="{{ isset($job_creation) ? $job_creation->job_ind : old('job_ind') }}" required maxlength="255">
                                        @error('job_ind')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label for="display_designation" class="form-label">Display Designation <span class="text-danger">*</span></label>
                                        <input type="text" id="display_designation" class="form-control" name="display_designation"
                                            placeholder="Enter Display Designation"
                                            value="{{ isset($job_creation) ? $job_creation->display_designation : old('display_designation') }}" required maxlength="255">
                                        @error('display_designation')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label for="display_serial" class="form-label">Display Serial <span class="text-danger">*</span></label>
                                        <input type="text" id="display_serial" class="form-control" name="display_serial"
                                            placeholder="Enter Display Serial"
                                            value="{{ isset($job_creation) ? $job_creation->display_serial : old('display_serial') }}" required maxlength="50">
                                        @error('display_serial')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-2">
                                        <label for="remarks" class="form-label">Remarks</label>
                                        <textarea id="remarks" class="form-control" name="remarks" maxlength="500">{{ isset($job_creation) ? $job_creation->remarks : old('remarks') }}</textarea>
                                        @error('remarks')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-2">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-select" name="status">
                                            <option value="active" @if (isset($job_creation) && $job_creation->status == 'active') selected @endif>Active</option>
                                            <option value="inactive" @if (isset($job_creation) && $job_creation->status == 'inactive') selected @endif>Inactive</option>
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

