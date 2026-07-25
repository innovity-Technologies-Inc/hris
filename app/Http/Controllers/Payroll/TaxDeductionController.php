<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Payroll\TaxDeductionHistory;
use App\Models\Company\Company;
use App\Models\Company\CompanyLocation;
use App\Models\Company\Division;
use App\Models\Company\Department;
use App\Models\Company\Section;
use App\Services\Payroll\TaxDeductionServices;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;

use App\Exports\Payroll\TaxDeductionExport;
use Maatwebsite\Excel\Facades\Excel;

class TaxDeductionController extends Controller
{
    use ApiResponse;

    protected $taxDeductionServices;

    public function __construct(TaxDeductionServices $taxDeductionServices)
    {
        $this->taxDeductionServices = $taxDeductionServices;
    }

    /**
     * Display the list of tax deduction histories.
     */
    public function index(Request $request, FlexSearch $flexSearch)
    {
        $title = 'Tax Deduction History';
        $section = 'Finance';
        $sub_section = 'Tax Deduction';

        $deductions = $this->taxDeductionServices->searchResult($request, $flexSearch);

        if ($request->ajax() || $request->boolean('_ajax')) {
            return view('payroll.tax_deduction.partials.search_results', compact('deductions'))->render();
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

        return view('payroll.tax_deduction.index', compact(
            'title', 'section', 'sub_section', 'deductions', 'companies',
            'selectedBranch', 'selectedDivision', 'selectedDepartment', 'selectedSection'
        ));
    }

    /**
     * Export tax deductions to Excel (not paginated).
     */
    public function export(Request $request, FlexSearch $flexSearch)
    {
        $deductions = $this->taxDeductionServices->searchResult($request, $flexSearch, false);

        return Excel::download(
            new TaxDeductionExport($deductions),
            'tax_deduction_history_' . now()->format('Ymd_His') . '.xlsx'
        );
    }

    /**
     * Print tax deduction list (not paginated).
     */
    public function printIndex(Request $request, FlexSearch $flexSearch)
    {
        $records = $this->taxDeductionServices->searchResult($request, $flexSearch, false);
        $title = 'Tax Deduction History';

        return view('payroll.tax_deduction.print_index', compact('records', 'title'));
    }
}
