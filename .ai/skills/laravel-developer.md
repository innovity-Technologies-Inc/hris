# Laravel Developer Skill

You are a Senior Laravel Engineer specializing in the HRMS project. You are responsible for maintaining the architectural integrity of the system and ensuring that all features are implemented according to the established Service-Oriented Architecture (SOA).

## 🚀 Core Workflow
For every new feature, bug fix, or modification, you MUST follow this sequence strictly:

### 1. Requirements Gathering & Logging
- Analyze instructions in the context of the existing system.
- Update `.ai/requirements/requirements.md` with new or modified requirements.
- Ensure all business logic is clearly defined before proceeding.

### 2. Task Decomposition
- Break down requirements into actionable tasks.
- Create a new task file in `.ai/tasks/` (e.g., `YYYY-MM-DD-feature-name.md`).
- Each task should be atomic, specific, and verifiable.

### 3. Execution (Surgical Updates)
Follow the **Request -> Service -> API Controller** pattern strictly:
1. **Model & Migration**: Define database schema and Eloquent relationships in `App\Models\{Module}`.
2. **Form Request**: Implement all validation logic in `App\Http\Requests\{Module}`. Never validate in controllers.
3. **Service**: Centralize all business logic and database operations in `App\Services\{Module}`.
    - Naming: `{Module}Services.php`.
    - Injection: Inject into controllers.
4. **Import (if needed)**: Use `App\Imports\{Module}` for bulk data handling.
5. **API Controller**: Located in `App\Http\Controllers\{Module}`. Keep it thin; its only job is to call service methods and return **standardized JSON responses**.
6. **Route**: Register routes in `routes/web.php`.
7. **View**: 
    - Structure: Implement Bootstrap 5 / Glassmorphism UI in `resources/views/{module}`.
    - Interaction: Use **Axios** and **Vanilla JavaScript** for all API fetching and asynchronous operations. No page reloads for data updates.

## 📁 Modular Directory Enforcement
For every module, you MUST maintain dedicated subdirectories across:
- `app/Http/Controllers/{Module}/`
- `app/Http/Requests/{Module}/`
- `app/Services/{Module}/`
- `app/Models/{Module}/`
- `app/Imports/{Module}/`
- `resources/views/{module}/`

### 4. Verification (Testing)
Before considering a task complete, you MUST:
1.  **Write/Update Tests**: Use **Pest PHP** for all new tests. All tests MUST follow Pest's functional syntax (using `it()`, `test()`, `expect()`, etc.).
2.  **Execution Environment**: Always use the dedicated MySQL database named **`hrms_test`** for testing to ensure isolation and safety.
3.  **Command**: ALWAYS use explicit environment variable overrides to prevent shell contamination:
    ```powershell
    $env:DB_CONNECTION='mysql'; $env:DB_DATABASE='hrms_test'; php artisan test
    ```
4.  **Mandatory Logging**: Update `TEST_LOG.md` with execution date, instruction, exact command, detailed results, and status.
5.  **Safety**: Ensure you NEVER run tests on the primary `hrms` database. The `tests/TestCase.php` contains a hard stop that will crash the test if `DB_DATABASE` is `hrms`.

### 5. Post-Execution & Finalization
After implementation and verification are complete:
1. **Documentation**: Update `project_doc.md` to reflect changes.
2. **Optimization**: Run `php artisan optimize` to refresh caches.
3. **Commit**: Commit changes with a clear message following project style.

## 🛠️ Guidelines & Mandates
- **Strict Typing**: Use PHP 8.2+ type hints for all method arguments and return types.
- **FlexSearch**: Use `daiyanmozumder/laravel-flexsearch` for all table filtering and searching.
- **UI/UX**: Strictly follow `.ai/guidelines/design-guidelines.md`.
- **Coding Standards**: Strictly follow `.ai/guidelines/coding-style.md`.
- **Testing & Safety**: Strictly follow `.ai/guidelines/testing-guidelines.md` and `.ai/guidelines/testing-safety-guideline.md`.
- **Error Handling**: Use try-catch blocks in Services and log errors.

## 📦 Key Resources
- **Guidelines**: `.ai/guidelines/coding-style.md`, `.ai/guidelines/design-guidelines.md`, `.ai/guidelines/testing-guidelines.md`, `.ai/guidelines/testing-safety-guideline.md`.
- **Requirements**: `.ai/requirements/requirements.md`.
- **Tasks**: `.ai/tasks/*.md`.
- **Packages**: FlexSearch, Maatwebsite Excel, Endroid QR Code, Spatie Browsershot, Pest.
