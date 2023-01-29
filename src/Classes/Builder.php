<?php

namespace AshAllenDesign\ShortURL\Classes;

use AshAllenDesign\ShortURL\Controllers\ShortURLController;
use AshAllenDesign\ShortURL\Exceptions\ShortURLException;
use AshAllenDesign\ShortURL\Exceptions\ValidationException;
use AshAllenDesign\ShortURL\Models\ShortURL;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Conditionable;

class Builder
{
    use Conditionable;

    /**
     * The class that is used for generating the
     * random URL keys.
     */
    private KeyGenerator $keyGenerator;

    /**
     * The destination URL that the short URL will
     * redirect to.
     */
    protected string|null $destinationUrl = null;

    /**
     * Whether or not if the shortened URL can be
     * accessed more than once.
     */
    protected bool $singleUse = false;

    /**
     * Whether or not to force the destination URL
     * and the shortened URL to use HTTPS rather
     * than HTTP.
     */
    protected bool|null $secure = null;

    /**
     * Whether or not the short url should
     * forward query params to the
     * destination url.
     */
    protected bool|null $forwardQueryParams = null;

    /**
     * Whether or not if the short URL should track
     * statistics about the visitors.
     */
    protected bool|null $trackVisits = null;

    /**
     * This can hold a custom URL key that might be
     * explicitly set for this URL.
     */
    protected string|null $urlKey = null;

    /**
     * The HTTP status code that will be used when
     * redirecting the user.
     */
    protected int $redirectStatusCode = 301;

    /**
     * Whether or not the visitor's IP address should
     * be recorded.
     */
    protected bool|null $trackIPAddress = null;

    /**
     * Whether or not the visitor's operating system
     * should be recorded.
     */
    protected bool|null $trackOperatingSystem = null;

    /**
     * Whether or not the visitor's operating system
     * version should be recorded.
     */
    protected bool|null $trackOperatingSystemVersion = null;

    /**
     * Whether or not the visitor's browser should
     * be recorded.
     */
    protected bool|null $trackBrowser = null;

    /**
     * Whether or not the visitor's browser version
     * should be recorded.
     */
    protected bool|null $trackBrowserVersion = null;

    /**
     * Whether or not the visitor's referer URL should
     * be recorded.
     */
    protected bool|null $trackRefererURL = null;

    /**
     * Whether or not the visitor's device type should
     * be recorded.
     */
    protected bool|null $trackDeviceType = null;

    /**
     * The date and time that the short URL should become
     * active so that it can be visited.
     */
    protected Carbon|null $activateAt = null;

    /**
     * The date and time that the short URL should be
     * deactivated so that it cannot be visited.
     */
    protected Carbon|null $deactivateAt = null;

    /**
     * Builder constructor.
     *
     * When constructing this class, ensure that the
     * config variables are validated.
     *
     * @throws ValidationException
     */
    public function __construct(Validation $validation = null, KeyGenerator $keyGenerator = null)
    {
        if (! $validation) {
            $validation = new Validation();
        }

        $this->keyGenerator = $keyGenerator ?? new KeyGenerator();

        $validation->validateConfig();
    }

    /**
     * Get the short URL route prefix.
     */
    public function prefix(): string|null
    {
        $prefix = config('short-url.prefix');

        if ($prefix === null) {
            return null;
        }

        return trim($prefix, '/');
    }

    /**
     * Get the middleware for short URL route.
     */
    public function middleware(): array
    {
        return config('short-url.middleware', []);
    }

    /**
     * Register the routes to handle the Short URL visits.
     */
    public function routes(): void
    {
        Route::middleware($this->middleware())
            ->group(function (): void {
                Route::get(
                    '/'.$this->prefix().'/{shortURLKey}',
                    ShortURLController::class
                )->name('short-url.invoke');
            });
    }

    /**
     * Set the destination URL that the shortened URL
     * will redirect to.
     *
     * @throws ShortURLException
     */
    public function destinationUrl(string $url): self
    {
        if (! Str::startsWith($url, ['http://', 'https://'])) {
            throw new ShortURLException('The destination URL must begin with http:// or https://');
        }

        $this->destinationUrl = $url;

        return $this;
    }

    /**
     * Set whether if the shortened URL can be accessed
     * more than once.
     */
    public function singleUse(bool $isSingleUse = true): self
    {
        $this->singleUse = $isSingleUse;

        return $this;
    }

