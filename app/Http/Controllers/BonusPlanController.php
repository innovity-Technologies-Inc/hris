<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\BonusPlansImport;
use App\Models\BonusPlan;
use App\Services\PlanService;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class BonusPlanController extends Controller
{
    protected $planServices;

    public function __construct(PlanService $planServices)
    {
        $this->planServices = $planServices;
    }

    public function index(Request $request)
    {
        $title = 'Bonus Plan';
        $section = 'Plans Setup';
        $sub_section = 'Bonus Plan';
        $term = $request->get('keyword');
        $columns = ['name', 'bonus_type'];

        $plans = $this->planServices->search(BonusPlan::class, $columns, [], [], $term, 20);
        if ($request->ajax()) {
            return view('plans.bonus_plans.search_results', compact('plans'))->render();
        }
//        dd($plans);
        return view('plans.bonus_plans.index', compact('title', 'section', 'sub_section', 'plans'));
    }

    public function create()
    {
        $title = 'Create Bonus Plan';
        $section = 'Bonus Plans';
        $sub_section = 'Create';
        $section_url = route('plans.bonus_plans.index');
        return view('plans.bonus_plans.form', compact('title', 'section', 'sub_section', 'section_url'));
    }

    public function store(Request $request)
    {
        $validated = $this->planServices->bonusPlanValidation($request);

        try {
            $this->planServices->planSave($validated, BonusPlan::class);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong, Try Again Later',
                'alert-type' => 'error',
            ]);
        }

        return redirect()->route('plans.bonus_plans.index')->with([
            'message' => 'Bonus Plan Created Successfully',
            'alert-type' => 'success',
        ]);
    }

    public function show($id)
    {
        $title = 'Bonus Plan';
        $section = 'Bonus Plans';
        $sub_section = 'Show';
        $section_url = route('plans.bonus_plans.index');
        $plan = $this->planServices->getPlanById($id, BonusPlan::class);
        return view('plans.bonus_plans.view', compact('title', 'section', 'sub_section', 'section_url', 'plan'));
    }

    public function edit($id)
    {
        $title = 'Edit Bonus Plan';
        $section = 'Bonus Plans';
        $sub_section = 'Edit';
        $section_url = route('plans.bonus_plans.index');
        $plan = $this->planServices->getPlanById($id, BonusPlan::class);
        return view('plans.bonus_plans.form', compact('title', 'section', 'sub_section', 'section_url', 'plan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $this->planServices->bonusPlanValidation($request);

        try {
            $this->planServices->planSave($validated, BonusPlan::class, $id);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong, Try Again Later',
                'alert-type' => 'error',
            ]);
        }

        return redirect()->route('plans.bonus_plans.index')->with([
            'message' => 'Bonus Plan Updated Successfully',
            'alert-type' => 'success',
        ]);
    }

    public function delete($id)
    {
        try {
            $this->planServices->planDelete(BonusPlan::class, $id);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong, Try Again Later',
                'alert-type' => 'error',
            ]);
        }

        return redirect()->back()->with([
            'message' => 'Bonus Plan Deleted Successfully',
        ]);
    }

    public function import(Request $request){
        $request->validate([
            'file' => 'required|mimes:text/csv,text/plain,application/csv,text/comma-separated-values,text/anytext,application/octet-stream,application/txt,xlsx,csv,txt',
        ]);
//        dd($request->all());
        try{
            Excel::import(new BonusPlansImport(), $request->file('file'));
            return redirect()->route('plans.bonus_plans.index')->with([
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
