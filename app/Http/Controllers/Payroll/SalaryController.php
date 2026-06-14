<?php

namespace App\Http\Controllers\Payroll;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Models\Company\Company;
use App\Models\Employee\Employee;
use App\Models\Payroll\Payroll;
use App\Models\Payroll\PayrollProcess;
use App\Services\Payroll\PayrollServices;
use App\Services\Payroll\PayslipService;
use App\Services\Payroll\SalaryCertificateService;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalaryController extends Controller
{
    protected $payrollService;
    protected $payslipService;
    protected $salaryCertificateService;

    public function __construct(
        PayrollServices $payrollService, 
        PayslipService $payslipService,
        SalaryCertificateService $salaryCertificateService
    )
    {
        $this->payrollService = $payrollService;
        $this->payslipService = $payslipService;
        $this->salaryCertificateService = $salaryCertificateService;
    }

    public function generatePayslip($id)
    {
        try {
            $pdfContent = $this->payslipService->generatePayslip($id);
            $payroll = Payroll::with('getEmployee')->findOrFail($id);
            $fileName = 'Payslip_' . str_replace(' ', '_', $payroll->getEmployee->full_name) . '_' . date('M_Y', strtotime($payroll->created_at)) . '.pdf';

            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="' . $fileName . '"');
        } catch (\Exception $e) {
            Log::error('Payslip generation failed: ' . $e->getMessage());
            return redirect()->back()->with([
                'alert-type' => 'error',
                'message' => 'Failed to generate payslip: ' . $e->getMessage()
            ]);
        }
    }

    public function generateSalaryCertificate($id)
    {
        try {
            $pdfContent = $this->salaryCertificateService->generateSalaryCertificateFromPayroll($id);
            $payroll = Payroll::with('getEmployee')->findOrFail($id);
            $fileName = 'Salary_Certificate_' . str_replace(' ', '_', $payroll->getEmployee->full_name) . '.pdf';

            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="' . $fileName . '"');
        } catch (\Exception $e) {
            Log::error('Salary Certificate generation failed: ' . $e->getMessage());
            return redirect()->back()->with([
                'alert-type' => 'error',
                'message' => 'Failed to generate salary certificate: ' . $e->getMessage()
            ]);
        }
    }

    public function generateSalaryCertificateFromProfile($employee_id)
    {
        try {
            // Security check: Employees can only generate their own certificate
            if (auth()->user()->user_type === UserType::Employee && auth()->user()->employee_id != $employee_id) {
                abort(403, 'Unauthorized access to other profiles.');
            }

            $pdfContent = $this->salaryCertificateService->generateSalaryCertificateFromEmployee($employee_id);
            $employee = Employee::findOrFail($employee_id);
            $fileName = 'Salary_Certificate_' . str_replace(' ', '_', $employee->full_name) . '.pdf';

            return response($pdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="' . $fileName . '"');
        } catch (\Exception $e) {
            Log::error('Salary Certificate generation from profile failed: ' . $e->getMessage());
            return redirect()->back()->with([
                'alert-type' => 'error',
                'message' => 'Failed to generate salary certificate: ' . $e->getMessage()
            ]);
        }
    }

    public function index(Request $request, FlexSearch $flexSearch)
    {
        $title = 'Employee Payroll';
        $section = 'Employee Payroll';
        $sub_section = 'Index';
        $searchResult = $this->payrollService->payrollProcessSearchResult($request,PayrollProcess::class,
            $flexSearch);
        $payrollProcesses = $searchResult->where('type', 'salary')->orderBy('created_at', 'desc')->paginate(20);
        if ($request->ajax()) {
            return view('payroll.salary.partials.search_results', compact('payrollProcesses'));
        }
        return view('payroll.salary.index', compact('title', 'section', 'sub_section',
            'payrollProcesses'));
    }

    public function create()
    {
        $title = 'Add Employee Payroll';
        $section = 'Employee Payroll';
        $section_url = route('salary.index');
        $sub_section = 'Add';
        $companies = Company::all();
        $payGroups = \App\Models\Company\PayGroup::where('status', 'active')->get();
        return view('payroll.salary.create', compact('title', 'section',
            'sub_section', 'section_url', 'companies', 'payGroups'));
    }

    public function edit($id)
    {
        $title = 'Edit Payroll Data';
        $section = 'Employee Payroll';
        $section_url = route('salary.index');
        $employees = Employee::has('salary')->where('status', 'active')->get();
        $sub_section = 'Edit';
        $companies = Company::all();
        $payGroups = \App\Models\Company\PayGroup::where('status', 'active')->get();
        $salaryData = PayrollProcess::find($id);
        return view('payroll.salary.create', compact('title', 'section',
            'section_url', 'salaryData', 'employees', 'sub_section', 'companies', 'payGroups'));
    }

    public function save(Request $request, $id=null){
        Log::info('Salary generation started.', [
            'id' => $id,
            'request_data' => $request->all()
        ]);
        $data = $this->payrollService->payrollProcessDataValidation($request);
        try{
            if ($id == null) {
                $this->payrollService->salaryProcess($data);
            }else{
                Log::info('Updating existing salary process.', ['process_id' => $id]);
                DB::transaction(function () use ($id, $data) {
                    $this->payrollService->salaryDelete($id);
                    $this->payrollService->salaryProcess($data, $id);
                });
            }
        }catch (\Exception $exception){
            Log::error('Salary generation failed.', [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString()
            ]);
            return redirect()->back()->with([
                'alert-type' => 'error',
                'message' => $exception->getMessage() == 'Eligible Employees not found.'?  $exception->getMessage() : 'Something went wrong! '
            ]);
        }
        Log::info('Salary generation completed successfully.', ['id' => $id]);
        return redirect()->route('salary.index')->with([
            'alert-type' => 'success',
            'message' => 'Salary Generated successfully! Wait for approval.'
        ]);
    }

    public function show($id)
    {
        $title = 'Eligible Employees';
        $section = 'Employee Payroll';
        $section_url = route('salary.index');
        $sub_section = 'Eligible Employee';
        $process = PayrollProcess::findOrFail($id);
        $salary_month = $process->salary_month;
        $salaryes = Payroll::where('process_id', $id)->orderBy('created_at', 'desc')->paginate(10);
        return view('payroll.salary.view', compact('title', 'section', 'section_url', 'sub_section',
            'salaryes', 'salary_month'));
    }

    public function showPayroll($id)
    {
        $payroll = Payroll::with(['getEmployee', 'getBatch'])->findOrFail($id);
        $title = 'Payroll Details';
        $section = 'Employee Payroll';
        $section_url = route('salary.index');
        $sub_section = 'Payroll Detail';
        
        return view('payroll.salary.payroll_view', compact('payroll', 'title', 'section', 'section_url', 'sub_section'));
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id) {
            $this->payrollService->salaryDelete($id);
            $this->payrollService->processDelete($id);
        });
        return redirect()->back()->with([
            'message' => 'Deleted Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function statusUpdate(Request $request, $id){
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);
        $process = PayrollProcess::find($id);
        $process->update([
            'approval_status' => $request->status,
        ]);

        return redirect()->route('salary.index')->with([
            'message' => 'Updated Successfully',
        ]);
    }
}

