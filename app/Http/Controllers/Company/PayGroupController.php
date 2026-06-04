<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\PayGroupRequest;
use App\Models\Company\Company;
use App\Models\Company\PayGroup;
use App\Services\Company\PayGroupServices;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;

class PayGroupController extends Controller
{
    protected $payGroupService;

    public function __construct(PayGroupServices $payGroupService)
    {
        $this->payGroupService = $payGroupService;
    }

    public function index(Request $request, FlexSearch $flexsearch)
    {
        $title = 'Pay Groups';
        $section = 'Company Info';
        $sub_section = 'Pay Groups';

        if ($request->ajax()) {
            $payGroups = $this->payGroupService->getPayGroups($request, $flexsearch);
            return view('company.pay_groups.search_results', compact('payGroups'))->render();
        }

        $companies = Company::all();
        return view('company.pay_groups.index', compact('title', 'section', 'sub_section', 'companies'));
    }

    public function store(PayGroupRequest $request)
    {
        try {
            $payGroup = $this->payGroupService->storePayGroup($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Pay Group saved successfully.',
                'data' => $payGroup
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save Pay Group.'
            ], 500);
        }
    }

    public function edit($id)
    {
        $payGroup = PayGroup::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $payGroup
        ]);
    }

    public function update(PayGroupRequest $request, $id)
    {
        try {
            $payGroup = PayGroup::findOrFail($id);
            $this->payGroupService->updatePayGroup($payGroup, $request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Pay Group updated successfully.',
                'data' => $payGroup
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Pay Group.'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $payGroup = PayGroup::findOrFail($id);
            $this->payGroupService->deletePayGroup($payGroup);
            return response()->json([
                'success' => true,
                'message' => 'Pay Group deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete Pay Group.'
            ], 500);
        }
    }
}
