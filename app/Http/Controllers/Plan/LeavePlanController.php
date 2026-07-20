<?php

namespace App\Http\Controllers\Plan;

use App\Http\Controllers\Controller;
use App\Imports\Plan\LeavePlansImport;
use App\Models\Plan\LeavePlan;
use App\Services\Plan\PlanService;
use App\Http\Requests\Plan\StoreLeavePlanRequest;
use App\Http\Requests\Plan\UpdateLeavePlanRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Exception;

class LeavePlanController extends Controller
{
    protected $planServices;

    public function __construct(PlanService $planServices)
    {
        $this->planServices = $planServices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'Leave Plan';
        $section = 'Plans Setup';
        $sub_section = 'Leave Plan';
        $columns = ['name', 'short_name', 'leave_type'];
        $term = $request->get('keyword');
        $plans = $this->planServices->search(LeavePlan::class, $columns, [], [], $term, 20);

        if ($request->ajax()) {
            return view('plan.leave_plans.search_results', compact('plans'))->render();
        }
        return view('plan.leave_plans.index', compact('title', 'section', 'sub_section', 'plans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $title = 'Create Leave Plan';
        $section = 'Leave Plans';
        $sub_section = 'Create';
        $section_url = route('plan.leave_plans.index');
        return view('plan.leave_plans.form', compact('title', 'section', 'sub_section', 'section_url'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLeavePlanRequest $request): JsonResponse
    {
        try {
            $plan = $this->planServices->planSave($request->validated(), LeavePlan::class);
            return $this->createdResponse('Leave Plan created successfully.', [
                'redirect' => route('plan.leave_plans.index'),
                'plan' => $plan
            ]);
        } catch (Exception $e) {
            Log::error('Error saving Leave Plan: ' . $e->getMessage());
            return $this->errorResponse('Something went wrong. Please try again later.', 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $title = 'Leave Plan';
        $section = 'Leave Plans';
        $sub_section = 'Show';
        $section_url = route('plan.leave_plans.index');
        $plan = $this->planServices->getPlanById($id, LeavePlan::class);
        return view('plan.leave_plans.view', compact('title', 'section', 'sub_section', 'section_url', 'plan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $title = 'Edit Leave Plan';
        $section = 'Leave Plans';
        $sub_section = 'Edit';
        $section_url = route('plan.leave_plans.index');
        $plan = $this->planServices->getPlanById($id, LeavePlan::class);
        return view('plan.leave_plans.form', compact('title', 'section', 'sub_section', 'section_url', 'plan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLeavePlanRequest $request, $id): JsonResponse
    {
        try {
            $plan = $this->planServices->planSave($request->validated(), LeavePlan::class, $id);
            return $this->successResponse('Leave Plan updated successfully.', [
                'redirect' => route('plan.leave_plans.index'),
                'plan' => $plan
            ]);
        } catch (Exception $e) {
            Log::error('Error updating Leave Plan: ' . $e->getMessage());
            return $this->errorResponse('Something went wrong. Please try again later.', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id): JsonResponse
    {
        try {
            $this->planServices->planDelete(LeavePlan::class, $id);
            return $this->deletedResponse('Leave Plan deleted successfully.');
        } catch (Exception $e) {
            Log::error('Error deleting Leave Plan: ' . $e->getMessage());
            return $this->errorResponse('Something went wrong. Please try again later.', 500);
        }
    }

    /**
     * Import a listing of resources from Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:text/csv,text/plain,application/csv,text/comma-separated-values,text/anytext,application/octet-stream,application/txt,xlsx,csv,txt',
        ]);

        try {
            Excel::import(new LeavePlansImport(), $request->file('file'));
            return redirect()->route('plan.leave_plans.index')->with([
                'message' => 'Imported Successfully',
                'alert-type' => 'success'
            ]);
        } catch (Exception $e) {
            Log::error('Error importing Leave Plans: ' . $e->getMessage());
            return redirect()->back()->with([
                'message' => $e->getMessage() . ' - Contact with your administrator',
                'alert-type' => 'error'
            ]);
        }
    }
}
