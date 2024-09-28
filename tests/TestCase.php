<?php

namespace Ugly\Pay\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Ugly\Pay\ServiceProvider;

class TestCase extends Orchestra
{
    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->artisan('migrate');
    }

    protected function getPackageProviders($app): array
    {
        return [
            ServiceProvider::class,
        ];
    }
}
