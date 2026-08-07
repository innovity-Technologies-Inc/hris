<?php

namespace App\Services\Organization;

use App\Models\Organization\Organization;
use App\Models\Setting\GeneralSetting;
use App\Models\User;
use App\HelperClass;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class OrganizationService
{
    /** Default roles to provision per new organization (besides Super Admin which is global). */
    private const ORG_ROLES = [
        'Admin',
        'HR Manager',
        'Manager',
        'Employee',
    ];

    /**
     * Get list of organizations with optional search/filters.
     */
    public function listOrganizations(array $filters = [])
    {
        $query = Organization::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
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
     *
     * This provisions:
     *  - The organization record
     *  - Org-scoped roles cloned from org 1 (Admin, HR Manager, Manager, Employee)
     *  - An Admin user for the organization (if admin_email provided)
     *  - A default GeneralSetting for the organization
     */
    public function createOrganization(array $data): Organization
    {
        return DB::transaction(function () use ($data) {
            // Handle logo file upload
            if (isset($data['logo']) && $data['logo'] instanceof \Illuminate\Http\UploadedFile) {
                $data['logo'] = HelperClass::file_upload($data['logo'], 'organization_logo');
            }

            // Extract admin provisioning fields (not stored on org)
            $adminEmail    = $data['admin_email'] ?? null;
            $adminPassword = $data['admin_password'] ?? null;
            $adminName     = $data['admin_name'] ?? null;
            unset($data['admin_email'], $data['admin_password'], $data['admin_name']);

            $organization = Organization::create($data);

            // 1. Provision org-scoped roles
            $this->provisionRoles($organization);

            // 2. Provision an Admin user if email is provided
            if ($adminEmail) {
                $this->provisionAdminUser($organization, $adminEmail, $adminPassword ?? 'password', $adminName ?? 'Admin');
            }

            // 3. Provision default GeneralSetting for this org
            $this->provisionGeneralSetting($organization);

            Log::info('Organization created and provisioned.', ['organization_id' => $organization->id]);

            return $organization;
        });
    }

    /**
     * Update an organization.
     */
    public function updateOrganization(Organization $organization, array $data): Organization
    {
        return DB::transaction(function () use ($organization, $data) {
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
     * Suspend an organization (sets status to inactive, blocks all its users from logging in).
     */
    public function suspendOrganization(Organization $organization): Organization
    {
        $organization->update(['status' => 'inactive']);

        // Block all users in this org
        User::where('organization_id', $organization->id)->update(['status' => 'inactive']);

        Log::info('Organization suspended.', ['organization_id' => $organization->id]);

        return $organization;
    }

    /**
     * Delete an organization.
     */
    public function deleteOrganization(Organization $organization): bool
    {
        return DB::transaction(function () use ($organization) {
            // Delete logo file
            if ($organization->logo) {
                HelperClass::file_delete($organization->logo);
            }

            $organization->delete();

            Log::info('Organization deleted successfully.', ['organization_id' => $organization->id]);

            return true;
        });
    }

    // ──────────────────────────────────────────
    //  Private provisioning helpers
    // ──────────────────────────────────────────

    /**
     * Clone the default roles (Admin, HR Manager, Manager, Employee) for the new organization.
     * Each role is scoped to the new organization_id.
     * Permissions are copied from the matching org-1 template role.
     */
    private function provisionRoles(Organization $organization): void
    {
        foreach (self::ORG_ROLES as $roleName) {
            // Skip if this org already has this role (e.g., re-provisioning)
            $exists = Role::where('name', $roleName)
                ->where('organization_id', $organization->id)
                ->exists();

            if ($exists) {
                continue;
            }

            // Find the template role from org 1
            $templateRole = Role::where('name', $roleName)
                ->where('organization_id', 1)
                ->first();

            $newRole = Role::create([
                'name'            => $roleName,
                'guard_name'      => 'web',
                'organization_id' => $organization->id,
            ]);

            // Copy permissions from template
            if ($templateRole) {
                $newRole->syncPermissions($templateRole->permissions);
            } else {
                // Fallback: grant all permissions to Admin
                if ($roleName === 'Admin') {
                    $newRole->syncPermissions(Permission::all());
                }
            }
        }
    }

    /**
     * Create the Admin user for the new organization.
     */
    private function provisionAdminUser(
        Organization $organization,
        string $email,
        string $password,
        string $name
    ): User {
        $user = User::create([
            'name'            => $name,
            'email'           => $email,
            'password'        => Hash::make($password),
            'organization_id' => $organization->id,
            'status'          => 'active',
        ]);

        // Assign the org-scoped Admin role
        $adminRole = Role::where('name', 'Admin')
            ->where('organization_id', $organization->id)
            ->first();

        if ($adminRole) {
            $user->assignRole($adminRole);
        }

        return $user;
    }

    /**
     * Create a default GeneralSetting record for the organization.
     */
    private function provisionGeneralSetting(Organization $organization): GeneralSetting
    {
        // Clone from org 1 settings if they exist
        $template = GeneralSetting::where('organization_id', 1)->first()
            ?? GeneralSetting::first();

        return GeneralSetting::create([
            'organization_id'   => $organization->id,
            'name'              => $organization->name,
            'currency'          => $template?->currency ?? 'BDT',
            'branch_status'     => $template?->branch_status ?? 1,
            'division_status'   => $template?->division_status ?? 1,
            'department_status' => $template?->department_status ?? 1,
            'section_status'    => $template?->section_status ?? 1,
        ]);
    }
}
