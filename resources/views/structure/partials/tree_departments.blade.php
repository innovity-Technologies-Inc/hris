@foreach ($departments as $department)
    @if ($showDepartment)
        <!-- DEPARTMENT LEVEL -->
        <div class="tree-item tree-item-nested" data-aos="fade-left" data-aos-duration="400" data-aos-once="true">
            @php
                $hasDepartmentChildren = ($showSection && $department->sections->count() > 0);
            @endphp
            <div class="tree-header" data-target="#department{{ $department->id }}">
                @if ($hasDepartmentChildren)
                    <i class="bi bi-chevron-right expand-icon"></i>
                @else
                    <i class="bi bi-chevron-right expand-icon" style="visibility: hidden;"></i>
                @endif
                <i class="bi bi-briefcase-fill level-icon icon-department"></i>
                <span class="level-badge badge-department">Department</span>
                <span class="tree-label">{{ $department->department_name }}</span>
                <span class="count-badge" title="Total Employees">{{ $department->employees_count ?? 0 }}</span>
                <span class="count-badge key-members-department" title="Key Members">
                    <i class="bi bi-people-fill me-1"></i>{{ $department->key_members_count ?? 0 }}
                </span>
                <button class="key-people-btn" type="button" data-level="department" data-id="{{ $department->id }}" data-name="{{ $department->department_name }}">
                    <i class="bi bi-people-fill"></i><span>Key People</span>
                </button>
            </div>
            <div class="tree-content" id="department{{ $department->id }}">
                @if ($showSection)
                    @include('structure.partials.tree_sections', [
                        'sections' => $department->sections
                    ])
                @endif
            </div>
        </div>
    @else
        @if ($showSection)
            @include('structure.partials.tree_sections', [
                'sections' => $department->sections
            ])
        @endif
    @endif
@endforeach
