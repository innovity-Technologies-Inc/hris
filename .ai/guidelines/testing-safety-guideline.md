# Pest Testing & Database Safety Guidelines

This document outlines the mandatory configuration and safety protocols for using **Pest** in Laravel projects to ensure high-performance testing and absolute protection of development/production databases.

---

## 🛡️ 1. Absolute Database Safety (Fail-Safe)

To prevent tests from accidentally wiping your local database, you **must** implement a multi-layered isolation strategy.

### Layer A: Testing Database Configuration
Configure `phpunit.xml` to use the dedicated MySQL testing database.

### Layer B: The "Hard Stop" Exception
Add a safety check in `tests/TestCase.php`. If the app tries to use the main database during tests, it must crash immediately.

```php
// tests/TestCase.php

protected function setUp(): void
{
    parent::setUp();

    if (config('database.connections.mysql.database') === 'hrms') {
        throw new \Exception("CRITICAL SAFETY ERROR: Tests are trying to run on the primary 'hrms' database.");
    }
}
```

### Layer C: Configuration Precedence
Use `<server>` tags instead of `<env>` tags in `phpunit.xml`, as they have higher precedence in Laravel's boot process.

```xml
<!-- phpunit.xml -->
<php>
    <server name="DB_CONNECTION" value="mysql"/>
    <server name="DB_DATABASE" value="hrms_test"/>
    <env name="APP_ENV" value="testing"/>
</php>
```

---

## 🚀 2. Pest Configuration Excellence

### Use `RefreshDatabase` Globally
Instead of adding the trait to every file, bind it in `tests/Pest.php` for the entire Feature directory.

```php
// tests/Pest.php

uses(
    Tests\TestCase::class, 
    Illuminate\Foundation\Testing\RefreshDatabase::class
)->in('Feature');
```

### Handling Custom Model Hashing
If your models use the `'password' => 'hashed'` cast (Laravel 11+ default), ensure your `UserFactory` provides a **plain string**. Hashing it twice will cause authentication tests to fail.

```php
// database/factories/UserFactory.php
public function definition(): array
{
    return [
        'password' => 'password', // Don't use Hash::make() here
    ];
}
```

---

## 🛠️ 3. Troubleshooting & "Ghost" Configs

### The Config Cache Trap
If your tests are still hitting your local database despite following the rules above, your **Configuration is Cached**. The cache bypasses `phpunit.xml` and `config/*.php` logic.

**The Solution:**
```bash
# Clear the ghost cache
php artisan config:clear

# Or manually delete it if the command fails
rm bootstrap/cache/config.php
```

---

## 📝 4. Mandatory Test Logging

Always maintain a `TEST_LOG.md` to track your verification history. This ensures consistency and accountability.

---

## 🛡️ 5. Zero-Destruction & Data Preservation

### Understanding `RefreshDatabase`
*   **Behavior:** The `RefreshDatabase` trait (configured in `tests/Pest.php`) ensures each test starts with a clean database schema.
*   **Isolation:** This project uses a dedicated **MySQL database named `hrms_test`** for testing. 
*   **Configuration:** The connection is strictly managed via `phpunit.xml` using `<server>` tags. 
    *   `DB_CONNECTION`: `mysql`
    *   `DB_DATABASE`: `hrms_test`
*   **Safety Rule:** It **NEVER** touches your main MySQL `hrms` database. Never manually run `migrate:refresh` or `db:wipe` on the main environment.

### Why is my main database empty?
If your main database becomes empty after running tests, it is likely because:
1.  You have misconfigured `phpunit.xml` or `.env.testing` to point to the main `hrms` database.
2.  You accidentally ran a destructive artisan command like `migrate:fresh` or `migrate:refresh` on the main environment.

**Always ensure your test output shows that it is using the "hrms_test" database to be 100% safe.**
