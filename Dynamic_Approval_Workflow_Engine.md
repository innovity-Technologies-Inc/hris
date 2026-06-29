# DYNAMIC APPROVAL WORKFLOW ENGINE (Sequential vs Random)

This advanced architecture supports both **Sequential** (step-by-step) and **Random/Parallel** (all-at-once) approval routing. Instead of tracking a single `current_step`, the engine generates distinct "Tasks" (Step Requests) for the approvers.

## 2.1 Complete Database Schema

### Table 1: `approval_workflows`
Defines the overarching workflow strategy for a specific module.
- `id` (PK)
- `module_name` (String: e.g., `leave_request`, `promotion`)
- `type` (Enum: `sequential`, `random`) **<- Dictates generation strategy!**
- `total_steps` (Integer): Total number of approval steps defined for this workflow.
- `required_approvals` (Integer): Used when type is `random` to dictate how many approvals are strictly required.
- `is_active` (Boolean: true)

### Table 2: `approval_workflow_steps`
Defines the blueprint of required approvers.
- `id` (PK)
- `approval_workflow_id` (FK)
- `step_order` (Integer: 1, 2, 3... - *Only matters if workflow is sequential*)
- `required_user_type` (Enum mapping to `App\Enums\UserType`: `Group`, `Company`, `Division`, `Department`, `Section`)

### Table 3: `approval_requests` (The Master Container)
The overarching container that links to the specific item (Promotion/Leave).
- `id` (PK)
- `approvable_type` (String: e.g., `App\Models\Leave\LeaveRequest`)
- `approvable_id` (Integer: e.g., `45`)
- `employee_id` (Integer: **Crucial for OrganizationScoped!**)
- `approval_workflow_id` (FK)
- `status` (Enum: `pending`, `approved`, `rejected`)

### Table 4: `approval_step_requests` (The Individual Tasks)
The literal tasks assigned to the approvers. A record in this table is what the Approver actually clicks "Approve" on.
- `id` (PK)
- `approval_request_id` (FK: Links to Table 3)
- `workflow_step_id` (FK: Links to Table 2 so we know *who* needs to approve this)
- `status` (Enum: `pending`, `approved`, `rejected`)
- `approved_by` (FK: User ID who clicked the button)
- `comments` (Text: Optional note)

---

## 2.2 How the Strategies Work

### Strategy A: Random (Parallel) Workflow
**Scenario:** A Leave Request requires approval from the Department Head AND the HR Admin, but it doesn't matter who approves it first.
1. Employee submits Leave.
2. Engine creates 1 `approval_requests` row (`status = pending`).
3. Engine checks the `approval_workflows` and sees `type = random`.
4. Engine **immediately generates ALL** `approval_step_requests` rows (one for the Dept Head, one for the HR Admin).
5. Both approvers receive notifications simultaneously.
6. When an approver clicks Approve, their specific `approval_step_request` row is marked `approved`.
7. The system checks if the total number of approved tasks meets the `required_approvals` threshold defined in the workflow. If yes, the master `approval_requests` is marked `approved` (and any remaining pending tasks can optionally be auto-cancelled).

### Strategy B: Sequential Workflow
**Scenario:** A Promotion requires the Department Head's approval *before* it can be seen by the HR Admin.
1. Employee submits Promotion.
2. Engine creates 1 `approval_requests` row (`status = pending`).
3. Engine checks the `approval_workflows` and sees `type = sequential`.
4. Engine looks for `step_order = 1` and **generates only 1** `approval_step_requests` row (for the Dept Head).
5. The HR Admin sees nothing yet.
6. The Dept Head approves their task.
7. The Engine sees that Step 1 is done, so it **now generates** the `approval_step_requests` row for Step 2 (HR Admin).
8. When the final step is approved, the master `approval_requests` is marked `approved`.

---

## 2.3 Engine Logic & Verification Checks

### Why do we generate Task Rows?
By generating distinct `approval_step_requests` rows for each approver, we decouple the workflow state from the user. These rows act like "Inbox Items". It allows users to see exactly what is waiting on *them*, enables tracking exactly *who* approved *which* step, and natively supports parallel approvals where multiple users can approve their own specific task at the same time.

### How it Checks: Random Workflows
When an approver clicks "Approve", the engine marks their specific Task row as approved. Then, the engine queries the database:
*Does the count of approved tasks equal or exceed the `required_approvals` defined in the main workflow?*
If **Yes**, the Engine updates the Master `approval_requests` to `approved` and resolves the workflow.

### How it Checks: Sequential Workflows
When an approver clicks "Approve", the engine marks their Task as approved. Then, the engine queries the config table:
*Does a Step exist where step_order > current_step_order?*
If **Yes**, it generates a new Task row for that next step. If **No**, it checks if any pending tasks exist. If not, the Master Request is finalized!

### How OrganizationScoped Secures the Inbox
Because the individual `approval_step_requests` (the Tasks) belong to a Master `approval_requests` record, and that Master record holds the `employee_id`, the security is bulletproof. 

When a user (e.g., a Department Head) loads their Inbox, the `OrganizationScoped` trait automatically appends a WHERE clause ensuring they **only see Tasks where the originating `employee_id` belongs to their department**. You do not need to write complex SQL to figure out who the manager is.
