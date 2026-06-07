<?php

namespace App\Http\Controllers\Plan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Plan\StorePenaltyPlanRequest;
use App\Services\Plan\PenaltyPlanServices;
use Illuminate\Http\Request;

class PenaltyPlanController extends Controller
{
    protected $penaltyServices;

    public function __construct(PenaltyPlanServices $penaltyServices)
    {
        $this->penaltyServices = $penaltyServices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $plans = $this->penaltyServices->getPenaltyPlans($request);

        if ($request->ajax()) {
            return view('plan.penalty_plans.search_results', compact('plans'))->render();
        }

        $title = 'Penalty Plans';
        $section = 'Plans';
        $sub_section = 'Penalty Plan Management';

        return view('plan.penalty_plans.index', compact('title', 'section', 'sub_section', 'plans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePenaltyPlanRequest $request)
    {
        try {
            $this->penaltyServices->savePenaltyPlan($request->validated());
            return response()->json(['success' => true, 'message' => 'Penalty Plan created successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $plan = $this->penaltyServices->getPenaltyPlanById($id);
        return response()->json(['success' => true, 'data' => $plan]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StorePenaltyPlanRequest $request, $id)
    {
        try {
            $this->penaltyServices->savePenaltyPlan($request->validated(), $id);
            return response()->json(['success' => true, 'message' => 'Penalty Plan updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $this->penaltyServices->deletePenaltyPlan($id);
            return response()->json(['success' => true, 'message' => 'Penalty Plan deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
