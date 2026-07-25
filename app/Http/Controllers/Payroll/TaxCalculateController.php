<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Payroll\TaxCalculation;
use App\Models\Company\Company;
use App\Models\Company\CompanyLocation;
use App\Models\Company\Division;
use App\Models\Company\Department;
use App\Models\Company\Section;
use App\Services\Payroll\TaxCalculateService;
use App\Jobs\Payroll\ProcessTaxCalculationJob;
use App\Exports\Payroll\EmployeeTaxExport;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class TaxCalculateController extends Controller
{
    use ApiResponse;

    protected $taxCalculateService;

    public function __construct(TaxCalculateService $taxCalculateService)
    {
        $this->taxCalculateService = $taxCalculateService;
    }

    /**
     * Display the search/index list of tax calculations.
     */
    public function index(Request $request, FlexSearch $flexSearch)
    {
        $title = 'Employee Tax';
        $section = 'Finance';
        $sub_section = 'Employee Tax';

        $calculations = $this->taxCalculateService->searchResult($request, TaxCalculation::class, $flexSearch);

        if ($request->ajax() || $request->boolean('_ajax')) {
            return view('payroll.tax_calculate.partials.search_results', compact('calculations'))->render();
        }

        $companies = Company::select('id', 'name')->orderBy('name')->get();
        $selectedBranch = $request->filled('business_unit') 
            ? CompanyLocation::find($request->input('business_unit')) 
            : null;
        $selectedDivision = $request->filled('division') 
            ? Division::find($request->input('division')) 
            : null;
        $selectedDepartment = $request->filled('department') 
            ? Department::find($request->input('department')) 
            : null;
        $selectedSection = $request->filled('section') 
            ? Section::find($request->input('section')) 
            : null;

        return view('payroll.tax_calculate.index', compact(
            'title', 'section', 'sub_section', 'calculations', 'companies',
            'selectedBranch', 'selectedDivision', 'selectedDepartment', 'selectedSection'
        ));
    }

    /**
     * Display the instruction and calculate tax trigger page.
     */
    public function processPage()
    {
        $title = 'Calculate Tax';
        $section = 'Finance';
        $sub_section = 'Calculate Tax';

        return view('payroll.tax_calculate.process', compact('title', 'section', 'sub_section'));
    }

    /**
     * Export employee tax calculations to Excel (not paginated).
     */
    public function export(Request $request, FlexSearch $flexSearch)
    {
        Log::info('TaxCalculateController: Exporting employee taxes.');
        
        $calculations = $this->taxCalculateService->searchResult($request, TaxCalculation::class, $flexSearch, false);

        return Excel::download(
            new EmployeeTaxExport($calculations),
            'employee_taxes_' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    /**
     * Trigger batch tax calculation job.
     */
    public function calculate(Request $request)
    {
        try {
            $employeeCount = \App\Models\Employee\Employee::where('status', 'active')->count();

            // Threshold: if dataset is small (e.g. <= 500 employees), process synchronously for instant feedback.
            // Otherwise, dispatch background queue job to avoid HTTP gateway timeout.
            if ($employeeCount <= 500) {
                Log::info('TaxCalculateController: Processing tax calculation synchronously.', ['count' => $employeeCount]);
                $this->taxCalculateService->calculateTaxForAllEmployees();
                return $this->successResponse('Tax calculation completed successfully.');
            } else {
                Log::info('TaxCalculateController: Dispatching background queue job.', ['count' => $employeeCount]);
                ProcessTaxCalculationJob::dispatch();
                return $this->successResponse('Tax calculation initiated successfully. Slabs are being evaluated in the background.');
            }
        } catch (\Exception $e) {
            Log::error('TaxCalculateController: Failed to calculate tax.', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to calculate tax: ' . $e->getMessage());
        }
    }

    /**
     * Get tax calculation progress status.
     */
    public function progress()
    {
        $status = \Illuminate\Support\Facades\Cache::get('tax_calculation_status', [
            'total' => 0,
            'processed' => 0,
            'status' => 'idle'
        ]);

        return response()->json($status);
    }
}