    /**
     * Set whether if the destination URL and shortened
     * URL should be forced to use HTTPS.
     */
    public function secure(bool $isSecure = true): self
    {
        $this->secure = $isSecure;

        return $this;
    }

    /**
     * Set whether if the short URL should forward
     * query params to the destination URL.
     */
    public function forwardQueryParams(bool $shouldForwardQueryParams = true): self
    {
        $this->forwardQueryParams = $shouldForwardQueryParams;

        return $this;
    }

    /**
     * Set whether if the short URL should track some
     * statistics of the visitors.
     */
    public function trackVisits(bool $trackUrlVisits = true): self
    {
        $this->trackVisits = $trackUrlVisits;

        return $this;
    }

    /**
     * Set whether if the short URL should track the
     * IP address of the visitor.
     */
    public function trackIPAddress(bool $track = true): self
    {
        $this->trackIPAddress = $track;

        return $this;
    }

    /**
     * Set whether if the short URL should track the
     * operating system of the visitor.
     */
    public function trackOperatingSystem(bool $track = true): self
    {
        $this->trackOperatingSystem = $track;

        return $this;
    }

    /**
     * Set whether if the short URL should track the
     * operating system version of the visitor.
     */
    public function trackOperatingSystemVersion(bool $track = true): self
    {
        $this->trackOperatingSystemVersion = $track;

        return $this;
    }

    /**
     * Set whether if the short URL should track the
     * browser of the visitor.
     */
    public function trackBrowser(bool $track = true): self
    {
        $this->trackBrowser = $track;

        return $this;
    }

    /**
     * Set whether if the short URL should track the
     * browser version of the visitor.
     */
    public function trackBrowserVersion(bool $track = true): self
    {
        $this->trackBrowserVersion = $track;

        return $this;
    }

    /**
     * Set whether if the short URL should track the
     * referer URL of the visitor.
     */
    public function trackRefererURL(bool $track = true): self
    {
        $this->trackRefererURL = $track;

        return $this;
    }

    /**
     * Set whether if the short URL should track the
     * device type of the visitor.
     */
    public function trackDeviceType(bool $track = true): self
    {
        $this->trackDeviceType = $track;

        return $this;
    }

    /**
     * Explicitly set a URL key for this short URL.
     */
    public function urlKey(string $key): self
    {
        $this->urlKey = urlencode($key);

        return $this;
    }

    /**
     * Explicitly set the key generator.
     */
    public function keyGenerator(KeyGenerator $keyGenerator): self
    {
        $this->keyGenerator = $keyGenerator;

        return $this;
    }

    /**
     * Override the HTTP status code that will be used
     * for redirecting the visitor.
     *
     * @throws ShortURLException
     */
    public function redirectStatusCode(int $statusCode): self
    {
        if ($statusCode < 300 || $statusCode > 399) {
            throw new ShortURLException('The redirect status code must be a valid redirect HTTP status code.');
        }

        $this->redirectStatusCode = $statusCode;

        return $this;
    }

    /**
     * Set the date and time that the short URL should
     * be activated and allowed to visit.
     *
     * @throws ShortURLException
     */
    public function activateAt(Carbon $activationTime): self
    {
        if ($activationTime->isPast()) {
            throw new ShortURLException('The activation date must not be in the past.');
        }

        $this->activateAt = $activationTime;

        return $this;
    }

    /**
     * Set the date and time that the short URL should
     * be deactivated and not allowed to visit.
     *
     * @throws ShortURLException
     */
    public function deactivateAt(Carbon $deactivationTime): self
    {
        if ($deactivationTime->isPast()) {
            throw new ShortURLException('The deactivation date must not be in the past.');
        }

        if ($this->activateAt && $deactivationTime->isBefore($this->activateAt)) {
            throw new ShortURLException('The deactivation date must not be before the activation date.');
        }

        $this->deactivateAt = $deactivationTime;

        return $this;
    }

    /**
     * Attempt to build a shortened URL and return it.
     *
     * @throws ShortURLException
     */
    public function make(): ShortURL
    {
        if (! $this->destinationUrl) {
            throw new ShortURLException('No destination URL has been set.');
        }

        $data = $this->toArray();

        $this->checkKeyDoesNotExist();

        $shortURL = ShortURL::create($data);

        $this->resetOptions();

        return $shortURL;
    }

