<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
        <h5 class="mb-0 text-primary fw-bold">
            <i class="mdi mdi-folder-multiple me-2"></i> Employee Documents
        </h5>
    </div>
    <div class="card-body p-4">
        
        <!-- Upload Form -->
        @if(auth()->user()->can('employee-management.update') || auth()->user()->employee_id == $employee->id)
        <div class="mb-5 p-4 bg-light rounded">
            <h6 class="fw-bold mb-3">Upload New Documents</h6>
            <form action="{{ route('employee.profile.documents.store', $employee->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="document-inputs-container">
                    <div class="row mb-3 document-input-row align-items-end">
                        <div class="col-md-5">
                            <label class="form-label">Document Title <span class="text-danger">*</span></label>
                            <input type="text" name="documents[0][title]" class="form-control" required placeholder="e.g., Passport, Resume">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">File <span class="text-danger">*</span></label>
                            <input type="file" name="documents[0][file]" class="form-control" required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-success add-document-btn w-100">
                                <i class="mdi mdi-plus"></i> Add
                            </button>
                        </div>
                    </div>
                </div>
                <div class="text-end mt-3">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="mdi mdi-upload me-1"></i> Upload Files
                    </button>
                </div>
            </form>
        </div>
        @endif

        <!-- Document Showcase -->
        <h6 class="fw-bold mb-3">Uploaded Documents</h6>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @forelse($employee->documents as $document)
                <div class="col">
                    <div class="card h-100 border shadow-sm document-card position-relative">
                        <!-- Action buttons (Delete) -->
                        @if(auth()->user()->can('employee-management.delete') || auth()->user()->employee_id == $employee->id)
                            <form action="{{ route('employee.profile.documents.delete', ['id' => $employee->id, 'document_id' => $document->id]) }}" method="POST" class="position-absolute" style="top: 10px; right: 10px; z-index: 10;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger rounded-circle" onclick="return confirm('Are you sure you want to delete this document?')" title="Delete Document">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </form>
                        @endif

                        <div class="card-body text-center d-flex flex-column align-items-center justify-content-center pt-5 pb-4">
                            @php
                                $ext = strtolower($document->file_type);
                                $icon = 'mdi-file-document-outline';
                                $color = 'text-secondary';
                                if(in_array($ext, ['pdf'])) { $icon = 'mdi-file-pdf-box'; $color = 'text-danger'; }
                                elseif(in_array($ext, ['doc', 'docx'])) { $icon = 'mdi-file-word-box'; $color = 'text-primary'; }
                                elseif(in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) { $icon = 'mdi-file-image'; $color = 'text-success'; }
                                elseif(in_array($ext, ['csv', 'xlsx'])) { $icon = 'mdi-file-excel-box'; $color = 'text-success'; }
                            @endphp
                            <i class="mdi {{ $icon }} {{ $color }}" style="font-size: 4rem;"></i>
                            <h6 class="mt-3 fw-bold text-truncate w-100 px-2" title="{{ $document->title }}">{{ $document->title }}</h6>
                            <p class="small text-muted mb-3">{{ strtoupper($ext) }} File &bull; {{ $document->created_at->format('M d, Y') }}</p>
                            
                            <a href="{{ asset($document->file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm px-4 rounded-pill mt-auto">
                                <i class="mdi mdi-eye me-1"></i> View File
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="mdi mdi-folder-open-outline text-muted" style="font-size: 4rem;"></i>
                    <p class="text-muted mt-2">No documents found for this employee.</p>
                </div>
            @endforelse
        </div>

    </div>
</div>

<style>
.document-card {
    transition: transform 0.2s, box-shadow 0.2s;
}
.document-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let index = 1;
    
    // Add Document Input Row
    document.body.addEventListener('click', function(e) {
        if(e.target.closest('.add-document-btn')) {
            const container = document.getElementById('document-inputs-container');
            const row = document.createElement('div');
            row.className = 'row mb-3 document-input-row align-items-end';
            row.innerHTML = `
                <div class="col-md-5">
                    <label class="form-label">Document Title <span class="text-danger">*</span></label>
                    <input type="text" name="documents[${index}][title]" class="form-control" required placeholder="e.g., Certificate, ID Card">
                </div>
                <div class="col-md-5">
                    <label class="form-label">File <span class="text-danger">*</span></label>
                    <input type="file" name="documents[${index}][file]" class="form-control" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-document-btn w-100">
                        <i class="mdi mdi-delete"></i> Remove
                    </button>
                </div>
            `;
            container.appendChild(row);
            index++;
        }
        
        // Remove Document Input Row
        if(e.target.closest('.remove-document-btn')) {
            e.target.closest('.document-input-row').remove();
        }
    });
});
</script>
