@extends('structure.master')
@section('content')
    {{--    Form --}}

    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">{{ isset($section) ? 'Edit' : 'Add' }} Section</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form
                                action="{{ isset($section) ? route('sections.update', $section->id) : route('sections.store') }}"
                                method="post">
                                @csrf
                                @if (isset($section))
                                    @method('PUT')
                                @endif

                                <div class="row">

                                    <div class="col-md-6 mb-2">
                                        <label for="section_name" class="form-label">Section Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="section_name" class="form-control" name="section_name"
                                               placeholder="Enter Section Name"
                                               value="{{ isset($section) ? $section->section_name : old('section_name') }}"
                                               required maxlength="255">
                                        @error('section_name')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <label for="short_name" class="form-label">Short Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="short_name" class="form-control" name="short_name"
                                               placeholder="Enter Short Name"
                                               value="{{ isset($section) ? $section->short_name : old('short_name') }}"
                                               required maxlength="50">
                                        @error('short_name')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label for="division_id" class="form-label">Division <span
                                                class="text-danger">*</span></label>
                                        <select id="division_id" class="form-select select2_list" name="division_id" required>
                                            <option value="">Select Division</option>
                                            @foreach ($divisions as $division)
                                                <option value="{{ $division->id }}"
                                                    @if (isset($section) && $section->division_id == $division->id) selected @endif>
                                                    {{ $division->division_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('division_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-2">
                                        <label for="department_id" class="form-label">Department <span
                                                class="text-danger">*</span></label>
                                        <select id="department_id" class="form-select select2_list" name="department_id" required>
                                            <option value="">Select Department</option>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->id }}"
                                                        @if (isset($section) && $section->department_id == $department->id) selected @endif>
                                                    {{ $department->department_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('department_id')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>



                                    <div class="col-md-4 mb-2">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-select" name="status">
                                            <option value="active" @if (isset($section) && $section->status == 'active') selected @endif>Active
                                            </option>
                                            <option value="inactive" @if (isset($section) && $section->status == 'inactive') selected @endif>
                                                Inactive</option>
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
