<?php

namespace Tests\Support;

use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabaseState;

trait RefreshAndSeedDatabase
{
    use RefreshDatabase {
        refreshInMemoryDatabase as baseRefreshInMemoryDatabase;
    }

    /**
     * Refresh the in-memory database.
     *
     * @return void
     */
    protected function refreshInMemoryDatabase()
    {
        $this->baseRefreshInMemoryDatabase();

        $class = 'TestingSeeder';

        $this->artisan('db:seed', ['--class' => $class]);
        $this->app[Kernel::class]->setArtisan(null);
    }

    /**
     * Refresh a conventional test database.
     *
     * @return void
     */
    protected function refreshTestDatabase()
    {
        // if (!RefreshDatabaseState::$migrated) {
        //     $this->artisan('migrate:fresh');
        //
        //     $this->app[Kernel::class]->setArtisan(null);
        //
        //     RefreshDatabaseState::$migrated = true;
        // }
        //
        // if (!SeedDatabaseState::$seeded) {
        //     $this->artisan('db:seed');
        //
        //     $this->app[Kernel::class]->setArtisan(null);
        //
        //     SeedDatabaseState::$seeded = true;
        // }

        $this->beginDatabaseTransaction();
    }
}
