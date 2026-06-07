<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\PayScaleRequest;
use App\Models\Company\PayScale;
use App\Models\Company\SalaryGrade;
use App\Models\Company\PayGroup;
use App\Services\Company\PayScaleServices;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;

class PayScaleController extends Controller
{
    protected $payScaleService;

    public function __construct(PayScaleServices $payScaleService)
    {
        $this->payScaleService = $payScaleService;
    }

    public function index(Request $request, FlexSearch $flexsearch)
    {
        $title = 'Pay Scales';
        $section = 'Company Info';
        $sub_section = 'Pay Scales';

        if ($request->ajax()) {
            $payScales = $this->payScaleService->getPayScales($request, $flexsearch);
            return view('company.pay_scales.search_results', compact('payScales'))->render();
        }

        $grades = SalaryGrade::where('status', 'active')->get();
        $payGroups = PayGroup::where('status', 'active')->get();
        
        return view('company.pay_scales.index', compact('title', 'section', 'sub_section', 'grades', 'payGroups'));
    }

    public function store(PayScaleRequest $request)
    {
        try {
            $payScale = $this->payScaleService->storePayScale($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Pay Scale saved successfully.',
                'data' => $payScale
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save Pay Scale.'
            ], 500);
        }
    }

    public function edit($id)
    {
        $payScale = PayScale::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $payScale
        ]);
    }

    public function update(PayScaleRequest $request, $id)
    {
        try {
            $payScale = PayScale::findOrFail($id);
            $this->payScaleService->updatePayScale($payScale, $request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Pay Scale updated successfully.',
                'data' => $payScale
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Pay Scale.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $payScale = PayScale::findOrFail($id);
            $this->payScaleService->deletePayScale($payScale);
            return response()->json([
                'success' => true,
                'message' => 'Pay Scale deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete Pay Scale.'
            ], 500);
        }
    }
}
