<?php

namespace App\Http\Controllers\Setting;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Innovity\ApprovalEngine\Models\Workflow as ApprovalWorkflow;
use Illuminate\Support\Facades\DB;

class ApprovalWorkflowController extends Controller
{
    public function index()
    {
        $workflows = ApprovalWorkflow::with('steps')->get();
        return view('setting.approval_workflow.index', compact('workflows'));
    }

    public function create()
    {
        $existingModules = ApprovalWorkflow::pluck('module')->toArray();
        $modules = array_filter(config('approval-engine.modules', []), function ($key) use ($existingModules) {
            return !in_array($key, $existingModules);
        }, ARRAY_FILTER_USE_KEY);

        $userTypes = UserType::cases();
        $roles = \Spatie\Permission\Models\Role::orderBy('name')->get();
        $users = \App\Models\User::orderBy('name')->get();
        return view('setting.approval_workflow.create', compact('modules', 'userTypes', 'roles', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'module_name' => 'required|string|unique:approval_workflows,module',
            'type' => 'required|in:sequential,random',
            'required_approvals' => 'nullable|integer|min:1|max:' . max(1, count($request->steps ?? [])),
            'steps' => 'required|array|min:1',
            'steps.*.type' => 'required|in:user-type,role-user,specific-user',
            'steps.*.required_user_type' => 'nullable|required_if:steps.*.type,user-type,role-user|string',
            'steps.*.role_id' => 'nullable|required_if:steps.*.type,role-user|exists:roles,id',
            'steps.*.user_id' => 'nullable|required_if:steps.*.type,specific-user|exists:users,id',
            
            // Inclusions & Exclusions validation
            'requester_user_types' => 'nullable|array',
            'requester_role_ids' => 'nullable|array',
            'requester_user_ids' => 'nullable|array',
            'exclude_user_types' => 'nullable|array',
            'exclude_role_ids' => 'nullable|array',
            'exclude_user_ids' => 'nullable|array',
        ], [
            'module_name.unique' => 'An approval workflow already exists for this module.',
        ]);

        if ($request->type === 'sequential' && is_array($request->steps)) {
            $error = $this->validateSequentialSteps($request->steps);
            if ($error) {
                return response()->json(['message' => 'Validation failed: ' . $error], 422);
            }
        }

        // Sanitize Inclusion fields
        $requesterUserTypes = $request->scope_type !== 'all' && $request->has('requester_user_types') ? $request->requester_user_types : null;
        $requesterRoleIds = $request->scope_type !== 'all' && $request->has('requester_role_ids') ? $request->requester_role_ids : null;
        $requesterUserIds = $request->scope_type !== 'all' && $request->has('requester_user_ids') ? $request->requester_user_ids : null;

        if ($request->scope_type === 'user_type') {
            $requesterRoleIds = null;
            $requesterUserIds = null;
        } elseif ($request->scope_type === 'role') {
            $requesterUserTypes = null;
            $requesterUserIds = null;
        } elseif ($request->scope_type === 'user_type_role') {
            $requesterUserIds = null;
        } elseif ($request->scope_type === 'specific_user') {
            $requesterUserTypes = null;
            $requesterRoleIds = null;
        } else {
            $requesterUserTypes = null;
            $requesterRoleIds = null;
            $requesterUserIds = null;
        }

        // Sanitize Exclusion fields
        $excludeUserTypes = $request->exclude_scope_type !== 'none' && $request->has('exclude_user_types') ? $request->exclude_user_types : null;
        $excludeRoleIds = $request->exclude_scope_type !== 'none' && $request->has('exclude_role_ids') ? $request->exclude_role_ids : null;
        $excludeUserIds = $request->exclude_scope_type !== 'none' && $request->has('exclude_user_ids') ? $request->exclude_user_ids : null;

        if ($request->exclude_scope_type === 'user_type') {
            $excludeRoleIds = null;
            $excludeUserIds = null;
        } elseif ($request->exclude_scope_type === 'role') {
            $excludeUserTypes = null;
            $excludeUserIds = null;
        } elseif ($request->exclude_scope_type === 'user_type_role') {
            $excludeUserIds = null;
        } elseif ($request->exclude_scope_type === 'specific_user') {
            $excludeUserTypes = null;
            $excludeRoleIds = null;
        } else {
            $excludeUserTypes = null;
            $excludeRoleIds = null;
            $excludeUserIds = null;
        }

        DB::beginTransaction();
        try {
            $workflow = ApprovalWorkflow::create([
                'name' => ucfirst($request->module_name) . ' Workflow',
                'module' => $request->module_name,
                'type' => $request->type,
                'total_steps' => count($request->steps),
                'required_approvals' => $request->type === 'random' ? $request->required_approvals : null,
                'is_active' => $request->is_active == '1',
                
                // Save Inclusions & Exclusions
                'requester_user_types' => $requesterUserTypes,
                'requester_role_ids' => $requesterRoleIds,
                'requester_user_ids' => $requesterUserIds,
                'exclude_user_types' => $excludeUserTypes,
                'exclude_role_ids' => $excludeRoleIds,
                'exclude_user_ids' => $excludeUserIds,
            ]);

            foreach ($request->steps as $index => $step) {
                // Determine a descriptive step name based on its type
                $stepName = 'Step ' . ($index + 1) . ' - ';
                if ($step['type'] === 'user-type') {
                    $stepName .= ucfirst(str_replace('-', ' ', $step['required_user_type']));
                } elseif ($step['type'] === 'role-user') {
                    $roleName = \Spatie\Permission\Models\Role::find($step['role_id'])->name ?? 'Role';
                    $stepName .= ucfirst(str_replace('-', ' ', $step['required_user_type'])) . ' (' . $roleName . ')';
                } elseif ($step['type'] === 'specific-user') {
                    $userName = \App\Models\User::find($step['user_id'])->name ?? 'User';
                    $stepName .= $userName;
                }

                $workflow->steps()->create([
                    'name' => $stepName,
                    'step_order' => $index + 1,
                    'type' => $step['type'],
                    'required_user_type' => $step['required_user_type'] ?? null,
                    'role_id' => $step['role_id'] ?? null,
                    'user_id' => $step['user_id'] ?? null,
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Workflow created successfully.', 'redirect' => route('setting.approval_workflows.index')], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Error creating workflow: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong. Please try again later.'], 500);
        }
    }

    public function edit($id)
    {
        $workflow = ApprovalWorkflow::with('steps')->findOrFail($id);
        
        $existingModules = ApprovalWorkflow::where('id', '!=', $id)->pluck('module')->toArray();
        $modules = array_filter(config('approval-engine.modules', []), function ($key) use ($existingModules) {
            return !in_array($key, $existingModules);
        }, ARRAY_FILTER_USE_KEY);

        $userTypes = UserType::cases();
        $roles = \Spatie\Permission\Models\Role::orderBy('name')->get();
        $users = \App\Models\User::orderBy('name')->get();
        return view('setting.approval_workflow.edit', compact('workflow', 'modules', 'userTypes', 'roles', 'users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'module_name' => 'required|string|unique:approval_workflows,module,' . $id,
            'type' => 'required|in:sequential,random',
            'required_approvals' => 'nullable|integer|min:1|max:' . max(1, count($request->steps ?? [])),
            'steps' => 'required|array|min:1',
            'steps.*.type' => 'required|in:user-type,role-user,specific-user',
            'steps.*.required_user_type' => 'nullable|required_if:steps.*.type,user-type,role-user|string',
            'steps.*.role_id' => 'nullable|required_if:steps.*.type,role-user|exists:roles,id',
            'steps.*.user_id' => 'nullable|required_if:steps.*.type,specific-user|exists:users,id',
            
            // Inclusions & Exclusions validation
            'requester_user_types' => 'nullable|array',
            'requester_role_ids' => 'nullable|array',
            'requester_user_ids' => 'nullable|array',
            'exclude_user_types' => 'nullable|array',
            'exclude_role_ids' => 'nullable|array',
            'exclude_user_ids' => 'nullable|array',
        ], [
            'module_name.unique' => 'An approval workflow already exists for this module.',
        ]);

        if ($request->type === 'sequential' && is_array($request->steps)) {
            $error = $this->validateSequentialSteps($request->steps);
            if ($error) {
                return response()->json(['message' => 'Validation failed: ' . $error], 422);
            }
        }

        // Sanitize Inclusion fields
        $requesterUserTypes = $request->scope_type !== 'all' && $request->has('requester_user_types') ? $request->requester_user_types : null;
        $requesterRoleIds = $request->scope_type !== 'all' && $request->has('requester_role_ids') ? $request->requester_role_ids : null;
        $requesterUserIds = $request->scope_type !== 'all' && $request->has('requester_user_ids') ? $request->requester_user_ids : null;

        if ($request->scope_type === 'user_type') {
            $requesterRoleIds = null;
            $requesterUserIds = null;
        } elseif ($request->scope_type === 'role') {
            $requesterUserTypes = null;
            $requesterUserIds = null;
        } elseif ($request->scope_type === 'user_type_role') {
            $requesterUserIds = null;
        } elseif ($request->scope_type === 'specific_user') {
            $requesterUserTypes = null;
            $requesterRoleIds = null;
        } else {
            $requesterUserTypes = null;
            $requesterRoleIds = null;
            $requesterUserIds = null;
        }

        // Sanitize Exclusion fields
        $excludeUserTypes = $request->exclude_scope_type !== 'none' && $request->has('exclude_user_types') ? $request->exclude_user_types : null;
        $excludeRoleIds = $request->exclude_scope_type !== 'none' && $request->has('exclude_role_ids') ? $request->exclude_role_ids : null;
        $excludeUserIds = $request->exclude_scope_type !== 'none' && $request->has('exclude_user_ids') ? $request->exclude_user_ids : null;

        if ($request->exclude_scope_type === 'user_type') {
            $excludeRoleIds = null;
            $excludeUserIds = null;
        } elseif ($request->exclude_scope_type === 'role') {
            $excludeUserTypes = null;
            $excludeUserIds = null;
        } elseif ($request->exclude_scope_type === 'user_type_role') {
            $excludeUserIds = null;
        } elseif ($request->exclude_scope_type === 'specific_user') {
            $excludeUserTypes = null;
            $excludeRoleIds = null;
        } else {
            $excludeUserTypes = null;
            $excludeRoleIds = null;
            $excludeUserIds = null;
        }

        DB::beginTransaction();
        try {
            $workflow = ApprovalWorkflow::findOrFail($id);
            $workflow->update([
                'name' => ucfirst($request->module_name) . ' Workflow',
                'module' => $request->module_name,
                'type' => $request->type,
                'total_steps' => count($request->steps),
                'required_approvals' => $request->type === 'random' ? $request->required_approvals : null,
                'is_active' => $request->is_active == '1',
                
                // Save Inclusions & Exclusions
                'requester_user_types' => $requesterUserTypes,
                'requester_role_ids' => $requesterRoleIds,
                'requester_user_ids' => $requesterUserIds,
                'exclude_user_types' => $excludeUserTypes,
                'exclude_role_ids' => $excludeRoleIds,
                'exclude_user_ids' => $excludeUserIds,
            ]);

            $workflow->steps()->delete();

            foreach ($request->steps as $index => $step) {
                // Determine a descriptive step name based on its type
                $stepName = 'Step ' . ($index + 1) . ' - ';
                if ($step['type'] === 'user-type') {
                    $stepName .= ucfirst(str_replace('-', ' ', $step['required_user_type']));
                } elseif ($step['type'] === 'role-user') {
                    $roleName = \Spatie\Permission\Models\Role::find($step['role_id'])->name ?? 'Role';
                    $stepName .= ucfirst(str_replace('-', ' ', $step['required_user_type'])) . ' (' . $roleName . ')';
                } elseif ($step['type'] === 'specific-user') {
                    $userName = \App\Models\User::find($step['user_id'])->name ?? 'User';
                    $stepName .= $userName;
                }

                $workflow->steps()->create([
                    'name' => $stepName,
                    'step_order' => $index + 1,
                    'type' => $step['type'],
                    'required_user_type' => $step['required_user_type'] ?? null,
                    'role_id' => $step['role_id'] ?? null,
                    'user_id' => $step['user_id'] ?? null,
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Workflow updated successfully.', 'redirect' => route('setting.approval_workflows.index')], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Error updating workflow: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong. Please try again later.'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            ApprovalWorkflow::findOrFail($id)->delete();
            return response()->json(['message' => 'Workflow deleted successfully.'], 200);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error deleting workflow: ' . $e->getMessage());
            return response()->json(['message' => 'Something went wrong. Please try again later.'], 500);
        }
    }

    private function validateSequentialSteps(array $steps): ?string
    {
        $previousWeight = null;
        $previousName = '';

        foreach ($steps as $index => $step) {
            $currentWeight = null;
            $currentName = '';

            if (($step['type'] ?? '') === 'user-type' || ($step['type'] ?? '') === 'role-user') {
                if (!empty($step['required_user_type'])) {
                    $currentWeight = \App\Enums\UserType::getWeight($step['required_user_type']);
                    $currentName = $step['required_user_type'];
                }
            } elseif (($step['type'] ?? '') === 'specific-user') {
                if (!empty($step['user_id'])) {
                    $user = \App\Models\User::find($step['user_id']);
                    if ($user) {
                        $currentWeight = $user->user_type->weight();
                        $currentName = $user->user_type->value;
                    }
                }
            }

            if ($currentWeight !== null) {
                if ($previousWeight !== null && $previousWeight < $currentWeight) {
                    return "Step " . ($index + 1) . " (level: {$currentName}) cannot have a lower authority level than Step " . $index . " (level: {$previousName}) in a sequential workflow.";
                }
                $previousWeight = $currentWeight;
                $previousName = $currentName;
            }
        }

        return null;
    }
}
