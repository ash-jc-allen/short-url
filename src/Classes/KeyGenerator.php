<?php

declare(strict_types=1);

namespace AshAllenDesign\ShortURL\Classes;

use AshAllenDesign\ShortURL\Interfaces\UrlKeyGenerator;
use AshAllenDesign\ShortURL\Models\ShortURL;
use Hashids\Hashids;

class KeyGenerator implements UrlKeyGenerator
{
    /**
     * The library class that is used for generating the unique hash.
     */
    private Hashids $hashids;

    public function __construct(Hashids $hashids)
    {
        $this->hashids = $hashids;
    }

    /**
     * Generate a unique and random URL key using the Hashids package.
     *
     * @return string
     */
    public function generateRandom(): string
    {
        do {
            $key = $this->hashids->encodeHex(uniqid());
        } while (ShortURL::where('url_key', $key)->exists());

        return $key;
    }

    /**
     * Generate a key for the short URL. This method allows you to pass a
     * seed value to the key generator. If no seed is passed, a random
     * key will be generated.
     */
    public function generateKeyUsing(int $seed = null): string
    {
        return $seed
            ? $this->hashids->encode($seed)
            : $this->generateRandom();
    }
}
