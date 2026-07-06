# Approval Engine Refactoring & Integration Plan

This plan details how to encapsulate all workflow configurations, controllers, and routing within the `laravel-approval-engine` package, leaving only the UI layout templates and styling in the main HRMS application.

---

## 🏗️ Architectural Overview

```mermaid
graph TD
    subgraph Host Application (HRMS)
        UI[Blade UI Views] -->|Axios AJAX| Route[Package Routes]
        Impl[ApproverResolver] -->|Implements| Contract[ApproverResolverInterface]
    end

    subgraph laravel-approval-engine Package
        Route -->|Invokes| Ctrl[Package Controllers]
        Ctrl -->|Resolves| Models[Package Models]
        Ctrl -->|Queries| Contract
    end
```

---

## 📋 The 4-Phase Implementation Plan

### Phase 1: Package Models & Configurations Setup
1. **Add Workflow Configuration Endpoints**:
   - Define migrations inside the package for `approval_workflows` and `approval_workflow_steps` (if not already present).
   - Ensure package models (`Workflow`, `WorkflowStep`, `ApprovalRequest`, `ApprovalStepRequest`) contain all necessary relationships.
2. **Expose Configurable Route Settings**:
   - Publish a default config file `config/approval-engine.php` defining the route prefix, middlewares, and authorization guards:
     ```php
     return [
         'routes' => [
             'prefix' => 'admin/approval-engine',
             'middleware' => ['web', 'auth', 'permission:workflow-settings.manage'],
         ],
         'user_model' => \App\Models\User::class,
     ];
     ```

### Phase 2: Implement Package Controllers & API Routes
Migrate controllers from the local application into the package's `src/Http/Controllers/` namespace.

1. **`WorkflowConfigController.php` (Package)**:
   - Handle CRUD operations for workflows and workflow steps configuration.
   - Expose the following JSON endpoints:
     - `GET /workflows` (List all blueprints)
     - `POST /workflows` (Create/Update blueprint and sequential steps)
     - `DELETE /workflows/{id}` (Delete blueprint)
2. **`ApprovalActionController.php` (Package)**:
   - Handle reviewer decisions (Approve/Reject) using the transactional concurrency lock structure (`lockForUpdate`).
3. **Register Package Routes**:
   - Create `routes/web.php` inside the package:
     ```php
     use Illuminate\Support\Facades\Route;
     use Innovity\ApprovalEngine\Http\Controllers\WorkflowConfigController;
     use Innovity\ApprovalEngine\Http\Controllers\ApprovalActionController;

     Route::group([
         'prefix' => config('approval-engine.routes.prefix', 'approval-engine'),
         'middleware' => config('approval-engine.routes.middleware', ['web', 'auth']),
     ], function () {
         Route::apiResource('workflows', WorkflowConfigController::class);
         Route::post('step-requests/{id}/action', [ApprovalActionController::class, 'action'])
              ->name('approval-engine.step-action');
     });
     ```

### Phase 3: Host Application Cleanup
Once package controllers are loaded, clean up redundant code in the HRMS project.

1. **Remove Local Controllers**:
   - Delete local controllers in `app/Http/Controllers/` that duplicate the package controllers (e.g. `ApprovalActionController.php`).
2. **Map Axios/JavaScript Requests**:
   - Update AJAX calls in Blade templates (like `workflow_history.blade.php` and workflow setup views) to submit directly to the new package-defined routes:
     - Form action submissions redirect to `/admin/approval-engine/step-requests/{id}/action`.
     - Settings dashboard queries JSON records from `/admin/approval-engine/workflows`.

### Phase 4: Validation & Testing
1. **Unit and Feature Testing**:
   - Run tests using a mock workflow instance to verify that calling step action endpoints correctly updates the database and triggers the listener events.
2. **Cache Verification**:
   - Verify that configuration overrides can be successfully cached and cleared via `php artisan optimize`.
