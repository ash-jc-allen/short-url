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
    public function __construct()
    {
        $this->hashids = new Hashids(config('short-url.key_salt'), config('short-url.key_length'));
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
        if ($lastInserted = ShortURL::latest()->select('id')->first()) {
            return $lastInserted->id;
        }

        return 0;
    }
}
