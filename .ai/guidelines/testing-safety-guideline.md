# Pest Testing & Database Safety Guidelines

This document outlines the mandatory configuration and safety protocols for using **Pest** in Laravel projects to ensure high-performance testing and absolute protection of development/production databases.

---

## 🛡️ 1. Absolute Database Safety (Fail-Safe)

To prevent tests from accidentally wiping your local database, you **must** implement a multi-layered isolation strategy.

### Layer A: Smart Test Runner Detection
Update `config/database.php` to force SQLite when a test runner is active. This is the most reliable way to override global environment variables.

```php
// config/database.php

'default' => (isset($_SERVER['argv']) && (
    str_contains(implode(' ', $_SERVER['argv']), 'pest') || 
    str_contains(implode(' ', $_SERVER['argv']), 'phpunit') || 
    str_contains(implode(' ', $_SERVER['argv']), 'artisan test')
)) ? 'sqlite' : env('DB_CONNECTION', 'sqlite'),

'connections' => [
    'sqlite' => [
        'driver' => 'sqlite',
        'database' => (isset($_SERVER['argv']) && (
            str_contains(implode(' ', $_SERVER['argv']), 'pest') || 
            str_contains(implode(' ', $_SERVER['argv']), 'phpunit') || 
            str_contains(implode(' ', $_SERVER['argv']), 'artisan test')
        )) ? ':memory:' : env('DB_DATABASE', database_path('database.sqlite')),
        // ...
    ],
],
```

### Layer B: The "Hard Stop" Exception
Add a safety check in `tests/TestCase.php`. If the app tries to use anything but SQLite during tests, it must crash immediately.

```php
// tests/TestCase.php

protected function setUp(): void
{
    parent::setUp();

    if (config('database.default') !== 'sqlite') {
        throw new \Exception("CRITICAL SAFETY ERROR: Tests are trying to run on '" . config('database.default') . "' database. Testing is strictly restricted to 'sqlite' (:memory:).");
    }
}
```

### Layer C: Configuration Precedence
Use `<server>` tags instead of `<env>` tags in `phpunit.xml`, as they have higher precedence in Laravel's boot process.

```xml
<!-- phpunit.xml -->
<php>
    <server name="DB_CONNECTION" value="sqlite"/>
    <server name="DB_DATABASE" value=":memory:"/>
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

**Format:**
- **Date:** [YYYY-MM-DD]
- **Goal:** Short description of the feature/bug fix.
- **Command:** The exact Pest command used.
- **Result:** Assertion counts and Status (✅ SUCCESS / ❌ FAILED).
