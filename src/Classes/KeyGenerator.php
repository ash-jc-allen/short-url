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
     * Hunt for a valid url_key using mt_rand()
     *
     * @return string
     */
    public function generateRandom(): string
    {

        do {
            $key = $this->hashids->encode(mt_rand());
        } while (ShortURL::where('url_key', $key)->exists());

        return $key;
    }

}
