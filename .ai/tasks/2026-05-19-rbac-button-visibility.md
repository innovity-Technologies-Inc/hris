# Task: RBAC Visibility for Action Buttons

Implement RBAC visibility for action buttons (Create, Edit, Delete, Import) across several index and partial blade files.

## Status
- [ ] Company Setup Module
- [ ] Plans Module
- [ ] Transport Module

## Details
Use the permission slugs from the `PermissionSeeder`. The format is `slug.action`.

### Actions to gate:
- **Create Button**: `@can('slug.create')`
- **Edit Button**: `@can('slug.edit')`
- **Delete Button**: `@can('slug.delete')`
- **Import Button**: `@can('slug.import')`
- **View/Search**: `@can('slug.view')`

### Mapping:
- Groups: `groups`
- Company Types: `company-types`
- Companies: `companies`
- Company Branches: `company-branches`
- Divisions: `divisions`
- Departments: `departments`
- Sections: `sections`
- Designations: `designations`
- Salary Acts (Tofsils): `salary-acts`
- Salary Grades: `salary-grades`
- Banks: `banks`
- Bank Branches: `bank-branches`
- Bank Accounts: `bank-accounts`
- Holidays: `holidays`
- Job Creations: `job-creations`
- Meal Plans: `meal-plans`
- Shift Plans: `shift-plans`
- Leave Plans: `leave-plans`
- OT Plans: `ot-plans`
- Roster Plans: `roster-plans`
- Off-Day Work Plans: `off-day-work-plans`
- Bonus Plans: `bonus-plans`
- Allowance Plans: `allowance-plans`
- TA Plans: `ta-plans`
- DA Plans: `da-plans`
- Deduction Plan: `deduction-plan`
- Vehicles: `vehicles`
- Assign Driver: `assign-driver`
- Vehicle Requisition: `vehicle-requisition`
- Employee Transport: `employee-transport`
- Vehicle Allocation: `vehicle-allocation`

## Todo
- [ ] Apply checks to `resources/views/company_setup/**/*.blade.php`
- [ ] Apply checks to `resources/views/plans/**/*.blade.php`
- [ ] Apply checks to `resources/views/transport/**/*.blade.php`
- [ ] Run `php artisan optimize`
