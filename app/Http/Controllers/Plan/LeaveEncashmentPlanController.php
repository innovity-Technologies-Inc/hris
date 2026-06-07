<?php

namespace App\Http\Controllers\Plan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Plan\StoreLeaveEncashmentPlanRequest;
use App\Models\Plan\LeaveEncashmentPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LeaveEncashmentPlanController extends Controller
{
    /**
     * Display the leave encashment plan (if exists) or show option to create
     */
    public function index()
    {
        $title = 'Leave Encashment Plan';
        $section = 'Plans Setup';
        $sub_section = 'Leave Encashment Plan';

        // Check if a leave encashment plan already exists
        $plan = LeaveEncashmentPlan::first();

        return view('plan.leave_encashment_plans.index', compact('title', 'section', 'sub_section', 'plan'));
    }

    /**
     * Show the form for creating a new leave encashment plan
     */
    public function create()
    {
        // Check if a plan already exists
        $existingPlan = LeaveEncashmentPlan::first();

        if ($existingPlan) {
            return redirect()->route('plan.leave_encashment_plans.index')->with([
                'message' => 'A leave encashment plan already exists. You can only edit the existing plan.',
                'alert-type' => 'warning',
            ]);
        }

        $title = 'Create Leave Encashment Plan';
        $section = 'Leave Encashment Plans';
        $sub_section = 'Create';
        $section_url = route('plan.leave_encashment_plans.index');

        return view('plan.leave_encashment_plans.form', compact('title', 'section', 'sub_section', 'section_url'));
    }

    /**
     * Store a newly created leave encashment plan
     */
    public function store(StoreLeaveEncashmentPlanRequest $request)
    {
        // Check if a plan already exists
        $existingPlan = LeaveEncashmentPlan::first();

        if ($existingPlan) {
            return redirect()->route('plan.leave_encashment_plans.index')->with([
                'message' => 'A leave encashment plan already exists. You can only edit the existing plan.',
                'alert-type' => 'warning',
            ]);
        }

        try {
            LeaveEncashmentPlan::create($request->validated());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong, Try Again Later',
                'alert-type' => 'error',
            ]);
        }

        return redirect()->route('plan.leave_encashment_plans.index')->with([
            'message' => 'Leave Encashment Plan Created Successfully',
            'alert-type' => 'success',
        ]);
    }

    /**
     * Show the form for editing the leave encashment plan
     */
    public function edit()
    {
        $plan = LeaveEncashmentPlan::first();

        if (!$plan) {
            return redirect()->route('plan.leave_encashment_plans.create')->with([
                'message' => 'No leave encashment plan exists. Please create one first.',
                'alert-type' => 'info',
            ]);
        }

        $title = 'Edit Leave Encashment Plan';
        $section = 'Leave Encashment Plans';
        $sub_section = 'Edit';
        $section_url = route('plan.leave_encashment_plans.index');

        return view('plan.leave_encashment_plans.form', compact('title', 'section', 'sub_section', 'plan', 'section_url'));
    }

    /**
     * Update the leave encashment plan
     */
    public function update(StoreLeaveEncashmentPlanRequest $request)
    {
        $plan = LeaveEncashmentPlan::first();

        if (!$plan) {
            return redirect()->route('plan.leave_encashment_plans.create')->with([
                'message' => 'No leave encashment plan exists. Please create one first.',
                'alert-type' => 'info',
            ]);
        }

        try {
            $plan->update($request->validated());
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong, Try Again Later',
                'alert-type' => 'error',
            ]);
        }

        return redirect()->route('plan.leave_encashment_plans.index')->with([
            'message' => 'Leave Encashment Plan Updated Successfully',
            'alert-type' => 'success',
        ]);
    }
}
