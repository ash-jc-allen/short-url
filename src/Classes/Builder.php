<?php

namespace AshAllenDesign\ShortURL\Classes;

use AshAllenDesign\ShortURL\Exceptions\ShortUrlException;
use AshAllenDesign\ShortURL\Exceptions\ValidationException;
use AshAllenDesign\ShortURL\Models\ShortURL;
use Illuminate\Support\Str;

class Builder
{
    /**
     * The destination URL that the short URL will
     * redirect to.
     *
     * @var string|null
     */
    private $destinationUrl = null;

    /**
     * Whether or not if the shortened URL can be
     * accessed more than once.
     *
     * @var bool
     */
    private $singleUse = false;

    /**
     * Whether or not to force the destination URL
     * and the shortened URL to use HTTPS rather
     * than HTTP.
     *
     * @var bool
     */
    private $secure = true;

    /**
     * Whether or not if the short URL should track
     * statistics about the visitors.
     *
     * @var bool|null
     */
    private $trackVisits = null;

    /**
     * Builder constructor.
     *
     * When constructing this class, ensure that the
     * config variables are validated.
     *
     * @param  Validation  $validation
     * @throws ValidationException
     */
    public function __construct(Validation $validation = null)
    {
        if (! $validation) {
            $validation = new Validation();
        }

        $validation->validateConfig();
    }

    /**
     * Set the destination URL that the shortened URL
     * will redirect to.
     *
     * @param  string  $url
     * @return Builder
     * @throws ShortUrlException
     */
    public function destinationUrl(string $url): self
    {
        if (! Str::startsWith($url, ['http://', 'https://'])) {
            throw new ShortUrlException('The destination URL must begin with http:// or https://');
        }

        $routeName = null;
        $this->destinationUrl = $url;

        return $this;
    }

    /**
     * Set whether if the shortened URL can be accessed
     * more than once.
     *
     * @param  bool  $isSingleUse
     * @return Builder
     */
    public function singleUse(bool $isSingleUse = true): self
    {
        $this->singleUse = $isSingleUse;

        return $this;
    }

    /**
     * Set whether if the destination URL and shortened
     * URL should be forced to use HTTPS.
     *
     * @param  bool  $isSecure
     * @return Builder
     */
    public function secure(bool $isSecure = true): self
    {
        $this->secure = $isSecure;

        return $this;
    }

    /**
     * Set whether if the short URL should track some
     * statistics of the visitors.
     *
     * @param  bool  $trackUrlVisits
     * @return $this
     */
    public function trackVisits(bool $trackUrlVisits = true): self
    {
        $this->trackVisits = $trackUrlVisits;

        return $this;
    }

    /**
     * Attempt to build a shortened URL and return it.
     *
     * @return string
     * @throws ShortUrlException
     */
    public function make(): string
    {
        if (! $this->destinationUrl) {
            throw new ShortUrlException('No destination URL has been set.');
        }

        if ($this->secure) {
            $this->destinationUrl = str_replace('http://', 'https://', $this->destinationUrl);
        }

        if ($this->trackVisits === null) {
            $this->trackVisits = config('short-url.tracking.default_enabled');
        }

        $storedUrl = $this->insertShortURLIntoDatabase();

        return $storedUrl->short_url;
    }

    /**
     * Store the short URL in the database. Start by
     * trying to find a unique key that isn't
     * already in use in the database by
     * another short URL.
     *
     * @return ShortURL
     */
    protected function insertShortURLIntoDatabase(): ShortURL
    {
        do {
            $urlKey = Str::random(config('short-url.url_length'));
        } while (ShortURL::where('url_key', $urlKey)->exists());

        return ShortURL::create([
            'destination_url' => $this->destinationUrl,
            'short_url'       => config('app.url').'/short/'.$urlKey,
            'url_key'         => $urlKey,
            'single_use'      => $this->singleUse,
            'track_visits'    => $this->trackVisits,
        ]);
    }
}
