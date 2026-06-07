<?php

namespace App\Services\Plan;

use App\Models\Plan\PenaltyPlan;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;

class PenaltyPlanServices
{
    /**
     * Get list of penalty plans with filtering.
     */
    public function getPenaltyPlans(Request $request)
    {
        return FlexSearch::for(PenaltyPlan::class)
            ->search(['title', 'description'])
            ->paginate($request->get('per_page', 10));
    }

    /**
     * Get a single penalty plan by ID.
     */
    public function getPenaltyPlanById($id)
    {
        return PenaltyPlan::findOrFail($id);
    }

    /**
     * Create or Update a penalty plan.
     */
    public function savePenaltyPlan(array $data, $id = null)
    {
        if ($id) {
            $plan = PenaltyPlan::findOrFail($id);
            $plan->update($data);
            return $plan;
        }

        return PenaltyPlan::create($data);
    }

    /**
     * Delete a penalty plan.
     */
    public function deletePenaltyPlan($id)
    {
        $plan = PenaltyPlan::findOrFail($id);
        return $plan->delete();
    }
}
