# Multi-Tenancy Implementation Plan
## Branch: `feature/multi-tenancy`

---

## 1. Overview & Goal

We are adding a **true Organization-level tenancy layer** on top of the existing multi-company structure.

| Layer | Description |
|---|---|
| **Super Admin** | Cross-organization. System-preserved. Sees ALL organizations' data. Has ALL permissions. |
| **Admin** (formerly Super Admin) | Per-organization. System-preserved per org. Scoped to their own organization only. Has all org permissions. |
| **HR Manager / Manager / Employee** | Same as now, but also scoped to their organization. |

---

## 2. New Concept: `Organization`

A new top-level entity is introduced:

```
Organization
    └── Companies
         └── CompanyLocations (Branches)
              └── Divisions → Departments → Sections
                   └── Employees → Users
```

Every **existing record** that belongs to a tenant (companies, plans, leaves, employees, payroll, etc.) gets an `organization_id` column to isolate it per tenant.

---

## 3. Role Changes

| Old Role | New Role | system_preserved | organization_id |
|---|---|---|---|
| Super Admin | **Super Admin** (NEW) | ✅ | ❌ NULL (cross-org) |
| Super Admin | **Admin** (RENAMED) | ✅ | ✅ (scoped to one org) |
| HR Manager | HR Manager | ✅ | ✅ |
| Manager | Manager | ✅ | ✅ |
| Employee | Employee | ✅ | ✅ |

- The **Super Admin** role has `organization_id = NULL` and bypasses all org scopes (Spatie `teams` feature will NOT be used; we handle it manually).
- The **Admin** role has an `organization_id` and is fully scoped to their organization.

---

## 4. Database Changes

### 4.1 New `organizations` Table

```php
Schema::create('organizations', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('slug')->unique();         // used for URL-based routing (optional)
    $table->string('logo')->nullable();
    $table->string('email')->nullable();
    $table->string('phone')->nullable();
    $table->string('address')->nullable();
    $table->enum('status', ['active', 'inactive'])->default('active');
    $table->timestamps();
    $table->unsignedBigInteger('created_by')->nullable();
    $table->unsignedBigInteger('updated_by')->nullable();
});
```

### 4.2 Add `organization_id` to `users` Table

```php
$table->unsignedBigInteger('organization_id')->nullable()->after('id');
$table->foreign('organization_id')->references('id')->on('organizations')->nullOnDelete();
```

- **Super Admin users** → `organization_id = NULL`
- **All other users** → `organization_id = <their org>`

### 4.3 Add `organization_id` to `roles` Table

Spatie's `roles` table gets an `organization_id` column to scope roles per organization.

```php
$table->unsignedBigInteger('organization_id')->nullable()->after('id');
```

- **Super Admin role** → `organization_id = NULL` (global)
- **All other roles (Admin, HR Manager, etc.)** → seeded per-organization when an org is created

### 4.4 Add `organization_id` to All Key Tables

Every table that uses `OrganizationScoped` or holds tenant data gets `organization_id`:

**Priority tables (Phase 1):**
- `companies` — the top of org hierarchy
- `groups`
- `general_settings` — one settings record per org
- `menus` — one menu config per org

**Phase 2 (cascaded via company_id → organization_id relationship):**
- `company_locations`, `divisions`, `departments`, `sections`
- `employees`, `employee_office_infos`
- All `*_plans` tables (leave, bonus, shift, roster, etc.)
- `leaves`, `leave_counts`, `attendance`, `payrolls`, `payroll_process`
- `disbursements`, `transfers`, `movements`, etc.

> [!IMPORTANT]
> Because `companies` will have `organization_id`, and most other records link through `company_id`, we can derive the organization in most cases. We only need to add `organization_id` directly to tables that do **not** have a `company_id` chain.

---

## 5. OrganizationScoped Trait — Changes

The existing `OrganizationScoped` trait is updated to add a **first filter layer** for `organization_id` before applying the existing company/branch/division filters.

