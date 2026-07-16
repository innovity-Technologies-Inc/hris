<?php

namespace App\Http\Controllers\Setting;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\StoreApprovalWorkflowRequest;
use App\Http\Requests\Setting\UpdateApprovalWorkflowRequest;
use App\Services\Setting\ApprovalWorkflowServices;
use Innovity\ApprovalEngine\Models\Workflow as ApprovalWorkflow;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Exception;

class ApprovalWorkflowController extends Controller
{
    protected ApprovalWorkflowServices $workflowService;

    public function __construct(ApprovalWorkflowServices $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $workflows = ApprovalWorkflow::with('steps')->get();
        return view('setting.approval_workflow.index', compact('workflows'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreApprovalWorkflowRequest $request): JsonResponse
    {
        try {
            $workflow = $this->workflowService->createWorkflow($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Workflow created successfully.',
                'redirect' => route('setting.approval_workflows.index'),
                'data' => $workflow
            ], 200);

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error creating workflow: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
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

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateApprovalWorkflowRequest $request, int $id): JsonResponse
    {
        try {
            $workflow = $this->workflowService->updateWorkflow($id, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Workflow updated successfully.',
                'redirect' => route('setting.approval_workflows.index'),
                'data' => $workflow
            ], 200);

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error updating workflow: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->workflowService->deleteWorkflow($id);
            return response()->json([
                'success' => true,
                'message' => 'Workflow deleted successfully.'
            ], 200);

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error deleting workflow: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }
}
