@extends('structure.master')

@push('styles')
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        /* Tree Container */
        .tree-container {
            padding: 1.5rem 1rem;
            max-width: 1400px;
            margin: 0 auto;
            background: var(--bs-tertiary-bg);
            min-height: calc(100vh - 200px);
        }

        /* Tree Item */
        .tree-item {
            background: var(--bs-card-bg);
            border-radius: 10px;
            margin-bottom: 0.625rem;
            border: 1px solid var(--bs-border-color);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .tree-item:hover {
            border-color: var(--bs-primary);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transform: translateX(3px);
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
            background: rgba(var(--bs-primary-rgb), 0.05);
        }

        .tree-header.expanded {
            background: rgba(var(--bs-primary-rgb), 0.05);
            border-bottom: 1px solid var(--bs-border-color);
            border-radius: 10px 10px 0 0;
        }

        /* Expand Icon */
        .expand-icon {
            color: var(--bs-primary);
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
            color: #108dff;
            filter: drop-shadow(0 2px 2px rgba(16, 141, 255, 0.2));
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
            background: rgba(16, 141, 255, 0.15);
            color: #108dff;
            border-color: rgba(16, 141, 255, 0.3);
        }

        .badge-company {
            background: rgba(124, 58, 237, 0.15);
            color: #7c3aed;
            border-color: rgba(124, 58, 237, 0.3);
        }

        .badge-location {
            background: rgba(220, 38, 38, 0.15);
            color: #dc2626;
            border-color: rgba(220, 38, 38, 0.3);
        }

        .badge-division {
            background: rgba(234, 88, 12, 0.15);
            color: #ea580c;
            border-color: rgba(234, 88, 12, 0.3);
        }

        .badge-department {
            background: rgba(5, 150, 105, 0.15);
            color: #059669;
            border-color: rgba(5, 150, 105, 0.3);
        }

        .badge-section {
            background: rgba(8, 145, 178, 0.15);
            color: #0891b2;
            border-color: rgba(8, 145, 178, 0.3);
        }

        /* Tree Label */
        .tree-label {
            flex: 1;
            font-weight: 600;
            color: var(--bs-body-color);
            font-size: 0.9rem;
            transition: color 0.25s ease;
        }

        .tree-header:hover .tree-label {
            color: var(--bs-primary);
        }

        .tree-label.level-group {
            font-weight: 700;
            font-size: 1rem;
        }

        /* Count Badge */
        .count-badge {
            background: var(--bs-card-bg);
            color: var(--bs-gray-600);
            padding: 0.3rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            border: 1px solid var(--bs-border-color);
            transition: all 0.25s ease;
            min-width: 40px;
            text-align: center;
        }

        .tree-header:hover .count-badge {
            background: rgba(var(--bs-primary-rgb), 0.1);
            color: var(--bs-primary);
            border-color: var(--bs-primary);
        }

        /* Key Member Count Badges */
        .count-badge.key-members-group {
            background: rgba(16, 141, 255, 0.15);
            color: #108dff;
            border-color: rgba(16, 141, 255, 0.3);
            margin-left: 0.25rem;
        }

        .count-badge.key-members-company {
            background: rgba(124, 58, 237, 0.15);
            color: #7c3aed;
            border-color: rgba(124, 58, 237, 0.3);
            margin-left: 0.25rem;
        }

        .count-badge.key-members-location {
            background: rgba(220, 38, 38, 0.15);
            color: #dc2626;
            border-color: rgba(220, 38, 38, 0.3);
            margin-left: 0.25rem;
        }

        .count-badge.key-members-division {
            background: rgba(234, 88, 12, 0.15);
            color: #ea580c;
            border-color: rgba(234, 88, 12, 0.3);
            margin-left: 0.25rem;
        }

        .count-badge.key-members-department {
            background: rgba(5, 150, 105, 0.15);
            color: #059669;
            border-color: rgba(5, 150, 105, 0.3);
            margin-left: 0.25rem;
        }

        .count-badge.key-members-section {
            background: rgba(8, 145, 178, 0.15);
            color: #0891b2;
            border-color: rgba(8, 145, 178, 0.3);
            margin-left: 0.25rem;
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
            border: 2px solid var(--bs-primary);
            background: var(--bs-card-bg);
            color: var(--bs-primary);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
            box-shadow: 0 2px 4px rgba(var(--bs-primary-rgb), 0.1);
        }

        .key-people-btn:hover {
            background: var(--bs-primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(var(--bs-primary-rgb), 0.3);
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
            transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                opacity 0.25s ease-out,
                padding 0.25s ease-out;
        }

        .tree-content.show {
            max-height: 10000px;
            opacity: 1;
            padding-bottom: 0.75rem;
            transition: max-height 0.5s cubic-bezier(0.4, 0, 0.2, 1),
                opacity 0.3s ease-in;
        }

        /* Connecting Lines */
        .tree-content::before {
            content: '';
            position: absolute;
            left: 2rem;
            top: 0;
            bottom: 0.75rem;
            width: 2px;
            background: var(--bs-border-color);
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
            background: var(--bs-border-color);
            border-radius: 1px;
        }

        /* Person Card */
        .person-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-radius: 10px;
            background: var(--bs-card-bg);
            margin-bottom: 0.875rem;
            border: 1px solid var(--bs-border-color);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .person-card:hover {
            border-color: var(--bs-primary);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transform: translateX(4px);
            background: rgba(var(--bs-primary-rgb), 0.05);
        }

        .person-avatar {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: var(--bs-primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            flex-shrink: 0;
            box-shadow: 0 4px 8px rgba(var(--bs-primary-rgb), 0.25);
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
            color: var(--bs-body-color);
            font-size: 0.9rem;
        }

        .person-info p {
            margin: 0;
            font-size: 0.8rem;
            color: var(--bs-gray-600);
        }

        /* View Profile Button */
        .view-profile-btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            border: 2px solid var(--bs-primary);
            background: var(--bs-card-bg);
            color: var(--bs-primary);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            white-space: nowrap;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
        }

        .view-profile-btn:hover {
            background: var(--bs-primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(var(--bs-primary-rgb), 0.3);
            text-decoration: none;
        }

        /* Modal Enhancements */
        .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            background: var(--bs-card-bg);
        }

        .modal-header {
            background: var(--bs-primary);
            color: white;
            border-radius: 16px 16px 0 0;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--bs-border-color);
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
            background: var(--bs-tertiary-bg);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .tree-container {
                padding: 1rem 0.5rem;
            }

            .tree-header {
                padding: 0.75rem 0.875rem;
                gap: 0.5rem;
                flex-wrap: wrap;
            }

            .tree-content {
                padding-left: 1.5rem;
            }

            .tree-item-nested {
                margin-left: 0.75rem;
            }

            .key-people-btn {
                padding: 0.375rem 0.75rem;
                font-size: 0.75rem;
            }

            .key-people-btn span {
                display: none;
            }

            .person-card {
                flex-direction: column;
                align-items: flex-start;
                padding: 0.875rem;
            }

            .view-profile-btn {
                width: 100%;
                justify-content: center;
            }

            .level-icon {
                font-size: 1.25rem;
                min-width: 28px;
            }

            .level-badge {
                font-size: 0.6rem;
                padding: 0.3rem 0.65rem;
            }

            .count-badge {
                font-size: 0.7rem;
                padding: 0.25rem 0.5rem;
                min-width: 32px;
            }
        }

        @media (max-width: 576px) {
            .tree-label {
                font-size: 0.85rem;
            }

            .tree-label.level-group {
                font-size: 0.95rem;
            }

            .person-avatar {
                width: 44px;
                height: 44px;
                font-size: 1rem;
            }

            .person-info h6 {
                font-size: 0.85rem;
            }

            .person-info p {
                font-size: 0.75rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                <div class="card-header text-white py-3 bg-primary">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
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

                <div class="card-body p-0" style="background: var(--bs-tertiary-bg);">
                    <!-- Tree Structure -->
                    <div class="tree-container">
                        <div class="container-fluid px-3">
                            @php($generalSetting = \App\HelperClass::getGeneralSetting())
                            @php($showBranch = isset($generalSetting->branch_status) && $generalSetting->branch_status == 1)
                            @php($showDivision = isset($generalSetting->division_status) && $generalSetting->division_status == 1)
                            @php($showDepartment = isset($generalSetting->department_status) && $generalSetting->department_status == 1)
                            @php($showSection = isset($generalSetting->section_status) && $generalSetting->section_status == 1)

                            @foreach ($groups as $group)
                                <!-- GROUP LEVEL -->
                                <div class="tree-item" data-aos="fade-up" data-aos-duration="400" data-aos-once="true">
                                    <div class="tree-header" data-target="#group{{ $group->id }}">
                                        <i class="bi bi-chevron-right expand-icon"></i>
                                        <i class="bi bi-building level-icon icon-group"></i>
                                        <span class="level-badge badge-group">Group</span>
                                        <span class="tree-label level-group">{{ $group->name }}</span>
                                        <span class="count-badge" title="Total Employees">{{ $group->companies->sum(fn($c) => $c->employees_count ?? 0) }}</span>
                                        <span class="count-badge key-members-group" title="Key Members">
                                            <i class="bi bi-people-fill me-1"></i>{{ $group->key_members_count ?? 0 }}
                                        </span>
                                        <button class="key-people-btn" type="button" data-level="group" data-id="{{ $group->id }}" data-name="{{ $group->name }}">
                                            <i class="bi bi-people-fill"></i><span>Key People</span>
                                        </button>
                                    </div>
                                    <div class="tree-content" id="group{{ $group->id }}">
                                        @foreach ($group->companies as $company)
                                            <!-- COMPANY LEVEL -->
                                            <div class="tree-item tree-item-nested" data-aos="fade-left" data-aos-duration="400" data-aos-once="true">
                                                <div class="tree-header" data-target="#company{{ $company->id }}">
                                                    <i class="bi bi-chevron-right expand-icon"></i>
                                                    <i class="bi bi-building-fill level-icon icon-company"></i>
                                                    <span class="level-badge badge-company">Company</span>
                                                    <span class="tree-label">{{ $company->name }}</span>
                                                    <span class="count-badge" title="Total Employees">{{ $company->employees_count ?? 0 }}</span>
                                                    <span class="count-badge key-members-company" title="Key Members">
                                                        <i class="bi bi-people-fill me-1"></i>{{ $company->key_members_count ?? 0 }}
                                                    </span>
                                                    <button class="key-people-btn" type="button" data-level="company" data-id="{{ $company->id }}" data-name="{{ $company->name }}">
                                                        <i class="bi bi-people-fill"></i><span>Key People</span>
                                                    </button>
                                                </div>
                                                <div class="tree-content" id="company{{ $company->id }}">
                                                    @foreach ($company->locations as $location)
                                                        @if ($showBranch)
                                                            <!-- LOCATION LEVEL -->
                                                            <div class="tree-item tree-item-nested" data-aos="fade-left" data-aos-duration="400" data-aos-once="true">
                                                                @php
                                                                    $hasLocationChildren = false;
                                                                    if ($showDivision && $location->divisions->count() > 0) {
                                                                        $hasLocationChildren = true;
                                                                    } elseif (!$showDivision && $showDepartment) {
                                                                        foreach ($location->divisions as $div) {
                                                                            if ($div->departments->count() > 0) { $hasLocationChildren = true; break; }
                                                                        }
                                                                    } elseif (!$showDivision && !$showDepartment && $showSection) {
                                                                        foreach ($location->divisions as $div) {
                                                                            foreach ($div->departments as $dept) {
                                                                                if ($dept->sections->count() > 0) { $hasLocationChildren = true; break 2; }
                                                                            }
                                                                        }
                                                                    }
                                                                @endphp
                                                                <div class="tree-header" data-target="#location{{ $location->id }}">
                                                                    @if ($hasLocationChildren)
                                                                        <i class="bi bi-chevron-right expand-icon"></i>
                                                                    @else
                                                                        <i class="bi bi-chevron-right expand-icon" style="visibility: hidden;"></i>
                                                                    @endif
                                                                    <i class="bi bi-geo-alt-fill level-icon icon-location"></i>
                                                                    <span class="level-badge badge-location">Branch</span>
                                                                    <span class="tree-label">{{ $location->name }}</span>
                                                                    <span class="count-badge" title="Total Employees">{{ $location->employees_count ?? 0 }}</span>
                                                                    <span class="count-badge key-members-location" title="Key Members">
                                                                        <i class="bi bi-people-fill me-1"></i>{{ $location->key_members_count ?? 0 }}
                                                                    </span>
                                                                    <button class="key-people-btn" type="button" data-level="location" data-id="{{ $location->id }}" data-name="{{ $location->name }}">
                                                                        <i class="bi bi-people-fill"></i><span>Key People</span>
                                                                    </button>
                                                                </div>
                                                                <div class="tree-content" id="location{{ $location->id }}">
                                                                    @include('structure.partials.tree_divisions', [
                                                                        'divisions' => $location->divisions,
                                                                        'showDivision' => $showDivision,
                                                                        'showDepartment' => $showDepartment,
                                                                        'showSection' => $showSection
                                                                    ])
                                                                </div>
                                                            </div>
                                                        @else
                                                            @include('structure.partials.tree_divisions', [
                                                                'divisions' => $location->divisions,
                                                                'showDivision' => $showDivision,
                                                                'showDepartment' => $showDepartment,
                                                                'showSection' => $showSection
                                                            ])
                                                        @endif
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
    </div><!-- Key People Modal -->
    <div class="modal fade" id="keyPeopleModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="keyPeopleModalLabel"><i class="bi bi-people-fill me-2"></i>Key
                        People </h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status"><span
                                class="visually-hidden">Loading...</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection @push('scripts')
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

            // Helper function to generate avatar HTML (mimics HelperClass::generateAvatar)
            function generateAvatarHtml(photoPath, fullName, size = 52) {
                if (photoPath && photoPath.trim() !== '') {
                    return `<img src="/storage/${photoPath}" alt="${fullName}" style="width: ${size}px; height: ${size}px; border-radius: 50%; object-fit: cover; border: 2px solid var(--bs-primary);">`;
                } else {
                    const initials = fullName ? fullName.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase() :
                        '??';
                    const fontSize = Math.floor(size * 0.4);
                    return `<div class="person-avatar" style="width: ${size}px; height: ${size}px; background-color: #974063; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: ${fontSize}px; border: 2px solid var(--bs-primary);">${initials}</div>`;
                }
            }

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
                        // Generate avatar using consistent helper function
                        const avatarHtml = generateAvatarHtml(person.photo_path, person.name, 52);

                        // Determine profile link - Both Board Members and Key Members go to organization-structure profile
                        let profileLink = `/organization-structure/${person.id}`;

                        html += `
                    <div class="person-card" style="animation-delay: ${index * 0.1}s;">
                        ${avatarHtml}
                        <div class="person-info">
                            <h6>${person.name || 'N/A'}</h6>
                            <p><i class="bi bi-briefcase me-1"></i>${person.position || 'N/A'}</p>
                            <p class="mb-0" style="font-size: 0.7rem;"><span class="badge ${person.member_type === 'Board Member' ? 'bg-primary' : 'bg-success'}">${person.member_type}</span></p>
                        </div>
                        <a href="${profileLink}" class="view-profile-btn">
                            <i class="bi bi-person-circle"></i>
                            View Profile
                        </a>
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
    </script><!-- AOS Animation Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 400,
            easing: 'ease-out',
            once: true,
            offset: 50
        });
    </script>
@endpush

