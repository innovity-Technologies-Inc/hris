<?php

namespace App\Http\Controllers;

use App\Models\EmployeeEmploymentHistory;
use App\Services\EmployeeServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmployeeEmploymentHistoryController extends Controller
{
    protected $empServices;

    public function __construct(EmployeeServices $empServices)
    {
        $this->empServices = $empServices;
    }

    public function create($id)
    {
        $title = 'Add Employment History';
        $section = 'Employees';
        $sub_section = 'Employment History / Add';
        $section_url = route('employees.index');
        $employee = $this->empServices->getEmployeeById($id);

        if (!$employee) {
            abort(404, 'Employee not found');
        }

        // Security check: Employees can only view their own profile
        if (auth()->user()->user_type === 'Employee' && auth()->user()->employee_id != $id) {
            abort(403, 'Unauthorized access to other profiles.');
        }

        return view('employees.employment_histories.form', compact('employee', 'title', 'section', 'sub_section', 'section_url'));
    }

    public function store(Request $request)
    {
        $validated = $this->empServices->employeeEmploymentHistoryValidation($request);
        try {
            $history = $this->empServices->employeeEmploymentHistorySave($validated);
            return redirect()->route('employees.profile.employment_history', $history->employee_id)->with([
                'message' => 'Employment History Added Successfully',
                'alert-type' => 'success'
            ]);
        } catch (\Exception $e) {
            Log::error('Employment History Store Error: ' . $e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something went wrong while saving employment history.',
                'alert-type' => 'error'
            ]);
        }
    }

    public function show($id)
    {
        $title = 'Employment History';
        $section = 'Employees';
        $sub_section = 'Profile - Employment History';
        $section_url = route('employees.index');
        $employee = $this->empServices->getEmployeeById($id);

        if (!$employee) {
            abort(404, 'Employee not found');
        }

        // Security check
        if (auth()->user()->user_type === 'Employee' && auth()->user()->employee_id != $id) {
            abort(403, 'Unauthorized access.');
        }

        $historyData = EmployeeEmploymentHistory::where('employee_id', $id)->first();
        $histories = $historyData->histories ?? [];

        return view('employees.profile', compact('employee', 'histories', 'title', 'section', 'sub_section', 'section_url', 'historyData'));
    }

    public function edit($id)
    {
        $employee = $this->empServices->getEmployeeById($id);

        if (!$employee) {
            abort(404, 'Employee not found');
        }

        // Security check
        if (auth()->user()->user_type === 'Employee' && auth()->user()->employee_id != $id) {
            abort(403, 'Unauthorized access.');
        }

        $title = 'Edit Employment History';
        $section = 'Employees';
        $sub_section = 'Employment History / Edit';
        $section_url = route('employees.index');
        $historyData = EmployeeEmploymentHistory::where('employee_id', $id)->firstOrFail();

        return view('employees.employment_histories.form', compact('historyData', 'employee', 'title', 'section', 'sub_section', 'section_url'));
    }

    public function update(Request $request, $id)
    {
        $validated = $this->empServices->employeeEmploymentHistoryValidation($request);
        $history = EmployeeEmploymentHistory::where('employee_id', $id)->first();

        try {
            $history = $this->empServices->employeeEmploymentHistorySave($validated, $history);
            
            $redirectRoute = (auth()->user()->user_type === 'Employee')
                ? route('employees.profile.employment_history', $id)
                : route('employees.index');

            return redirect()->to($redirectRoute)->with([
                'message' => 'Employment History Updated Successfully',
                'alert-type' => 'success'
            ]);
        } catch (\Exception $e) {
            Log::error('Employment History Update Error: ' . $e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something went wrong while updating employment history.',
                'alert-type' => 'error'
            ]);
        }
    }
}
