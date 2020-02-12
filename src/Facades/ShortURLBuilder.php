<?php

namespace AshAllenDesign\ShortURL\Facades;

use AshAllenDesign\ShortURL\Classes\Builder;
use AshAllenDesign\ShortURL\Models\ShortURL;
use Illuminate\Support\Facades\Facade;
use RuntimeException;

/**
 * @method static self destinationUrl(string $url)
 * @method static self singleUse(bool $isSingleUse = true)
 * @method static self secure(bool $isSecure = true)
 * @method static self trackVisits(bool $trackVisits = true)
 * @method static self urlKey(string $key)
 * @method static self redirectStatusCode(int $statusCode)
 * @method static ShortURL make()
 *
 * @see Builder
 */
class ShortURLBuilder extends Facade
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
