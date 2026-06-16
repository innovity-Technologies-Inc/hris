<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\Payroll\PayrollProcess;
use App\Services\Payroll\DisbursementServices;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DisbursementController extends Controller
{
    protected $disbursementService;

    public function __construct(DisbursementServices $disbursementService)
    {
        $this->disbursementService = $disbursementService;
    }

    public function index(Request $request, FlexSearch $flexsearch)
    {
        try {
            $data = $this->disbursementService->getPendingProcesses($request, $flexsearch);
            return view('payroll.disbursement.index', compact('data'));
        } catch (\Exception $e) {
            Log::error('Error loading Disbursement index.', ['message' => $e->getMessage()]);
            return redirect()->back()->with(['alert-type' => 'error', 'message' => 'Failed to load list.']);
        }
    }

    public function process($id)
    {
        try {
            $details = $this->disbursementService->getProcessDetails($id);
            $process = $details['process'];
            $items = $details['items'];

            if ($items->isEmpty()) {
                return redirect()->route('disbursement.index')->with([
                    'alert-type' => 'info',
                    'message' => 'All employees in this batch have already been disbursed or have a zero balance.'
                ]);
            }

            return view('payroll.disbursement.process', compact('process', 'items'));
        } catch (\Exception $e) {
            Log::error('Error loading Disbursement process view.', ['message' => $e->getMessage()]);
            return redirect()->route('disbursement.index')->with(['alert-type' => 'error', 'message' => 'Process not found.']);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'process_id' => 'required|exists:payroll_process,id',
            'record_ids' => 'required|array|min:1',
            'payment_method' => 'required|string|max:255',
            'note' => 'nullable|string',
            'attachments.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,zip|max:5120', // 5MB max
        ]);

        try {
            $this->disbursementService->processDisbursement($request->all());
            
            return redirect()->route('disbursement.index')->with([
                'alert-type' => 'success',
                'message' => 'Disbursement processed successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Disbursement processing failed.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with([
                'alert-type' => 'error',
                'message' => 'Processing failed: ' . $e->getMessage()
            ])->withInput();
        }
    }
}
