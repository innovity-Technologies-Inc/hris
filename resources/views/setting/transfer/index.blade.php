@extends('structure.master')

@section('content')
<div class="row">
    <div class="col-xl-6">
        <div class="card glass-card">
            <div class="card-header">
                <h5 class="card-title mb-0 text-white">Transfer Level Settings</h5>
            </div>
            <div class="card-body">
                <form id="transferSettingForm">
                    <div class="mb-4">
                        <label class="form-label text-white-50">Employee Transfer Request Level</label>
                        <select name="employee_transfer_level" class="form-select bg-dark text-white border-secondary">
                            <option value="company" {{ $setting->employee_transfer_level == 'company' ? 'selected' : '' }}>Company</option>
                            <option value="business_unit" {{ $setting->employee_transfer_level == 'business_unit' ? 'selected' : '' }}>Business Unit / Branch</option>
                            <option value="division" {{ $setting->employee_transfer_level == 'division' ? 'selected' : '' }}>Division</option>
                            <option value="department" {{ $setting->employee_transfer_level == 'department' ? 'selected' : '' }}>Department</option>
                            <option value="section" {{ $setting->employee_transfer_level == 'section' ? 'selected' : '' }}>Section</option>
                            <option value="designation" {{ $setting->employee_transfer_level == 'designation' ? 'selected' : '' }}>Designation</option>
                        </select>
                        <small class="text-info mt-1 d-block">Defines the minimum organizational level an Employee can select in their transfer application.</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-white-50">Supervisor Transfer Request Level</label>
                        <select name="supervisor_transfer_level" class="form-select bg-dark text-white border-secondary">
                            <option value="company" {{ $setting->supervisor_transfer_level == 'company' ? 'selected' : '' }}>Company</option>
                            <option value="business_unit" {{ $setting->supervisor_transfer_level == 'business_unit' ? 'selected' : '' }}>Business Unit / Branch</option>
                            <option value="division" {{ $setting->supervisor_transfer_level == 'division' ? 'selected' : '' }}>Division</option>
                            <option value="department" {{ $setting->supervisor_transfer_level == 'department' ? 'selected' : '' }}>Department</option>
                            <option value="section" {{ $setting->supervisor_transfer_level == 'section' ? 'selected' : '' }}>Section</option>
                            <option value="designation" {{ $setting->supervisor_transfer_level == 'designation' ? 'selected' : '' }}>Designation</option>
                        </select>
                        <small class="text-info mt-1 d-block">Defines the minimum organizational level for Managers/Admins when creating transfers for others.</small>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i data-feather="save" class="me-1"></i> Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.getElementById('transferSettingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());

    axios.post('{{ route('setting.transfer.update') }}', data)
        .then(res => {
            if (res.data.success) {
                Swal.fire('Success!', res.data.message, 'success');
            }
        })
        .catch(err => {
            Swal.fire('Error!', 'Failed to update settings.', 'error');
        });
});
</script>
@endpush
