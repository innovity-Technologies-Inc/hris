# DYNAMIC EMPLOYEE FIELD ENGINE

A good enterprise design separates the form into four layers:
```text
Form
├── Sections
│      ├── Fields
│      │      ├── Validation
│      │      ├── UI Configuration
│      │      └── Options (if Select/Radio/Checkbox)
│      └── Data
```

## 1. `employee_sections`
This defines the major sections of the employee profile.

| Column | Example | Description |
| :--- | :--- | :--- |
| `id` | `1` | Primary Key |
| `name` | `Personal Information` | Display name |
| `slug` | `personal-info` | URL safe identifier |
| `description` | `Basic details` | Helper text |
| `storage_type` | `single` / `multiple` | `single` for Personal Info, `multiple` for Education |
| `display_order`| `1` | Sorting |
| `icon` | `user` | FontAwesome/Feather icon class |
| `is_active` | `true` | Boolean toggle |

## 2. `employee_fields`
This dictates how data is collected, formatted, and validated.

| Column | Example |
| :--- | :--- |
| `id` | `10` |
| `section_id` | `1` |
| `name` | `degree` |
| `label` | `Degree Title` |
| `field_type` | `text`, `file`, `select` |
| `placeholder` | `Enter Degree` |
| `required` | `true` |
| `column_width` | `6` (Renders as col-md-6) |
| `component` | `input`, `textarea`, `select` |
| `validation_json`| *See Section 4* |
| `ui_config` | *See Section 5* |
| `option_source` | `static`, `table`, `api` |
| `source_table` | `departments` |

## 3. `employee_field_options`
Stores static configurations for Select, Radio, Checkbox fields.

| Column | Example |
| :--- | :--- |
| `field_id` | `5` (e.g., Blood Group) |
| `label` | `O Positive` |
| `value` | `O+` |
| `is_active` | `true` |

## 4. `validation_json`
Laravel can read this JSON and build validation rules dynamically (e.g., `required|numeric|min:0|max:5`).
```json
{
   "required": true,
   "numeric": true,
   "min": 0,
   "max": 5
}
```

## 5. UI Configuration (JSON)
Controls frontend rendering behavior:
```json
{
   "placeholder": "Enter Degree",
   "width": 6,
   "icon": "graduation-cap",
   "autocomplete": true
}
```

## 6. Option Source (Dynamic Dropdowns)
For fields that load dynamic data (e.g., Department, Country):
- `option_source`: `table`
- `source_table`: `departments`
*(The frontend reads this config and fetches options via API).*

## 7. File Upload Fields
If `type = file`, extra configuration is required:
```json
{
   "accept": ["pdf", "jpg", "png"],
   "max_size": 2048,
   "multiple": false
}
```

## 8. Repeater Sections & Data Storage (`employee_section_records`)
When `storage_type` is `multiple` (e.g., Education), each click of **"+ Add Education"** creates a distinct JSON record row in the DB.

- `employee_id`: 105
- `section_id`: 2 (Education)
- `data`: `{"degree": "BSc", "institute": "MIT", "cgpa": 3.8}`
