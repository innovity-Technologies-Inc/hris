# Task: Gate Plan Assignment and Removal in Employee Profile

Gate the plan assignment and removal actions in the employee profile with `@can('employee-management.edit')`.

## Sub-tasks
1.  **Meal Plan**: Wrap "Create New", "Remove", and "Delete" buttons in `@can('employee-management.edit')`.
2.  **Shift Plan**: Wrap "Add", "Remove", and "Delete" buttons in `@can('employee-management.edit')`.
3.  **Roster Plan**: Wrap "Add", "Remove", and "Delete" buttons in `@can('employee-management.edit')`.
4.  **OT Plan**: Wrap "Create New", "Remove", and "Delete" buttons in `@can('employee-management.edit')`.
5.  **Off Day Plan**: Wrap "Add", "Remove", and "Delete" buttons in `@can('employee-management.edit')`.
6.  **Bonus Plan**: Wrap "Submit Selected" button and plan selection checkboxes in `@can('employee-management.edit')`.
7.  **Leave Plan**: Wrap "Submit Selected" button and plan selection checkboxes in `@can('employee-management.edit')`.

## Verification
- Verify that users with `employee-management.edit` permission can see and interact with these buttons.
- Verify that users without the permission cannot see these buttons.
