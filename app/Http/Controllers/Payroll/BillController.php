<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payroll\UpdateBillPaymentStatusRequest;
use App\Services\Payroll\BillServices;
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

        $filters = [];
        if ($request->filled('payment_status')) {
            $filters['payment_status'] = $request->input('payment_status');
        }

        $keyword = $request->input('keyword');

        $bills = $this->billServices->getBillsList($filters, $keyword, $flexsearch);

        if ($request->ajax() || $request->boolean('_ajax')) {
            return view('payroll.bills.partials.search_results', compact('bills'))->render();
        }

        return view('payroll.bills.index', compact('title', 'section', 'sub_section', 'bills'));
    }

    /**
     * Change payment status of a bill.
     */
    public function changePaymentStatus(UpdateBillPaymentStatusRequest $request)
    {
        $validated = $request->validated();
        
        $bill = $this->billServices->updatePaymentStatus(
            (int) $validated['id'],
            $validated['payment_status']
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
