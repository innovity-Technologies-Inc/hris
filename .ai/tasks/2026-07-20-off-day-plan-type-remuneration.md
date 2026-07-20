# Task: Implement Off Day Plan Type and Remuneration Disabling Logic

## Problem Statement
Currently, Off Day Plans do not distinguish between `Paid` and `comp-off` types. As a result, remuneration fields are always active in forms and required in backend validations, which is unnecessary and confusing when configuring a `comp-off` off day plan.

## Proposed Solution
1. Add a `type` enum column (`Paid`, `comp-off`) to the `off_day_plans` database table with default `'Paid'`.
2. Update `OffDayPlan` Eloquent model to include `type` in `$fillable`.
3. Refactor `PlanService::offDayPlanValidation` to validate `type` and make remuneration configuration optional when `type` is `comp-off`.
4. Update Excel import (`OffDayPlansImport`) to handle `type`.
5. Update `form.blade.php` to include Plan Type selection and dynamic JS script that disables/enables the remuneration configuration section based on selected type.
6. Update `search_results.blade.php`, `view.blade.php`, and profile view partials (`offday_plan.blade.php`, `view_offday_modal.blade.php`) to display Plan Type and handle `comp-off` remuneration display cleanly.

## Step-by-Step Execution Plan

### Step 1: Migration Creation & Schema Update
- Create migration `database/migrations/2026_07_20_000001_add_type_to_off_day_plans_table.php`.
- Add `$table->enum('type', ['Paid', 'comp-off'])->default('Paid')->after('short_name');`.
- Run `php artisan migrate`.

### Step 2: Model & Service Layer Updates
- Update `App\Models\Plan\OffDayPlan.php` `$fillable` array.
- Update `App\Services\Plan\PlanService.php` `offDayPlanValidation($request)`:
  - Validate `type` => `required|in:Paid,comp-off`.
  - Validate `offday_config_type` => `required_if:type,Paid|nullable|in:Salary Based,Custom`.
  - Validate `salary_rate_type` => `required_if:offday_config_type,Salary Based|nullable|in:Basic Rate,Multiplier`.
- Update `App\Imports\Plan\OffDayPlansImport.php` to include `type`.

### Step 3: Blade Views & Dynamic UI
- Update `resources/views/plan/off_day_plans/form.blade.php`:
  - Add "Plan Type" radio buttons / dropdown (`Paid`, `comp-off`).
  - Update `toggleOffdayConfigSections()` JavaScript function to check `type`.
  - If `type === 'comp-off'`, disable remuneration card opacity, disable inputs, and display alert.
- Update `resources/views/plan/off_day_plans/search_results.blade.php` and `view.blade.php`.
- Update `resources/views/employee/partials/profile_view/partials/offday_plan.blade.php` and `resources/views/employee/partials/modal/view_offday_modal.blade.php`.

### Step 4: Optimization & Verification
- Run `php artisan optimize`.
- Manual verification of Off Day Plan creation/edit/view with both `Paid` and `comp-off` types.
