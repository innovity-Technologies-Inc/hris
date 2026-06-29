# Plan: Dynamic Approval Workflow Laravel Package

This plan outlines the steps required to extract the Dynamic Approval Workflow Engine into a standalone, reusable Laravel package.

## Phase 1: Package Scaffolding & Setup
1. **Initialize Package Structure**: 
   - Choose a vendor/package name (e.g., `vendor-name/laravel-dynamic-approvals`).
   - Create standard package directories (`src`, `config`, `database/migrations`, `tests`).
   - Setup `composer.json` with appropriate namespace mapping (e.g., `"VendorName\\DynamicApprovals\\": "src/"`) and dependencies (Laravel Support/Database).
2. **Service Provider**: 
   - Create `DynamicApprovalsServiceProvider.php`.
   - Register the configuration publishing, migrations, and bind core engine interfaces to the container.
3. **Module Registration (Config/Seeder)**:
   - Publish a config file (e.g., `config/dynamic-approvals.php`) containing supported modules (e.g., `['leave_request' => 'Leave Request']`).
   - Alternatively, support a `workflow_modules` table that can be seeded to dynamically populate dropdowns in the UI.

## Phase 2: Database & Models
1. **Migrations**: 
   - Extract the 4 core tables into package migrations:
     - `approval_workflows` (Add `total_steps` integer, and `required_approvals` integer for random workflows).
     - `approval_workflow_steps`
     - `approval_requests` (Add a `payload` JSON column to support Field-Level Approvals, like Employee Designation changes).
     - `approval_step_requests`
   - Make table names customizable via the config file.
2. **Models & Traits**:
   - Create package Models for the tables.
   - Develop an `Approvable` trait (or `HasApprovalWorkflow`) to be applied to standard application models (e.g., `LeaveRequest`, `Promotion`). This trait will establish morph-to-many or polymorphic relationships with the `approval_requests` table.
   - Define Enums for Workflow Types (`sequential`, `random`) and Statuses (`pending`, `approved`, `rejected`).

## Phase 3: Core Engine Logic (Services & Actions)
1. **Workflow Generator Service**:
   - Class responsible for intercepting a model submission and determining if it requires an approval flow.
   - Logic to evaluate whether the workflow type is `sequential` or `random`.
2. **Task Emitter Service**:
   - **Random/Parallel**: Generate all `approval_step_requests` immediately.
   - **Sequential**: Generate the first step and queue subsequent steps dynamically based on `step_order`.
3. **Approval Resolution Engine**:
   - Handle the "Approve/Reject" action.
   - Logic to evaluate:
     - *If Random*: Has the number of approved tasks met the `required_approvals` threshold? If yes, resolve master request.
     - *If Sequential*: Is there a next step based on `total_steps` or step order? If yes, emit next task. If no, resolve master request.
4. **Events**:
   - Dispatch rich Laravel events (e.g., `ApprovalRequested`, `ApprovalStepApproved`, `ApprovalCompleted`, `ApprovalRejected`) so the main application can hook into the lifecycle to send emails/notifications.

## Phase 4: Security & Scoping
1. **Approval Scope Contract**:
   - Create a customizable resolution contract/interface (e.g., `ApproverResolverInterface`) because the concept of "Department Head" or "Section" differs between applications.
   - Allow developers to define *how* a `required_user_type` is resolved into actual User IDs inside the host application.
2. **Authorization Scopes**:
   - Port the `OrganizationScoped` logic into an optional trait or provide a query scope method (e.g., `scopeForApprover($query, $user)`) to securely load a user's inbox items based on their structural authority.

## Phase 5: Testing & Documentation
1. **Test Suite (Pest)**:
   - Use an in-memory SQLite database to mock an `Approvable` model.
   - Test Random Workflow: Ensure parallel tasks are emitted and master request is only approved when all complete.
   - Test Sequential Workflow: Ensure tasks emit progressively.
2. **Readme / Documentation**:
   - Provide installation instructions, trait usage examples, configuration examples, and how to hook into the emitted Events.
   - **Crucial**: Include dedicated sections explaining how developers can populate the "Modules" dropdown dynamically and how to leverage the `payload` column for Field-Level Approvals (e.g., changing a designation).

## Phase 6: Integration (Host Application)
1. Delete the local host implementation of the approval workflow.
2. Install the new package locally via a `path` repository in the main application's `composer.json` for integration testing.
3. Replace existing hardcoded workflow usage with the package's traits and event listeners.
