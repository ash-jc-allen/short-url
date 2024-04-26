<?php

namespace AshAllenDesign\ShortURL\Facades;

use Illuminate\Support\Facades\Facade;
use RuntimeException;

/**
 * @method static string|null prefix()
 * @method static array middleware()
 * @method static void routes()
 * @method static \AshAllenDesign\ShortURL\Classes\Builder destinationUrl(string $url)
 * @method static \AshAllenDesign\ShortURL\Classes\Builder singleUse(bool $isSingleUse = true)
 * @method static \AshAllenDesign\ShortURL\Classes\Builder secure(bool $isSecure = true)
 * @method static \AshAllenDesign\ShortURL\Classes\Builder forwardQueryParams(bool $shouldForwardQueryParams = true)
 * @method static \AshAllenDesign\ShortURL\Classes\Builder trackVisits(bool $trackUrlVisits = true)
 * @method static \AshAllenDesign\ShortURL\Classes\Builder trackIPAddress(bool $track = true)
 * @method static \AshAllenDesign\ShortURL\Classes\Builder trackOperatingSystem(bool $track = true)
 * @method static \AshAllenDesign\ShortURL\Classes\Builder trackOperatingSystemVersion(bool $track = true)
 * @method static \AshAllenDesign\ShortURL\Classes\Builder trackBrowser(bool $track = true)
 * @method static \AshAllenDesign\ShortURL\Classes\Builder trackBrowserVersion(bool $track = true)
 * @method static \AshAllenDesign\ShortURL\Classes\Builder trackRefererURL(bool $track = true)
 * @method static \AshAllenDesign\ShortURL\Classes\Builder trackDeviceType(bool $track = true)
 * @method static \AshAllenDesign\ShortURL\Classes\Builder urlKey(string $key)
 * @method static \AshAllenDesign\ShortURL\Classes\Builder keyGenerator(\AshAllenDesign\ShortURL\Classes\KeyGenerator $keyGenerator)
 * @method static \AshAllenDesign\ShortURL\Classes\Builder redirectStatusCode(int $statusCode)
 * @method static \AshAllenDesign\ShortURL\Classes\Builder activateAt(\Carbon\Carbon $activationTime)
 * @method static \AshAllenDesign\ShortURL\Classes\Builder deactivateAt(\Carbon\Carbon $deactivationTime)
 * @method static \AshAllenDesign\ShortURL\Classes\Builder generateKeyUsing(int $generateUsing)
 * @method static \AshAllenDesign\ShortURL\Classes\Builder beforeCreate(\Closure $callback)
 * @method static \AshAllenDesign\ShortURL\Models\ShortURL make()
 * @method static array toArray()
 * @method static \AshAllenDesign\ShortURL\Classes\Builder resetOptions()
 * @method static \AshAllenDesign\ShortURL\Classes\Builder|mixed when(\Closure|mixed|null $value = null, callable|null $callback = null, callable|null $default = null)
 * @method static \AshAllenDesign\ShortURL\Classes\Builder|mixed unless(\Closure|mixed|null $value = null, callable|null $callback = null, callable|null $default = null)
 *
 * @see \AshAllenDesign\ShortURL\Classes\Builder
 */
class ShortURL extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @throws RuntimeException
     */
    protected static function getFacadeAccessor(): string
    {
        return 'short-url.builder';
    }
}
