@foreach ($sections as $section)
    <!-- SECTION LEVEL -->
    <div class="tree-item tree-item-nested" data-aos="fade-left" data-aos-duration="400" data-aos-once="true">
        <div class="tree-header">
            <i class="bi bi-chevron-right expand-icon" style="visibility: hidden;"></i>
            <i class="bi bi-file-earmark-text-fill level-icon icon-section"></i>
            <span class="level-badge badge-section">Section</span>
            <span class="tree-label">{{ $section->name }}</span>
            <span class="count-badge" title="Total Employees">{{ $section->employees_count ?? 0 }}</span>
            <span class="count-badge key-members-section" title="Key Members">
                <i class="bi bi-people-fill me-1"></i>{{ $section->key_members_count ?? 0 }}
            </span>
            <button class="key-people-btn" type="button" data-level="section" data-id="{{ $section->id }}" data-name="{{ $section->name }}">
                <i class="bi bi-people-fill"></i><span>Key People</span>
            </button>
        </div>
    </div>
@endforeach
