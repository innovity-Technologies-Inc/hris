# Resign Module Requirements

## Overview
Implement a new **Resign** (`Resignation`) module allowing employees and managers to submit, track, and process employee resignations with a 5-tier organizational hierarchy cascade (Company -> Branch -> Division -> Department -> Section -> Employee), approval workflow integration, thin request-service-controller architecture, generic `ApiResponse` trait, and modern Bootstrap 5 UI aesthetics.

## Key Requirements

### 1. Organizational Hierarchy Cascade (5-Tier Employee Selection)
- Cascade dropdowns on create/edit form:
  1. Company (`company_id`)
  2. Branch / Company Location (`branch_id`)
  3. Division (`division_id`)
  4. Department (`department_id`)
  5. Section (`section_id`)
  6. Employee (`employee_id`)
- Dynamically filter eligible employees via Axios endpoints based on selected hierarchy tiers.

### 2. Resignation Schema & Attributes
- Database Table: `resignations`
- Attributes:
  - `employee_id` (foreign key `employees.id`)
  - `resignation_date` (date)
  - `notice_period_days` (integer)
  - `last_working_day` (date)
  - `reason` (text)
  - `status` (enum: `'pending'`, `'approved'`, `'rejected'`, `'cancelled'`) default `'pending'`
  - `remarks` (text, nullable)
  - `created_by`, `updated_by` (foreign keys `users.id`, nullable)
  - Timestamps and soft deletes

### 3. Model Traits & Approval Workflow Engine
- Eloquent Model: `App\Models\Resignation\Resignation.php`
- Uses `App\Traits\OrganizationScoped` for database query security.
- Uses `\Innovity\ApprovalEngine\Traits\Approvable` for approval engine integration.
- Register module `'resign' => 'Resignation'` in `config/approval-engine.php`.
- Start approval workflow `$resignation->startWorkflow('resign')` upon creation.

### 4. Modular Directory Structure
- Migration: `database/migrations/2026_07_20_000007_create_resignations_table.php`
- Model: `App\Models\Resignation\Resignation.php`
- Requests: `App\Http\Requests\Resignation\StoreResignationRequest.php` & `UpdateResignationRequest.php`
- Service: `App\Services\Resignation\ResignationServices.php`
- Controller: `App\Http\Controllers\Resignation\ResignationController.php`
- Views: `resources/views/resignation/{index,create,edit,show,search_results}.blade.php`

### 5. API & UI Standards
- Thin controller delegating business logic to `ResignationServices`.
- Uses generic `ApiResponse` trait methods (`$this->createdResponse()`, `$this->successResponse()`, `$this->deletedResponse()`, `$this->errorResponse()`).
- All form submissions, deletions, and cascading dropdown updates via Axios & SweetAlert2.
- Bootstrap 5 glassmorphism layout, dark mode support, and responsive design.

### 6. Permission Seeder
- Add `Resignations` menu and permissions (`resignations.view`, `resignations.create`, `resignations.edit`, `resignations.delete`, `resignations.approve`) in `PermissionSeeder.php`.
