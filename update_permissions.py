import re
import os

file_path = r'P:\Project\Web\hrms\routes\web.php'

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Simple replacements for general modules
replacements = {
    "'permission:search-employee.view'": "'permission:employee-management.view'",
    "'permission:employee-information.view'": "'permission:employee-management.view'",
    "'permission:employee-information.create'": "'permission:employee-management.create'",
    "'permission:employee-information.edit'": "'permission:employee-management.edit'",
    "'permission:employee-information.delete'": "'permission:employee-management.delete'",
    
    "'permission:leave-logs.view'": "'permission:leaves.view'",
    "'permission:leave-application.create'": "'permission:leaves.create'",
    "'permission:leave-application.edit'": "'permission:leaves.edit'",
    "'permission:leave-application.delete'": "'permission:leaves.delete'",
    
    "'permission:movement-logs.view'": "'permission:movement.view'",
    "'permission:movement-application.create'": "'permission:movement.create'",
    "'permission:movement-application.edit'": "'permission:movement.edit'",
    "'permission:movement-application.delete'": "'permission:movement.delete'",
    
    "'permission:records.view'": "'permission:attendance.view'",
    "'permission:create-attendance.create'": "'permission:attendance.create'",
    "'permission:bulk-upload-attendance.create'": "'permission:attendance.import'",
    "'permission:clock-in-out.view'": "'permission:attendance.create'",
    
    "permission:db-backup.view": "permission:db-backup.download",
    "permission:db-backup.create": "permission:db-backup.download",
}

for old, new in replacements.items():
    content = content.replace(old, new)

# 2. Handle context-dependent bulk-upload
content = re.sub(
    r"(Route::get\('bulk-upload', function \(\) \{\s*return view\('company_setup.bulk_uploads.form'\);\s*\}\)->name\('company_setup.bulk_upload'\)->middleware\(')permission:bulk-upload.view('\);)",
    r"\g<1>permission:companies.import\g<2>",
    content
)

content = re.sub(
    r"(Route::get\('bulk-upload', function \(\) \{\s*return view\('plans.bulk_uploads.form'\);\s*\}\)->name\('plans.bulk_upload'\)->middleware\(')permission:bulk-upload.view('\);)",
    r"\g<1>permission:leave-plans.import\g<2>",
    content
)

content = content.replace("'permission:bulk-upload.view'", "'permission:employee-management.import'")
content = content.replace("'permission:bulk-upload.create'", "'permission:employee-management.import'")

# 3. Structural fix for 'import' routes inside '.create' groups
plan_prefixes = [
    'meal_plans', 'shift_plans', 'leave_plans', 'ot_plans', 'roster_plans',
    'off_day_plans', 'bonus_plans', 'allowance_plans', 'ta_plans', 'da_plans'
]

for plan in plan_prefixes:
    # pattern: (Route::middleware('permission:([\w-]+)\.create')->group\(function \(\) \{(?:[^{}]+|\{[^{}]*\})*Route::[\w]+\('import', [^;]+->name\('plans\.{plan}\.import'\);(?:[^{}]+|\{[^{}]*\})*\}\);)
    
    pattern = r"(Route::middleware\('permission:([\w-]+)\.create'\)->group\(function \(\) \{(?:[^{}]+|\{[^{}]*\})*Route::[\w]+\('import', [^;]+->name\('plans\." + plan + r"\.import'\);(?:[^{}]+|\{[^{}]*\})*\}\);)"
    
    match = re.search(pattern, content)
    if match:
        full_group = match.group(0)
        base_perm = match.group(2)
        
        import_line_pattern = r"\s*Route::[\w]+\('import', [^;]+->name\('plans\." + plan + r"\.import'\);"
        import_match = re.search(import_line_pattern, full_group)
        
        if import_match:
            import_line = import_match.group(0)
            updated_group = full_group.replace(import_line, "")
            import_block = f"\n            Route::middleware('permission:{base_perm}.import')->group(function () {{\n                {import_line.strip()}\n            }});"
            content = content.replace(full_group, updated_group + import_block)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Permissions updated successfully.")
