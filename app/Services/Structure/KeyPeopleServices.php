<?php

namespace App\Services\Structure;

use App\Models\Structure\OrganizationStructure;
use App\Models\Employee\Employee;
use App\HelperClass;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Exception;

class KeyPeopleServices
{
    /**
     * Search and paginate key people using FlexSearch.
     */
    public function search(FlexSearch $flexsearch, ?string $keyword, int $perPage = 10)
    {
        $query = OrganizationStructure::with([
            'getGroup',
            'getCompany',
            'getBranchUnit',
            'getDivision',
            'getDepartment',
            'getSection',
            'getEmployee'
        ]);

        $searchableFields = ['name', 'position', 'email', 'contact_no', 'type'];

        return $flexsearch->apply($query, [], $keyword, $searchableFields)
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get key person by ID.
     */
    public function getById(int $id): OrganizationStructure
    {
        return OrganizationStructure::with([
            'getGroup',
            'getCompany',
            'getBranchUnit',
            'getDivision',
            'getDepartment',
            'getSection',
            'getEmployee'
        ])->findOrFail($id);
    }

    /**
     * Store a new key person.
     */
    public function store(array $validatedData, ?UploadedFile $photo = null): OrganizationStructure
    {
        $data = $this->prepareData($validatedData);

        // Handle profile image upload
        if ($photo) {
            $data['photo_path'] = HelperClass::file_upload($photo, 'organization_structure');
        }

        return OrganizationStructure::create($data);
    }

    /**
     * Update an existing key person.
     */
    public function update(int $id, array $validatedData, ?UploadedFile $photo = null): OrganizationStructure
    {
        $person = OrganizationStructure::findOrFail($id);
        $data = $this->prepareData($validatedData);

        // Handle profile image upload
        if ($photo) {
            // Delete old photo if exists
            if ($person->photo_path) {
                HelperClass::file_delete($person->photo_path);
            }
            $data['photo_path'] = HelperClass::file_upload($photo, 'organization_structure');
        }

        $person->update($data);
        return $person;
    }

    /**
     * Delete a key person.
     */
    public function delete(int $id): bool
    {
        $person = OrganizationStructure::findOrFail($id);

        if ($person->photo_path) {
            HelperClass::file_delete($person->photo_path);
        }

        return $person->delete();
    }

    /**
     * Prepare data array for saving/updating, resolving mapping.
     */
    protected function prepareData(array $validatedData): array
    {
        // Enforce member_type to 'Key Member' by default, but support 'Board Member' if explicitly sent (e.g. for backward compatibility/Pest tests)
        $memberType = request('member_type', 'Key Member');
        $validatedData['member_type'] = in_array($memberType, ['Board Member', 'Key Member']) ? $memberType : 'Key Member';

        // Direct mapping from lower case types to database enum values
        $typeMap = [
            'group' => 'Group',
            'company' => 'Company',
            'location' => 'Branch Unit',
            'division' => 'Division',
            'department' => 'Department',
            'section' => 'Section'
        ];

        if (isset($validatedData['type'])) {
            $validatedData['type'] = $typeMap[$validatedData['type']] ?? $validatedData['type'];
        }

        if (isset($validatedData['status'])) {
            $validatedData['status'] = ucfirst($validatedData['status']);
        }

        // If employee is attached, resolve name from Employee model if name is empty
        if (!empty($validatedData['employee_id'])) {
            $employee = Employee::find($validatedData['employee_id']);
            if ($employee) {
                if (empty($validatedData['name'])) {
                    $validatedData['name'] = $employee->full_name;
                }
                // Prefill other empty fields from employee for better consistency
                if (empty($validatedData['email'])) {
                    $validatedData['email'] = $employee->email;
                }
                if (empty($validatedData['contact_no'])) {
                    $validatedData['contact_no'] = $employee->contact_no ?? $employee->phone;
                }
            }
        }

        // Ensure target fields are null if not relevant for the type
        $type = strtolower(array_search($validatedData['type'], $typeMap) ?: $validatedData['type']);
        
        if ($type === 'group') {
            $validatedData['company_id'] = null;
            $validatedData['branch_unit_id'] = null;
            $validatedData['division_id'] = null;
            $validatedData['department_id'] = null;
            $validatedData['section_id'] = null;
        } elseif ($type === 'company') {
            $validatedData['branch_unit_id'] = null;
            $validatedData['division_id'] = null;
            $validatedData['department_id'] = null;
            $validatedData['section_id'] = null;
        } elseif ($type === 'location') {
            $validatedData['division_id'] = null;
            $validatedData['department_id'] = null;
            $validatedData['section_id'] = null;
        } elseif ($type === 'division') {
            $validatedData['branch_unit_id'] = null;
            $validatedData['department_id'] = null;
            $validatedData['section_id'] = null;
        } elseif ($type === 'department') {
            $validatedData['branch_unit_id'] = null;
            $validatedData['section_id'] = null;
        }

        return $validatedData;
    }
}
