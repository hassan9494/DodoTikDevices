<?php

namespace Tests;

use Database\Seeders\LegacyBaseSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Schema;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected bool $seed = true;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('generals')) {
            $this->seed(LegacyBaseSeeder::class);
        }
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadLaravelMigrations();
        $this->loadMigrationsFrom(database_path('migrations'));

        $legacyPath = database_path('migrations/migrations');
        if (is_dir($legacyPath)) {
            $this->loadMigrationsFrom($legacyPath);
        }
    }
}
