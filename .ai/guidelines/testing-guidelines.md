# Testing & Verification Guidelines

This document outlines the mandatory testing procedures and documentation standards for the HRMS project.

## 🏗️ Testing Framework
- **Primary Framework**: [Pest PHP](https://pestphp.com/). All new tests must be written using Pest's functional syntax.
- **Test Types**:
    - **Feature Tests**: Use for testing business logic, organizational scoping, and controller responses.
    - **Unit Tests**: Use for isolated logic within Services or Helpers.

## 🗄️ Database Environment
- **Default for Testing**: Always use **SQLite in-memory** (`:memory:`).
- **Rationale**: Ensures maximum execution speed, complete isolation between test runs, and avoids conflicts with MySQL-specific configurations in the local environment.
- **Execution Command Template**:
  ```powershell
  $env:DB_CONNECTION='sqlite'; $env:DB_DATABASE=':memory:'; php artisan test [path_to_test]
  ```

## 📝 Mandatory Test Logging
Every time a test is requested, implemented, or executed, the `TEST_LOG.md` file in the project root must be updated.

### Log Entry Requirements:
Each entry in `TEST_LOG.md` must include:
1.  **Date**: The date of execution.
2.  **Instruction**: The specific goal or requirement being tested.
3.  **Command**: The exact command used to run the tests.
4.  **Results**: Summary of passed/failed tests and assertions.
5.  **Status**: A clear indicator (✅ SUCCESS or ❌ FAILED).

## ✅ Completion Criteria
A task involving code changes is not considered "Complete" until:
1.  Relevant tests have been written or updated.
2.  Tests pass in the SQLite in-memory environment.
3.  The `TEST_LOG.md` has been updated with the latest results.
