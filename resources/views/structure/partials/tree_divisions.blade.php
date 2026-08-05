@foreach ($divisions as $division)
    @if ($showDivision)
        <!-- DIVISION LEVEL -->
        <div class="tree-item tree-item-nested" data-aos="fade-left" data-aos-duration="400" data-aos-once="true">
            <?php
                $hasDivisionChildren = false;
                if ($showDepartment && $division->departments->count() > 0) {
                    $hasDivisionChildren = true;
                } elseif (!$showDepartment && $showSection) {
                    foreach ($division->departments as $dept) {
                        if ($dept->sections->count() > 0) { $hasDivisionChildren = true; break; }
                    }
                }
            ?>
            <div class="tree-header" data-target="#division{{ $division->id }}">
                @if ($hasDivisionChildren)
                    <i class="bi bi-chevron-right expand-icon"></i>
                @else
                    <i class="bi bi-chevron-right expand-icon" style="visibility: hidden;"></i>
                @endif
                <i class="bi bi-diagram-3-fill level-icon icon-division"></i>
                <span class="level-badge badge-division">Division</span>
                <span class="tree-label">{{ $division->name }}</span>
                <span class="count-badge" title="Total Employees">{{ $division->employees_count ?? 0 }}</span>
                <span class="count-badge key-members-division" title="Key Members">
                    <i class="bi bi-people-fill me-1"></i>{{ $division->key_members_count ?? 0 }}
                </span>
                <button class="key-people-btn" type="button" data-level="division" data-id="{{ $division->id }}" data-name="{{ $division->name }}">
                    <i class="bi bi-people-fill"></i><span>Key People</span>
                </button>
            </div>
            <div class="tree-content" id="division{{ $division->id }}">
                @include('structure.partials.tree_departments', [
                    'departments' => $division->departments,
                    'showDepartment' => $showDepartment,
                    'showSection' => $showSection
                ])
            </div>
        </div>
    @else
        @include('structure.partials.tree_departments', [
            'departments' => $division->departments,
            'showDepartment' => $showDepartment,
            'showSection' => $showSection
        ])
    @endif
@endforeach
