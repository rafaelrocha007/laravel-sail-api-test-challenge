<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations;

    protected static $initialized = false;

    public function setUp(): void
    {
        parent::setUp();

        if (!self::$initialized) {
            Artisan::call('migrate',['-vvv' => true]);
            Artisan::call('passport:install',['-vvv' => true]);
            self::$initialized = true;
        }
    }
}
