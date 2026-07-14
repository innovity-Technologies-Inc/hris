<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Model;
use App\Traits\OrganizationScoped;
use App\Traits\Userstamps;

class ProfileUpdateRequest extends Model
{
    use \Innovity\ApprovalEngine\Traits\Approvable;
    use OrganizationScoped;
    use Userstamps;

    protected $fillable = [
        'employee_id',
        'type',
        'section',
        'previous_data',
        'requested_data',
        'status',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'previous_data' => 'array',
        'requested_data' => 'array',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getCreatorAttribute()
    {
        if ($this->created_by) {
            return $this->creator()->first();
        }
        return $this->hasOneThrough(
            \App\Models\User::class,
            Employee::class,
            'id',
            'employee_id',
            'employee_id',
            'id'
        )->withoutGlobalScopes()->first();
    }

    /**
     * Helper to check if workflow is active and create an admin update request if so.
     */
    public static function createAdminRequest($employeeId, $section, array $validated, $currentModel)
    {
        $previousData = [];
        $requestedData = [];
        $hasChanges = false;

        foreach ($validated as $key => $value) {
            $currentVal = $currentModel ? $currentModel->$key : null;
            
            $normCurrent = is_array($currentVal) ? json_encode($currentVal) : (is_null($currentVal) ? '' : (string)$currentVal);
            $normNew = is_array($value) ? json_encode($value) : (is_null($value) ? '' : (string)$value);
            
            if ($normCurrent !== $normNew) {
                $hasChanges = true;
            }
            
            $previousData[$key] = $currentVal;
            $requestedData[$key] = $value;
        }

        if (!$hasChanges) {
            return null; // No changes to request
        }

        $updateRequest = self::create([
            'employee_id' => $employeeId,
            'type' => 'admin',
            'section' => $section,
            'previous_data' => $previousData,
            'requested_data' => $requestedData,
            'status' => 'pending',
            'created_by' => auth()->id(),
        ]);

        $updateRequest->startWorkflow($section);

        return $updateRequest;
    }
}
