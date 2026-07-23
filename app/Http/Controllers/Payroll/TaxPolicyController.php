<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payroll\StoreTaxPolicyRequest;
use App\Models\Payroll\TaxPolicy;
use App\Models\Company\Company;
use App\Services\Payroll\TaxPolicyService;
use App\Traits\ApiResponse;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaxPolicyController extends Controller
{
    use ApiResponse;

    protected $taxPolicyService;

    public function __construct(TaxPolicyService $taxPolicyService)
    {
        $this->taxPolicyService = $taxPolicyService;
    }

    /**
     * Get the allowance fields mapping for the dropdown.
     */
    private function getAllowanceMapping(): array
    {
        return [
            'house_allowance' => 'House Allowance',
            'transport_allowance' => 'Transport Allowance',
            'food_allowance' => 'Food Allowance',
            'medical_allowance' => 'Medical Allowance',
            'other_earnings' => 'Other Earnings',
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, FlexSearch $flexsearch)
    {
        $title = 'Tax Policies';
        $section = 'Finance';
        $sub_section = 'Tax Policy';

        $query = TaxPolicy::with('slabs');
        
        // Use FlexSearch to search
        $policies = $flexsearch->apply($query, [], $request->get('keyword'), ['zero_tax_male', 'zero_tax_female', 'min_tax_amount', 'exemption_type'])
            ->orderBy('id', 'desc')
            ->paginate(15);

        if ($request->ajax() || $request->boolean('_ajax')) {
            return view('payroll.tax_policy.partials.search_results', compact('policies'))->render();
        }

        return view('payroll.tax_policy.index', compact('title', 'section', 'sub_section', 'policies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Create Tax Policy';
        $section = 'Finance';
        $sub_section = 'Create';
        $section_url = route('tax-policy.index');
        
        $companies = Company::all();
        $allowanceMapping = $this->getAllowanceMapping();

        return view('payroll.tax_policy.create', compact('title', 'section', 'sub_section', 'section_url', 'companies', 'allowanceMapping'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaxPolicyRequest $request)
    {
        try {
            $policy = $this->taxPolicyService->saveTaxPolicy($request->validated());
            return $this->createdResponse('Tax Policy created successfully.', [
                'redirect_url' => route('tax-policy.index')
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to store tax policy.', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to create Tax Policy: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $title = 'Edit Tax Policy';
        $section = 'Finance';
        $sub_section = 'Edit';
        $section_url = route('tax-policy.index');
        
        $policy = TaxPolicy::with('slabs')->findOrFail($id);
        $companies = Company::all();
        $allowanceMapping = $this->getAllowanceMapping();

        return view('payroll.tax_policy.create', compact('title', 'section', 'sub_section', 'section_url', 'policy', 'companies', 'allowanceMapping'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTaxPolicyRequest $request, $id)
    {
        try {
            $policy = $this->taxPolicyService->saveTaxPolicy($request->validated(), $id);
            return $this->successResponse('Tax Policy updated successfully.', [
                'redirect_url' => route('tax-policy.index')
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update tax policy.', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update Tax Policy: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $this->taxPolicyService->deleteTaxPolicy($id);
            return $this->deletedResponse('Tax Policy deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to delete tax policy.', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to delete Tax Policy: ' . $e->getMessage());
        }
    }
}
