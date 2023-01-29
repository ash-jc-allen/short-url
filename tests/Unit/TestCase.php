<?php

namespace AshAllenDesign\ShortURL\Tests\Unit;

use AshAllenDesign\ShortURL\Facades\ShortURL;
use AshAllenDesign\ShortURL\ShortURLProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate', ['--database' => 'testdb'])->run();
    }

    /**
     * Load package service provider.
     */
    protected function getPackageProviders($app): array
    {
        return [
            ShortURLProvider::class,
        ];
    }

    /**
     * Get package aliases.
     */
    protected function getPackageAliases($app): array
    {
        return [
            'ShortURL' => ShortURL::class,
        ];
    }

    /**
     * Define environment setup.
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testdb');
        $app['config']->set('database.connections.testdb', [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);
    }
}
