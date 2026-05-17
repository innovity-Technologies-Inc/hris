# Task: Reset Permission System

## Description
Clear all permission-related data and re-run the `PermissionSeeder` to restore system default roles, permissions, and menus.

## Requirements
- Clear data from `roles`, `permissions`, `model_has_roles`, `model_has_permissions`, `role_has_permissions`, and `menus` tables.
- Run `PermissionSeeder`.
- Verify that default permissions and roles are restored.

## Steps
- [x] Clear permission-related tables.
- [x] Execute `PermissionSeeder`.
- [x] Verify `Super Admin` role and permissions exist.
- [x] Run `php artisan optimize`.
