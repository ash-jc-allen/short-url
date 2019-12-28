<?php

namespace AshAllenDesign\ShortURL\Classes;

use AshAllenDesign\ShortURL\Exceptions\ShortUrlException;
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
     * Set the destination URL that the shortened URL
     * will redirect to.
     *
     * @param string $url
     * @return Builder
     */
    public function destinationUrl(string $url): self
    {
        // TODO VALIDATE THAT THE URL BEGINS WITH HTTPS OR HTTP!

        $routeName = null;
        $this->destinationUrl = $url;

        return $this;
    }

    /**
     * Set whether if the shortened URL can be accessed
     * more than once.
     *
     * @param bool $isSingleUse
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
     * @param bool $isSecure
     * @return Builder
     */
    public function secure(bool $isSecure = true): self
    {
        $this->secure = $isSecure;

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

        // TODO ADD A SHORT URL TO THE DATABASE.
        // TODO BUILD THE SHORT URL AND RETURN IT.
        if (! $this->destinationUrl) {
            throw new ShortUrlException('No destination URL has been set.');
        }

        if ($this->secure) {
            $this->destinationUrl = str_replace('http://', 'https://', $this->destinationUrl);
        }

        $storedUrl = $this->insertShortURLIntoDatabase();

        // TODO CHECK THAT A URL DOES NOT ALREADY EXIST.
        // TODO GIVE THE OPTION IN THE CONFIG TO SET THE DEFAULT CHARACTER LENGTH. DEFAULT TO 5.

        return $storedUrl->short_url;
    }

    private function insertShortURLIntoDatabase(): ShortURL
    {
        do {
            // TODO EXTRACT THE ROUTE IN TO THE CONFIG SO THAT THE USER CAN DECIDE WHAT THEY WANT.
            // TODO MAKE SURE THAT THE URL_LENGTH IS AN INTEGER. TRY AND JUST CAST IT.
            $shortUrl = config('app.url').'/short/'.Str::random(config('short-url.url_length'));
        } while (ShortURL::where('short_url', $shortUrl)->exists());

        return ShortURL::create([
            'destination_url' => $this->destinationUrl,
            'short_url'       => $shortUrl,
            'single_use'      => $this->singleUse,
        ]);
    }
}