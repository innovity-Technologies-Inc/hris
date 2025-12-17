<?php

namespace App\Http\Controllers;

use App\Imports\TAPlansImport;
use App\Models\TAPlan;
use App\Services\PlanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class TAPlanController extends Controller
{
    protected $planServices;

    public function __construct(PlanService $planServices)
    {
        $this->planServices = $planServices;
    }

    public function index(Request $request)
    {
        $title = 'Transport Allowance Plan';
        $section = 'Plans Setup';
        $sub_section = 'Transport Allowance Plan';
        $columns = ['name', 'short_name'];
        $term = $request->get('keyword');
        $plans = $this->planServices->search(TAPlan::class, $columns, [], [], $term, 20);

        if ($request->ajax()) {
            return view('plans.ta_plans.search_results', compact('plans'))->render();
        }
        return view('plans.ta_plans.index', compact('title', 'section', 'sub_section', 'plans'));
    }

    public function create()
    {
        $title = 'Create Transport Allowance Plan';
        $section = 'Transport Allowance Plans';
        $sub_section = 'Create';
        $section_url = route('plans.ta_plans.index');
        return view('plans.ta_plans.form', compact('title', 'section', 'sub_section', 'section_url'));
    }

    public function store(Request $request)
    {
        $validated = $this->planServices->taPlanValidation($request);

        try {
            $this->planServices->planSave($validated, TAPlan::class);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong, Try Again Later',
                'alert-type' => 'error',
            ]);
        }

        return redirect()->route('plans.ta_plans.index')->with([
            'message' => 'TA Plan Created Successfully',
            'alert-type' => 'success',
        ]);
    }

    public function edit($id)
    {
        $title = 'Edit Transport Allowance Plan';
        $section = 'Transport Allowance Plans';
        $sub_section = 'Edit';
        $section_url = route('plans.ta_plans.index');
        $plan = $this->planServices->getPlanById($id, TAPlan::class);
        return view('plans.ta_plans.form', compact('title', 'section', 'sub_section', 'section_url', 'plan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $this->planServices->taPlanValidation($request);

        try {
            $this->planServices->planSave($validated, TAPlan::class, $id);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong, Try Again Later',
                'alert-type' => 'error',
            ]);
        }

        return redirect()->route('plans.ta_plans.index')->with([
            'message' => 'TA Plan Updated Successfully',
            'alert-type' => 'success',
        ]);
    }

    public function delete($id)
    {
        try {
            $this->planServices->planDelete(TAPlan::class, $id);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong, Try Again Later',
                'alert-type' => 'error',
            ]);
        }

        return redirect()->back()->with([
            'message' => 'TA Plan Deleted Successfully',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:text/csv,text/plain,application/csv,text/comma-separated-values,text/anytext,application/octet-stream,application/txt,xlsx,csv,txt',
        ]);

        try {
            Excel::import(new TAPlansImport(), $request->file('file'));
            return redirect()->route('plans.ta_plans.index')->with([
                'message' => 'Imported Successfully',
                'alert-type' => 'success'
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => $e->getMessage() . ' Contact with your administrator',
                'alert-type' => 'error'
            ]);
        }
    }
}
