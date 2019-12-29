<?php

namespace AshAllenDesign\ShortURL\Tests\Unit;

use AshAllenDesign\ShortURL\Providers\ShortURLProvider;
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
}
