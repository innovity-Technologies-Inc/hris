# Enterprise Dynamic HRMS Architecture

This document provides a highly detailed architectural breakdown for two core enterprise modules:
1. **Dynamic Employee Field Engine** (The Headless CMS for Employee Data)
2. **Dynamic Approval Workflow Engine** (The Polymorphic Multi-Level Router)

---

## Part 1: Dynamic Employee Field Engine

To make an HRMS future-proof, adding new fields (like "Passport Expiry" or "Dietary Requirements") shouldn't require new database migrations. A robust enterprise design separates the form into four distinct layers:

```text
Form Architecture
├── Sections (Personal, Education)
│      ├── Fields (Degree, Institute)
│      │      ├── Validation JSON (Rules like required, min, max)
│      │      ├── UI Configuration JSON (Icons, widths, prefixes)
│      │      └── Options (Static Dropdowns / Dynamic API Links)
│      └── Data (Stored as JSON records)
```

### 1.1 `employee_sections` Table
Defines the major groupings of the employee profile. The most critical column here is `storage_type`, which determines if the section is a single block or a repeater.

| Column | Type | Explanation | Example |
| :--- | :--- | :--- | :--- |
| `id` | PK | Unique identifier | `1` |
| `name` | String | Display name of the section | `Education` |
| `slug` | String | URL friendly identifier | `education` |
| `storage_type` | Enum | Decides database behavior (`single` vs `multiple`) | `multiple` |
| `display_order`| Integer | UI sorting | `2` |

**Why `storage_type` is brilliant:**
If `Personal Information` is `single`, the system knows there will only be 1 JSON record per employee. If `Education` is `multiple`, the UI automatically renders an "+ Add Education" repeater button, and stores each degree as a separate JSON object record.

### 1.2 `employee_fields` Table
The absolute core of the engine. This table defines exactly how each field behaves, validates, and renders.

| Column | Type | Explanation | Example |
| :--- | :--- | :--- | :--- |
| `section_id` | FK | Links to `employee_sections` | `1` |
| `name` | String | Internal database key | `degree` |
| `label` | String | Human readable label | `Degree Title` |
| `field_type` | String | Data type (`text`, `number`, `file`) | `text` |
| `column_width` | Integer | Bootstrap/Tailwind grid width (`12`,`6`,`4`,`3`) | `6` (Takes 50% of the row) |
| `component` | String | HTML Element to render | `input` or `select` |
| `validation_json`| JSON | Backend & Frontend validation rules | `{"required": true, "min": 2}` |
| `ui_config` | JSON | Extended UI behavior | `{"icon": "graduation-cap", "placeholder": "Enter Degree"}` |
| `option_source` | Enum | Where dropdown options come from | `static`, `table`, `api` |
| `source_table` | String | Table name if `option_source` is `table`| `departments` |

**Advanced JSON Validation Example:**
By storing rules as JSON, Laravel can dynamically build Form Requests on the fly:
```json
{
   "required": true,
   "numeric": true,
   "min": 0,
   "max": 5,
   "step": 0.01
}
```
*Translates dynamically to:* `$rules['cgpa'] = 'required|numeric|min:0|max:5';`

### 1.3 `employee_field_options` Table
When `option_source` is set to `static`, this table provides the literal dropdown choices. It replaces large, unmanageable JSON arrays with a structured relational table, allowing you to disable (`is_active = false`) specific options like deprecating a "Blood Group".

| Column | Type | Explanation | Example |
| :--- | :--- | :--- | :--- |
| `field_id` | FK | Links to the specific dropdown field | `5` (e.g., Gender Field) |
| `label` | String | What the user sees | `Male` |
| `value` | String | What is saved to the DB | `male` |
| `is_default` | Boolean| If this should be pre-selected | `false` |

*Note: If `option_source` is `table` (e.g. mapping to `countries`), this table is ignored, and the frontend dynamically fetches `/api/countries` to populate the dropdown.*

### 1.4 `employee_section_records` Table
Where the actual employee data lives.

| Column | Type | Explanation | Example |
| :--- | :--- | :--- | :--- |
| `employee_id` | FK | Links to `employees` | `104` |
| `section_id` | FK | Links to `employee_sections` | `1` (Education) |
| `data` | JSON | The literal key-value pairs | `{"degree": "BSc", "institute": "MIT"}` |

---

## Part 2: Dynamic Approval Workflow Engine

To prevent hardcoding approval hierarchies (e.g., "Supervisor -> HR -> Finance"), we separate the routing logic from the actual module. 

### 2.1 Engine Tables

1. **`approval_workflows`**: Maps a module to the engine.
   - `module_name`: `promotion`, `leave_request`, `expense`
2. **`approval_workflow_steps`**: The sequence of approvers.
   - `step_order`: `1`
   - `required_user_type`: Maps to your enums (`Department`, `Division`, `Group`)
3. **`approval_requests` (Polymorphic)**: The vehicle traversing the steps.
   - `approvable_type`: `App\Models\LeaveRequest`
   - `employee_id`: The employee who submitted it.
   - `current_step_order`: Tracks current state (`1`)
4. **`approval_logs`**: Audit trail.
   - Tracks the exact user ID, timestamp, and their comments (`approved`/`rejected`).

### 2.2 The `OrganizationScoped` Superpower

The brilliance of this engine is how it integrates with your existing `OrganizationScoped` trait.

Instead of writing complex code to figure out *"Who exactly is the Department Head for Employee #104?"*, you simply:
1. Set **Step 1** to require `UserType::Department`.
2. When a `Department` user logs in, they query all `ApprovalRequests` where `current_step_order = 1`.
3. The `OrganizationScoped` trait intercepts this query. Because `approval_requests` stores the `employee_id`, the trait **automatically filters the database results**. The Department Head only sees requests originating from employees inside their specific department.

### 2.3 The Developer Implementation

To add approvals to any new module, developers simply attach the `HasApprovalWorkflow` trait to the Model:

```php
$expense = ExpenseReport::create($data);

// Automatically triggers the engine and routes it to Step 1
$expense->submitForApproval('expense_report', $employee->id);
```

No module controller ever needs to know *who* is approving it. The engine handles the entire lifecycle securely and dynamically.
