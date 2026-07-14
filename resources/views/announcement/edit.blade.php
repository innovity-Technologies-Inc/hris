@extends('structure.master')

@section('content')
    <div class="row">
        <div class="col-xl-8 offset-xl-2">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-transparent border-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                    <h4 class="card-title fw-bold text-primary mb-0">Edit Announcement</h4>
                    <a href="{{ route('announcements.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i style="height: 12px; width: 12px;" data-feather="arrow-left"></i> Back to List
                    </a>
                </div>

                <div class="card-body p-4">
                    <form id="announcementForm" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label fw-semibold">Announcement Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control" placeholder="Enter announcement title" value="{{ $announcement->title }}" required>
                        </div>

                        <!-- Content (Summernote) -->
                        <div class="mb-3">
                            <label for="content_editor" class="form-label fw-semibold">Content <span class="text-danger">*</span></label>
                            <textarea name="content" id="content_editor" class="form-control" required>{!! $announcement->content !!}</textarea>
                        </div>

                        <!-- Scopes / Related Fields -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="company_id" class="form-label fw-semibold">Target Company</label>
                                <select name="company_id" id="company_id" class="form-select">
                                    <option value="">Global (All Companies)</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ $announcement->company_id == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="branch_id" class="form-label fw-semibold">Target Branch</label>
                                <select name="branch_id" id="branch_id" class="form-select">
                                    <option value="">Global (All Branches)</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ $announcement->branch_id == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="department_id" class="form-label fw-semibold">Target Department</label>
                                <select name="department_id" id="department_id" class="form-select">
                                    <option value="">Global (All Departments)</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ $announcement->department_id == $dept->id ? 'selected' : '' }}>{{ $dept->department_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Current Attachment -->
                        @if($announcement->attachment_path)
                            <div class="mb-3 p-3 bg-light border rounded">
                                <span class="fw-semibold d-block mb-1">Current Attachment:</span>
                                <a href="{{ Storage::url($announcement->attachment_path) }}" target="_blank" class="text-primary d-inline-flex align-items-center gap-1">
                                    <i style="height: 14px; width: 14px;" data-feather="file"></i> View Current File
                                </a>
                            </div>
                        @endif

                        <!-- Attachment -->
                        <div class="mb-4">
                            <label for="attachment" class="form-label fw-semibold">Upload New Attachment <span class="text-muted">(Optional - leaves existing if empty)</span></label>
                            <input type="file" name="attachment" id="attachment" class="form-control">
                        </div>

                        <!-- Actions -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">Update Announcement</button>
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
        // Initialize Summernote
        $('#content_editor').summernote({
            placeholder: 'Write announcement content here...',
            tabsize: 2,
            height: 250,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear', 'fontsize', 'color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture', 'video', 'table']],
                ['view', ['codeview', 'help']]
            ]
        });

        // Form Submit
        document.getElementById('announcementForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate Summernote content
            if ($('#content_editor').summernote('isEmpty')) {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please write some content for the announcement.'
                });
                return;
            }

            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...';

            $('.alert-danger').remove();

            const formData = new FormData(this);
            // Append _method parameter because PHP doesn't support raw PUT file uploads
            formData.append('_method', 'PUT');

            axios.post('{{ route('announcements.update', $announcement->id) }}', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(response => {
                if (response.data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.data.message
                    }).then(() => {
                        window.location.href = response.data.redirect;
                    });
                } else {
                    throw new Error(response.data.message || 'Failed to update form');
                }
            })
            .catch(error => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Update Announcement';

                let errorMsg = 'Something went wrong. Please try again.';
                if (error.response && error.response.data) {
                    if (error.response.data.errors) {
                        errorMsg = Object.values(error.response.data.errors).flat().join('<br>');
                    } else if (error.response.data.message) {
                        errorMsg = error.response.data.message;
                    }
                }

                const alertHtml = `<div class="alert alert-danger mt-3">${errorMsg}</div>`;
                $('#announcementForm').prepend(alertHtml);
                window.scrollTo(0, 0);
            });
        });
    });
</script>
@endpush
