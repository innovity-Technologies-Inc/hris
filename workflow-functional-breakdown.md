# Approval Workflow Functional Breakdown

This document provides a complete, end-to-end breakdown of how the Innovity Approval Engine operates behind the scenes in the HRMS system, detailing the interactions between the package classes and the application's core logic.

## The Core Database Models
The engine relies on a strict hierarchy of database tables to track state:

*   **`Workflow` (`vendor/innovity/laravel-approval-engine/src/Models/Workflow.php`) (The Blueprint):** Defines the overarching rules for a specific module (like "Promotion"). It tracks things like whether the flow is `Sequential` (one after another) or `Random` (anyone can approve in any order), and the required threshold of approvals.
    *   **Table:** `approval_workflows`
    *   **Fields:** `id`, `name`, `module` (e.g., 'promotion'), `type` (sequential/random), `total_steps`, `required_approvals`, `is_active`, `created_at`, `updated_at`

*   **`WorkflowStep` (`vendor/innovity/laravel-approval-engine/src/Models/WorkflowStep.php`) (The Blueprint Steps):** The individual tiers inside a Workflow (e.g., Step 1: Section, Step 2: Department, Step 3: Division).
    *   **Table:** `approval_workflow_steps`
    *   **Fields:** `id`, `workflow_id` (Foreign Key), `name`, `step_order` (1, 2, 3...), `required_user_type` (e.g., 'department'), `created_at`, `updated_at`

*   **`ApprovalRequest` (`vendor/innovity/laravel-approval-engine/src/Models/ApprovalRequest.php`) (The Master Tracker):** When a Promotion is created, one of these is generated. This is the master record that tracks the *overall* progress of that specific Promotion through the blueprint. 
    *   **Table:** `approval_requests`
    *   **Fields:** `id`, `workflow_id`, `approvable_type` (e.g., 'App\Models\Payroll\Promotion'), `approvable_id` (e.g., 7), `status` ('pending', 'approved', 'rejected'), `payload` (JSON), `created_at`, `updated_at`

*   **`ApprovalStepRequest` (`vendor/innovity/laravel-approval-engine/src/Models/ApprovalStepRequest.php`) (The Active Token):** These are the individual "tasks" assigned to approvers. The engine generates these one by one (or all at once if random) to ask for a yes/no decision.
    *   **Table:** `approval_step_requests`
    *   **Fields:** `id`, `approval_request_id` (Foreign Key to master tracker), `workflow_step_id` (Foreign Key to blueprint step), `approver_id` (ID of user who took action), `status` ('pending', 'approved', 'rejected'), `comments`, `action_taken_at`, `created_at`, `updated_at`

## Step-by-Step Functional Cycle

### 1. Initiation (Creating the Request)
**The Hook (`Approvable` Trait in `vendor/innovity/laravel-approval-engine/src/Traits/Approvable.php`):** 
The core models (e.g., `Promotion`, `Increment`) use the `Approvable` trait. It is important to note that **this trait does not automatically trigger the workflow** when a model is saved. This is intentional to prevent accidental workflows starting when users save "Drafts" or when running database seeders.

Instead, the `Approvable` trait provides a helper method called `startWorkflow()`.

**The Ignition:**
To start the engine, you must manually call the helper method from your controller (e.g., `PromotionController@save`) right after the model is created and ready for approval:
```php
$promotion->startWorkflow('promotion');
```

When this generator is called, it performs the following:
1. **Looks up the Blueprint:** It searches for an active `Workflow` record matching the module string (`'promotion'`).
2. **Creates the Master Tracker:** It creates the polymorphic `ApprovalRequest` record linked to the newly created Promotion model, setting its status to `pending`.
3. **Hands off to the Dealer:** It passes the new master request to the `TaskEmitter` to figure out what happens first.
4. **Broadcasts an Event:** It dispatches the `ApprovalRequested` system event.

### 2. Task Emission (`TaskEmitter` in `vendor/innovity/laravel-approval-engine/src/Services/TaskEmitter.php`)
The `TaskEmitter`'s only job is to "deal the cards" (generate `ApprovalStepRequests`). 
*   **If the Workflow is Random:** It creates a step request for *every single step* in the blueprint simultaneously. 
*   **If the Workflow is Sequential:** It looks at the step orders (1, 2, 3), finds the next step that hasn't been created yet, and emits *only* that one step request.

### 3. Notification Routing & Approver Resolution
**Catching the Step:** 
In `AppServiceProvider` (`app/Providers/AppServiceProvider.php`), there is an Eloquent event listener waiting for any `ApprovalStepRequest` to be created. Because it is attached to the database `created` event inside the Service Provider, the notification logic is entirely decoupled from your controllers.

**Resolving Approvers (`ApproverResolverInterface`):** 
The engine generates a step requiring a specific authority level (e.g., "Department"), but the engine itself knows nothing about your company structure. It relies on your application's `App\Services\ApproverResolver` (`app/Services/ApproverResolver.php`) to find the right person.
*   The resolver looks at the employee and identifies who holds the required authority (finding the user whose `UserType` is "Department" and shares the same `current_department_id` as the employee).
*   *Note: This resolver is designed to bypass standard global scopes (`withoutGlobalScopes`) to ensure that routing succeeds regardless of which user triggered the event.*

