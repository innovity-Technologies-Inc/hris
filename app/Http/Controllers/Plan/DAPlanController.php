<?php

namespace App\Http\Controllers\Plan;

use App\Http\Controllers\Controller;

use App\Imports\Plan\DAPlansImport;
use App\Models\Plan\DAPlan;
use App\Services\Plan\PlanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class DAPlanController extends Controller
{
    protected $planServices;

    public function __construct(PlanService $planServices)
    {
        $this->planServices = $planServices;
    }

    public function index(Request $request)
    {
        $title = ' Dining Allowance Plan';
        $section = 'Plans Setup';
        $sub_section = 'Dining Allowance Plan';
        $columns = ['name', 'short_name'];
        $term = $request->get('keyword');
        $plans = $this->planServices->search(DAPlan::class, $columns, [], [], $term, 20);

        if ($request->ajax()) {
            return view('plan.da_plans.search_results', compact('plans'))->render();
        }
        return view('plan.da_plans.index', compact('title', 'section', 'sub_section', 'plans'));
    }

    public function create()
    {
        $title = 'Create Dining Allowance Plan';
        $section = 'Dining Allowance Plans';
        $sub_section = 'Create';
        $section_url = route('plan.da_plans.index');
        return view('plan.da_plans.form', compact('title', 'section', 'sub_section', 'section_url'));
    }

    public function store(Request $request)
    {
        $validated = $this->planServices->daPlanValidation($request);

        try {
            $this->planServices->planSave($validated, DAPlan::class);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong, Try Again Later',
                'alert-type' => 'error',
            ]);
        }

        return redirect()->route('plan.da_plans.index')->with([
            'message' => 'Dining Allowance Plan Created Successfully',
            'alert-type' => 'success',
        ]);
    }

    public function edit($id)
    {
        $title = 'Edit Dining Allowance Plan';
        $section = 'Dining Allowance Plans';
        $sub_section = 'Edit';
        $section_url = route('plan.da_plans.index');
        $plan = $this->planServices->getPlanById($id, DAPlan::class);
        return view('plan.da_plans.form', compact('title', 'section', 'sub_section', 'section_url', 'plan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $this->planServices->daPlanValidation($request);

        try {
            $this->planServices->planSave($validated, DAPlan::class, $id);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong, Try Again Later',
                'alert-type' => 'error',
            ]);
        }

        return redirect()->route('plan.da_plans.index')->with([
            'message' => 'Dining Allowance Plan Updated Successfully',
            'alert-type' => 'success',
        ]);
    }

    public function delete($id)
    {
        try {
            $this->planServices->planDelete(DAPlan::class, $id);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong, Try Again Later',
                'alert-type' => 'error',
            ]);
        }

        return redirect()->back()->with([
            'message' => 'Dining Allowance Plan Deleted Successfully',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:text/csv,text/plain,application/csv,text/comma-separated-values,text/anytext,application/octet-stream,application/txt,xlsx,csv,txt',
        ]);

        try {
            Excel::import(new DAPlansImport(), $request->file('file'));
            return redirect()->route('plan.da_plans.index')->with([
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

