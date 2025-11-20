<?php

namespace App\Http\Controllers;

use App\Imports\OTPlansImport;
use App\Models\OTPlan;
use App\Services\PlanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class OTPlanController extends Controller
{
    protected $planServices;

    public function __construct(PlanService $planServices)
    {
        $this->planServices = $planServices;
    }

    public function index(Request $request)
    {
        $title = 'OT Plan';
        $section = 'Plans Setup';
        $sub_section = 'OT Plan';
        $term = $request->get('keyword');
        $columns = ['name', 'ot_type'];


        $plans = $this->planServices->search(OTPlan::class, $columns, [], [], $term, 20);
        if ($request->ajax()) {
            return view('plans.ot_plans.search_results', compact('plans'))->render();
        }
//        dd($plans);
        return view('plans.ot_plans.index', compact('title', 'section', 'sub_section', 'plans'));
    }

    public function create()
    {
        $title = 'Create OT Plan';
        $section = 'OT Plans';
        $sub_section = 'Create';
        $section_url = route('plans.ot_plans.index');
        return view('plans.ot_plans.form', compact('title', 'section', 'sub_section', 'section_url'));
    }

    public function store(Request $request)
    {
        $validated = $this->planServices->otPlanValidation($request);

        try {
            $this->planServices->planSave($validated, OTPlan::class);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong, Try Again Later',
                'alert-type' => 'error',
            ]);
        }

        return redirect()->route('plans.ot_plans.index')->with([
            'message' => 'OT Plan Created Successfully',
            'alert-type' => 'success',
        ]);
    }

    public function show($id)
    {
        $title = 'OT Plan';
        $section = 'OT Plans';
        $sub_section = 'Show';
        $section_url = route('plans.ot_plans.index');
        $plan = $this->planServices->getPlanById($id, OTPlan::class);
        return view('plans.ot_plans.view', compact('title', 'section', 'sub_section', 'section_url', 'plan'));
    }

    public function edit($id)
    {
        $title = 'Edit OT Plan';
        $section = 'OT Plans';
        $sub_section = 'Edit';
        $section_url = route('plans.ot_plans.index');
        $plan = $this->planServices->getPlanById($id, OTPlan::class);
        return view('plans.ot_plans.form', compact('title', 'section', 'sub_section', 'section_url', 'plan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $this->planServices->otPlanValidation($request);

        try {
            $this->planServices->planSave($validated, OTPlan::class, $id);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong, Try Again Later',
                'alert-type' => 'error',
            ]);
        }

        return redirect()->route('plans.ot_plans.index')->with([
            'message' => 'OT Plan Updated Successfully',
            'alert-type' => 'success',
        ]);
    }

    public function delete($id)
    {
        try {
            $this->planServices->planDelete(OTPlan::class, $id);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong, Try Again Later',
                'alert-type' => 'error',
            ]);
        }

        return redirect()->back()->with([
            'message' => 'OT Plan Deleted Successfully',
        ]);
    }

    public function import(Request $request){
        $request->validate([
            'file' => 'required|mimes:text/csv,text/plain,application/csv,text/comma-separated-values,text/anytext,application/octet-stream,application/txt,xlsx,csv,txt',
        ]);
//        dd($request->all());
        try{
            Excel::import(new OTPlansImport(), $request->file('file'));
            return redirect()->route('plans.ot_plans.index')->with([
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
