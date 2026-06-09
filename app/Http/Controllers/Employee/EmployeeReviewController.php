<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;

use App\Models\Employee\Employee;
use App\Services\Employee\EmployeeServices;
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
            'officeInfo.getCurrentDesignation',
            'educationInfo',
            'nomineeInfo',
            'employmentHistory',
        ]);

        $searchableColumns = [
            'full_name',
            'personal_email',
            'work_email',
            'applicant_id',
            'punch_card_no',
            'system_id',
        ];

        $keyword = $request->input('search');
        $filters = [];

        $employees = $flexsearch->apply($query, $filters, $keyword, $searchableColumns)
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('employee.review.partials.table', compact('employees'))->render(),
                'pagination' => $employees->links()->render()
            ]);
        }

        return view('employee.review.index', compact('title', 'section', 'sub_section', 'employees'));
    }

    /**
     * Process the profile review.
     */
    public function review(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,incomplete',
            'cause' => 'required_if:status,incomplete|nullable|string',
            'sections' => 'nullable|array',
        ]);

        try {
            $employee = Employee::findOrFail($id);
            $this->employeeServices->reviewProfile($employee, $request->status, $request->cause, $request->sections);

            return redirect()->route('employee.review.index')->with([
                'message' => 'Employee profile reviewed successfully.',
                'alert-type' => 'success'
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in EmployeeReviewController@review: ' . $e->getMessage(), ['exception' => $e]);

            return redirect()->back()->with([
                'message' => 'Something went wrong while reviewing the profile.',
                'alert-type' => 'error'
            ]);
        }
    }
}

