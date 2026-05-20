# Task: Company Models Standardization and Structure Module Finalization

Standardize namespaces for Company models, move Group model, and update all references to ensure architectural consistency.

## Status
- **Status**: In Progress 🏗️
- **Priority**: High 🔴
- **Date**: 2026-05-20

## Requirements
- Move `app/Models/Group.php` to `app/Models/Company/Group.php`.
- Standardize namespaces for all models in `app/Models/Company/` to `App\Models\Company`.
- Update imports and references to these models throughout the project.
- Update references to `App\Models\Structure\OrganizationStructure`.

## Todo List
- [ ] Move `app/Models/Group.php` to `app/Models/Company/Group.php` <!-- id: 0 -->
- [ ] Update namespaces in `app/Models/Company/*.php` <!-- id: 1 -->
- [ ] Update imports in Company models for relationships <!-- id: 2 -->
- [ ] Update references to `App\Models\Group` in Controllers, Models, and Views <!-- id: 3 -->
- [ ] Update references to `OrganizationStructure` to use fully qualified namespace or correct import <!-- id: 4 -->
- [ ] Run `php artisan optimize` <!-- id: 5 -->
- [ ] Verify with tests <!-- id: 6 -->

## Progress Tracking
- 2026-05-20: Task initiated. Research completed.
