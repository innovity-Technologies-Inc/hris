@extends('structure.master')
@section('content')
    {{--    Form --}}

    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">{{ isset($division) ? 'Edit' : 'Add' }} Division</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form
                                action="{{ isset($division) ? route('divisions.update', $division->id) : route('divisions.store') }}"
                                method="post">
                                @csrf
                                @if (isset($division))
                                    @method('PUT')
                                @endif

                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <label for="name" class="form-label">Division Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="name" class="form-control" name="name"
                                            placeholder="Enter Division Name"
                                            value="{{ isset($division) ? $division->name : old('name') }}" required
                                            maxlength="255">
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="short_name" class="form-label">Short Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="short_name" class="form-control" name="short_name"
                                            placeholder="Enter Short Name"
                                            value="{{ isset($division) ? $division->short_name : old('short_name') }}"
                                            required maxlength="50">
                                        @error('short_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-2">
                                        <label for="company_id" class="form-label">Company <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select select2_list" name="company_id" id="company_id" required>
                                            <option value="">Choose Company</option>
                                            @foreach ($companies as $item)
                                                <option value="{{ $item->id }}"
                                                    @if (isset($division) && $division->company_id == $item->id) selected @endif>
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('company_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    @if(\App\HelperClass::getGeneralSetting()->branch_status == 1)
                                        <div class="col-md-6 mb-2">
                                            <label for="location_id" class="form-label">Branch <span
                                                class="text-danger">*</span></label>
                                            <select class="form-select select2_list" name="location_id" id="location_id">
                                            <option value="">Choose Branch</option>
                                                @if(isset($division))
                                                @foreach ($locations as $item)
                                                    <option value="{{$item->id}}" {{isset($division) && $division->location_id == $item->id ? 'selected' : ''}}>
                                                        {{$item->name}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            @error('location_id')
                                            <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    @endif



                                    <div class="col-md-6 mb-2">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-select" name="status">
                                            <option value="active" @if (isset($division) && $division->status == 'active') selected @endif>Active
                                            </option>
                                            <option value="inactive" @if (isset($division) && $division->status == 'inactive') selected @endif>
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


    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        $(document).ready(function () {

            $('#company_id').on('change', function () {
                let companyId = $(this).val();

                $('#location_id').html('<option value="">Loading...</option>');

                if (companyId) {
                    $.ajax({
                        url: "/get-units/" + companyId,
                        type: "GET",
                        success: function (data) {
                            $('#location_id').empty();
                            $('#location_id').append('<option value="">Select Location</option>');

                            $.each(data, function (key, value) {
                                $('#location_id').append(
                                    '<option value="' + value.id + '">' + value.name + '</option>'
                                );
                            });
                        }
                    });
                } else {
                    $('#location_id').html('<option value="">Select Location</option>');
                }
            });

        });
    </script>

@endsection

