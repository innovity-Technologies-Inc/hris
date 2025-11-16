<?php

namespace App\Http\Controllers;

use App\Imports\RosterPlansImport;
use App\Models\RosterPlan;
use App\Models\ShiftPlan;
use App\Services\PlanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class RosterPlansController extends Controller
{

    protected $planServices;
    public function __construct(PlanService $planServices){
        $this->planServices = $planServices;
    }

    public function index(){
        $title = 'Roster Plan';
        $section = 'Plans Setup';
        $sub_section = 'Roster Plan';
        $plans = $this->planServices->getPlans(RosterPlan::class, 20);
        return view('plans.roster_plans.index', compact('title', 'section', 'sub_section', 'plans'));
    }
    public function create(){
        $title = 'Create Roster Plan';
        $section = 'Roster Plans';
        $shifts = ShiftPlan::all();
        $sub_section = 'Create';
        $section_url = route('plans.roster_plans.index');
        return view('plans.roster_plans.form', compact('title', 'section', 'sub_section', 'section_url', 'shifts'));
    }
    public function store(Request $request){
        $validated = $this->planServices->rosterPlanValidation($request);

        try {
            $this->planServices->planSave($validated, RosterPlan::class);
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong, Try Again Later',
                'alert-type' => 'error',
            ]);
        }
        return redirect()->route('plans.roster_plans.index')->with([
            'message' => 'Roster Plan Created Successfully',
            'alert-type' => 'success',
        ]);
    }

    public function show($id){
        $title = 'Roster Plan';
        $section = 'Roster Plans';
        $sub_section = 'Show';
        $section_url = route('plans.roster_plans.index');
        $plan = $this->planServices->getPlanById($id, RosterPlan::class );
//        dd($plan);
        return view('plans.roster_plans.view', compact('title', 'section', 'sub_section', 'section_url', 'plan'));
    }
    public function edit($id){
        $title = 'Create Roster Plan';
        $section = 'Roster Plans';
        $sub_section = 'Edit';
        $section_url = route('plans.roster_plans.index');
        $plan = $this->planServices->getPlanById($id,RosterPlan::class );
        $shifts = ShiftPlan::all();
        return view('plans.roster_plans.form', compact('title', 'section', 'sub_section', 'section_url', 'plan', 'shifts'));
    }
    public function update(Request $request, $id){
        $validated = $this->planServices->rosterPlanValidation($request);
        try {
            $this->planServices->planSave($validated, RosterPlan::class, $id);
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong, Try Again Later',
                'alert-type' => 'error',
            ]);
        }
        return redirect()->route('plans.roster_plans.index')->with([
            'message' => 'Roster Plan Updated Successfully',
            'alert-type' => 'success',
        ]);
    }

    public function delete($id){
        try {
            $this->planServices->planDelete(RosterPlan::class, $id);
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong, Try Again Later',
                'alert-type' => 'error',
            ]);
        }
        return redirect()->back()->with([
            'message' => 'Roster Plan Deleted Successfully',
        ]);
    }

    public function import(Request $request){
        $request->validate([
            'file' => 'required|mimes:text/csv,text/plain,application/csv,text/comma-separated-values,text/anytext,application/octet-stream,application/txt,xlsx,csv,txt',
        ]);
//        dd($request->all());
        try{
            Excel::import(new RosterPlansImport(), $request->file('file'));
            return redirect()->route('plans.roster_plans.index')->with([
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
