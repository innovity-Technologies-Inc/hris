<?php

namespace App\Http\Controllers\Plan;

use App\Http\Controllers\Controller;

use App\Models\Plan\DeductionPlan;
use App\Services\Plan\PlanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DeductionPlanController extends Controller
{
    protected $planServices;

    public function __construct(PlanService $planServices)
    {
        $this->planServices = $planServices;
    }

    /**
     * Display the deduction plan (if exists) or show option to create
     */
    public function index()
    {
        $title = 'Deduction Plan';
        $section = 'Plans Setup';
        $sub_section = 'Deduction Plan';

        // Check if a deduction plan already exists
        $plan = DeductionPlan::first();

        return view('plan.deduction_plans.index', compact('title', 'section', 'sub_section', 'plan'));
    }

    /**
     * Show the form for creating a new deduction plan
     * Only accessible if no plan exists
     */
    public function create()
    {
        // Check if a plan already exists
        $existingPlan = DeductionPlan::first();

        if ($existingPlan) {
            return redirect()->route('plan.deduction_plans.index')->with([
                'message' => 'A deduction plan already exists. You can only edit the existing plan.',
                'alert-type' => 'warning',
            ]);
        }

        $title = 'Create Deduction Plan';
        $section = 'Deduction Plans';
        $sub_section = 'Create';
        $section_url = route('plan.deduction_plans.index');

        return view('plan.deduction_plans.form', compact('title', 'section', 'sub_section', 'section_url'));
    }

    /**
     * Store a newly created deduction plan
     */
    public function store(Request $request)
    {
        // Check if a plan already exists
        $existingPlan = DeductionPlan::first();

        if ($existingPlan) {
            return redirect()->route('plan.deduction_plans.index')->with([
                'message' => 'A deduction plan already exists. You can only edit the existing plan.',
                'alert-type' => 'warning',
            ]);
        }

        $validated = $this->planServices->deductionPlanValidation($request);

        try {
            $this->planServices->planSave($validated, DeductionPlan::class);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong, Try Again Later',
                'alert-type' => 'error',
            ]);
        }

        return redirect()->route('plan.deduction_plans.index')->with([
            'message' => 'Deduction Plan Created Successfully',
            'alert-type' => 'success',
        ]);
    }

    /**
     * Show the form for editing the deduction plan
     */
    public function edit()
    {
        $plan = DeductionPlan::first();

        if (!$plan) {
            return redirect()->route('plan.deduction_plans.create')->with([
                'message' => 'No deduction plan exists. Please create one first.',
                'alert-type' => 'info',
            ]);
        }

        $title = 'Edit Deduction Plan';
        $section = 'Deduction Plans';
        $sub_section = 'Edit';
        $section_url = route('plan.deduction_plans.index');

        return view('plan.deduction_plans.form', compact('title', 'section', 'sub_section', 'section_url', 'plan'));
    }

    /**
     * Update the deduction plan
     */
    public function update(Request $request)
    {
        $plan = DeductionPlan::first();

        if (!$plan) {
            return redirect()->route('plan.deduction_plans.create')->with([
                'message' => 'No deduction plan exists. Please create one first.',
                'alert-type' => 'info',
            ]);
        }

        $validated = $this->planServices->deductionPlanValidation($request);

        try {
            $this->planServices->planSave($validated, DeductionPlan::class, $plan->id);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong, Try Again Later',
                'alert-type' => 'error',
            ]);
        }

        return redirect()->route('plan.deduction_plans.index')->with([
            'message' => 'Deduction Plan Updated Successfully',
            'alert-type' => 'success',
        ]);
    }
}

