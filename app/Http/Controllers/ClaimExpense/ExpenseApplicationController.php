<?php

namespace App\Http\Controllers\ClaimExpense;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClaimExpense\ExpenseApplicationRequest;
use App\Models\ClaimExpense\ExpenseApplication;
use App\Models\ClaimExpense\ExpenseType;
use App\Models\Employee\Employee;
use App\Services\ClaimExpense\ExpenseApplicationService;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use App\Enums\UserType;

class ExpenseApplicationController extends Controller
{
    protected $expenseService;

    public function __construct(ExpenseApplicationService $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    public function index(Request $request, FlexSearch $flexsearch)
    {
        $title = 'Claim Expense Logs';
        $section = 'Claim Expense';

        if ($request->ajax()) {
            $user = auth()->user();
            $query = ExpenseApplication::withoutGlobalScopes()
                ->with(['employee', 'expenseType']);

            // Apply organization / user scoping manually
            if ($user->user_type !== UserType::Group) {
                $query->where(function($q) use ($user) {
                    $q->where('created_by', $user->id);

                    $employee = $user->employee()->with('officeInfo')->first();
                    if ($employee && $employee->officeInfo) {
                        $office = $employee->officeInfo;
                        $q->orWhereHas('employee.officeInfo', function($oq) use ($user, $office) {
                            if ($user->user_type === UserType::Company) $oq->where('current_company_id', $office->current_company_id);
                            elseif ($user->user_type === UserType::BusinessUnit) $oq->where('current_business_unit_id', $office->current_business_unit_id);
                            elseif ($user->user_type === UserType::Division) $oq->where('current_division_id', $office->current_division_id);
                            elseif ($user->user_type === UserType::Department) $oq->where('current_department_id', $office->current_department_id);
                            elseif ($user->user_type === UserType::Section) $oq->where('current_section_id', $office->current_section_id);
                        });
                    }

                    $q->orWhereHas('approvalRequests.stepRequests', function($sq) use ($user) {
                        $sq->where('approver_id', $user->id)->where('status', 'pending');
                    });
                });
            }

            $applications = $flexsearch->apply($query, [], $request->get('keyword'), ['employee.full_name', 'expenseType.name', 'purpose', 'payment_method', 'status'])
                ->orderBy('id', 'desc')
                ->paginate(15);

            return view('claim_expense.expense_applications.search_results', compact('applications'))->render();
        }

        return view('claim_expense.expense_applications.index', compact('title', 'section'));
    }

    public function create()
    {
        $title = 'Claim Expense Application';
        $section = 'Claim Expense';
        
        $user = auth()->user();
        $isEmployee = $user->user_type === UserType::Employee;
        $loggedInEmployeeId = $user->employee_id;

        // Retrieve scoped expense types and employees
        $expenseTypes = ExpenseType::where('status', 'active')->get();
        $employees = Employee::select('id', 'full_name', 'applicant_id')->get();

        return view('claim_expense.expense_applications.create', compact('title', 'section', 'isEmployee', 'loggedInEmployeeId', 'expenseTypes', 'employees'));
    }

    public function store(ExpenseApplicationRequest $request)
    {
        try {
            $application = $this->expenseService->createApplication(
                $request->safe()->except(['receipt']),
                $request->file('receipt')
            );

            return response()->json([
                'success' => true,
                'message' => 'Expense application submitted successfully.',
                'data' => $application
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit application: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $title = 'Claim Expense Details';
        $section = 'Claim Expense';

        $application = ExpenseApplication::withoutGlobalScopes()
            ->with(['employee.officeInfo', 'expenseType', 'approvalRequests.stepRequests.workflowStep', 'creator'])
            ->findOrFail($id);

        return view('claim_expense.expense_applications.show', compact('title', 'section', 'application'));
    }

    public function destroy($id)
    {
        try {
            $application = ExpenseApplication::withoutGlobalScopes()->findOrFail($id);
            $this->expenseService->deleteApplication($application);

            return response()->json([
                'success' => true,
                'message' => 'Expense application deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete application: ' . $e->getMessage()
            ], 500);
        }
    }
}
