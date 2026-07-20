# Offboarding Module Requirements

## Overview
Re-structure and expand the offboarding architecture to encompass both **Resignation** and **Termination** under a unified **Offboarding** module. Implement shared form templates, distinct index pages for Resignation and Termination, employee status updates (`'resigned'`, `'terminated'`), portal access restrictions for offboarded employees, approval workflow integration, thin request-service-controller pattern, and permission seeder.

## Key Requirements

### 1. Sidebar Menu & Permission Restructuring
- Parent Menu: **Offboarding** (Icon: `door-open` / `box-arrow-right`)
- Submenus:
  1. **Resignation** (`route('offboarding.resignation.index')`)
  2. **Termination** (`route('offboarding.termination.index')`)
- Permissions:
  - Resignation: `resignations.view`, `create`, `edit`, `delete`, `approve`
  - Termination: `terminations.view`, `create`, `edit`, `delete`, `approve`

### 2. Schema & Model
- Migration: Update `resignations` to `offboardings` table (or migration adding `offboarding_type` enum `'resignation'`, `'termination'`).
- Model: `App\Models\Offboarding\Offboarding.php` with `OrganizationScoped` and `Approvable` traits (`$allowNullableOrgScope = true`).
- Approval Engine: Register `'offboarding-resignation' => 'Offboarding Resignation'` and `'offboarding-termination' => 'Offboarding Termination'` in `config/approval-engine.php`.

### 3. Shared Form & Dynamic Type Locking
- Form Title: `Offboarding - Resignation` or `Offboarding - Termination`.
- `offboarding_type`: Pre-selected based on route parameter (`resignation` vs `termination`) and **disabled** on the UI so it cannot be altered.
- 5-Tier Organizational Hierarchy Cascade for selecting Employee (Company, Branch, Division, Department, Section, Employee).
- Offboarding fields: `resignation_date`, `notice_period_days`, `last_working_day` (auto-calculated), `reason`, `remarks`.

### 4. Distinct Index & Detail Pages
- Shared Index Blade template (`resources/views/offboarding/index.blade.php` and `search_results.blade.php`) rendered via distinct controller routes (`offboarding.resignation.index` & `offboarding.termination.index`).
- Shared Detail Blade template (`resources/views/offboarding/show.blade.php`).

### 5. Employee Status Update & Offboarded Portal Access Restriction
- Upon offboarding creation/approval, update the target employee's `status` to `'resigned'` or `'terminated'`.
- Middleware `EnsureNotOffboarded`: Intercepts authenticated users whose employee status is `'resigned'` or `'terminated'`. Redirects them exclusively to `/my-offboarding` (rendering a single read-only offboarding status details page) and blocks access to all other portal routes.

### 6. Modular Code Standards
- Requests: `StoreOffboardingRequest`, `UpdateOffboardingRequest`.
- Service: `OffboardingServices`.
- Controller: `OffboardingController` using generic `ApiResponse` trait.
