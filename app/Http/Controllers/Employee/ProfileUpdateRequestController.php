<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee\ProfileUpdateRequest;
use App\Models\Employee\Employee;
use Illuminate\Http\Request;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;

class ProfileUpdateRequestController extends Controller
{
    public function index(Request $request, FlexSearch $flexSearch)
    {
        $title = 'Profile Update Requests';
        $section = 'Employee';
        $sub_section = 'Profile Update Requests';
        $section_url = route('profile_update_requests.index');

        $query = ProfileUpdateRequest::with('employee')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $searchableColumns = ['section', 'status'];
        $keyword = $request->input('search');
        $filters = [];

        $requests = $flexSearch->apply($query, $filters, $keyword, $searchableColumns)
            ->paginate(15);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('employee.profile_update_requests.partials.table', compact('requests'))->render(),
                'pagination' => $requests->links()->render(),
            ]);
        }

        return view('employee.profile_update_requests.index', compact('requests', 'title', 'section', 'sub_section', 'section_url'));
    }

    public function show($id)
    {
        $title = 'Review Profile Update Request';
        $section = 'Employee';
        $sub_section = 'Review Update Request';
        $section_url = route('profile_update_requests.index');

        $updateRequest = ProfileUpdateRequest::with('employee')->findOrFail($id);
        return view('employee.profile_update_requests.show', compact('updateRequest', 'title', 'section', 'sub_section', 'section_url'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'section' => 'required|string|in:general,education,employment_history,emergency_contact',
            'requested_data' => 'required|array',
            'previous_data' => 'nullable|array',
        ]);

        $updateRequest = ProfileUpdateRequest::create([
            'employee_id' => $validated['employee_id'],
            'section' => $validated['section'],
            'requested_data' => $validated['requested_data'],
            'previous_data' => $validated['previous_data'] ?? [],
            'status' => 'pending',
        ]);

        // Start the approval workflow
        try {
            $updateRequest->startWorkflow('profile-update');
        } catch (\Exception $e) {
            \Log::error('Approval workflow failed for profile update request: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Profile update request submitted successfully and is pending approval.'
        ]);
    }

    public function destroy($id)
    {
        $updateRequest = ProfileUpdateRequest::findOrFail($id);
        $updateRequest->delete();

        return response()->json([
            'success' => true,
            'message' => 'Profile update request deleted successfully.'
        ]);
    }
}
