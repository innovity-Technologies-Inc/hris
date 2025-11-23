<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

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

    public function planDelete($modelName, $id)
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
