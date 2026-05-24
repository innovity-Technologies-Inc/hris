<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        // Use $_ENV or env() for pre-boot check, as config() helper is not available yet
        if (($_ENV['DB_DATABASE'] ?? env('DB_DATABASE')) === 'hrms') {
            throw new \Exception("CRITICAL SAFETY ERROR: Tests are trying to run on the primary 'hrms' database.");
        }

        parent::setUp();
    }
}

