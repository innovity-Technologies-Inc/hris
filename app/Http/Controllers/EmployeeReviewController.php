<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Services\EmployeeServices;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmployeeReviewController extends Controller
{
    protected $employeeServices;

    public function __construct(EmployeeServices $employeeServices)
    {
        $this->employeeServices = $employeeServices;
    }

    /**
     * Display a listing of employees with pending status.
     */
    public function index(Request $request, FlexSearch $flexsearch)
    {
        $title = 'Profile Review';
        $section = 'Employees';
        $sub_section = 'Review List';

        $query = Employee::where('status', 'pending')->with([
            'officeInfo.getCurrentCompany',
            'officeInfo.getCurrentDepartment',
            'officeInfo.getCurrentDesignation'
        ]);

        $searchableColumns = [
            'applicant_id',
            'full_name',
            'system_id',
            'personal_mobile',
            'work_email',
        ];

        $keyword = $request->input('search');
        $filters = [];

        if ($request->filled('department')) {
            $query->whereHas('officeInfo', function($q) use ($request) {
                $q->where('current_department_id', $request->input('department'));
            });
        }

        $employees = $flexsearch->apply($query, $filters, $keyword, $searchableColumns)
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('employees.review.partials.table', compact('employees'))->render(),
                'pagination' => $employees->links()->render()
            ]);
        }

        $departments = \App\Models\Department::select('id', 'department_name')->orderBy('department_name')->get();

        return view('employees.review.index', compact('title', 'section', 'sub_section', 'employees', 'departments'));
    }

    /**
     * Process the profile review.
     */
    public function review(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,incomplete',
            'cause' => 'required_if:status,incomplete|nullable|string',
        ]);

        try {
            $employee = Employee::findOrFail($id);
            $this->employeeServices->reviewProfile($employee, $request->status, $request->cause);

            return redirect()->route('employees.review.index')->with([
                'message' => 'Employee profile reviewed successfully.',
                'alert-type' => 'success'
            ]);
        } catch (\Exception $e) {
            Log::error('Profile Review Error: ' . $e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something went wrong while reviewing the profile.',
                'alert-type' => 'error'
            ]);
        }
    }
}
