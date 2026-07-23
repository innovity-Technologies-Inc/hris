<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Payroll\TaxCalculation;
use App\Services\Payroll\TaxCalculateService;
use App\Jobs\Payroll\ProcessTaxCalculationJob;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Support\Facades\Log;

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
        $title = 'Tax Calculation Logs';
        $section = 'Finance';
        $sub_section = 'Tax Calculate';

        $calculations = $this->taxCalculateService->searchResult($request, TaxCalculation::class, $flexSearch);

        if ($request->ajax() || $request->boolean('_ajax')) {
            return view('payroll.tax_calculate.partials.search_results', compact('calculations'))->render();
        }

        return view('payroll.tax_calculate.index', compact('title', 'section', 'sub_section', 'calculations'));
    }

    /**
     * Trigger batch tax calculation job.
     */
    public function calculate(Request $request)
    {
        try {
            Log::info('TaxCalculateController: Dispatching tax calculation job.');
            
            // Dispatch job
            ProcessTaxCalculationJob::dispatch();

            return $this->successResponse('Tax calculation initiated successfully. Slabs are being evaluated in the background.');
        } catch (\Exception $e) {
            Log::error('TaxCalculateController: Failed to trigger tax calculation.', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to trigger tax calculation: ' . $e->getMessage());
        }
    }
}
