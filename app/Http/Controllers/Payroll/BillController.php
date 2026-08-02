<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Payroll\Bill;
use Illuminate\Http\Request;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;

class BillController extends Controller
{
    /**
     * Display a listing of the bills.
     */
    public function index(Request $request, FlexSearch $flexsearch)
    {
        $title = 'Bill Pay';
        $section = 'Payroll';
        $sub_section = 'Bill Pay';

        $query = Bill::with('employee')->latest();

        $searchableColumns = ['employee.full_name', 'type', 'expense_type', 'payment_status'];
        $keyword = $request->input('keyword');

        $filters = [];
        if ($request->filled('payment_status')) {
            $filters['payment_status'] = $request->input('payment_status');
        }

        $bills = $flexsearch
            ->apply($query, $filters, $keyword, $searchableColumns)
            ->paginate(10);

        if ($request->ajax() || $request->boolean('_ajax')) {
            return view('payroll.bills.partials.search_results', compact('bills'))->render();
        }

        return view('payroll.bills.index', compact('title', 'section', 'sub_section', 'bills'));
    }

    /**
     * Change payment status of a bill.
     */
    public function changePaymentStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:bills,id',
            'payment_status' => 'required|in:paid,unpaid',
        ]);

        $bill = Bill::findOrFail($request->input('id'));
        $bill->update([
            'payment_status' => $request->input('payment_status'),
        ]);

        // Sync with EmployeeMovement if applicable
        if ($bill->type === 'travel-movement') {
            $movement = \App\Models\Movement\EmployeeMovement::find($bill->expense_id);
            if ($movement) {
                $movement->update(['payment_status' => $bill->payment_status]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Bill payment status updated successfully.',
            'data' => $bill,
        ]);
    }

    /**
     * Delete a bill.
     */
    public function destroy($id)
    {
        $bill = Bill::findOrFail($id);
        $bill->delete();

        return response()->json([
            'success' => true,
            'message' => 'Resource deleted successfully.',
        ]);
    }
}
