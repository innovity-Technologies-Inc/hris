# Package Update Plan: Requester Includers & Excluders

This document provides clear, actionable instructions for an AI coding agent to implement **Creator/Requester Includer and Excluder** configuration matching and bypass auto-approval in the `innovity/laravel-approval-engine` package.

---

## 1. Database Schema Specifications
Add the following nullable JSON columns to the `approval_workflows` table (either by updating the package's primary migration `2026_01_01_000000_create_approval_engine_tables.php` or creating a new migration):

### Includers (Workflow applies if the creator matches these)
* `requester_user_types` (JSON, nullable): An array of user type strings.
* `requester_role_ids` (JSON, nullable): An array of Spatie Role IDs.
* `requester_user_ids` (JSON, nullable): An array of User IDs.

### Excluders (Workflow bypasses/auto-approves if the creator matches these)
* `exclude_user_types` (JSON, nullable): An array of user type strings.
* `exclude_role_ids` (JSON, nullable): An array of Spatie Role IDs.
* `exclude_user_ids` (JSON, nullable): An array of User IDs.

---

## 2. Model Casts (`Workflow.php`)
Ensure the package's `Workflow` model casts all six columns as `array`:

```php
protected $casts = [
    'type' => WorkflowType::class,
    'is_active' => 'boolean',
    'requester_user_types' => 'array',
    'requester_role_ids' => 'array',
    'requester_user_ids' => 'array',
    'exclude_user_types' => 'array',
    'exclude_role_ids' => 'array',
    'exclude_user_ids' => 'array',
];
```

---

## 3. Workflow Resolution and Bypass Logic (`WorkflowGenerator.php`)
Implement the matching engine in `WorkflowGenerator::generate()` using these rules in sequence:

### Step A: Identify Creator User
Determine the user who created the request:
```php
$creator = $approvable->creator 
    ?? (method_exists($approvable, 'creator') ? $approvable->creator()->first() : null) 
    ?? auth()->user();

$creatorUserType = null;
if ($creator) {
    $creatorUserType = $creator->user_type instanceof \BackedEnum 
        ? $creator->user_type->value 
        : $creator->user_type;
}
```

### Step B: Helper to Check Roles
Define a role-matching helper that handles Spatie's `hasRole` check:
```php
$hasAnyRole = function ($roleIds) use ($creator) {
    if (empty($roleIds) || !$creator || !method_exists($creator, 'hasRole')) {
        return false;
    }
    foreach ($roleIds as $roleId) {
        if ($creator->hasRole($roleId)) {
            return true;
        }
    }
    return false;
};
```

### Step C: Fetch Workflows
Fetch all active workflows for the module:
```php
$workflows = Workflow::where('module', $module)->where('is_active', true)->get();
if ($workflows->isEmpty()) {
    return null; // Bypassed completely
}
```

### Step D: Check Excluders (First Priority)
Check if the creator matches any **Excluder criteria** defined in any active workflow for this module. If they match, the request bypasses approval (auto-approves immediately):
```php
$isExcluded = $workflows->contains(fn($w) => 
    ($creator && !empty($w->exclude_user_ids) && in_array($creator->id, $w->exclude_user_ids)) ||
    ($creatorUserType && !empty($w->exclude_user_types) && in_array($creatorUserType, $w->exclude_user_types)) ||
    (!empty($w->exclude_role_ids) && $hasAnyRole($w->exclude_role_ids))
);

if ($isExcluded) {
    return $this->autoApproveRequest($approvable, $workflows->first(), $payload);
}
```

### Step E: Specific Includer Matching (Second Priority)
Evaluate workflows with specific **Includer criteria** in order of specificity:
```php
$matchedWorkflow = null;

if ($creator) {
    // 1. Specific User ID Match
    $matchedWorkflow = $workflows->first(fn($w) => 
        !empty($w->requester_user_ids) && in_array($creator->id, $w->requester_user_ids)
    );

    // 2. User Type + Role ID Match
    if (!$matchedWorkflow) {
        $matchedWorkflow = $workflows->first(fn($w) => 
            !empty($w->requester_user_types) && in_array($creatorUserType, $w->requester_user_types) &&
            !empty($w->requester_role_ids) && $hasAnyRole($w->requester_role_ids)
        );
    }

    // 3. Role ID only Match
    if (!$matchedWorkflow) {
        $matchedWorkflow = $workflows->first(fn($w) => 
            !empty($w->requester_role_ids) && $hasAnyRole($w->requester_role_ids) &&
            empty($w->requester_user_types)
        );
    }

    // 4. User Type only Match
    if (!$matchedWorkflow) {
        $matchedWorkflow = $workflows->first(fn($w) => 
            !empty($w->requester_user_types) && in_array($creatorUserType, $w->requester_user_types) &&
            empty($w->requester_role_ids)
        );
    }
}
```

### Step F: Evaluate Includer Bypass vs. Global Fallback
1. **If a specific workflow matched**: Use that workflow to generate the request and emit the steps.
2. **If no specific workflow matched**:
   * Check if there are *any* specific includer workflows defined for this module (workflows where requester columns are not empty).
   * **If yes**: It means the administrator targeted only specific groups for approval. Since the current creator is outside these groups, their request **does not need approval** (Auto-approves immediately!).
   * **If no**: Check if a global fallback workflow exists (where all requester columns are empty). If so, match it. If not, auto-approve the request.

```php
if ($matchedWorkflow) {
    return $this->createPendingRequest($approvable, $matchedWorkflow, $payload);
}

// Check if any workflow for this module specifies any Includers
$hasIncluderWorkflows = $workflows->contains(fn($w) => 
    !empty($w->requester_user_types) || 
    !empty($w->requester_role_ids) || 
    !empty($w->requester_user_ids)
);

if ($hasIncluderWorkflows) {
    // Creator is not in the included targets -> Auto-approve!
    return $this->autoApproveRequest($approvable, $workflows->first(), $payload);
}

// Fallback to Global Default Workflow (all requester arrays empty)
$globalWorkflow = $workflows->first(fn($w) => 
    empty($w->requester_user_types) && 
    empty($w->requester_role_ids) && 
    empty($w->requester_user_ids)
);

if ($globalWorkflow) {
    return $this->createPendingRequest($approvable, $globalWorkflow, $payload);
}

return $this->autoApproveRequest($approvable, $workflows->first(), $payload);
```

### Step G: Helper Methods in `WorkflowGenerator.php`
```php
protected function createPendingRequest($approvable, $workflow, $payload)
{
    $request = $approvable->approvalRequests()->create([
        'workflow_id' => $workflow->id,
        'payload' => $payload,
        'status' => 'pending',
    ]);

    $this->emitter->emit($request);
    ApprovalRequested::dispatch($request);

    return $request;
}

protected function autoApproveRequest($approvable, $workflow, $payload)
{
    $request = $approvable->approvalRequests()->create([
        'workflow_id' => $workflow->id,
        'payload' => $payload,
        'status' => 'approved',
    ]);

    ApprovalCompleted::dispatch($request);

    return $request;
}
```

---

## 4. Test Cases to Write
Ensure the package tests verify:
1. **Excluder Bypass**: Creating a workflow targeting all users except those in the excluder lists. Assert that when an excluded creator requests, it auto-approves immediately without generating steps.
2. **Includer Target**: Creating a workflow that specifies `requester_user_types = ['division']`. Assert that a `division` creator's request generates steps, while an `employee` creator's request is auto-approved immediately.
3. **Global Fallback**: When no criteria match and no specific includers exclude them, the global fallback workflow steps are correctly emitted.
