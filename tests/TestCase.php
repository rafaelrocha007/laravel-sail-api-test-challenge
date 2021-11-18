<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations;

    protected static $initialized = false;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate', ['-vvv' => true, '--no-interaction' => true,]);
        $this->artisan('passport:install', ['-vvv' => true, '--no-interaction' => true,]);
    }
}