    /**
     * Returns an array of all properties used during record creation.
     */
    public function toArray(): array
    {
        $this->setOptions();

        return [
            'destination_url'                => $this->destinationUrl,
            'default_short_url'              => $this->buildDefaultShortUrl(),
            'url_key'                        => $this->urlKey,
            'single_use'                     => $this->singleUse,
            'forward_query_params'           => $this->forwardQueryParams,
            'track_visits'                   => $this->trackVisits,
            'redirect_status_code'           => $this->redirectStatusCode,
            'track_ip_address'               => $this->trackIPAddress,
            'track_operating_system'         => $this->trackOperatingSystem,
            'track_operating_system_version' => $this->trackOperatingSystemVersion,
            'track_browser'                  => $this->trackBrowser,
            'track_browser_version'          => $this->trackBrowserVersion,
            'track_referer_url'              => $this->trackRefererURL,
            'track_device_type'              => $this->trackDeviceType,
            'activated_at'                   => $this->activateAt,
            'deactivated_at'                 => $this->deactivateAt,
        ];
    }

    /**
     * Check whether if a short URL already exists in
     * the database with this explicitly defined
     * URL key.
     *
     * @throws ShortURLException
     */
    protected function checkKeyDoesNotExist(): void
    {
        if (ShortURL::where('url_key', $this->urlKey)->exists()) {
            throw new ShortURLException('A short URL with this key already exists.');
        }
    }

    /**
     * Set the options for the short URL that is being
     * created.
     */
    private function setOptions(): void
    {
        if ($this->secure === null) {
            $this->secure = config('short-url.enforce_https');
        }

        if ($this->secure) {
            $this->destinationUrl = str_replace('http://', 'https://', $this->destinationUrl);
        }

        if ($this->forwardQueryParams === null) {
            $this->forwardQueryParams = config('short-url.forward_query_params') ?? false;
        }

        if (! $this->urlKey) {
            $this->urlKey = $this->keyGenerator->generateRandom();
        }

        if (! $this->activateAt) {
            $this->activateAt = now();
        }

        $this->setTrackingOptions();
    }

    /**
     * Set the tracking-specific options for the short
     * URL that is being created.
     */
    private function setTrackingOptions(): void
    {
        if ($this->trackVisits === null) {
            $this->trackVisits = config('short-url.tracking.default_enabled');
        }

        if ($this->trackIPAddress === null) {
            $this->trackIPAddress = config('short-url.tracking.fields.ip_address');
        }

        if ($this->trackOperatingSystem === null) {
            $this->trackOperatingSystem = config('short-url.tracking.fields.operating_system');
        }

        if ($this->trackOperatingSystemVersion === null) {
            $this->trackOperatingSystemVersion = config('short-url.tracking.fields.operating_system_version');
        }

        if ($this->trackBrowser === null) {
            $this->trackBrowser = config('short-url.tracking.fields.browser');
        }

        if ($this->trackBrowserVersion === null) {
            $this->trackBrowserVersion = config('short-url.tracking.fields.browser_version');
        }

        if ($this->trackRefererURL === null) {
            $this->trackRefererURL = config('short-url.tracking.fields.referer_url');
        }

        if ($this->trackDeviceType === null) {
            $this->trackDeviceType = config('short-url.tracking.fields.device_type');
        }
    }

    /**
     * Reset the options for the class. This is useful
     * for stopping options carrying over into
     * different short URLs that are being
     * created with the same instance of
     * this class.
     */
    public function resetOptions(): self
    {
        $this->urlKey = null;
        $this->singleUse = false;
        $this->secure = null;
        $this->forwardQueryParams = null;
        $this->redirectStatusCode = 301;

        $this->trackVisits = null;
        $this->trackIPAddress = null;
        $this->trackOperatingSystem = null;
        $this->trackBrowser = null;
        $this->trackBrowserVersion = null;
        $this->trackRefererURL = null;
        $this->trackDeviceType = null;

        return $this;
    }

    /**
     * Build and return the default short URL that will be stored in the
     * database.
     */
    private function buildDefaultShortUrl(): string
    {
        $baseUrl = config('short-url.default_url') ?? config('app.url');
        $baseUrl .= '/';

        if ($this->prefix() !== null) {
            $baseUrl .= $this->prefix().'/';
        }

        return $baseUrl.$this->urlKey;
    }
}
