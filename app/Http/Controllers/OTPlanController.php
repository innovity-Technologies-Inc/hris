<?php

namespace App\Http\Controllers;

use App\Models\OTPlan;
use App\Services\PlanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OTPlanController extends Controller
{
    protected $planServices;

    public function __construct(PlanService $planServices)
    {
        $this->planServices = $planServices;
    }

    public function index()
    {
        $title = 'OT Plan';
        $section = 'Plans Setup';
        $sub_section = 'OT Plan';
        $plans = $this->planServices->getPlans(OTPlan::class, 20);
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
}
