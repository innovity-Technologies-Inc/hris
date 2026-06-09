<?php

namespace App\Services\Employee;

use App\Models\Employee\Employee;
use Illuminate\Support\Facades\Log;

class NIDVerificationServices
{
    /**
     * Verify employee NID (Dummy implementation)
     */
    public function verifyNID(int $employeeId)
    {
        try {
            $employee = Employee::findOrFail($employeeId);

            // Dummy verification logic
            // In a real scenario, this would call an external API (e.g., Election Commission API)
            
            // Simulate API delay
            // usleep(500000); // 0.5 seconds

            // Update verification status
            $employee->update([
                'is_nid_verified' => true
            ]);

            Log::info("NID Verified for employee ID: {$employeeId}");

            return [
                'success' => true,
                'message' => 'NID verified successfully.',
                'data' => $employee
            ];

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in NIDVerificationServices@verifyNID: ' . $e->getMessage(), ['exception' => $e]);

            return [
                'success' => false,
                'message' => 'NID verification failed: ' . $e->getMessage()
            ];
        }
    }
}
