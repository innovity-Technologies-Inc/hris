<?php

namespace App\Services\Plan;

use App\Models\Plan\LeaveEncashmentPlan;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;

class LeaveEncashmentPlanServices
{
    /**
     * Get list of leave encashment plans with filtering.
     */
    public function getLeaveEncashmentPlans(Request $request, FlexSearch $flexsearch)
    {
        $query = LeaveEncashmentPlan::query();
        $keyword = $request->get('keyword');
        $searchableFields = ['title', 'description'];

        return $flexsearch->apply($query, [], $keyword, $searchableFields)
            ->orderBy('id', 'desc')
            ->paginate($request->get('per_page', 10));
    }

    /**
     * Get a single plan by ID.
     */
    public function getPlanById($id)
    {
        return LeaveEncashmentPlan::findOrFail($id);
    }

    /**
     * Create or Update a plan.
     */
    public function savePlan(array $data, $id = null)
    {
        if ($id) {
            $plan = LeaveEncashmentPlan::findOrFail($id);
            $plan->update($data);
            return $plan;
        }

        return LeaveEncashmentPlan::create($data);
    }

    /**
     * Delete a plan.
     */
    public function deletePlan($id)
    {
        $plan = LeaveEncashmentPlan::findOrFail($id);
        return $plan->delete();
    }
}
