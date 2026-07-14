@extends('structure.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <!-- Header Banner / Color Bar -->
                <div class="bg-primary bg-opacity-10 p-4 border-bottom border-primary border-opacity-10 d-flex justify-content-between align-items-center">
                    <div>
                        <span class="badge bg-primary-subtle text-primary mb-2 px-3 py-1 text-uppercase fw-semibold" style="letter-spacing: 0.5px;">Announcement</span>
                        <h3 class="fw-bold text-dark mb-0">{{ $announcement->title }}</h3>
                        <small class="text-muted d-block mt-1">
                            <i class="mdi mdi-calendar-outline me-1"></i> Posted on {{ $announcement->created_at->format('F d, Y \a\t h:i A') }}
                        </small>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('announcements.pdf', $announcement->id) }}" class="btn btn-success btn-sm d-flex align-items-center gap-1">
                            <i style="height: 12px; width: 12px;" data-feather="download"></i> PDF Download
                        </a>
                        <a href="{{ route('announcements.index') }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1">
                            <i style="height: 12px; width: 12px;" data-feather="arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <!-- Main Body -->
                <div class="card-body p-4 p-md-5">
                    @php
                        $generalSettings = \App\HelperClass::getGeneralSetting();
                    @endphp
                    <!-- Scope/Audience metadata pill tags -->
                    <div class="d-flex flex-wrap gap-2 mb-4 bg-light p-3 rounded-3 border">
                        <div class="d-flex align-items-center text-muted me-3">
                            <i class="mdi mdi-account-group-outline me-1"></i> <strong>Target Audience:</strong>
                        </div>
                        <div>
                            <span class="badge bg-secondary-subtle text-secondary py-1 px-2">
                                Company: {{ $announcement->company->name ?? 'All (Global)' }}
                            </span>
                            @if($generalSettings->branch_status == 1)
                                <span class="badge bg-secondary-subtle text-secondary py-1 px-2">
                                    Branch: {{ $announcement->branch->name ?? 'All (Global)' }}
                                </span>
                            @endif
                            @if($generalSettings->division_status == 1)
                                <span class="badge bg-secondary-subtle text-secondary py-1 px-2">
                                    Division: {{ $announcement->division->name ?? 'All (Global)' }}
                                </span>
                            @endif
                            @if($generalSettings->department_status == 1)
                                <span class="badge bg-secondary-subtle text-secondary py-1 px-2">
                                    Department: {{ $announcement->department->department_name ?? 'All (Global)' }}
                                </span>
                            @endif
                            @if($generalSettings->section_status == 1)
                                <span class="badge bg-secondary-subtle text-secondary py-1 px-2">
                                    Section: {{ $announcement->section->name ?? 'All (Global)' }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Announcement Content -->
                    <div class="announcement-content text-dark mb-5 lh-base fs-5" style="min-height: 150px;">
                        {!! $announcement->content !!}
                    </div>

                    <!-- Attachment Section -->
                    @if($announcement->attachment_path)
                        <div class="border-top pt-4">
                            <h5 class="fw-bold text-dark mb-3">Attachment File</h5>
                            <div class="d-flex align-items-center p-3 bg-light border rounded-3 justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-danger bg-opacity-10 text-danger p-3 rounded-3">
                                        <i class="mdi mdi-file-pdf-box fs-2" style="font-size: 2rem;"></i>
                                    </div>
                                    <div>
                                        <span class="fw-semibold text-dark d-block">Announcement Attachment</span>
                                        <small class="text-muted">Click to view or download the attached document</small>
                                    </div>
                                </div>
                                <div>
                                    <a href="{{ Storage::url($announcement->attachment_path) }}" target="_blank" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                                        <i style="height: 12px; width: 12px;" data-feather="external-link"></i> View File
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Footer with timestamps -->
                <div class="card-footer bg-light border-0 py-3 text-center text-muted small">
                    Posted by General Administration
                </div>
            </div>
        </div>
    </div>
@endsection
