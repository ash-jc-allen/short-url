<?php

namespace AshAllenDesign\ShortURL\Facades;

use AshAllenDesign\ShortURL\Classes\Builder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Facade;
use RuntimeException;

/**
 * @method static self destinationUrl(string $url)
 * @method static self singleUse(bool $isSingleUse = true)
 * @method static self secure(bool $isSecure = true)
 * @method static self trackVisits(bool $trackVisits = true)
 * @method static self trackIPAddress(bool $track)
 * @method static self trackOperatingSystem(bool $track)
 * @method static self trackOperatingSystemVersion(bool $track)
 * @method static self trackBrowser(bool $track)
 * @method static self trackBrowserVersion(bool $track)
 * @method static self trackRefererURL(bool $track)
 * @method static self trackDeviceType(bool $track)
 * @method static self urlKey(string $key)
 * @method static self redirectStatusCode(int $statusCode)
 * @method static self resetOptions()
 * @method static self activateAt(Carbon $activationTime)
 * @method static self deactivateAt(Carbon $deactivationTime)
 * @method static \AshAllenDesign\ShortURL\Models\ShortURL make()
 *
 * @see Builder
 */
class ShortURL extends Facade
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
