<?php

namespace App\Http\Controllers;

use App\Models\MealPlan;
use App\Services\PlanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        return view('plans.meal_plans.index', compact('title', 'section', 'sub_section', 'breakfast_plans', 'lunch_plans', 'dinner_plans', 'snacks_plans'));;
    }
    public function store(Request $request){
        try {
            $validated = $this->planServices->mealPlanValidation($request);
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
        try {
            $validated = $this->planServices->mealPlanValidation($request);
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
}
