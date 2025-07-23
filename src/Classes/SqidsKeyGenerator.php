<?php

/**
 * This could be the new class, I'm open to corrections and suggestions.
 */

declare(strict_types=1);

namespace AshAllenDesign\ShortURL\Classes;

use AshAllenDesign\ShortURL\Interfaces\UrlKeyGenerator;
use AshAllenDesign\ShortURL\Models\ShortURL;
use Squids\Sqids;

class SqidsKeyGenerator implements UrlKeyGenerator
{
    /**
     * The library class that is used for generating the unique hash.
     */
    private Squids $sqids;

    public function __construct(Sqids $sqids)
    {
        $this->sqids = $sqids;
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
        $ID = $this->getLastInsertedID();

        do {
            $ID++;
            $key = $this->sqids->encode($ID);
        } while (ShortURL::where('url_key', $key)->exists());

        return $key;
    }

    /**
     * Generate a key for the short URL. This method allows you to pass a
     * seed value to the key generator. If no seed is passed, a random
     * key will be generated.
     */
    public function generateKeyUsing(?int $seed = null): string
    {
        return $seed
            ? $this->sqids->encode($seed)
            : $this->generateRandom();
    }

    /**
     * Get the ID of the last inserted ShortURL. This is done so that we can predict
     * what the ID of the ShortURL that will be inserted will be called. From doing
     * this, we can create a unique hash with a reduced chance of a collision.
     */
    protected function getLastInsertedID(): int
    {
        return (int) ShortURL::max('id');
    }
}
