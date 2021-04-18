<?php

namespace AshAllenDesign\ShortURL\Tests\Unit;

use AshAllenDesign\ShortURL\Facades\ShortURL;
use AshAllenDesign\ShortURL\Providers\ShortURLProvider;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    /**
     * Load package service provider.
     *
     * @param $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [ShortURLProvider::class];
    }

    /**
     * Get package aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'ShortURL'        => ShortURL::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  Application  $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testdb');
        $app['config']->set('database.connections.testdb', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
        ]);

        $this->migrateDatabase();
    }

    /**
     * Include each of the migrations and migrate them to
     * finish preparing the database for running the
     * tests.
     */
    private function migrateDatabase(): void
    {
        include_once __DIR__.'/../../database/migrations/2019_12_22_015115_create_short_urls_table.php';
        include_once __DIR__.'/../../database/migrations/2019_12_22_015214_create_short_url_visits_table.php';
        include_once __DIR__.'/../../database/migrations/2020_02_11_224848_update_short_url_table_for_version_two_zero_zero.php';
        include_once __DIR__.'/../../database/migrations/2020_02_12_008432_update_short_url_visits_table_for_version_two_zero_zero.php';
        include_once __DIR__.'/../../database/migrations/2020_04_10_224546_update_short_url_table_for_version_three_zero_zero.php';

        (new \CreateShortUrlsTable)->up();
        (new \CreateShortUrlVisitsTable)->up();
        (new \UpdateShortURLTableForVersionTwoZeroZero)->up();
        (new \UpdateShortURLVisitsTableForVersionTwoZeroZero)->up();
        (new \UpdateShortURLTableForVersionThreeZeroZero)->up();
    }
}