```php
// NEW — first layer: filter by organization
if ($user->user_type !== UserType::SuperAdmin) {
    $orgId = $user->organization_id;
    
    if ($hasColumn('organization_id')) {
        $builder->where('organization_id', $orgId);
    } elseif ($hasColumn('company_id')) {
        // Scope via company that belongs to this org
        $builder->whereHas('company', fn($q) => $q->where('organization_id', $orgId));
        // OR use a subquery on companies table:
        $builder->whereIn('company_id', 
            Company::where('organization_id', $orgId)->pluck('id')
        );
    }
}

// THEN — apply existing user_type-based scoping (company/branch/division/etc.)
// (existing code stays as-is below)
```

**New `UserType` enum value:**
```php
case SuperAdmin = 'super_admin';
```

The check `$user->user_type === UserType::Group` that bypasses all scopes is updated to:
```php
if ($user->user_type === UserType::SuperAdmin || $user->user_type === UserType::Group) {
    return; // bypass all scopes — Super Admin sees everything
}
```

---

## 6. User Model — Changes

```php
class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password', 'user_type', 
        'employee_id', 'status',
        'organization_id', // NEW
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
```

---

## 7. Login Flow — Changes

The login flow is updated to attach `organization_id` to the session after authentication.

### Option A: Session-based (Simpler — Recommended for now)

After `Auth::attempt()` succeeds in `LoginRequest::authenticate()`:

```php
// Store org_id in session for quick access
session(['organization_id' => auth()->user()->organization_id]);
```

A new `SetOrganizationContext` middleware is added to the global web middleware group:

```php
class SetOrganizationContext
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $orgId = Auth::user()->organization_id;
            // Make it available app-wide
            app()->instance('current.organization_id', $orgId);
        }
        return $next($request);
    }
}
```

### Option B: Subdomain-based (Future Enhancement)

Each organization gets a subdomain: `orgname.yourapp.com`. The middleware resolves `organization_id` from the subdomain. This is a future enhancement and not in Phase 1.

---

## 8. Organization Management Module

A new **Organization** module is added for the Super Admin:

```
App\Http\Controllers\Organization\OrganizationController.php
App\Http\Requests\Organization\StoreOrganizationRequest.php
App\Http\Requests\Organization\UpdateOrganizationRequest.php
App\Services\Organization\OrganizationService.php
App\Models\Organization\Organization.php
resources/views/organization/
    ├── index.blade.php
    ├── create.blade.php
    └── edit.blade.php
```

**OrganizationService responsibilities:**
- `createOrganization(array $data)` — Creates the org, then **auto-seeds**:
  - An **Admin** role for this organization (`organization_id = $org->id`)
  - An Admin user for the organization (email, password provided at creation)
  - A default `GeneralSetting` record for the organization
  - Default menus for the organization
- `suspendOrganization($orgId)` — Sets status to `inactive`, blocks login for all users in that org
- `deleteOrganization($orgId)` — Soft delete or hard delete with cascade

---

## 9. Permission & Role Scoping

Because Spatie `laravel-permission` does not natively support per-row role scoping without Teams, we handle it manually:

### Approach: `organization_id` Column on `roles` Table

1. We add `organization_id` (nullable) to the `roles` table.
2. When fetching roles for assignment or checking, we always filter by `organization_id`:
   ```php
   Role::where('organization_id', $user->organization_id)->get();
   ```
3. The **Super Admin role** (`organization_id = NULL`) is not returned in any org-scoped role list.
4. When a new Organization is created, `OrganizationService` clones the default roles (Admin, HR Manager, Manager, Employee) with the new `organization_id`.

> [!NOTE]
> We do **not** enable Spatie's built-in `teams` feature, as it requires resetting all permissions/roles. Our manual `organization_id` column approach is simpler and backwards-compatible.

---

## 10. Super Admin Guards

All routes for the Super Admin Organization management module are wrapped in a dedicated permission check:

```php
Route::middleware(['auth', 'role:Super Admin'])->prefix('system')->group(function () {
    Route::resource('organizations', OrganizationController::class);
});
```

