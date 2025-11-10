<?php

namespace App\Http\Controllers;

use App\Imports\ShiftPlansImport;
use App\Models\ShiftPlan;
use App\Services\PlanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ShiftPlanController extends Controller
{
    protected $planServices;
    public function __construct(PlanService $planServices){
        $this->planServices = $planServices;
    }

    public function index(){
        $title = 'Shift Plan';
        $section = 'Plans Setup';
        $sub_section = 'Shift Plan';
        $plans = $this->planServices->getPlans(ShiftPlan::class, 20);
        return view('plans.shift_plans.index', compact('title', 'section', 'sub_section', 'plans'));
    }
    public function create(){
        $title = 'Create Shift Plan';
        $section = 'Shift Plans';
        $sub_section = 'Create';
        $section_url = route('plans.shift_plans.index');
        return view('plans.shift_plans.form', compact('title', 'section', 'sub_section', 'section_url'));
    }
    public function store(Request $request){
        $validated = $this->planServices->shiftPlanValidation($request);

        try {
            $this->planServices->planSave($validated, ShiftPlan::class);
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong, Try Again Later',
                'alert-type' => 'error',
            ]);
        }
        return redirect()->route('plans.shift_plans.index')->with([
            'message' => 'Shift Plan Created Successfully',
            'alert-type' => 'success',
        ]);
    }

    public function show($id){
        $title = 'Shift Plan';
        $section = 'Shift Plans';
        $sub_section = 'Show';
        $section_url = route('plans.shift_plans.index');
        $plan = $this->planServices->getPlanById($id, ShiftPlan::class );
//        dd($plan);
        return view('plans.shift_plans.view', compact('title', 'section', 'sub_section', 'section_url', 'plan'));
    }
    public function edit($id){
            $title = 'Create Shift Plan';
            $section = 'Shift Plans';
            $sub_section = 'Edit';
            $section_url = route('plans.shift_plans.index');
            $plan = $this->planServices->getPlanById($id,ShiftPlan::class );
            return view('plans.shift_plans.form', compact('title', 'section', 'sub_section', 'section_url', 'plan'));
    }
    public function update(Request $request, $id){
        $validated = $this->planServices->shiftPlanValidation($request);
        try {
            $this->planServices->planSave($validated, ShiftPlan::class, $id);
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong, Try Again Later',
                'alert-type' => 'error',
            ]);
        }
        return redirect()->route('plans.shift_plans.index')->with([
            'message' => 'Shift Plan Updated Successfully',
            'alert-type' => 'success',
        ]);
    }

    public function delete($id){
        try {
            $this->planServices->planDelete(ShiftPlan::class, $id);
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong, Try Again Later',
                'alert-type' => 'error',
            ]);
        }
        return redirect()->back()->with([
            'message' => 'Shift Plan Deleted Successfully',
        ]);
    }

    public function import(Request $request){
        $request->validate([
            'file' => 'required|mimes:text/csv,text/plain,application/csv,text/comma-separated-values,text/anytext,application/octet-stream,application/txt,xlsx,csv,txt',
        ]);
//        dd($request->all());
        try{
            Excel::import(new ShiftPlansImport(), $request->file('file'));
            return redirect()->route('plans.shift_plans.index')->with([
                'message' => 'Imported Successfully',
                'alert-type' => 'success'
            ]);
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => $e->getMessage(). 'Contact with your administrator',
                'alert-type' => 'error'
            ]);
        }

    }
}
