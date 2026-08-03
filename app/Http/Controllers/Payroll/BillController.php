<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payroll\UpdateBillPaymentStatusRequest;
use App\Services\Payroll\BillServices;
use App\Models\Payroll\Bill;
use Illuminate\Http\Request;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use App\Http\Responses\ApiResponse;

class BillController extends Controller
{
    protected BillServices $billServices;

    public function __construct(BillServices $billServices)
    {
        $this->billServices = $billServices;
    }

    /**
     * Display a listing of the bills.
     */
    public function index(Request $request, FlexSearch $flexsearch)
    {
        $title = 'Bill Pay';
        $section = 'Payroll';
        $sub_section = 'Bill Pay';

        if ($request->ajax() || $request->boolean('_ajax')) {
            $bills = $this->getBillsQuery($request, $flexsearch)->paginate(10);
            return view('payroll.bills.partials.search_results', compact('bills'))->render();
        }

        return view('payroll.bills.index', compact('title', 'section', 'sub_section'));
    }

    /**
     * Build the query for bills list.
     */
    private function getBillsQuery(Request $request, FlexSearch $flexsearch)
    {
        $query = Bill::with('employee')->latest();
        $filters = [];
        
        if ($request->filled('payment_status')) {
            $filters['payment_status'] = $request->input('payment_status');
        }

        return $flexsearch->apply($query, $filters, $request->input('keyword'), ['employee.full_name', 'type', 'expense_type', 'payment_status']);
    }

    /**
     * Export bills to Excel.
     */
    public function exportExcel(Request $request, FlexSearch $flexsearch)
    {
        $records = $this->getBillsQuery($request, $flexsearch)->get();
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\Payroll\BillExport($records), 'bills_' . now()->format('Ymd_His') . '.xlsx');
    }

    /**
     * Print bills list.
     */
    public function printIndex(Request $request, FlexSearch $flexsearch)
    {
        $records = $this->getBillsQuery($request, $flexsearch)->get();
        return view('payroll.bills.print_index', compact('records'));
    }

    public function changePaymentStatus(UpdateBillPaymentStatusRequest $request)
    {
        $validated = $request->validated();
        
        $bill = $this->billServices->updatePaymentStatus(
            (int) $validated['id'],
            $validated['payment_status'],
            $validated['payment_method'] ?? null,
            $validated['remarks'] ?? null,
            $request->file('attachment')
        );

        return ApiResponse::success('Resource updated successfully.', $bill);
    }

    /**
     * Delete a bill.
     */
    public function destroy($id)
    {
        $this->billServices->deleteBill((int) $id);

        return ApiResponse::deleted('Resource deleted successfully.');
    }
}
