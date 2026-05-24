# Testing & Verification Guidelines

This document outlines the mandatory testing procedures and documentation standards for the HRMS project.

## 🏗️ Testing Framework
- **Primary Framework**: [Pest PHP](https://pestphp.com/). All new tests must be written using Pest's functional syntax.
- **Test Types**:
    - **Feature Tests**: Use for testing business logic, organizational scoping, and controller responses.
    - **Unit Tests**: Use for isolated logic within Services or Helpers.

## 🗄️ Database Environment
- **Default for Testing**: Always use the dedicated MySQL database named **`hrms_test`**.
- **Rationale**: Ensures complete isolation from the production/development database (`hrms`) while maintaining feature parity with the target environment.
- **Execution Command Template**:
  ```powershell
  php artisan test [path_to_test]
  ```
  (Note: Ensure `phpunit.xml` or `.env.testing` is correctly configured to point to `hrms_test`)

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
2.  Tests pass in the **`hrms_test`** environment.
3.  The `TEST_LOG.md` has been updated with the latest results.
