<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (config('database.default') === 'mysql' && config('database.connections.mysql.database') === 'hrms') {
            throw new \Exception("CRITICAL SAFETY ERROR: Tests are trying to run on the primary 'hrms' database.");
        }
    }
}