**Generating the Notifications:** 
For every resolved approver found (e.g., User IDs `[45, 82]`), the system loops through them and generates two types of notifications:

1. **Email Notification:** It uses Laravel's standard Notification system to send an email via `ApprovalActionRequiredNotification` (`app/Notifications/Approval/ApprovalActionRequiredNotification.php`).
2. **Dashboard (In-App) Notification:** 
   * It dynamically builds the redirect URL by taking the module name from the workflow (e.g., `leave`) and checking if a route named `{module_name}.show` exists. If so, it generates the route (e.g., `hrms.com/leaves/5/view`).
   * Finally, it triggers `NotificationServices::createNotification` (`app/Services/Setting/NotificationServices.php`) to generate the bell-icon alert in the top right corner of the dashboard with the dynamic URL attached.

### 4. User Interaction (The Frontend)
*   **Viewing the Timeline:** The targeted approver clicks the notification and is routed to the view page. The `workflow_history.blade.php` component (`resources/views/approval_engine/workflow_history.blade.php`) reads the pending `ApprovalStepRequest`.
*   **Authorization Check:** The blade file re-runs the `ApproverResolver` in real-time. If the currently logged-in user is on the list of authorized approvers for that specific pending step, the "Approve" and "Reject" buttons are rendered.
*   **Submitting the Action:** When the user clicks Approve/Reject, an Axios AJAX `POST` request is fired to the `ApprovalActionController` (`app/Http/Controllers/ApprovalActionController.php`).

### 5. Package Processing (`ApprovalResolver` in `vendor/innovity/laravel-approval-engine/src/Services/ApprovalResolver.php`)
This acts as the "Judge" of the engine. When the AJAX request hits, it evaluates the decision:

**When an Approver clicks "Approve":**
1. It updates the specific `ApprovalStepRequest` to `approved`.
2. It evaluates the Master `ApprovalRequest`.
3. If sequential, it asks the `TaskEmitter` to generate the next step in line. (This loops back to **Section 3**, generating the next notification).
4. If random, it checks if the "required approval count" has been met.
5. If there are no more steps left to generate, the Judge declares the Master Request as `approved` and fires the `ApprovalCompleted` event.

**When an Approver clicks "Reject":**
1. It updates the specific `ApprovalStepRequest` to `rejected`.
2. **Sequential:** If one person rejects it, the Judge immediately kills the entire workflow. The Master Request becomes `rejected`, and it fires the `ApprovalRejected` event.
3. **Random:** If it's random, it evaluates whether to wait for more approvals or fail immediately if the mathematical threshold can no longer be reached.

### 6. Finalizing the Core Data (`WorkflowStatusListener` in `app/Listeners/WorkflowStatusListener.php`)
Once the `Innovity\ApprovalEngine` finishes its mathematics, its job is strictly done. It broadcasts the final result back to the main Laravel application.

*   Your custom `WorkflowStatusListener` intercepts the `ApprovalCompleted` or `ApprovalRejected` events.
*   It checks the workflow's `module` property (e.g., `'promotion'`).
*   It then fetches the core `Promotion` model and physically updates its status column from `pending` to `approved` or `rejected`. 

This clean separation ensures the package strictly handles the rules and timeline of the workflow, while the HRMS system handles the organizational security scoping, email notifications, and final model mutations!

## Workflow Events & Dispatching

The engine fires system-wide events at critical transition points in the workflow. These events are dispatched by the package and can be listened to by the application to execute custom side effects (like updating live databases or logging audits).

### 1. `ApprovalRequested`
*   **Namespace:** `Innovity\ApprovalEngine\Events\ApprovalRequested`
*   **Where it is generated:** Inside `vendor/innovity/laravel-approval-engine/src/Services/WorkflowGenerator.php` (during request initialization).
*   **Trigger:** When an approvable model initiates a new workflow via `$approvable->startWorkflow()`.

### 2. `ApprovalStepApproved`
*   **Namespace:** `Innovity\ApprovalEngine\Events\ApprovalStepApproved`
*   **Where it is generated:** Inside `vendor/innovity/laravel-approval-engine/src/Services/ApprovalResolver.php` (during step approval).
*   **Trigger:** When an individual step request is approved by an assigned reviewer.

### 3. `ApprovalCompleted`
*   **Namespace:** `Innovity\ApprovalEngine\Events\ApprovalCompleted`
*   **Where it is generated:** Inside `vendor/innovity/laravel-approval-engine/src/Services/ApprovalResolver.php` (upon workflow success).
*   **Trigger:** When the total approved steps meet the blueprint's threshold (sequential or random), completing the entire workflow successfully.

### 4. `ApprovalRejected`
*   **Namespace:** `Innovity\ApprovalEngine\Events\ApprovalRejected`
*   **Where it is generated:** Inside `vendor/innovity/laravel-approval-engine/src/Services/ApprovalResolver.php` (upon workflow failure).
*   **Trigger:** When a step rejection makes it mathematically impossible to satisfy the required approvals, or when any step in a sequential workflow is rejected.
