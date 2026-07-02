# Implementation Plan: Enhancing Approval Workflows with Dynamic Step Types

This document outlines the step-by-step plan to add three distinct step types (`user-type`, `role-user`, and `specific-user`) to the Approval Workflow Engine, permitting direct target assignments by Role or Specific User ID alongside the existing organizational hierarchy resolving.

---

## 1. Database Schema Changes

We will create a new migration to add the required columns to the `approval_workflow_steps` table.

### Migration File: `database/migrations/xxxx_xx_xx_add_type_role_user_to_workflow_steps_table.php`
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $stepsTable = config('approval-engine.table_names.workflow_steps', 'approval_workflow_steps');

        Schema::table($stepsTable, function (Blueprint $table) {
            // Step type selection: user-type, role-user, specific-user
            $table->string('type')->default('user-type')->after('workflow_id');
            
            // Nullable foreign keys for role and user target resolutions
            $table->unsignedBigInteger('role_id')->nullable()->after('required_user_type');
            $table->unsignedBigInteger('user_id')->nullable()->after('role_id');

            // Set existing column required_user_type as nullable (since specific-user does not require it)
            $table->string('required_user_type')->nullable()->change();

            // Set up optional constraints
            $table->foreign('role_id')->references('id')->on('roles')->nullOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        $stepsTable = config('approval-engine.table_names.workflow_steps', 'approval_workflow_steps');

        Schema::table($stepsTable, function (Blueprint $table) {
            $table->dropForeign([$stepsTable . '_role_id_foreign']);
            $table->dropForeign([$stepsTable . '_user_id_foreign']);
            $table->dropColumn(['type', 'role_id', 'user_id']);
            $table->string('required_user_type')->nullable(false)->change();
        });
    }
};
```

---

## 2. Approver Resolver Modifications

The concrete implementation class [ApproverResolver.php](file:///P:/Project/Web/hrms/app/Services/ApproverResolver.php) needs to be updated. Since the Spatie package interface `ApproverResolverInterface` typehints a `string $requiredUserType` rather than the `WorkflowStep` object itself, we can adapt in two ways:

1. **Option A (Preserve Interface signature - Recommended)**:
   Modify the caller sites to pass the database ID of the `WorkflowStep` as the first argument instead of `required_user_type`. We can parse the input inside `resolve()`: if it's numeric, find the `WorkflowStep` record and run the specialized checks; if not, fallback to the legacy user type resolver.
2. **Option B (Update Interface - Requires Package/Vendor modification)**:
   Change the interface signature in the package itself, but this requires editing vendor files which will break during dependency updates.

### Option A Implementation in `App\Services\ApproverResolver`:

```php
<?php

namespace App\Services;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Innovity\ApprovalEngine\Contracts\ApproverResolverInterface;
use Innovity\ApprovalEngine\Models\WorkflowStep;

class ApproverResolver implements ApproverResolverInterface
{
    public function resolve(string $requiredUserType, Model $approvable): array
    {
        // 1. Identify if a WorkflowStep ID was passed instead of a raw type string
        $step = null;
        if (is_numeric($requiredUserType)) {
            $step = WorkflowStep::find((int) $requiredUserType);
        }

        // 2. Fallback to basic user-type routing if no DB Step is found
        $type = $step ? $step->type : 'user-type';

        switch ($type) {
            case 'specific-user':
                return $step && $step->user_id ? [$step->user_id] : [];

            case 'role-user':
                if (!$step || !$step->role_id || !$step->required_user_type) {
                    return [];
                }
                
                // Get users of a specific user type (e.g. Department Head)
                $typeUserIds = $this->resolveUserType($step->required_user_type, $approvable);
                
                // Filter users to only those who also possess the Spatie Role
                return User::whereIn('id', $typeUserIds)
                    ->role($step->role_id) // Spatie Laravel Permission helper
                    ->pluck('id')
                    ->toArray();

            case 'user-type':
            default:
                $userTypeString = $step ? $step->required_user_type : $requiredUserType;
                return $this->resolveUserType($userTypeString, $approvable);
        }
    }

