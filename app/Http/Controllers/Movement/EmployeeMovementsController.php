<?php

namespace App\Http\Controllers\Movement;

use App\Enums\UserType;
use App\Exports\Movement\MovementExport;
use App\Http\Controllers\Controller;
use App\Models\Plan\DAPlan;
use App\Models\Employee\Employee;
use App\Models\Movement\EmployeeMovement;
use App\Models\Movement\EmployeeMovementDetail;
use App\Models\Plan\TAPlan;
use Carbon\Carbon;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeMovementsController extends Controller
{

    public function index(Request $request, FlexSearch $flexsearch){
        $title = 'Employee Travel Movement';
        $section = 'Travel Movement';
        $query = EmployeeMovement::with('getEmployee');

        $searchableColumns = ['getEmployee.full_name'];
        $keyword = $request->input('keyword');

        $filters = [];

        if ($request->filled('from')) {
            $filters['from_date>='] = Carbon::parse($request->input('from'))->copy()->startOfDay();
        }

        if ($request->filled('to')) {
            $filters['from_date<='] = Carbon::parse($request->input('to'))->copy()->endOfDay();
        }

        if ($request->filled('status')) {
            $filters['status'] = $request->input('status');
        }

        if ($request->filled('payment_status')) {
            $filters['payment_status'] = $request->input('payment_status');
        }

        $movements = $flexsearch
            ->apply($query, $filters, $keyword, $searchableColumns)
            ->paginate(10);

        $taPlans = TAPlan::where('status', 'active')->get();
        $daPlans = DAPlan::where('status', 'active')->get();

        if ($request->ajax() || $request->boolean('_ajax')) {
            return view('movement.partials.search_results', compact('movements', 'taPlans', 'daPlans'))->render();
        }
        return view('movement.index', compact('title', 'movements', 'section', 'taPlans', 'daPlans'));
    }

    public function form($id = null)
    {
        $title = (!empty($id) ? 'Edit' : 'Add') . ' Employee Travel Movement Information';
        $section = 'Travel Movement';
        $sub_section = !empty($id) ? 'Edit' : 'Add';
        $section_url = route('movement.index');
        
        $isEmployee = auth()->user()->user_type === UserType::Employee;
        
        if ($isEmployee) {
            $employees = Employee::where('id', auth()->user()->employee_id)->get();
        } else {
            $employees = Employee::where('status', 'active')->orderBy('full_name')->get();
        }

        $taPlans = TAPlan::where('status', 'active')->get();
        $daPlans = DAPlan::where('status', 'active')->get();
        $statusOptions = [
            ['value'=>'pending', 'label'=>'Pending'],
            ['value'=>'approved', 'label'=>'Approved'],
            ['value'=>'rejected', 'label'=>'Rejected'],
        ];

        if (!empty($id)){
            $movement = EmployeeMovement::with('details')->findOrFail($id);
            // Security: Employees can only edit their own movements
            if ($isEmployee && $movement->employee_id != auth()->user()->employee_id) {
                abort(403, 'Unauthorized access.');
            }
            return view('movement.form', compact(
                'employees', 'taPlans', 'daPlans', 'statusOptions', 'title', 'section', 'sub_section', 'section_url'
            , 'movement', 'isEmployee'));
        }else{
            return view('movement.form', compact(
                'employees', 'taPlans', 'daPlans', 'statusOptions', 'title', 'section', 'sub_section', 'section_url', 'isEmployee'
            ));
        }
    }

    public function update($id)
    {
        $title = 'Edit Employee Travel Movement Information';
        $section = 'Travel Movement';
        $sub_section = 'Edit';
        $section_url = route('movement.index');
        $employees = Employee::all();
        $taPlans = TAPlan::where('status', 'active')->get();
        $daPlans = DAPlan::where('status', 'active')->get();
        $statusOptions = [
            ['value'=>'pending', 'label'=>'Pending'],
            ['value'=>'approved', 'label'=>'Approved'],
            ['value'=>'rejected', 'label'=>'Rejected'],
        ];


    }

    public function save(Request $request, $id=null){
        $isEmployee = auth()->user()->user_type === UserType::Employee;

        // Security: Employees can only submit for themselves
        if ($isEmployee && $request->input('employee_id') != auth()->user()->employee_id) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'from_date' => ['required', 'date'],
            'to_date'   => ['required', 'date', 'after_or_equal:from_date'],
            'distance' => ['required', 'numeric', 'min:0'],
            'total_days' => ['required', 'numeric'],
            'status' => ['required', 'in:pending,approved,rejected'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['nullable'],
            'items.*.source_address' => ['required', 'string', 'max:255'],
            'items.*.source_lat' => ['required', 'numeric', 'between:-90,90'],
            'items.*.source_lng' => ['required', 'numeric', 'between:-180,180'],
            'items.*.destination_address' => ['required', 'string', 'max:255'],
            'items.*.dest_lat' => ['required', 'numeric', 'between:-90,90'],
            'items.*.dest_lng' => ['required', 'numeric', 'between:-180,180'],
            'items.*.distance' => ['required', 'numeric', 'min:0'],
            'items.*.reason' => ['nullable', 'string', 'max:1000'],
            'items.*.attachment' => ['nullable', 'file', 'max:5120'], // 5MB limit
        ], [
            'employee_id.required' => 'Please select an employee.',
            'from_date.required' => 'From date and time is required.',
            'to_date.required' => 'To date and time is required.',
            'to_date.after_or_equal' => 'To date must be later than or equal to From date.',
            'distance.required' => 'Total distance must be calculated before submitting.',
            'total_days.required' => 'Total days must be calculated before submitting.',
            'items.required' => 'At least one travel route/destination card is required.',
            'items.*.source_address.required' => 'Source address is required for all routes.',
            'items.*.destination_address.required' => 'Destination address is required for all routes.',
            'items.*.distance.required' => 'Distance must be calculated for all routes.',
        ]);

        try {
            DB::beginTransaction();

            $movementData = [
                'employee_id' => $validated['employee_id'],
                'from_date' => $validated['from_date'],
                'to_date' => $validated['to_date'],
                'distance' => $validated['distance'],
                'total_days' => $validated['total_days'],
                'status' => $validated['status'],
            ];

            // Set main source/destination/reason from first/last items for compatibility
            $items = $request->input('items', []);
            if (!empty($items)) {
                $firstItem = reset($items);
                $lastItem = end($items);
                $movementData['source_address'] = $firstItem['source_address'] ?? '';
                $movementData['source_lat'] = $firstItem['source_lat'] ?? null;
                $movementData['source_lng'] = $firstItem['source_lng'] ?? null;
                $movementData['destination_address'] = $lastItem['destination_address'] ?? '';
                $movementData['dest_lat'] = $lastItem['dest_lat'] ?? null;
                $movementData['dest_lng'] = $lastItem['dest_lng'] ?? null;
                $movementData['reason'] = $firstItem['reason'] ?? '';
            }

            if ($id) {
                $movement = EmployeeMovement::findOrFail($id);
                $movement->update($movementData);
            } else {
                $movement = EmployeeMovement::create($movementData);
            }

            // Sync items (details)
            $existingItemIds = $movement->details()->pluck('id')->toArray();
            $newItemIds = [];

            foreach ($items as $index => $itemData) {
                $detailId = $itemData['id'] ?? null;
                $detailData = [
                    'source_address' => $itemData['source_address'],
                    'source_lat' => $itemData['source_lat'],
                    'source_lng' => $itemData['source_lng'],
                    'destination_address' => $itemData['destination_address'],
                    'dest_lat' => $itemData['dest_lat'],
                    'dest_lng' => $itemData['dest_lng'],
                    'distance' => $itemData['distance'],
                    'reason' => $itemData['reason'] ?? null,
                ];

                // Handle attachment upload
                if ($request->hasFile("items.{$index}.attachment")) {
                    $file = $request->file("items.{$index}.attachment");
                    $filePath = \App\HelperClass::file_upload($file, 'movements');
                    $detailData['attachment_path'] = $filePath;
                }

                if ($detailId && in_array($detailId, $existingItemIds)) {
                    $detail = EmployeeMovementDetail::findOrFail($detailId);
                    // Keep old attachment if no new file is uploaded
                    if (!isset($detailData['attachment_path'])) {
                        unset($detailData['attachment_path']);
                    } else {
                        // Delete old file if updated
                        if ($detail->attachment_path) {
                            \App\HelperClass::file_delete($detail->attachment_path);
                        }
                    }
                    $detail->update($detailData);
                    $newItemIds[] = $detailId;
                } else {
                    $detail = $movement->details()->create($detailData);
                    $newItemIds[] = $detail->id;
                }
            }

            // Delete removed items
            $itemsToDelete = array_diff($existingItemIds, $newItemIds);
            foreach ($itemsToDelete as $deleteId) {
                $detail = EmployeeMovementDetail::find($deleteId);
                if ($detail) {
                    if ($detail->attachment_path) {
                        \App\HelperClass::file_delete($detail->attachment_path);
                    }
                    $detail->delete();
                }
            }

            DB::commit();

            return redirect()->route('movement.index')->with([
                'message' => $id ? 'Updated successfully.' : 'Created successfully.',
                'alert-type' => 'success'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving movement: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->back()->with([
                'message' => 'Something went wrong. Please try again later.',
                'alert-type' => 'error'
            ])->withInput();
        }
    }

    public function saveAllowances(Request $request, $id)
    {
        // Only HR/Admin can save/edit allowances
        if (auth()->user()->user_type === UserType::Employee) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'ta_plan_id' => ['nullable', 'exists:ta_plans,id'],
            'da_plan_id' => ['nullable', 'exists:da_plans,id'],
            'custom_ta' => ['nullable', 'numeric', 'min:0'],
            'custom_da' => ['nullable', 'numeric', 'min:0'],
            'total_ta' => ['required', 'numeric', 'min:0'],
            'total_da' => ['required', 'numeric', 'min:0'],
            'total_allowance' => ['required', 'numeric', 'min:0'],
        ]);

        try {
            $movement = EmployeeMovement::findOrFail($id);
            $movement->update([
                'ta_plan_id' => $validated['ta_plan_id'],
                'da_plan_id' => $validated['da_plan_id'],
                'custom_ta' => $validated['custom_ta'],
                'custom_da' => $validated['custom_da'],
                'total_ta' => $validated['total_ta'],
                'total_da' => $validated['total_da'],
                'total_allowance' => $validated['total_allowance'],
            ]);

            return redirect()->back()->with([
                'message' => 'Allowances updated successfully.',
                'alert-type' => 'success'
            ]);
        } catch (\Exception $e) {
            Log::error('Error saving allowances: ' . $e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something went wrong. Please try again later.',
                'alert-type' => 'error'
            ]);
        }
    }

    public function destroy($id){
        $movement = EmployeeMovement::find($id);
        $movement->delete();

        return redirect()->back()->with([
            'message' => 'Deleted Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function changeStatus(Request $request){
        $id = $request->input('id');
        $status = $request->input('status');
        
        try {
            $movement = EmployeeMovement::findOrFail($id);
            $movement->status = $status;
            $movement->save();
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something went wrong. Please try again later.',
                'alert-type' => 'error'
            ]);
        }

        return redirect()->back()->with([
            'message' => 'Status Changed Successfully',
            'alert-type' => 'success'
        ]);
    }

    public function changePaymentStatus(Request $request){
        $id = $request->input('id');
        $status = $request->input('payment_status');
        
        try {
            $movement = EmployeeMovement::findOrFail($id);
            $movement->payment_status = $status;
            $movement->save();
        }catch (\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->with([
                'message' => 'Something went wrong. Please try again later.',
                'alert-type' => 'error'
            ]);
        }

        return redirect()->back()->with([
            'message' => 'Payment Status Changed Successfully',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Export movements to Excel, respecting active filters.
     */
    public function exportExcel(Request $request, FlexSearch $flexsearch): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $query = EmployeeMovement::with(['getEmployee', 'getTaPlan', 'getDaPlan']);
        $searchableColumns = ['getEmployee.full_name'];
        $keyword = $request->input('keyword');
        $filters = [];

        if ($request->filled('from')) {
            $filters['from_date>='] = Carbon::parse($request->input('from'))->copy()->startOfDay();
        }
        if ($request->filled('to')) {
            $filters['from_date<='] = Carbon::parse($request->input('to'))->copy()->endOfDay();
        }
        if ($request->filled('status')) {
            $filters['status'] = $request->input('status');
        }
        if ($request->filled('payment_status')) {
            $filters['payment_status'] = $request->input('payment_status');
        }

        $records = $flexsearch->apply($query, $filters, $keyword, $searchableColumns)->get();

        return Excel::download(new MovementExport($records), 'travel_movements_' . now()->format('Ymd_His') . '.xlsx');
    }

    /**
     * Open a printable PDF-style view of movement records.
     */
    public function printIndex(Request $request, FlexSearch $flexsearch): \Illuminate\View\View
    {
        $query = EmployeeMovement::with(['getEmployee', 'getTaPlan', 'getDaPlan']);
        $searchableColumns = ['getEmployee.full_name'];
        $keyword = $request->input('keyword');
        $filters = [];

        if ($request->filled('from')) {
            $filters['from_date>='] = Carbon::parse($request->input('from'))->copy()->startOfDay();
        }
        if ($request->filled('to')) {
            $filters['from_date<='] = Carbon::parse($request->input('to'))->copy()->endOfDay();
        }
        if ($request->filled('status')) {
            $filters['status'] = $request->input('status');
        }
        if ($request->filled('payment_status')) {
            $filters['payment_status'] = $request->input('payment_status');
        }

        $records = $flexsearch->apply($query, $filters, $keyword, $searchableColumns)->get();

        return view('movement.print_index', compact('records'));
    }
}
