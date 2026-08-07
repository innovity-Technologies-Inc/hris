<?php

namespace App\Services\Organization;

use App\Models\Organization\Organization;
use App\HelperClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrganizationService
{
    /**
     * Get list of organizations with optional search/filters.
     */
    public function listOrganizations(array $filters = [])
    {
        $query = Organization::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('id', 'desc')->paginate($filters['per_page'] ?? 10);
    }

    /**
     * Create a new organization.
     */
    public function createOrganization(array $data)
    {
        return DB::transaction(function() use ($data) {
            // Handle logo file upload
            if (isset($data['logo']) && $data['logo'] instanceof \Illuminate\Http\UploadedFile) {
                $data['logo'] = HelperClass::file_upload($data['logo'], 'organization_logo');
            }

            $organization = Organization::create($data);

            Log::info('Organization created successfully.', ['organization_id' => $organization->id]);

            return $organization;
        });
    }

    /**
     * Update an organization.
     */
    public function updateOrganization(Organization $organization, array $data)
    {
        return DB::transaction(function() use ($organization, $data) {
            // Handle logo update
            if (isset($data['logo']) && $data['logo'] instanceof \Illuminate\Http\UploadedFile) {
                if ($organization->logo) {
                    HelperClass::file_delete($organization->logo);
                }
                $data['logo'] = HelperClass::file_upload($data['logo'], 'organization_logo');
            }

            $organization->update($data);

            Log::info('Organization updated successfully.', ['organization_id' => $organization->id]);

            return $organization;
        });
    }

    /**
     * Delete an organization.
     */
    public function deleteOrganization(Organization $organization)
    {
        return DB::transaction(function() use ($organization) {
            // Delete logo file
            if ($organization->logo) {
                HelperClass::file_delete($organization->logo);
            }

            $organization->delete();

            Log::info('Organization deleted successfully.', ['organization_id' => $organization->id]);

            return true;
        });
    }
}
