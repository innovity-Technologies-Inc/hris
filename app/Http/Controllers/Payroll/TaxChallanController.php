<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payroll\StoreTaxChallanRequest;
use App\Http\Requests\Payroll\UpdateTaxChallanRequest;
use App\Models\Payroll\TaxChallan;
use App\Models\Company\Company;
use App\Models\Employee\Employee;
use App\Services\Payroll\TaxChallanServices;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;

class TaxChallanController extends Controller
{
    use ApiResponse;

    protected $taxChallanServices;

    public function __construct(TaxChallanServices $taxChallanServices)
    {
        $this->taxChallanServices = $taxChallanServices;
    }

    /**
     * Display a listing of tax challans.
     */
    public function index(Request $request, FlexSearch $flexSearch)
    {
        $title = 'Tax Challan Management';
        $section = 'Finance';
        $sub_section = 'Tax Challan';

        $challans = $this->taxChallanServices->searchResult($request, $flexSearch);

        if ($request->ajax() || $request->boolean('_ajax')) {
            return view('payroll.tax_challan.partials.search_results', compact('challans'))->render();
        }

        $companies = Company::select('id', 'name')->orderBy('name')->get();
        
        // Load all active employees for selection dropdowns
        $employees = Employee::select('id', 'full_name', 'system_id', 'applicant_id')
            ->where('status', 'active')
            ->orderBy('full_name')
            ->get();

        return view('payroll.tax_challan.index', compact('title', 'section', 'sub_section', 'challans', 'companies', 'employees'));
    }

    /**
     * Store a newly created tax challan.
     */
    public function store(StoreTaxChallanRequest $request)
    {
        $challan = $this->taxChallanServices->storeChallan($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Tax Challan created successfully.',
            'data' => $challan
        ], 201);
    }

    /**
     * Show the form for editing the specified tax challan.
     */
    public function edit(int $id)
    {
        $challan = TaxChallan::with(['employee', 'company'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $challan
        ]);
    }

    /**
     * Update the specified tax challan.
     */
    public function update(UpdateTaxChallanRequest $request, int $id)
    {
        $challan = $this->taxChallanServices->updateChallan($id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Tax Challan updated successfully.',
            'data' => $challan
        ], 200);
    }

    /**
     * Remove the specified tax challan.
     */
    public function destroy(int $id)
    {
        $this->taxChallanServices->deleteChallan($id);

        return response()->json([
            'success' => true,
            'message' => 'Tax Challan deleted successfully.'
        ], 200);
    }
}
