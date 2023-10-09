<?php

namespace AshAllenDesign\ShortURL\Classes;

use AshAllenDesign\ShortURL\Models\ShortURL;
use Hashids\Hashids;

class KeyGenerator
{
    /**
     * The library class that is used for generating
     * the unique hash.
     *
     * @var Hashids
     */
    private $hashids;

    /**
     * KeyGenerator constructor.
     */
    public function __construct(Hashids $hashids = null)
    {
        $this->hashids = $hashids ?: new Hashids(config('short-url.key_salt'), config('short-url.key_length'), config('short-url.alphabet'));
    }

    /**
     * Generate a unique and random URL key using the
     * Hashids package. We start by predicting the
     * unique ID that the ShortURL will have in
     * the database. Then we can encode the ID
     * to create a unique hash. On the very
     * unlikely chance that a generated
     * key collides with another key,
     * we increment the ID and then
     * attempt to create a new
     * unique key again.
     *
     * @return string
     */
    public function generateRandom(): string
    {
        $ID = $this->getLastInsertedID();

        do {
            $ID++;
            $key = $this->hashids->encode($ID);
        } while (ShortURL::where('url_key', $key)->exists());

        return $key;
    }

    /**
     * Generate a key for the short URL. This method allows you to pass a
     * seed value to the key generator. If no seed is passed, a random
     * key will be generated.
     *
     * @param  int|null  $seed
     * @return string
     */
    public function generateKeyUsing(int $seed = null): string
    {
        return $seed
            ? $this->hashids->encode($seed)
            : $this->generateRandom();
    }

    /**
     * Get the ID of the last inserted ShortURL. This
     * is done so that we can predict what the ID of
     * the ShortURL that will be inserted will be
     * called. From doing this, we can create a
     * unique hash without a reduced chance of
     * a collision.
     *
     * @return int
     */
    protected function getLastInsertedID(): int
    {
        return ShortURL::max('id') ?? 0;
    }
}
