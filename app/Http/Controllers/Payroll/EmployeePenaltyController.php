<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payroll\StoreEmployeePenaltyRequest;
use App\Models\Employee\Employee;
use App\Models\Plan\PenaltyPlan;
use App\Services\Payroll\EmployeePenaltyServices;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;

class EmployeePenaltyController extends Controller
{
    protected $penaltyServices;

    public function __construct(EmployeePenaltyServices $penaltyServices)
    {
        $this->penaltyServices = $penaltyServices;
    }

    public function index(Request $request, FlexSearch $flexsearch)
    {
        $penalties = $this->penaltyServices->getPenalties($request, $flexsearch);

        if ($request->ajax() || $request->boolean('_ajax')) {
            return view('payroll.penalty.search_results', compact('penalties'))->render();
        }

        $title = 'Employee Penalty Management';
        $section = 'Payroll';
        $sub_section = 'Penalty Management';
        $employees = Employee::where('status', 'active')->get();
        $plans = PenaltyPlan::where('status', 'active')->get();

        return view('payroll.penalty.index', compact('title', 'section', 'sub_section', 'penalties', 'employees', 'plans'));
    }

    public function store(StoreEmployeePenaltyRequest $request)
    {
        try {
            $this->penaltyServices->savePenalty($request->validated());
            return response()->json(['success' => true, 'message' => 'Penalty assigned successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        $penalty = $this->penaltyServices->getPenaltyById($id);
        return response()->json(['success' => true, 'data' => $penalty]);
    }

    public function update(StoreEmployeePenaltyRequest $request, $id)
    {
        try {
            $this->penaltyServices->savePenalty($request->validated(), $id);
            return response()->json(['success' => true, 'message' => 'Penalty updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $this->penaltyServices->deletePenalty($id);
            return response()->json(['success' => true, 'message' => 'Penalty deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function exportExcel(Request $request, FlexSearch $flexsearch)
    {
        $penalties = $this->penaltyServices->getPenalties($request, $flexsearch, false);
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\Payroll\PenaltyExport($penalties), 'employee_penalties_' . now()->format('Ymd_His') . '.xlsx');
    }

    public function printIndex(Request $request, FlexSearch $flexsearch)
    {
        $records = $this->penaltyServices->getPenalties($request, $flexsearch, false);
        return view('payroll.penalty.print_index', compact('records'));
    }
}
