@extends('structure.master')

@push('styles')
    <style>
        :root {
            --primary: #1e40af;
            --secondary: #475569;
            --border: #e2e8f0;
            --bg-main: #f8fafc;
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        /* Tree Container */
        .tree-container {
            padding: 1.5rem 1rem;
            max-width: 1400px;
            margin: 0 auto;
            background: var(--bg-main);
            min-height: calc(100vh - 200px);
        }

        /* Tree Item */
        .tree-item {
            background: white;
            border-radius: 10px;
            margin-bottom: 0.625rem;
            border: 1px solid var(--border);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            animation: fadeInUp 0.4s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .tree-item:hover {
            border-color: #94a3b8;
            box-shadow: var(--shadow-lg);
            transform: translateX(2px);
        }

        /* Tree Header */
        .tree-header {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.125rem;
            cursor: pointer;
            user-select: none;
            gap: 0.875rem;
            transition: all 0.25s ease;
            border-radius: 10px;
        }

        .tree-header:hover {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .tree-header.expanded {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border-bottom: 1px solid var(--border);
            border-radius: 10px 10px 0 0;
        }

        /* Expand Icon */
        .expand-icon {
            color: var(--primary);
            font-size: 1.125rem;
            transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            min-width: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .expand-icon.rotated {
            transform: rotate(90deg);
        }

        /* Level Icons */
        .level-icon {
            font-size: 1.625rem;
            min-width: 36px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .tree-header:hover .level-icon {
            transform: scale(1.1);
        }

        .icon-group {
            color: #1e40af;
            filter: drop-shadow(0 2px 2px rgba(30, 64, 175, 0.2));
        }

        .icon-company {
            color: #7c3aed;
            filter: drop-shadow(0 2px 2px rgba(124, 58, 237, 0.2));
        }

        .icon-location {
            color: #dc2626;
            filter: drop-shadow(0 2px 2px rgba(220, 38, 38, 0.2));
        }

        .icon-division {
            color: #ea580c;
            filter: drop-shadow(0 2px 2px rgba(234, 88, 12, 0.2));
        }

        .icon-department {
            color: #059669;
            filter: drop-shadow(0 2px 2px rgba(5, 150, 105, 0.2));
        }

        .icon-section {
            color: #0891b2;
            filter: drop-shadow(0 2px 2px rgba(8, 145, 178, 0.2));
        }

        /* Level Badges */
        .level-badge {
            font-size: 0.65rem;
            font-weight: 700;
            padding: 0.375rem 0.875rem;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid;
            transition: all 0.25s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .badge-group {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: #1e40af;
            border-color: #93c5fd;
        }

        .badge-company {
            background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);
            color: #7c3aed;
            border-color: #c4b5fd;
        }

        .badge-location {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #dc2626;
            border-color: #fca5a5;
        }

        .badge-division {
            background: linear-gradient(135deg, #ffedd5 0%, #fed7aa 100%);
            color: #ea580c;
            border-color: #fdba74;
        }

        .badge-department {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #059669;
            border-color: #6ee7b7;
        }

        .badge-section {
            background: linear-gradient(135deg, #cffafe 0%, #a5f3fc 100%);
            color: #0891b2;
            border-color: #67e8f9;
        }

        /* Tree Label */
        .tree-label {
            flex: 1;
            font-weight: 600;
            color: #0f172a;
            font-size: 0.9rem;
            transition: color 0.25s ease;
        }

        .tree-header:hover .tree-label {
            color: var(--primary);
        }

        .tree-label.level-group {
            font-weight: 700;
            font-size: 1rem;
        }

        /* Count Badge */
        .count-badge {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            color: #475569;
            padding: 0.3rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            border: 1px solid var(--border);
            transition: all 0.25s ease;
            min-width: 40px;
            text-align: center;
        }

        .tree-header:hover .count-badge {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: var(--primary);
            border-color: #93c5fd;
        }

        /* Key People Button */
        .key-people-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1.125rem;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 600;
            border: 2px solid var(--primary);
            background: white;
            color: var(--primary);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
            box-shadow: 0 2px 4px rgba(30, 64, 175, 0.1);
        }

        .key-people-btn:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
        }

        .key-people-btn:active {
            transform: translateY(0);
        }

        /* Tree Content */
        .tree-content {
            padding: 0 0.75rem 0 2.5rem;
            position: relative;
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            transition: max-height 0.5s cubic-bezier(0.4, 0, 0.2, 1),
                opacity 0.4s ease-out,
                padding 0.4s ease-out;
        }

        .tree-content.show {
            max-height: 10000px;
            opacity: 1;
            padding-bottom: 0.75rem;
            transition: max-height 0.8s cubic-bezier(0.4, 0, 0.2, 1),
                opacity 0.5s ease-in;
        }

        /* Connecting Lines */
        .tree-content::before {
            content: '';
            position: absolute;
            left: 2rem;
            top: 0;
            bottom: 0.75rem;
            width: 2px;
            background: linear-gradient(180deg, #cbd5e1 0%, #e2e8f0 100%);
            border-radius: 1px;
        }

        .tree-item-nested {
            position: relative;
            margin-left: 1.5rem;
            padding-left: 1rem;
        }

        .tree-item-nested::before {
            content: '';
            position: absolute;
            left: -1.5rem;
            top: 50%;
            width: 1.5rem;
            height: 2px;
            background: linear-gradient(90deg, #cbd5e1 0%, #94a3b8 100%);
            border-radius: 1px;
        }

        /* Person Card */
        .person-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-radius: 10px;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            margin-bottom: 0.875rem;
            border: 1px solid var(--border);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .person-card:hover {
            border-color: #94a3b8;
            box-shadow: var(--shadow-lg);
            transform: translateX(4px);
            background: linear-gradient(135deg, #ffffff 0%, #eff6ff 100%);
        }

        .person-avatar {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary) 0%, #3b82f6 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            flex-shrink: 0;
            box-shadow: 0 4px 8px rgba(30, 64, 175, 0.25);
            transition: transform 0.3s ease;
        }

        .person-card:hover .person-avatar {
            transform: scale(1.05);
        }

        .person-info {
            flex: 1;
        }

        .person-info h6 {
            margin: 0 0 0.25rem 0;
            font-weight: 600;
            color: #0f172a;
            font-size: 0.9rem;
        }

        .person-info p {
            margin: 0;
            font-size: 0.8rem;
            color: #64748b;
        }

        /* View Profile Button */
        .view-profile-btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            border: 2px solid var(--primary);
            background: white;
            color: var(--primary);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
        }

        .view-profile-btn:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.3);
            text-decoration: none;
        }

        /* Modal Enhancements */
        .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary) 0%, #3b82f6 100%);
            color: white;
            border-radius: 16px 16px 0 0;
            padding: 1.25rem 1.5rem;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.8;
        }

        .modal-header .btn-close:hover {
            opacity: 1;
        }

        .modal-body {
            padding: 1.5rem;
            background: var(--bg-main);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .tree-content {
                padding-left: 2rem;
            }

            .tree-item-nested {
                margin-left: 1rem;
            }

            .key-people-btn span {
                display: none;
            }

            .person-card {
                flex-direction: column;
                align-items: flex-start;
            }

            .view-profile-btn {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header text-white py-3"
                    style="background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-sitemap me-2 fa-lg"></i>
                            <h5 class="mb-0 fw-bold">Organizational Structure View</h5>
                        </div>
                        <a href="{{ route('organization-structure.index') }}" class="btn btn-light btn-sm fw-semibold"
                            style="border-radius: 8px;">
                            <i class="fas fa-list me-1"></i>Members List
                        </a>
                    </div>
                </div>

                <div class="card-body p-0" style="background: var(--bg-main);"">
                    <!-- Tree Structure -->
                    <div class="tree-container">
                        <div class="container-fluid px-3">
                            @foreach ($groups as $group)
                                <!-- GROUP LEVEL -->
                                <div class="tree-item">
                                    <div class="tree-header" data-target="#group{{ $group->id }}">
                                        <i class="bi bi-chevron-right expand-icon"></i>
                                        <i class="bi bi-building level-icon icon-group"></i>
                                        <span class="level-badge badge-group">Group</span>
                                        <span class="tree-label level-group">{{ $group->name }}</span>
                                        <span class="count-badge"
                                            title="Total Employees">{{ $group->companies->sum(fn($c) => $c->employees_count ?? 0) }}</span>
                                        <span class="count-badge" title="Key Members"
                                            style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); color: #1e40af; margin-left: 0.25rem;">
                                            <i class="bi bi-people-fill me-1"></i>{{ $group->key_members_count ?? 0 }}
                                        </span>
                                        <button class="key-people-btn" type="button" data-level="group"
                                            data-id="{{ $group->id }}" data-name="{{ $group->name }}">
                                            <i class="bi bi-people-fill"></i>
                                            <span>Key People</span>
                                        </button>
                                    </div>

                                    <div class="tree-content" id="group{{ $group->id }}">
                                        @foreach ($group->companies as $company)
                                            <!-- COMPANY LEVEL -->
                                            <div class="tree-item tree-item-nested">
                                                <div class="tree-header" data-target="#company{{ $company->id }}">
                                                    <i class="bi bi-chevron-right expand-icon"></i>
                                                    <i class="bi bi-building-fill level-icon icon-company"></i>
                                                    <span class="level-badge badge-company">Company</span>
                                                    <span class="tree-label">{{ $company->name }}</span>
                                                    <span class="count-badge"
                                                        title="Total Employees">{{ $company->employees_count ?? 0 }}</span>
                                                    <span class="count-badge" title="Key Members"
                                                        style="background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%); color: #7c3aed; margin-left: 0.25rem;">
                                                        <i
                                                            class="bi bi-people-fill me-1"></i>{{ $company->key_members_count ?? 0 }}
                                                    </span>
                                                    <button class="key-people-btn" type="button" data-level="company"
                                                        data-id="{{ $company->id }}" data-name="{{ $company->name }}">
                                                        <i class="bi bi-people-fill"></i>
                                                        <span>Key People</span>
                                                    </button>
                                                </div>

                                                <div class="tree-content" id="company{{ $company->id }}">
                                                    @foreach ($company->locations as $location)
                                                        <!-- LOCATION LEVEL -->
                                                        <div class="tree-item tree-item-nested">
                                                            <div class="tree-header"
                                                                data-target="#location{{ $location->id }}">
                                                                @if ($location->divisions->count() > 0)
                                                                    <i class="bi bi-chevron-right expand-icon"></i>
                                                                @else
                                                                    <i class="bi bi-chevron-right expand-icon"
                                                                        style="visibility: hidden;"></i>
                                                                @endif
                                                                <i class="bi bi-geo-alt-fill level-icon icon-location"></i>
                                                                <span class="level-badge badge-location">Branch</span>
                                                                <span class="tree-label">{{ $location->name }}</span>
                                                                <span class="count-badge"
                                                                    title="Total Employees">{{ $location->employees_count ?? 0 }}</span>
                                                                <span class="count-badge" title="Key Members"
                                                                    style="background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%); color: #dc2626; margin-left: 0.25rem;">
                                                                    <i
                                                                        class="bi bi-people-fill me-1"></i>{{ $location->key_members_count ?? 0 }}
                                                                </span>
                                                                <button class="key-people-btn" type="button"
                                                                    data-level="location" data-id="{{ $location->id }}"
                                                                    data-name="{{ $location->name }}">
                                                                    <i class="bi bi-people-fill"></i>
                                                                    <span>Key People</span>
                                                                </button>
                                                            </div>

                                                            @if ($location->divisions->count() > 0)
                                                                <div class="tree-content" id="location{{ $location->id }}">
                                                                    @foreach ($location->divisions as $division)
                                                                        <!-- DIVISION LEVEL -->
                                                                        <div class="tree-item tree-item-nested">
                                                                            <div class="tree-header"
                                                                                data-target="#division{{ $division->id }}">
                                                                                @if ($division->departments->count() > 0)
                                                                                    <i
                                                                                        class="bi bi-chevron-right expand-icon"></i>
                                                                                @else
                                                                                    <i class="bi bi-chevron-right expand-icon"
                                                                                        style="visibility: hidden;"></i>
                                                                                @endif
                                                                                <i
                                                                                    class="bi bi-diagram-3-fill level-icon icon-division"></i>
                                                                                <span
                                                                                    class="level-badge badge-division">Division</span>
                                                                                <span
                                                                                    class="tree-label">{{ $division->name }}</span>
                                                                                <span class="count-badge"
                                                                                    title="Total Employees">{{ $division->employees_count ?? 0 }}</span>
                                                                                <span class="count-badge"
                                                                                    title="Key Members"
                                                                                    style="background: linear-gradient(135deg, #ffedd5 0%, #fed7aa 100%); color: #ea580c; margin-left: 0.25rem;">
                                                                                    <i
                                                                                        class="bi bi-people-fill me-1"></i>{{ $division->key_members_count ?? 0 }}
                                                                                </span>
                                                                                <button class="key-people-btn"
                                                                                    type="button" data-level="division"
                                                                                    data-id="{{ $division->id }}"
                                                                                    data-name="{{ $division->name }}">
                                                                                    <i class="bi bi-people-fill"></i>
                                                                                    <span>Key People</span>
                                                                                </button>
                                                                            </div>

                                                                            @if ($division->departments->count() > 0)
                                                                                <div class="tree-content"
                                                                                    id="division{{ $division->id }}">
                                                                                    @foreach ($division->departments as $department)
                                                                                        <!-- DEPARTMENT LEVEL -->
                                                                                        <div
                                                                                            class="tree-item tree-item-nested">
                                                                                            <div class="tree-header"
                                                                                                data-target="#department{{ $department->id }}">
                                                                                                @if ($department->sections->count() > 0)
                                                                                                    <i
                                                                                                        class="bi bi-chevron-right expand-icon"></i>
                                                                                                @else
                                                                                                    <i class="bi bi-chevron-right expand-icon"
                                                                                                        style="visibility: hidden;"></i>
                                                                                                @endif
                                                                                                <i
                                                                                                    class="bi bi-briefcase-fill level-icon icon-department"></i>
                                                                                                <span
                                                                                                    class="level-badge badge-department">Department</span>
                                                                                                <span
                                                                                                    class="tree-label">{{ $department->department_name }}</span>
                                                                                                <span class="count-badge"
                                                                                                    title="Total Employees">{{ $department->employees_count ?? 0 }}</span>
                                                                                                <span class="count-badge"
                                                                                                    title="Key Members"
                                                                                                    style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); color: #059669; margin-left: 0.25rem;">
                                                                                                    <i
                                                                                                        class="bi bi-people-fill me-1"></i>{{ $department->key_members_count ?? 0 }}
                                                                                                </span>
                                                                                                <button
                                                                                                    class="key-people-btn"
                                                                                                    type="button"
                                                                                                    data-level="department"
                                                                                                    data-id="{{ $department->id }}"
                                                                                                    data-name="{{ $department->department_name }}">
                                                                                                    <i
                                                                                                        class="bi bi-people-fill"></i>
                                                                                                    <span>Key People</span>
                                                                                                </button>
                                                                                            </div>

                                                                                            @if ($department->sections->count() > 0)
                                                                                                <div class="tree-content"
                                                                                                    id="department{{ $department->id }}">
                                                                                                    @foreach ($department->sections as $section)
                                                                                                        <!-- SECTION LEVEL -->
                                                                                                        <div
                                                                                                            class="tree-item tree-item-nested">
                                                                                                            <div
                                                                                                                class="tree-header">
                                                                                                                <i class="bi bi-chevron-right expand-icon"
                                                                                                                    style="visibility: hidden;"></i>
                                                                                                                <i
                                                                                                                    class="bi bi-file-earmark-text-fill level-icon icon-section"></i>
                                                                                                                <span
                                                                                                                    class="level-badge badge-section">Section</span>
                                                                                                                <span
                                                                                                                    class="tree-label">{{ $section->name }}</span>
                                                                                                                <span
                                                                                                                    class="count-badge"
                                                                                                                    title="Total Employees">{{ $section->employees_count ?? 0 }}</span>
                                                                                                                <span
                                                                                                                    class="count-badge"
                                                                                                                    title="Key Members"
                                                                                                                    style="background: linear-gradient(135deg, #cffafe 0%, #a5f3fc 100%); color: #0891b2; margin-left: 0.25rem;">
                                                                                                                    <i
                                                                                                                        class="bi bi-people-fill me-1"></i>{{ $section->key_members_count ?? 0 }}
                                                                                                                </span>
                                                                                                                <button
                                                                                                                    class="key-people-btn"
                                                                                                                    type="button"
                                                                                                                    data-level="section"
                                                                                                                    data-id="{{ $section->id }}"
                                                                                                                    data-name="{{ $section->name }}">
                                                                                                                    <i
                                                                                                                        class="bi bi-people-fill"></i>
                                                                                                                    <span>Key
                                                                                                                        People</span>
                                                                                                                </button>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    @endforeach
                                                                                                </div>
                                                                                            @endif
                                                                                        </div>
                                                                                    @endforeach
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Key People Modal -->
    <div class="modal fade" id="keyPeopleModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="keyPeopleModalLabel">
                        <i class="bi bi-people-fill me-2"></i>
                        Key People
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Tree expand/collapse with smooth animation
            document.querySelectorAll('.tree-header[data-target]').forEach(header => {
                header.addEventListener('click', function(e) {
                    if (e.target.closest('.key-people-btn')) return;

                    const target = document.querySelector(this.dataset.target);
                    const icon = this.querySelector('.expand-icon');

                    if (target) {
                        const isExpanding = !target.classList.contains('show');

                        // Toggle content visibility
                        target.classList.toggle('show');

                        // Toggle header expanded state
                        this.classList.toggle('expanded', isExpanding);

                        // Rotate icon
                        if (icon && icon.style.visibility !== 'hidden') {
                            icon.classList.toggle('rotated', isExpanding);
                        }

                        // Animate nested items on expand
                        if (isExpanding) {
                            const nestedItems = target.querySelectorAll('.tree-item');
                            nestedItems.forEach((item, index) => {
                                item.style.opacity = '0';
                                item.style.transform = 'translateY(10px)';
                                setTimeout(() => {
                                    item.style.transition =
                                        'opacity 0.3s ease, transform 0.3s ease';
                                    item.style.opacity = '1';
                                    item.style.transform = 'translateY(0)';
                                }, index * 50);
                            });
                        }
                    }
                });
            });

            // Key People button click
            document.querySelectorAll('.key-people-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    e.preventDefault();

                    const level = this.dataset.level;
                    const id = this.dataset.id;
                    const name = this.dataset.name;

                    showKeyPeople(level, id, name);
                });
            });

            // Add ripple effect to buttons
            document.querySelectorAll('.key-people-btn, .view-profile-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    ripple.style.cssText = `
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.4);
                transform: scale(0);
                animation: ripple 0.6s linear;
                pointer-events: none;
            `;
                    this.style.position = 'relative';
                    this.style.overflow = 'hidden';
                    this.appendChild(ripple);
                    setTimeout(() => ripple.remove(), 600);
                });
            });
        });

        function showKeyPeople(level, id, name) {
            document.getElementById('keyPeopleModalLabel').innerHTML =
                `<i class="bi bi-people-fill me-2"></i>Key People - ${name}`;

            document.getElementById('modalContent').innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Loading key people...</p>
        </div>
    `;

            new bootstrap.Modal(document.getElementById('keyPeopleModal')).show();

            // Fetch key people via AJAX
            $.get(`/organization-structure/key-people/${level}/${id}`, function(data) {
                let html = '';

                if (data.length === 0) {
                    html = `
                <div class="text-center py-5">
                    <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                    <p class="mt-3 text-muted mb-0">No key people assigned yet.</p>
                </div>
            `;
                } else {
                    data.forEach(function(person, index) {
                        // Display photo if available, otherwise show initials
                        let avatarHtml = '';
                        if (person.photo_path) {
                            avatarHtml =
                                `<img src="/storage/${person.photo_path}" alt="${person.name}" style="width: 52px; height: 52px; border-radius: 50%; object-fit: cover; border: 2px solid var(--primary);">`;
                        } else {
                            const initials = person.name ? person.name.split(' ').map(n => n[0]).join('')
                                .substring(0, 2).toUpperCase() : '??';
                            avatarHtml = `<div class="person-avatar">${initials}</div>`;
                        }

                        // Determine profile link - Board Members go to organization-structure edit, Key Members to employee profile
                        let profileLink = '';
                        if (person.member_type === 'Board Member') {
                            profileLink = `/organization-structure/${person.id}/edit`;
                        } else if (person.member_type === 'Key Member' && person.employee_id) {
                            profileLink = `/employees/profile/${person.employee_id}/general-informations`;
                        }

                        html += `
                    <div class="person-card" style="animation-delay: ${index * 0.1}s;">
                        ${avatarHtml}
                        <div class="person-info">
                            <h6>${person.name || 'N/A'}</h6>
                            <p><i class="bi bi-briefcase me-1"></i>${person.position || 'N/A'}</p>
                            <p class="mb-0" style="font-size: 0.7rem;"><span class="badge ${person.member_type === 'Board Member' ? 'bg-primary' : 'bg-success'}">${person.member_type}</span></p>
                        </div>
                        ${profileLink ? `
                                <a href="${profileLink}" class="view-profile-btn">
                                    <i class="bi bi-person-circle"></i>
                                    View Profile
                                </a>
                            ` : ''}
                    </div>
                `;
                    });
                }

                document.getElementById('modalContent').innerHTML = html;
            }).fail(function() {
                document.getElementById('modalContent').innerHTML = `
            <div class="text-center py-5">
                <i class="bi bi-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                <p class="mt-3 text-danger mb-0">Failed to load key people. Please try again.</p>
            </div>
        `;
            });
        }

        // Add CSS for ripple animation
        const style = document.createElement('style');
        style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
        document.head.appendChild(style);
    </script>
@endpush
