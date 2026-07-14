<?php

namespace App\Http\Controllers\ClaimExpense;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClaimExpense\ExpenseTypeRequest;
use App\Models\ClaimExpense\ExpenseType;
use App\Models\Company\Company;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;

class ExpenseTypeController extends Controller
{
    public function index(Request $request, FlexSearch $flexsearch)
    {
        $title = 'Expense Types';
        $section = 'Company Info';
        $sub_section = 'Expense Types';

        if ($request->ajax()) {
            $query = ExpenseType::query();
            $expenseTypes = $flexsearch->apply($query, [], $request->get('keyword'), ['name', 'description'])
                ->orderBy('id', 'desc')
                ->paginate(20);
            return view('claim_expense.expense_types.search_results', compact('expenseTypes'))->render();
        }

        return view('claim_expense.expense_types.index', compact('title', 'section', 'sub_section'));
    }

    public function store(ExpenseTypeRequest $request)
    {
        try {
            $expenseType = ExpenseType::create($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Expense Type saved successfully.',
                'data' => $expenseType
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save Expense Type: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $expenseType = ExpenseType::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $expenseType
        ]);
    }

    public function update(ExpenseTypeRequest $request, $id)
    {
        try {
            $expenseType = ExpenseType::findOrFail($id);
            $expenseType->update($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Expense Type updated successfully.',
                'data' => $expenseType
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Expense Type: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $expenseType = ExpenseType::findOrFail($id);
            $expenseType->delete();
            return response()->json([
                'success' => true,
                'message' => 'Expense Type deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete Expense Type: ' . $e->getMessage()
            ], 500);
        }
    }
}
