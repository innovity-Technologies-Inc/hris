<?php

namespace App\Services\Employee;

use App\Models\Employee\ProfileUpdateRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;

class ProfileUpdateRequestServices
{
    /**
     * Get paginated list of profile update requests with optional filtering.
     */
    public function getFilteredRequests(
        FlexSearch $flexSearch,
        ?string $status,
        ?string $type,
        ?string $keyword
    ): LengthAwarePaginator {
        $query = ProfileUpdateRequest::with('employee')->latest();

        if ($status) {
            $query->where('status', $status);
        }
        if ($type) {
            $query->where('type', $type);
        }

        $searchableColumns = ['section', 'status'];
        $filters = [];

        return $flexSearch->apply($query, $filters, $keyword, $searchableColumns)
            ->paginate(15);
    }

    /**
     * Find a profile update request by ID with employee relationship.
     */
    public function findOrFail(int $id): ProfileUpdateRequest
    {
        return ProfileUpdateRequest::with('employee')->findOrFail($id);
    }

    /**
     * Create a new profile update request and start the approval workflow.
     */
    public function createRequest(array $validated): ProfileUpdateRequest
    {
        try {
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
                Log::error('Approval workflow failed for profile update request: ' . $e->getMessage());
            }

            return $updateRequest;
        } catch (\Exception $e) {
            Log::error('Profile Update Request Create Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete a profile update request.
     */
    public function deleteRequest(int $id): void
    {
        try {
            $updateRequest = ProfileUpdateRequest::findOrFail($id);
            $updateRequest->delete();
        } catch (\Exception $e) {
            Log::error('Profile Update Request Delete Error: ' . $e->getMessage());
            throw $e;
        }
    }
}
