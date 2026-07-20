# Off Day Plan Type and Remuneration Disabling Requirement

## Overview
Enhance the Off Day Plan feature by adding a plan `type` column (`Paid` or `comp-off`). When the plan type is selected as `comp-off`, the remuneration configuration (Salary Based vs Custom Rate settings) must be disabled in the UI and marked optional in backend validation.

## Business Requirements
1. **Off Day Plan Type Selection**:
   - Each Off Day Plan must have a `type` attribute with allowed values: `Paid` and `comp-off`.
   - Default value for `type` is `Paid`.

2. **Conditional Remuneration Configuration**:
   - When `type` is `Paid`:
     - Remuneration configuration is enabled.
     - User can select `Salary Based` (Basic Rate or Multiplier) or `Custom Rate`.
   - When `type` is `comp-off`:
     - Remuneration configuration section is disabled in the form interface.
     - Inputs inside remuneration configuration are set to disabled state so they are not submitted/validated unnecessarily.
     - Clear visual indication/alert is displayed indicating that `comp-off` plans do not require remuneration setup.

3. **Validation & API Updates**:
   - Backend validation in `PlanService::offDayPlanValidation` must validate `type` as required (`in:Paid,comp-off`).
   - Remuneration fields (`offday_config_type`, `salary_rate_type`, `offday_multiplier`, `custom_offday_rate`) are required only when `type` is `Paid`.

4. **UI & Display Updates**:
   - `search_results.blade.php`: Display a badge for Plan Type (`Paid` or `comp-off`). When type is `comp-off`, show `Comp-off` badge under Rate column.
   - `view.blade.php`: Display Plan Type badge and handle `comp-off` remuneration display gracefully.
   - Profile offday plan views/modals: Handle `comp-off` type when rendering remuneration details.
