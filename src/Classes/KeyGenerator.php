<?php

declare(strict_types=1);

namespace AshAllenDesign\ShortURL\Classes;

use AshAllenDesign\ShortURL\Interfaces\UrlKeyGenerator;
use AshAllenDesign\ShortURL\Models\ShortURL;
use Hashids\Hashids;
use Illuminate\Support\Str;

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
     * Generate a unique and random URL key using the Hashids package. We start by
     * predicting the unique ID that the ShortURL will have in the database.
     * Then we can encode the ID to create a unique hash. On the very
     * unlikely chance that a generated key collides with another
     * key, we increment the ID and then attempt to create a new
     * unique key again.
     */
    public function generateRandom(): string
    {
        $id = $this->getLastInsertedID();

        $keyLength = $this->getKeyLength() - 1;

        do {
            $id++;

            $key = $this->hashids->encodeHex(
                substr(uniqid((string) $id), -$keyLength)
            );

            $keyLength++;
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

    /**
     * Get the minimum length of the Short URL key to generate.
     */
    protected function getKeyLength(): int
    {
        return config('short-url.key_length');
    }

    /**
     * Get the ID of the last inserted ShortURL. This is done so that we can predict
     * what the ID of the ShortURL that will be inserted will be called. From doing
     * this, we can create a unique hash without a reduced chance of a collision.
     */
    protected function getLastInsertedID(): int
    {
        return ShortURL::max('id') ?? 0;
    }
}
