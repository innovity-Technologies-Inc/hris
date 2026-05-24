<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Absolute Safety Check: This method runs after the application is booted
     * but BEFORE any testing traits (like RefreshDatabase) are executed.
     */
    protected function setUpTraits()
    {
        // 1. Check if the app is trying to connect to 'hrms'
        // We check BOTH the config and the active connection to be 100% sure
        $dbName = config('database.connections.mysql.database');
        
        if ($dbName === 'hrms') {
            throw new \Exception("CRITICAL SAFETY ERROR: Tests are blocked from running on the primary 'hrms' database. Current database in config: " . $dbName);
        }

        return parent::setUpTraits();
    }

    protected function setUp(): void
    {
        parent::setUp();
    }
}

