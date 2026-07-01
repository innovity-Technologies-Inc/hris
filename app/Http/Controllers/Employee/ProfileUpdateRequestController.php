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
        $query = ProfileUpdateRequest::with('employee')->latest();
        $requests = $flexSearch->search($query, $request->all(), ['employee.first_name', 'employee.last_name', 'section', 'status']);
        
        return view('employee.profile_update_requests.index', compact('requests'));
    }

    public function show($id)
    {
        $updateRequest = ProfileUpdateRequest::with('employee')->findOrFail($id);
        return view('employee.profile_update_requests.show', compact('updateRequest'));
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
            $updateRequest->startWorkflow('ProfileUpdateRequest');
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