The `Super Admin` role check passes only if the user has a role with `name = 'Super Admin'` and `organization_id = NULL`.

---

## 11. GeneralSetting — Per-Organization

Currently there is ONE `GeneralSetting` record for the entire system.

With multi-tenancy, each organization gets its own:
- Add `organization_id` to `general_settings`
- Helper `GeneralSetting::forCurrentOrg()` scoped to `Auth::user()->organization_id`
- Super Admin has a separate system-level settings

---

## 12. Implementation Phases

### ✅ Phase 0 — Already Done
- `OrganizationScoped` trait (scopes by company/branch/division/department/section)
- Spatie permission (roles/permissions)
- `user_type` enum on users

### 🔲 Phase 1 — Foundation (Multi-Tenancy Core)
1. Create `organizations` table migration
2. Create `Organization` model + `OrganizationService`
3. Add `organization_id` to `users` table
4. Add `organization_id` to `roles` table  
5. Add `organization_id` to `companies` and `general_settings` tables
6. Update `UserType` enum (add `SuperAdmin`)
7. Update `OrganizationScoped` trait (add org-level filter)
8. Add `SetOrganizationContext` middleware
9. Rename existing "Super Admin" role → "Admin" (update seeder)
10. Create new "Super Admin" role (null org, all permissions)
11. Create Organization CRUD module (Super Admin only)
12. Update login to store org context in session

### 🔲 Phase 2 — Data Isolation
1. Add `organization_id` to all plan tables, attendance, leaves, payroll
2. Update all seeders to accept `organization_id`
3. Create org provisioning seeder (auto-seed default data per new org)
4. Update existing data: assign all current records to a default "first" organization

### 🔲 Phase 3 — UI & UX
1. Super Admin dashboard: list all organizations, stats per org
2. Organization management pages (create/edit/suspend)
3. Admin dashboard: scoped to their org
4. Login page: optionally add org selector or org code input

### 🔲 Phase 4 — Advanced (Future)
1. Subdomain-based org resolution (`orgname.app.com`)
2. Per-organization custom domain support
3. Per-organization billing/subscription
4. Organization-level audit logs

---

## 13. Key Files to Create/Modify

| File | Action |
|---|---|
| `database/migrations/xxxx_create_organizations_table.php` | CREATE |
| `database/migrations/xxxx_add_organization_id_to_users_table.php` | CREATE |
| `database/migrations/xxxx_add_organization_id_to_roles_table.php` | CREATE |
| `database/migrations/xxxx_add_organization_id_to_companies_table.php` | CREATE |
| `database/migrations/xxxx_add_organization_id_to_general_settings_table.php` | CREATE |
| `app/Models/Organization/Organization.php` | CREATE |
| `app/Services/Organization/OrganizationService.php` | CREATE |
| `app/Http/Controllers/Organization/OrganizationController.php` | CREATE |
| `app/Http/Requests/Organization/StoreOrganizationRequest.php` | CREATE |
| `app/Http/Middleware/SetOrganizationContext.php` | CREATE |
| `app/Enums/UserType.php` | MODIFY — add `SuperAdmin` |
| `app/Traits/OrganizationScoped.php` | MODIFY — add org-level filter |
| `app/Models/User.php` | MODIFY — add `organization_id`, `organization()` relation |
| `database/seeders/PermissionSeeder.php` | MODIFY — rename Admin, add Super Admin |
| `bootstrap/app.php` | MODIFY — register new middleware |
| `resources/views/organization/` | CREATE — CRUD views |
| `routes/web.php` | MODIFY — add org management routes |

---

## 14. Risks & Mitigation

| Risk | Mitigation |
|---|---|
| Existing data has no `organization_id` | Migration creates a "Default Organization" and assigns all existing records to it |
| Spatie permission caching conflicts across orgs | Call `$user->flushCache()` on login and `organization_id` change |
| `OrganizationScoped` boot runs before user loads | Existing caching in the trait handles this; extend cache key with `organization_id` |
| Admin accidentally gets Super Admin permissions | Role names are system-preserved; UI filters out Super Admin from assignment dropdowns |
