<?php

namespace App\Http\Controllers\Plan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Plan\StoreLeaveEncashmentPlanRequest;
use App\Services\Plan\LeaveEncashmentPlanServices;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;

class LeaveEncashmentPlanController extends Controller
{
    protected $planServices;

    public function __construct(LeaveEncashmentPlanServices $planServices)
    {
        $this->planServices = $planServices;
    }

    public function index(Request $request, FlexSearch $flexsearch)
    {
        $plans = $this->planServices->getLeaveEncashmentPlans($request, $flexsearch);

        if ($request->ajax()) {
            return view('plan.leave_encashment_plans.search_results', compact('plans'))->render();
        }

        $title = 'Leave Encashment Plans';
        $section = 'Plans';
        $sub_section = 'Encashment Configuration';

        return view('plan.leave_encashment_plans.index', compact('title', 'section', 'sub_section', 'plans'));
    }

    public function store(StoreLeaveEncashmentPlanRequest $request)
    {
        try {
            $this->planServices->savePlan($request->validated());
            return response()->json(['success' => true, 'message' => 'Plan created successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        $plan = $this->planServices->getPlanById($id);
        return response()->json(['success' => true, 'data' => $plan]);
    }

    public function update(StoreLeaveEncashmentPlanRequest $request, $id)
    {
        try {
            $this->planServices->savePlan($request->validated(), $id);
            return response()->json(['success' => true, 'message' => 'Plan updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->planServices->deletePlan($id);
            return response()->json(['success' => true, 'message' => 'Plan deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
