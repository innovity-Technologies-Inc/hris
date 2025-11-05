<?php

namespace App\Services;

use App\Models\MealPlan;

class PlanService
{
    public function planSave($validated, $modelName, $id = null){
        if (isset($id)){
            $plan = $modelName::findOrFail($id);
            $plan->update($validated);
        }else{
            $plan = $modelName::create($validated);
        }
        return $plan;
    }
    public function planDelete($id, $modelName){
        $plan = $modelName::findOrFail($id);
        $plan->delete();
    }

    public function getMealPlans($type){
        $mealPlans = MealPlan::where('type', $type)->get();
        return $mealPlans;
    }

    public function getPlans($modelName, $paginate){
        $plans = $modelName::latest()->paginate($paginate);
        return $plans;
    }

    public function getPlanByID($id, $modelName){
        $plan = $modelName::findOrFail($id);
        return $plan;
    }
    public function mealPlanValidation($request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:breakfast,lunch,snacks,dinner',
            'description' => 'required|string',
            'cost' => 'required|numeric|min:0',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'required|in:active,inactive',
        ], [
            'name.required' => 'The menu name is required.',
            'name.string' => 'The menu name must be a string.',
            'name.max' => 'The menu name may not exceed 255 characters.',

            'type.required' => 'Please select a menu type.',
            'type.in' => 'The selected menu type is invalid.',

            'description.required' => 'Please provide a description for the menu.',
            'description.string' => 'Description must be a string.',

            'cost.required' => 'The cost field is required.',
            'cost.numeric' => 'The cost must be a number.',
            'cost.min' => 'The cost must be at least 0.',

            'start_time.required' => 'Please enter the start time.',
            'start_time.date_format' => 'Start time must be in the format HH:MM.',

            'end_time.required' => 'Please enter the end time.',
            'end_time.date_format' => 'End time must be in the format HH:MM.',
            'end_time.after' => 'End time must be after the start time.',

            'status.required' => 'Please select the menu status.',
            'status.in' => 'The selected status is invalid.',
        ]);
        return $validated;
    }

}