    /**
     * Resolve users by Organizational Hierarchy (Original Code)
     */
    protected function resolveUserType(?string $requiredUserType, Model $approvable): array
    {
        if (!$requiredUserType) return [];

        $requestingUser = null;
        if (method_exists($approvable, 'user')) {
            $requestingUser = $approvable->user;
        } elseif (method_exists($approvable, 'getEmployee')) {
            $emp = $approvable->getEmployee()->withoutGlobalScopes()->first();
            $requestingUser = $emp ? $emp->user : null;
        } elseif (method_exists($approvable, 'creator')) {
            $requestingUser = $approvable->creator;
        }
        
        if (!$requestingUser) return [];

        $employee = $requestingUser->employee()->withoutGlobalScopes()->with(['officeInfo' => function($q) {
            $q->withoutGlobalScopes();
        }])->first();

        if (!$employee || !$employee->officeInfo) return [];

        $officeInfo = $employee->officeInfo;
        $enumValue = UserType::tryFrom($requiredUserType);

        if ($enumValue) {
            $query = User::where('user_type', $enumValue->value);

            if ($enumValue !== UserType::Group) {
                $query->whereHas('employee', function ($q) use ($enumValue, $officeInfo) {
                    $q->withoutGlobalScopes()->whereHas('officeInfo', function ($q2) use ($enumValue, $officeInfo) {
                        $q2->withoutGlobalScopes();
                        match ($enumValue) {
                            UserType::Company => $q2->where('current_company_id', $officeInfo->current_company_id),
                            UserType::Division => $q2->where('current_division_id', $officeInfo->current_division_id),
                            UserType::Department => $q2->where('current_department_id', $officeInfo->current_department_id),
                            UserType::Section => $q2->where('current_section_id', $officeInfo->current_section_id),
                            UserType::BusinessUnit => $q2->where('current_business_unit_id', $officeInfo->current_business_unit_id),
                            default => $q2,
                        };
                    });
                });
            }

            return $query->pluck('id')->toArray();
        }

        // Custom manual types
        if ($requiredUserType === 'manager' && $requestingUser->manager_id) {
            return [$requestingUser->manager_id];
        }

        if ($requiredUserType === 'hr_admin') {
            return User::role('hr_admin')->pluck('id')->toArray();
        }

        return [];
    }
}
```

---

## 3. Modifying Caller Sites to Pass Workflow Step ID

We need to edit the locations that call the `$resolver->resolve()` method.

### File: `app/Providers/AppServiceProvider.php` (Line 105)
```diff
- $approverIds = $resolver->resolve($stepRequest->workflowStep->required_user_type, $approvable);
+ // Pass the step's primary key ID to resolve full db properties
+ $approverIds = $resolver->resolve((string) $stepRequest->workflowStep->id, $approvable);
```

### File: `resources/views/approval_engine/workflow_history.blade.php` (Line 21)
```diff
- $authorizedUserIds = $resolver->resolve($step->workflowStep->required_user_type, $approvable);
+ // Pass the step ID instead of just required_user_type
+ $authorizedUserIds = $resolver->resolve((string) $step->workflowStep->id, $approvable);
```

---

## 4. UI Builders: Workflow Creation and Edit Forms

We must modify the controller to load Spatie Roles and System Users list so that they can be populated in dropdown selections on the builder UI.

### File: `app/Http/Controllers/Setting/ApprovalWorkflowController.php`
```diff
    public function create()
    {
        $modules = config('approval-engine.modules');
        $userTypes = UserType::cases();
+       $roles = \Spatie\Permission\Models\Role::orderBy('name')->get();
+       $users = \App\Models\User::orderBy('name')->get(); // Autocomplete or simple list

-       return view('setting.approval_workflow.create', compact('modules', 'userTypes'));
+       return view('setting.approval_workflow.create', compact('modules', 'userTypes', 'roles', 'users'));
    }

    public function edit($id)
    {
        $workflow = ApprovalWorkflow::with('steps')->findOrFail($id);
        $modules = config('approval-engine.modules');
        $userTypes = UserType::cases();
+       $roles = \Spatie\Permission\Models\Role::orderBy('name')->get();
+       $users = \App\Models\User::orderBy('name')->get();

-       return view('setting.approval_workflow.edit', compact('workflow', 'modules', 'userTypes'));
+       return view('setting.approval_workflow.edit', compact('workflow', 'modules', 'userTypes', 'roles', 'users'));
    }
