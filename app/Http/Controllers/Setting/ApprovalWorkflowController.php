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
        return view('setting.approval_workflow.create', compact('modules', 'userTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'module_name' => 'required|string',
            'type' => 'required|in:sequential,random',
            'required_approvals' => 'nullable|integer|min:1|max:' . max(1, count($request->steps ?? [])),
            'steps' => 'required|array|min:1',
            'steps.*.required_user_type' => 'required|string',
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
                $workflow->steps()->create([
                    'name' => 'Step ' . ($index + 1) . ' - ' . ucfirst(str_replace('-', ' ', $step['required_user_type'])),
                    'step_order' => $index + 1,
                    'required_user_type' => $step['required_user_type'],
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
        return view('setting.approval_workflow.edit', compact('workflow', 'modules', 'userTypes'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'module_name' => 'required|string',
            'type' => 'required|in:sequential,random',
            'required_approvals' => 'nullable|integer|min:1|max:' . max(1, count($request->steps ?? [])),
            'steps' => 'required|array|min:1',
            'steps.*.required_user_type' => 'required|string',
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
                $workflow->steps()->create([
                    'name' => 'Step ' . ($index + 1) . ' - ' . ucfirst(str_replace('-', ' ', $step['required_user_type'])),
                    'step_order' => $index + 1,
                    'required_user_type' => $step['required_user_type'],
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
