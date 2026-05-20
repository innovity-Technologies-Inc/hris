<?php

namespace App\Http\Controllers\Plan;

use App\Http\Controllers\Controller;

use App\Imports\Plan\ShiftPlansImport;
use App\Models\Plan\ShiftPlan;
use App\Services\Plan\PlanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ShiftPlanController extends Controller
{
    protected $planServices;
    public function __construct(PlanService $planServices){
        $this->planServices = $planServices;
    }

    public function index(Request $request){
        $title = 'Shift Plan';
        $section = 'Plans Setup';
        $sub_section = 'Shift Plan';
        $term = $request->get('keyword');
        $columns = ['name'];

        $plans = $this->planServices->search(ShiftPlan::class, $columns, [], [], $term, 20);
        if ($request->ajax()) {
            return view('plan.shift_plans.search_results', compact('plans'))->render();
        }
        return view('plan.shift_plans.index', compact('title', 'section', 'sub_section', 'plans'));
    }
    public function create(){
        $title = 'Create Shift Plan';
        $section = 'Shift Plans';
        $sub_section = 'Create';
        $section_url = route('plan.shift_plans.index');
        return view('plan.shift_plans.form', compact('title', 'section', 'sub_section', 'section_url'));
    }
    public function store(Request $request){
        $validated = $this->planServices->shiftPlanValidation($request);

        try {
            $this->planServices->shiftPlanSave($validated, ShiftPlan::class);
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong, Try Again Later',
                'alert-type' => 'error',
            ]);
        }
        return redirect()->route('plan.shift_plans.index')->with([
            'message' => 'Shift Plan Created Successfully',
            'alert-type' => 'success',
        ]);
    }

    public function show($id){
        $title = 'Shift Plan';
        $section = 'Shift Plans';
        $sub_section = 'Show';
        $section_url = route('plan.shift_plans.index');
        $plan = $this->planServices->getPlanById($id, ShiftPlan::class );
//        dd($plan);
        return view('plan.shift_plans.view', compact('title', 'section', 'sub_section', 'section_url', 'plan'));
    }
    public function edit($id){
            $title = 'Create Shift Plan';
            $section = 'Shift Plans';
            $sub_section = 'Edit';
            $section_url = route('plan.shift_plans.index');
            $plan = $this->planServices->getPlanById($id,ShiftPlan::class );
            return view('plan.shift_plans.form', compact('title', 'section', 'sub_section', 'section_url', 'plan'));
    }
    public function update(Request $request, $id){
        $validated = $this->planServices->shiftPlanValidation($request);
        try {
            $this->planServices->shiftPlanSave($validated, ShiftPlan::class, $id);
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong, Try Again Later',
                'alert-type' => 'error',
            ]);
        }
        return redirect()->route('plan.shift_plans.index')->with([
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
            return redirect()->route('plan.shift_plans.index')->with([
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

