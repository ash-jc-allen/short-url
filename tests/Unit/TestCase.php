<?php

namespace AshAllenDesign\ShortURL\Tests\Unit;

use AshAllenDesign\ShortURL\Facades\ShortURL;
use AshAllenDesign\ShortURL\Providers\ShortURLProvider;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    use LazilyRefreshDatabase;
    use WithWorkbench;

    /**
     * Load package service provider.
     *
     * @param  $app
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
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'ShortURL' => ShortURL::class,
        ];
    }
}
