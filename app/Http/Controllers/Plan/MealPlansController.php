<?php

namespace App\Http\Controllers\Plan;

use App\Http\Controllers\Controller;

use App\Imports\Plan\MealPlansImport;
use App\Models\Plan\MealPlan;
use App\Services\Plan\PlanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class MealPlansController extends Controller
{
    protected $planServices;
    public function __construct(PlanService $planServices){
        $this->planServices = $planServices;
    }

    public function index(){
        $title = 'Meal Plan';
        $section = 'Plans Setup';
        $sub_section = 'Meal Plan';
        $breakfast_plans = $this->planServices->getMealPlans('breakfast');
        $lunch_plans = $this->planServices->getMealPlans('lunch');
        $dinner_plans = $this->planServices->getMealPlans('dinner');
        $snacks_plans = $this->planServices->getMealPlans('snacks');
        return view('plan.meal_plans.index', compact('title', 'section', 'sub_section', 'breakfast_plans', 'lunch_plans', 'dinner_plans', 'snacks_plans'));;
    }
    public function store(Request $request){
        $validated = $this->planServices->mealPlanValidation($request);

        try {
            $this->planServices->planSave($validated, MealPlan::class);
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong, Try Again Later',
                'alert-type' => 'error',
            ]);
        }
        return redirect()->back()->with([
            'message' => 'Meal Plan Created Successfully',
            'alert-type' => 'success',
        ]);
    }
    public function update(Request $request, $id){
        $validated = $this->planServices->mealPlanValidation($request);
        try {
            $this->planServices->planSave($validated, MealPlan::class, $id);
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong, Try Again Later',
                'alert-type' => 'error',
            ]);
        }
        return redirect()->back()->with([
            'message' => 'Meal Plan Updated Successfully',
            'alert-type' => 'success',
        ]);
    }

    public function delete($id){
        try {
            $this->planServices->planDelete(MealPlan::class, $id);
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong, Try Again Later',
                'alert-type' => 'error',
            ]);
        }
        return redirect()->back()->with([
            'message' => 'Meal Plan Deleted Successfully',
        ]);
    }

    public function import(Request $request){
        $request->validate([
            'file' => 'required|mimes:text/csv,text/plain,application/csv,text/comma-separated-values,text/anytext,application/octet-stream,application/txt,xlsx,csv,txt',
        ]);
//        dd($request->all());
        try{
            Excel::import(new MealPlansImport(), $request->file('file'));
            return redirect()->route('plan.meal_plans.index')->with([
                'message' => 'Imported Successfully',
                'alert-type' => 'success'
            ]);
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something went wrong. Contact with your administrator',
                'alert-type' => 'error'
            ]);
        }

    }

}