```

Add validation and store logic in `store()` / `update()` methods:
```php
        $request->validate([
            'module_name' => 'required|string',
            'type' => 'required|in:sequential,random',
            'required_approvals' => 'nullable|integer|min:1',
            'steps' => 'required|array|min:1',
            'steps.*.type' => 'required|in:user-type,role-user,specific-user',
            'steps.*.required_user_type' => 'nullable|required_if:steps.*.type,user-type,role-user|string',
            'steps.*.role_id' => 'nullable|required_if:steps.*.type,role-user|exists:roles,id',
            'steps.*.user_id' => 'nullable|required_if:steps.*.type,specific-user|exists:users,id',
        ]);
```

### File: `resources/views/setting/approval_workflow/create.blade.php` (And `edit.blade.php`)
We will rewrite the `<template id="stepTemplate">` tag and update JQuery logic:

```html
<template id="stepTemplate">
    <div class="card mb-2 step-row bg-light border shadow-none">
        <div class="card-body p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="step-handle text-muted" style="cursor: grab;">
                    <i data-feather="menu"></i>
                </div>
                <div class="step-number fw-bold bg-white border rounded px-2 py-1 text-center" style="min-width: 40px;">
                    1
                </div>
                
                <!-- Type Selection -->
                <div style="min-width: 150px;">
                    <select class="form-select step-type-select" name="steps[INDEX][type]" required>
                        <option value="user-type">User Type Only</option>
                        <option value="role-user">User Type + Role</option>
                        <option value="specific-user">Specific User</option>
                    </select>
                </div>

                <!-- User Type Selector -->
                <div class="flex-grow-1 user-type-wrapper">
                    <select class="form-select user-type-select" name="steps[INDEX][required_user_type]">
                        <option value="" disabled selected>Select User Type</option>
                        @foreach($userTypes as $type)
                            <option value="{{ $type->value }}">{{ ucfirst(str_replace('-', ' ', $type->value)) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Role Selector -->
                <div class="flex-grow-1 role-wrapper" style="display: none;">
                    <select class="form-select role-select" name="steps[INDEX][role_id]">
                        <option value="" disabled selected>Select Spatie Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- User Selector -->
                <div class="flex-grow-1 user-wrapper" style="display: none;">
                    <select class="form-select user-select select2" name="steps[INDEX][user_id]">
                        <option value="" disabled selected>Select Specific User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <button type="button" class="btn btn-sm btn-danger remove-step-btn">
                        <i style="height: 14px; width: 14px" data-feather="x"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
```

#### Updated JQuery dynamic show/hide in `<script>`:
```javascript
    // Handle toggle display on change
    stepsContainer.on('change', '.step-type-select', function() {
        const row = $(this).closest('.step-row');
        const type = $(this).val();
        
        row.find('.user-type-wrapper, .role-wrapper, .user-wrapper').hide();
        row.find('.user-type-select, .role-select, .user-select').removeAttr('required');

        if (type === 'user-type') {
            row.find('.user-type-wrapper').show();
            row.find('.user-type-select').attr('required', true);
        } else if (type === 'role-user') {
            row.find('.user-type-wrapper, .role-wrapper').show();
            row.find('.user-type-select, .role-select').attr('required', true);
        } else if (type === 'specific-user') {
            row.find('.user-wrapper').show();
            row.find('.user-select').attr('required', true);
        }
    });

    // Adapt updateSteps() names correctly
    function updateSteps() {
        const rows = stepsContainer.find('.step-row');
        rows.each(function(index) {
            $(this).find('.step-number').text(index + 1);
            $(this).find('.step-type-select').attr('name', `steps[${index}][type]`);
            $(this).find('.user-type-select').attr('name', `steps[${index}][required_user_type]`);
            $(this).find('.role-select').attr('name', `steps[${index}][role_id]`);
            $(this).find('.user-select').attr('name', `steps[${index}][user_id]`);
        });
    }
```
