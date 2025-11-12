<?php

namespace App\Http\Controllers;

use App\Models\LeavePlan;
use App\Services\PlanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LeavePlanController extends Controller
{
    protected $planServices;

    public function __construct(PlanService $planServices)
    {
        $this->planServices = $planServices;
    }

    public function index()
    {
        $title = 'Leave Plan';
        $section = 'Plans Setup';
        $sub_section = 'Leave Plan';
        $plans = $this->planServices->getPlans(LeavePlan::class, 20);
        return view('plans.leave_plans.index', compact('title', 'section', 'sub_section', 'plans'));
    }

    public function create()
    {
        $title = 'Create Leave Plan';
        $section = 'Leave Plans';
        $sub_section = 'Create';
        $section_url = route('plans.leave_plans.index');
        return view('plans.leave_plans.form', compact('title', 'section', 'sub_section', 'section_url'));
    }

    public function store(Request $request)
    {
        $validated = $this->planServices->leavePlanValidation($request);

        try {
            $this->planServices->planSave($validated, LeavePlan::class);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong, Try Again Later',
                'alert-type' => 'error',
            ]);
        }

        return redirect()->route('plans.leave_plans.index')->with([
            'message' => 'Leave Plan Created Successfully',
            'alert-type' => 'success',
        ]);
    }

    public function show($id)
    {
        $title = 'Leave Plan';
        $section = 'Leave Plans';
        $sub_section = 'Show';
        $section_url = route('plans.leave_plans.index');
        $plan = $this->planServices->getPlanById($id, LeavePlan::class);
        return view('plans.leave_plans.view', compact('title', 'section', 'sub_section', 'section_url', 'plan'));
    }

    public function edit($id)
    {
        $title = 'Edit Leave Plan';
        $section = 'Leave Plans';
        $sub_section = 'Edit';
        $section_url = route('plans.leave_plans.index');
        $plan = $this->planServices->getPlanById($id, LeavePlan::class);
        return view('plans.leave_plans.form', compact('title', 'section', 'sub_section', 'section_url', 'plan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $this->planServices->leavePlanValidation($request);

        try {
            $this->planServices->planSave($validated, LeavePlan::class, $id);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong, Try Again Later',
                'alert-type' => 'error',
            ]);
        }

        return redirect()->route('plans.leave_plans.index')->with([
            'message' => 'Leave Plan Updated Successfully',
            'alert-type' => 'success',
        ]);
    }

    public function delete($id)
    {
        try {
            $this->planServices->planDelete(LeavePlan::class, $id);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something Went Wrong, Try Again Later',
                'alert-type' => 'error',
            ]);
        }

        return redirect()->back()->with([
            'message' => 'Leave Plan Deleted Successfully',
        ]);
    }
}
