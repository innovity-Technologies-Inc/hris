<?php

namespace App\Services;

use App\Models\EmployeeMealPlan;
use App\Models\MealPlan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmployeePlansServices
{
    public function planSave($validated, $modelName)
    {
        $validated['status'] = 'active';
        $active_plan = $modelName::where('status', 'active')->first();
        if (empty($active_plan)) {
            $plan = $modelName::create($validated);
            return $plan;
        }else{
            DB::transaction(function () use ($validated, $modelName, $active_plan) {
                $active_plan->update(['status' => 'inactive']);
                $plan = $modelName::create($validated);
                return $plan;
            });
        }
    }

    public function mealPlanSave($validated, $request)
    {
        $validated['status'] = 'active';
        $meal_type = $request->meal_type;
        $meal_plan = EmployeeMealPlan::with('getPlan')->where('status', 'active')->
            whereHas('getPlan', function ($q) use ($meal_type){
                $q->where('type', $meal_type);
        })->first();
        if (empty($meal_plan)) {
            $meal_plan = EmployeeMealPlan::create($validated);
            return $meal_plan;
        }else{
            DB::transaction(function () use ($validated, $meal_plan) {
                $meal_plan->update(['status' => 'inactive']);
                $meal_plan = EmployeeMealPlan::create($validated);
                return $meal_plan;
            });
        }
    }

    public function planRemove($id, $modelName){
        $plan = $modelName::findOrFail($id);
        $plan->update(['status' => 'inactive']);
        return $plan;
    }

    public function planDelete($id, $modelName)
    {
        $plan = $modelName::findOrFail($id);
        $plan->delete();
    }

    public function validation($request){
        $validated = $request->validate([
            'employee_id' => 'required',
            'plan_id' => 'required',
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
        ], [
            'employee_id.required' => 'The employee field is required.',
            'plan_id.required' => 'The plan field is required.',
            'from.required' => 'The from date field is required.',
            'to.required' => 'The to date field is required.',
            'to.after_or_equal' => 'The to date must be after or equal to the from date.',
        ]);
        return $validated;
    }
}
