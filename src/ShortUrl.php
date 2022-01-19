<?php

namespace AshAllenDesign\ShortURL;

class ShortUrl
{
    /**
     * Indicates if the default route will be registered.
     *
     * @var bool
     */
    public static bool $registers_routes = true;

    /**
     * Configure to not register its routes.
     *
     * @return static
     */
    public static function ignoreRoutes(): static
    {
        static::$registers_routes = false;

        return new static;
    }
}
