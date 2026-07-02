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
        $modules = config('approval-engine.modules');
        $userTypes = UserType::cases();
        $roles = \Spatie\Permission\Models\Role::orderBy('name')->get();
        $users = \App\Models\User::orderBy('name')->get();
        return view('setting.approval_workflow.create', compact('modules', 'userTypes', 'roles', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'module_name' => 'required|string',
            'type' => 'required|in:sequential,random',
            'required_approvals' => 'nullable|integer|min:1|max:' . max(1, count($request->steps ?? [])),
            'steps' => 'required|array|min:1',
            'steps.*.type' => 'required|in:user-type,role-user,specific-user',
            'steps.*.required_user_type' => 'nullable|required_if:steps.*.type,user-type,role-user|string',
            'steps.*.role_id' => 'nullable|required_if:steps.*.type,role-user|exists:roles,id',
            'steps.*.user_id' => 'nullable|required_if:steps.*.type,specific-user|exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            $workflow = ApprovalWorkflow::create([
                'name' => ucfirst($request->module_name) . ' Workflow',
                'module' => $request->module_name,
                'type' => $request->type,
                'total_steps' => count($request->steps),
                'required_approvals' => $request->type === 'random' ? $request->required_approvals : null,
                'is_active' => $request->has('is_active') ? true : false,
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
        $modules = config('approval-engine.modules');
        $userTypes = UserType::cases();
        $roles = \Spatie\Permission\Models\Role::orderBy('name')->get();
        $users = \App\Models\User::orderBy('name')->get();
        return view('setting.approval_workflow.edit', compact('workflow', 'modules', 'userTypes', 'roles', 'users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'module_name' => 'required|string',
            'type' => 'required|in:sequential,random',
            'required_approvals' => 'nullable|integer|min:1|max:' . max(1, count($request->steps ?? [])),
            'steps' => 'required|array|min:1',
            'steps.*.type' => 'required|in:user-type,role-user,specific-user',
            'steps.*.required_user_type' => 'nullable|required_if:steps.*.type,user-type,role-user|string',
            'steps.*.role_id' => 'nullable|required_if:steps.*.type,role-user|exists:roles,id',
            'steps.*.user_id' => 'nullable|required_if:steps.*.type,specific-user|exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            $workflow = ApprovalWorkflow::findOrFail($id);
            $workflow->update([
                'name' => ucfirst($request->module_name) . ' Workflow',
                'module' => $request->module_name,
                'type' => $request->type,
                'total_steps' => count($request->steps),
                'required_approvals' => $request->type === 'random' ? $request->required_approvals : null,
                'is_active' => $request->has('is_active') ? true : false,
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
}
