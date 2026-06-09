<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;

use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeId;
use App\Services\Setting\IDCardService;
use Illuminate\Http\Request;
use Exception;

class EmployeeIdCardController extends Controller
{
    protected IDCardService $idCardService;

    public function __construct(IDCardService $idCardService)
    {
        $this->idCardService = $idCardService;
    }

    /**
     * Display a listing of all employee ID cards
     */
    public function index()
    {
        $title = 'Employee ID Cards';
        $section = 'Employees';
        $sub_section = 'ID Cards';

        $employeeIds = EmployeeId::with(['employee', 'idCardDesign'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('employee.id_cards.index', compact(
            'title',
            'section',
            'sub_section',
            'employeeIds'
        ));
    }

    /**
     * Generate ID card for an employee
     */
    public function generate($employeeId)
    {
        try {
            $employee = Employee::findOrFail($employeeId);

            // Check if active design exists
            if (!$this->idCardService->hasActiveDesign()) {
                return redirect()->back()
                    ->with('message', 'No active ID card design available. Please activate a design in Settings > ID Card Design first.')
                    ->with('alert-type', 'error');
            }

            // Generate the ID card
            $employeeIdCard = $this->idCardService->generateIdCard($employee);

            return redirect()->back()
                ->with('message', 'ID Card generated successfully! Card Number: ' . $employeeIdCard->card_number)
                ->with('alert-type', 'success');

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in EmployeeIdCardController@generate: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()
                ->with('message', 'Failed to generate ID card: ' . $e->getMessage())
                ->with('alert-type', 'error');
        }
    }

    /**
     * Regenerate ID card for an employee (invalidates previous card)
     */
    public function regenerate($employeeId)
    {
        try {
            $employee = Employee::findOrFail($employeeId);

            // Check if active design exists
            if (!$this->idCardService->hasActiveDesign()) {
                return redirect()->back()
                    ->with('message', 'No active ID card design available. Please activate a design first.')
                    ->with('alert-type', 'error');
            }

            // Regenerate the ID card
            $employeeIdCard = $this->idCardService->regenerateIdCard($employee);

            return redirect()->back()
                ->with('message', 'ID Card regenerated successfully! New Card Number: ' . $employeeIdCard->card_number)
                ->with('alert-type', 'success');

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in EmployeeIdCardController@regenerate: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()
                ->with('message', 'Failed to regenerate ID card: ' . $e->getMessage())
                ->with('alert-type', 'error');
        }
    }

    /**
     * View ID card PDF in browser
     */
    public function view($employeeId)
    {
        try {
            $employee = Employee::findOrFail($employeeId);
            $employeeIdCard = $this->idCardService->getActiveIdCard($employee);

            if (!$employeeIdCard) {
                return redirect()->back()
                    ->with('error', 'No active ID card found for this employee.');
            }

            if (!$employeeIdCard->pdfExists()) {
                return redirect()->back()
                    ->with('error', 'ID card PDF file not found. Please regenerate the ID card.');
            }

            return $this->idCardService->streamPdf($employeeIdCard);

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in EmployeeIdCardController@view: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()
                ->with('error', 'Failed to view ID card: ' . $e->getMessage());
        }
    }

    /**
     * Download ID card PDF
     */
    public function download($employeeId)
    {
        try {
            $employee = Employee::findOrFail($employeeId);
            $employeeIdCard = $this->idCardService->getActiveIdCard($employee);

            if (!$employeeIdCard) {
                return redirect()->back()
                    ->with('error', 'No active ID card found for this employee.');
            }

            if (!$employeeIdCard->pdfExists()) {
                return redirect()->back()
                    ->with('error', 'ID card PDF file not found. Please regenerate the ID card.');
            }

            return $this->idCardService->downloadPdf($employeeIdCard);

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in EmployeeIdCardController@download: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()
                ->with('error', 'Failed to download ID card: ' . $e->getMessage());
        }
    }

    /**
     * Preview ID card before generation (without saving)
     */
    public function preview($employeeId)
    {
        try {
            $employee = Employee::findOrFail($employeeId);
            $design = $this->idCardService->getActiveDesign();

            if (!$design) {
                return redirect()->back()
                    ->with('error', 'No active ID card design available.');
            }

            $html = $this->idCardService->renderIdCardHtml($design, $employee);

            return response($html)
                ->header('Content-Type', 'text/html');

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in EmployeeIdCardController@preview: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()
                ->with('error', 'Failed to preview ID card: ' . $e->getMessage());
        }
    }

    /**
     * Deactivate an employee's ID card
     */
    public function deactivate($employeeId)
    {
        try {
            $employee = Employee::findOrFail($employeeId);

            if (!$this->idCardService->hasActiveIdCard($employee)) {
                return redirect()->back()
                    ->with('message', 'Employee does not have an active ID card.')
                    ->with('alert-type', 'info');
            }

            $this->idCardService->deactivateIdCard($employee);

            return redirect()->back()
                ->with('message', 'ID Card deactivated successfully.')
                ->with('alert-type', 'success');

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in EmployeeIdCardController@deactivate: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()
                ->with('message', 'Failed to deactivate ID card: ' . $e->getMessage())
                ->with('alert-type', 'error');
        }
    }

    /**
     * Show ID card details
     */
    public function show($id)
    {
        $employeeIdCard = EmployeeId::with(['employee', 'idCardDesign'])->findOrFail($id);

        $title = 'ID Card Details';
        $section = 'Employees';
        $sub_section = 'ID Cards';

        return view('employee.id_cards.show', compact(
            'title',
            'section',
            'sub_section',
            'employeeIdCard'
        ));
    }

    /**
     * Get ID card status for an employee (API endpoint)
     */
    public function status($employeeId)
    {
        try {
            $employee = Employee::findOrFail($employeeId);
            $employeeIdCard = $this->idCardService->getActiveIdCard($employee);

            return response()->json([
                'has_active_card' => $employeeIdCard !== null,
                'card' => $employeeIdCard ? [
                    'id' => $employeeIdCard->id,
                    'card_number' => $employeeIdCard->card_number,
                    'status' => $employeeIdCard->status,
                    'issue_date' => $employeeIdCard->issue_date?->format('Y-m-d'),
                    'expiry_date' => $employeeIdCard->expiry_date?->format('Y-m-d'),
                    'is_expired' => $employeeIdCard->isExpired(),
                    'is_valid' => $employeeIdCard->isValid(),
                ] : null,
                'can_generate' => $this->idCardService->hasActiveDesign(),
            ]);

        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in EmployeeIdCardController@status: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }
}

