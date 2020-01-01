<?php

namespace AshAllenDesign\ShortURL\Facades;

use Illuminate\Support\Facades\Facade;
use RuntimeException;

class BuilderFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws RuntimeException
     */
    protected static function getFacadeAccessor(): string
    {
        return 'short-url.builder';
    }
}
