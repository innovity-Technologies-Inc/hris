<?php

namespace App\Http\Controllers;

use App\Imports\OffDayPlansImport;
use App\Models\OffDayPlan;
use App\Services\PlanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class OffDayPlansController extends Controller
{
    protected $planServices;

    public function __construct(PlanService $planServices)
    {
        $this->planServices = $planServices;
    }

    public function index()
    {
        $title = 'Off Day Plan';
        $section = 'Plans Setup';
        $sub_section = 'Off Day Plan';
        $plans = $this->planServices->getPlans(OffDayPlan::class, 20);
        return view('plans.off_day_plans.index', compact('title', 'section', 'sub_section', 'plans'));
    }

    public function create()
    {
        $title = 'Create Off Day Plan';
        $section = 'Off Day Plans';
        $sub_section = 'Create';
        $section_url = route('plans.off_day_plans.index');
        return view('plans.off_day_plans.form', compact('title', 'section', 'sub_section', 'section_url'));
    }

    public function store(Request $request)
    {
        $validated = $this->planServices->offDayPlanValidation($request);

        try {
            $this->planServices->planSave($validated, OffDayPlan::class);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong, Try Again Later',
                'alert-type' => 'error',
            ]);
        }

        return redirect()->route('plans.off_day_plans.index')->with([
            'message' => 'Off Day Plan Created Successfully',
            'alert-type' => 'success',
        ]);
    }

    public function show($id)
    {
        $title = 'Off Day Plan';
        $section = 'Off Day Plans';
        $sub_section = 'Show';
        $section_url = route('plans.off_day_plans.index');
        $plan = $this->planServices->getPlanById($id, OffDayPlan::class);
        return view('plans.off_day_plans.view', compact('title', 'section', 'sub_section', 'section_url', 'plan'));
    }

    public function edit($id)
    {
        $title = 'Edit Off Day Plan';
        $section = 'Off Day Plans';
        $sub_section = 'Edit';
        $section_url = route('plans.off_day_plans.index');
        $plan = $this->planServices->getPlanById($id, OffDayPlan::class);
        return view('plans.off_day_plans.form', compact('title', 'section', 'sub_section', 'section_url', 'plan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $this->planServices->offDayPlanValidation($request);

        try {
            $this->planServices->planSave($validated, OffDayPlan::class, $id);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong, Try Again Later',
                'alert-type' => 'error',
            ]);
        }

        return redirect()->route('plans.off_day_plans.index')->with([
            'message' => 'Off Day Plan Updated Successfully',
            'alert-type' => 'success',
        ]);
    }

    public function delete($id)
    {
        try {
            $this->planServices->planDelete(OffDayPlan::class, $id);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong, Try Again Later',
                'alert-type' => 'error',
            ]);
        }

        return redirect()->back()->with([
            'message' => 'Off Day Plan Deleted Successfully',
        ]);
    }

    public function import(Request $request){
        $request->validate([
            'file' => 'required|mimes:text/csv,text/plain,application/csv,text/comma-separated-values,text/anytext,application/octet-stream,application/txt,xlsx,csv,txt',
        ]);
//        dd($request->all());
        try{
            Excel::import(new OffDayPlansImport(), $request->file('file'));
            return redirect()->route('plans.off_day_plans.index')->with([
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
